<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

require("../includes/errors.php");
require("../includes/database.php");
require("../includes/request.php");
require("../includes/template.php");

function ab_render_users(array $users): void
{
  require("../templates/users.php");
}

ab_request_method_assert('GET');

$connection = ab_database_get_connection();

$statement = $connection->prepare('SELECT id, username, first_name, last_name, email FROM users');
$statement->execute();
$users = ab_escape_array_of_arrays($statement->fetchAll(PDO::FETCH_ASSOC));

ab_render_users($users);
