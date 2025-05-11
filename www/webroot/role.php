<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

require "../includes/errors.php";
require "../includes/database.php";
require "../includes/request.php";
require "../includes/template.php";
require "../includes/validate.php";
require "../includes/sanitize.php";

ab_request_methods_assert(['GET', 'POST']);

function ab_render_role(array $role, array $role_actions, array $other_actions, array $errors): void
{
    require "../templates/role.php";
}

$role_id = ab_request_query_get_integer('id', 0, PHP_INT_MAX);
$role_actions = [];
$other_actions = [];
$role = [
    "id" => 0,
    "name" => null,
    "description" => null,
];

$errors = [
    "name" => null,
    "description" => null,
];
$connection = ab_database_get_connection();

if (ab_request_is_method('GET')) {
    if ($role_id > 0) {
        $statement = $connection->prepare('SELECT id, name, description FROM roles WHERE id = :id');
        $statement->execute(['id' => $role_id]);
        if ($statement->rowCount() === 0) {
            ab_request_terminate(404);
        }
        $role = ab_escape_array($statement->fetch(PDO::FETCH_ASSOC));

        $statement = $connection->prepare('SELECT id, name, description FROM actions, roles_actions WHERE roles_actions.role_id = :role_id AND actions.id = roles_actions.action_id');
        $statement->execute(['role_id' => $role_id]);
        $role_actions = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = $connection->prepare('SELECT id, name, description FROM actions WHERE id NOT IN (SELECT action_id FROM roles_actions WHERE role_id = :role_id)');
        $statement->execute(['role_id' => $role_id]);
        $other_actions = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $role_id = ab_request_post_get_integer('id', 0, PHP_INT_MAX);
    $action_id = ab_request_post_get_integer('action_id', 0, PHP_INT_MAX);
    if ($action_id === 0) {
        $role = ab_request_get_post_parameters([
            'name' => FILTER_SANITIZE_SPECIAL_CHARS,
            'description' => FILTER_SANITIZE_SPECIAL_CHARS,
        ]);
        $role['id'] = $role_id;

        $role['name'] = ab_sanitize_rolename($role['name']);
        $role['description'] = ab_sanitize_description($role['description']);

        $errors['name'] = ab_validate_rolename($role['name'], 'Name');
        $errors['description'] = ab_validate_description($role['description'], 'Description');

        if (!isset($errors['name']) && !ab_database_is_unique_rolename($connection, $role['name'], $role_id)) {
            $errors['name'] = 'Role already exists';
        }

        if (!ab_validate_has_errors($errors)) {
            if ($role_id > 0) {
                $statement = $connection->prepare('UPDATE roles SET name = :name, description = :description WHERE id = :id');
                $statement->bindValue('id', $role['id'], PDO::PARAM_INT);
            } else {
                $statement = $connection->prepare('INSERT INTO roles (name, description) VALUES (:name, :description);');
            }

            $statement->bindValue('name', $role['name'], PDO::PARAM_STR);
            $statement->bindValue('description', $role['description'], PDO::PARAM_STR);
            $statement->execute();

            ab_request_redirect('/roles');
        }
    } else {
        if ($_POST['action'] === 'add_action') {
            // add action to role
            $statement = $connection->prepare('INSERT INTO roles_actions (role_id, action_id) VALUES (:role_id, :action_id)');
            $statement->bindValue('role_id', $role_id, PDO::PARAM_INT);
            $statement->bindValue('action_id', $action_id, PDO::PARAM_INT);
            $statement->execute();
        } else if ($_POST['action'] === 'delete_action') {
            $statement = $connection->prepare('DELETE FROM roles_actions WHERE role_id = :role_id AND action_id = :action_id');
            $statement->bindValue('role_id', $role_id, PDO::PARAM_INT);
            $statement->bindValue('action_id', $action_id, PDO::PARAM_INT);
            $statement->execute();
        }
        ab_request_redirect('/roles');
    }
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_role($role, $role_actions, $other_actions, $errors);
ab_template_render_footer();


// header("Content-Type: text/plain");
// var_dump($user);