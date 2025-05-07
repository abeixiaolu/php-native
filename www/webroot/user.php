<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

require "../includes/errors.php";
require "../includes/database.php";
require "../includes/request.php";
require "../includes/template.php";
require "../includes/validate.php";

ab_request_methods_assert(['GET', 'POST']);

function ab_render_user(array $user, array $errors): void
{
    require "../templates/user.php";
}

$user_id = ab_request_query_get_integer('id', 1, PHP_INT_MAX);
$errors = [
    "username" => null,
    "first_name" => null,
    "last_name" => null,
    "email" => null,
];
$connection = ab_database_get_connection();

if (ab_request_is_method('GET')) {
    $statement = $connection->prepare('SELECT id, username, first_name, last_name, email FROM users WHERE id = :id');
    $statement->execute(['id' => $user_id]);
    if ($statement->rowCount() === 0) {
        ab_request_terminate(404);
    }
    $user = ab_escape_array($statement->fetch(PDO::FETCH_ASSOC));
} else {
    $user_id = ab_request_post_get_integer('id', 0, PHP_INT_MAX);
    $user = ab_request_get_post_parameters([
        'username' => FILTER_SANITIZE_SPECIAL_CHARS,
        'first_name' => FILTER_SANITIZE_SPECIAL_CHARS,
        'last_name' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL,
    ]);
    $user['id'] = $user_id;

    $errors['username'] = ab_validate_username($user['username'], 'Username');
    $errors['first_name'] = ab_validate_name($user['first_name'], 'First Name');
    $errors['last_name'] = ab_validate_name($user['last_name'], 'Last Name');
    $errors['email'] = ab_validate_email($user['email'], 'Email');

    if (!ab_validate_has_errors($errors)) {
        $statement = $connection->prepare('UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id');
        $statement->bindValue('id', $user['id'], PDO::PARAM_INT);
        $statement->bindValue('username', $user['username'], PDO::PARAM_STR);
        $statement->bindValue('first_name', $user['first_name'], PDO::PARAM_STR);
        $statement->bindValue('last_name', $user['last_name'], PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], PDO::PARAM_STR);
        $statement->execute();

        ab_request_redirect('/users');
    }
}

ab_render_user($user, $errors);


// header("Content-Type: text/plain");
// var_dump($user);