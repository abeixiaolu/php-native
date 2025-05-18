<?php

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

function ab_render_category(array $category, array $errors): void
{
  require "../templates/category.php";
}

$category_id = ab_request_query_get_integer('id', 0, PHP_INT_MAX);

$category = [
  "id" => 0,
  "name" => null,
];
$errors = [
  "name" => null,
];

ab_template_render_header();
ab_template_render_sidebar();
ab_render_category($category, $errors);
ab_template_render_footer();
