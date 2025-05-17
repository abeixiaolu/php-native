<?php
// 启用严格类型模式：在函数调用和返回时，传参类型必须完全匹配，不能自动转换
declare(strict_types=1);

require("../includes/errors.php");
require("../includes/authentication.php");
require("../includes/authorization.php");
require("../includes/database.php");
require("../includes/request.php");
require("../includes/template.php");

ab_auth_assert_authorized("ListUsers");

function ab_render_users(array $users, int $page, int $page_size, int $total_page, string $url): void
{
  require("../templates/users.php");
}

ab_request_method_assert('GET');
$page = ab_request_query_get_integer('page', 1, PHP_INT_MAX, 1);
$page_size = ab_request_query_get_integer('size', 1, PHP_INT_MAX, 10);

$connection = ab_database_get_connection();

$statement = $connection->query('SELECT COUNT(*) FROM users;');
$total_count = $statement->fetchColumn();
$total_page = intval(ceil($total_count / $page_size));

if ($page > $total_page) {
  ab_request_terminate(400);
}

$statement = $connection->prepare('SELECT id, username, first_name, last_name, email FROM users LIMIT :offset, :limit;');
$statement->bindValue(':offset', ($page - 1) * $page_size, PDO::PARAM_INT);
$statement->bindValue(':limit', $page_size, PDO::PARAM_INT);
$statement->execute();
$users = ab_escape_array_of_arrays($statement->fetchAll(PDO::FETCH_ASSOC));

ab_template_render_header();
ab_template_render_sidebar();
ab_render_users($users, $page, $page_size, $total_page, "/users");
ab_template_render_footer();
