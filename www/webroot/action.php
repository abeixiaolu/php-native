<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

require "../includes/errors.php";
require("../includes/authentication.php");
require("../includes/authorization.php");
require "../includes/database.php";
require "../includes/request.php";
require "../includes/template.php";
require "../includes/validate.php";
require "../includes/sanitize.php";

ab_request_methods_assert(['GET', 'POST']);

function ab_render_action(array $action, array $errors): void
{
    require "../templates/action.php";
}

$action_id = ab_request_query_get_integer('id', 0, PHP_INT_MAX);

$action = [
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
    if ($action_id > 0) {
        ab_auth_assert_authorized_any(["ReadAction", "UpdateAction"]);
        $statement = $connection->prepare('SELECT id, name, description FROM actions WHERE id = :id');
        $statement->execute(['id' => $action_id]);
        if ($statement->rowCount() === 0) {
            ab_request_terminate(404);
        }
        $action = ab_escape_array($statement->fetch(PDO::FETCH_ASSOC));
    } else {
        ab_auth_assert_authorized("CreateAction");
    }
} else {
    ab_auth_assert_authorized_any(["CreateAction", "UpdateAction"]);
    $action_id = ab_request_post_get_integer('id', 0, PHP_INT_MAX);
    $action = ab_request_get_post_parameters([
        'name' => FILTER_SANITIZE_SPECIAL_CHARS,
        'description' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);
    $action['id'] = $action_id;

    $action['name'] = ab_sanitize_actionname($action['name']);
    $action['description'] = ab_sanitize_description($action['description']);

    $errors['name'] = ab_validate_actionname($action['name'], 'Name');
    $errors['description'] = ab_validate_description($action['description'], 'Description');

    if (!isset($errors['name']) && !ab_database_is_unique_actionname($connection, $action['name'], $action_id)) {
        $errors['name'] = 'action already exists';
    }

    if (!ab_validate_has_errors($errors)) {
        if ($action_id > 0) {
            ab_auth_assert_authorized("UpdateAction");
            $statement = $connection->prepare('UPDATE actions SET name = :name, description = :description WHERE id = :id');
            $statement->bindValue('id', $action['id'], PDO::PARAM_INT);
        } else {
            ab_auth_assert_authorized("CreateAction");
            $statement = $connection->prepare('INSERT INTO actions (name, description) VALUES (:name, :description);');
        }

        $statement->bindValue('name', $action['name'], PDO::PARAM_STR);
        $statement->bindValue('description', $action['description'], PDO::PARAM_STR);
        $statement->execute();

        ab_request_redirect('/actions');
    }
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_action($action, $errors);
ab_template_render_footer();


// header("Content-Type: text/plain");
// var_dump($user);