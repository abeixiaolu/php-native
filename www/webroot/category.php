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

function ab_render_category(array $category, array $categories, int $parent_id, array $errors): void
{
  require "../templates/category.php";
}

$connection = ab_database_get_connection();
$parent_id = 0;
$category_id = ab_request_query_get_integer('id', 0, PHP_INT_MAX);
$category = [
  "id" => 0,
  "name" => null,
];
$errors = [
  "name" => null,
];
$statement = $connection->prepare("SELECT
    node.id,
    node.name,
    node.lft,
    node.rgt,
    (COUNT(parent.id) - 1) AS depth
  FROM categories AS node, categories AS parent
  WHERE node.lft BETWEEN parent.lft AND parent.rgt
  GROUP BY node.id
  ORDER BY node.lft");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($category_id > 0) {
  $statement = $connection->prepare("SELECT parent.id FROM categories AS node, categories AS parent
      WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.id = :id ORDER BY parent.lft DESC LIMIT 1,1;
  ");
  $statement->execute(['id' => $category_id]);
  $has_parent = $statement->fetchColumn();
  if ($has_parent) {
    $parent_id = $has_parent;
  }
}

if (ab_request_is_method('GET')) {
  if ($category_id > 0) {
    $statement = $connection->prepare('SELECT id, name FROM categories WHERE id = :id');
    $statement->execute(['id' => $category_id]);
    if ($statement->rowCount() === 0) {
      ab_request_terminate(404);
    }
    $category = ab_escape_array($statement->fetch(PDO::FETCH_ASSOC));
  }
} else {
  $category_id = ab_request_post_get_integer('id', 0, PHP_INT_MAX);
  $parameters = ab_request_get_post_parameters([
    'name' => FILTER_SANITIZE_SPECIAL_CHARS,
  ]);
  $parameters['id'] = $category_id;
  $parameters['name'] = ab_sanitize_category_name($parameters['name']);
  $errors['name'] = ab_validate_category_name($parameters['name'], 'Name');

  if (!isset($errors['name']) && !ab_database_is_unique_category_name($connection, $parameters['name'], $category_id)) {
    $errors['name'] = 'Category name already exists';
  }

  if (!ab_validate_has_errors($errors)) {
    if ($category_id > 0) {
      $statement = $connection->prepare('UPDATE categories SET name = :name WHERE id = :id');
      $statement->bindValue('id', $category_id, PDO::PARAM_INT);
    } else {
      // create category
    }
    $statement->bindValue('name', $parameters['name'], PDO::PARAM_STR);
    $statement->execute();

    ab_request_redirect("/categories");
  }
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_category($category, $categories, $parent_id, $errors);
ab_template_render_footer();
