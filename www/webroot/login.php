<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

require "../includes/errors.php";
require("../includes/authentication.php");
require "../includes/database.php";
require "../includes/request.php";
require "../includes/template.php";
require "../includes/validate.php";
require "../includes/sanitize.php";

ab_request_methods_assert(['GET', 'POST']);

function ab_render_login(string $username, string $password, ?string $auth_error, array $errors): void
{
    require "../templates/login.php";
}

$auth_error = null;
$username = "";
$password = "";
$errors = [
    'username' => null,
    'password' => null,
];
if (ab_request_is_method('POST')) {
    $parameters = ab_request_get_post_parameters([
        'username' => FILTER_SANITIZE_SPECIAL_CHARS,
        'password' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);

    $username = ab_sanitize_username($parameters['username']);
    $password = ab_sanitize_password($parameters['password']);
    $errors['username'] = ab_validate_login_username($username, 'Username');
    $errors['password'] = ab_validate_login_password($password, 'Password');
    if (!ab_validate_has_errors($errors)) {
        $connection = ab_database_get_connection();
        $statement = $connection->prepare("SELECT id, password FROM users WHERE username = :username");
        $statement->execute([
            'username' => $username,
        ]);
        if ($statement->rowCount() !== 1) {
            $auth_error = "Incorrect username or password";
        } else {
            $user = $statement->fetch();
            if (password_verify($password, $user['password'])) {
                $_SESSION["user_id"] = $user["id"];
                ab_request_redirect("/users");
            } else {
                $auth_error = "Incorrect username or password";
            }
        }
    }
}

ab_template_render_header();
ab_render_login($username, $password, $auth_error, $errors);
ab_template_render_footer();


// header("Content-Type: text/plain");
// var_dump($user);