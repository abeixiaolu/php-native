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

function ab_render_role(array $role, array $errors): void
{
    require "../templates/role.php";
}

$role_id = ab_request_query_get_integer('id', 0, PHP_INT_MAX);

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
    }
} else {
    $role_id = ab_request_post_get_integer('id', 0, PHP_INT_MAX);
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
            $statement = $connection->prepare('INSERT INTO roles (name, description) VALUES (:name, :first_name);');
        }

        $statement->bindValue('name', $role['name'], PDO::PARAM_STR);
        $statement->bindValue('description', $role['description'], PDO::PARAM_STR);
        $statement->execute();

        ab_request_redirect('/roles');
    }
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_role($role, $errors);
ab_template_render_footer();


// header("Content-Type: text/plain");
// var_dump($user);