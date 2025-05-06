<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare (strict_types = 1);

require "../includes/errors.php";
require "../includes/database.php";
require "../includes/request.php";

ab_request_method_assert('GET');

$user_id = ab_request_query_get_integer('id', 1, PHP_INT_MAX);

function ab_render_user(array $user): void
{
    require "../templates/user.php";
}

$connection = ab_database_get_connection();

$statement = $connection->prepare('SELECT id, username, first_name, last_name, email FROM users WHERE id = :id');
$statement->execute(['id' => $user_id]);

if ($statement->rowCount() === 0) {
    ab_request_terminate(404);
}

$user = $statement->fetch(PDO::FETCH_ASSOC);

// header("Content-Type: text/plain");
// var_dump($user);

ab_render_user($user);
