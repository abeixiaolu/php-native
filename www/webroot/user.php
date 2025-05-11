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

function ab_render_user(array $user, array $user_roles, array $other_roles, array $errors): void
{
    require "../templates/user.php";
}

$user_id = ab_request_query_get_integer('id', 0, PHP_INT_MAX);

$user_roles = [];
$other_roles = [];
$user = [
    "id" => 0,
    "username" => null,
    "first_name" => null,
    "last_name" => null,
    "email" => null,
];

$errors = [
    "username" => null,
    "first_name" => null,
    "last_name" => null,
    "email" => null,
];
$connection = ab_database_get_connection();

if (ab_request_is_method('GET')) {
    if ($user_id > 0) {
        $statement = $connection->prepare('SELECT id, username, first_name, last_name, email FROM users WHERE id = :id');
        $statement->execute(['id' => $user_id]);
        if ($statement->rowCount() === 0) {
            ab_request_terminate(404);
        }
        $user = ab_escape_array($statement->fetch(PDO::FETCH_ASSOC));

        $statement = $connection->prepare('SELECT id, name, description FROM roles, users_roles WHERE users_roles.user_id = :user_id AND roles.id = users_roles.role_id');
        $statement->execute(['user_id' => $user_id]);
        $user_roles = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = $connection->prepare('SELECT id, name, description FROM roles WHERE id NOT IN (SELECT role_id FROM users_roles WHERE user_id = :user_id)');
        $statement->execute(['user_id' => $user_id]);
        $other_roles = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $user_id = ab_request_post_get_integer('id', 0, PHP_INT_MAX);
    $role_id = ab_request_post_get_integer('role_id', 0, PHP_INT_MAX, 0);
    if ($role_id === 0) {
        $user = ab_request_get_post_parameters([
            'username' => FILTER_SANITIZE_SPECIAL_CHARS,
            'first_name' => FILTER_SANITIZE_SPECIAL_CHARS,
            'last_name' => FILTER_SANITIZE_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
        ]);
        $user['id'] = $user_id;

        $user['username'] = ab_sanitize_username($user['username']);
        $user['first_name'] = ab_sanitize_name($user['first_name']);
        $user['last_name'] = ab_sanitize_name($user['last_name']);
        $user['email'] = ab_sanitize_email($user['email']);

        $errors['username'] = ab_validate_username($user['username'], 'Username');
        $errors['first_name'] = ab_validate_name($user['first_name'], 'First Name');
        $errors['last_name'] = ab_validate_name($user['last_name'], 'Last Name');
        $errors['email'] = ab_validate_email($user['email'], 'Email');

        if (!isset($errors['username']) && !ab_database_is_unique_username($connection, $user['username'], $user_id)) {
            $errors['username'] = 'Username already exists';
        }
        if (!isset($errors['email']) && !ab_database_is_unique_email($connection, $user['email'], $user_id)) {
            $errors['email'] = 'Email already exists';
        }
        if (!isset($errors['first_name']) && !isset($errors['last_name']) && !ab_database_is_unique_name($connection, $user['first_name'], $user['last_name'], $user_id)) {
            $errors['first_name'] = 'First name and last name already exists';
            $errors['last_name'] = 'First name and last name already exists';
        }

        if (!ab_validate_has_errors($errors)) {
            if ($user_id > 0) {
                $statement = $connection->prepare('UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id');
                $statement->bindValue('id', $user['id'], PDO::PARAM_INT);
            } else {
                $statement = $connection->prepare('INSERT INTO users (username, first_name, last_name, email) VALUES (:username, :first_name, :last_name, :email);');
            }

            $statement->bindValue('username', $user['username'], PDO::PARAM_STR);
            $statement->bindValue('first_name', $user['first_name'], PDO::PARAM_STR);
            $statement->bindValue('last_name', $user['last_name'], PDO::PARAM_STR);
            $statement->bindValue('email', $user['email'], PDO::PARAM_STR);
            $statement->execute();

            ab_request_redirect('/users');
        }
    } else {
        // add role to user
        if ($_POST['action'] === 'add_role') {
            $statement = $connection->prepare('INSERT INTO users_roles (user_id, role_id) VALUES (:user_id, :role_id)');
            $statement->bindValue('user_id', $user_id, PDO::PARAM_INT);
            $statement->bindValue('role_id', $role_id, PDO::PARAM_INT);
            $statement->execute();
        } else if ($_POST['action'] === 'delete_role') {
            $statement = $connection->prepare('DELETE FROM users_roles WHERE user_id = :user_id AND role_id = :role_id');
            $statement->bindValue('user_id', $user_id, PDO::PARAM_INT);
            $statement->bindValue('role_id', $role_id, PDO::PARAM_INT);
            $statement->execute();
        }

        ab_request_redirect('/user/' . $user_id);
    }
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_user($user, $user_roles, $other_roles, $errors);
ab_template_render_footer();


// header("Content-Type: text/plain");
// var_dump($user);