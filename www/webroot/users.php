<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

include("../includes/database.php");
include("../includes/request.php");

function ab_render_users(PDOStatement $statement): void
{
  include("../templates/users.php");
}

ab_request_method_assert('GET');

$connection = ab_database_get_connection();

$statement = $connection->prepare('SELECT id, username, first_name, last_name, email FROM users');
$statement->execute();

ab_render_users($statement);
?>
