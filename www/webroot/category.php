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
  if (!isset($_POST['action']) || $_POST['action'] !== 'delete') {
    $new_parent_id = ab_request_post_get_integer('parent_id', 0, PHP_INT_MAX);
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
        if ($parent_id == $new_parent_id) {
          $statement = $connection->prepare('UPDATE categories SET name = :name WHERE id = :id');
          $statement->bindValue('id', $category_id, PDO::PARAM_INT);
          $statement->bindValue('name', $parameters['name'], PDO::PARAM_STR);
          $statement->execute();
        } else {
          // move category to new parent
        }
      } else {
        // create category
        // 1. find the parent category
        $parent = array_find($categories, fn($cate) => $cate['id'] == intval($new_parent_id));
        if ($parent != null) {
          $connection->beginTransaction();
          // 2. update the category's lft and rgt, the rule is: lft >= parent.rgt lft + 2, rgt >= parent.rgt rgt + 2
          $statement = $connection->prepare('UPDATE categories SET lft = lft + 2 WHERE lft >= :parent_rgt');
          $statement->bindValue('parent_rgt', $parent['rgt'], PDO::PARAM_INT);
          $statement->execute();

          $statement = $connection->prepare('UPDATE categories SET rgt = rgt + 2 WHERE rgt >= :parent_rgt');
          $statement->bindValue('parent_rgt', $parent['rgt'], PDO::PARAM_INT);
          $statement->execute();

          $left = $parent['rgt'];
          $right = $left + 1;

          $statement = $connection->prepare('INSERT INTO categories (name, lft, rgt) VALUES (:name, :lft, :rgt)');
          $statement->execute([
            'name' => $parameters['name'],
            'lft' => $left,
            'rgt' => $right,
          ]);
          $connection->commit();
        } else {
          // insert new root node
          $statement = $connection->prepare('SELECT MAX(rgt) AS max_rgt FROM categories');
          $statement->execute();
          $max_rgt = $statement->fetchColumn();
          $left = $max_rgt + 1;
          $right = $left + 1;

          $statement = $connection->prepare('INSERT INTO categories (name, lft, rgt) VALUES (:name, :lft, :rgt)');
          $statement->execute([
            'name' => $parameters['name'],
            'lft' => $left,
            'rgt' => $right,
          ]);
        }
      }
    }
    ab_request_redirect("/categories");
  } else {
    // delete category
    $cate = array_find($categories, fn($cate) => $cate['id'] == $category_id);
    if ($cate === null) {
      ab_request_terminate(404);
    }

    if ($cate['rgt'] > $cate['lft'] + 1) {
      ab_request_terminate(400);
    }

    $connection->beginTransaction();

    $statement = $connection->prepare('DELETE FROM categories WHERE id = :id');
    $statement->bindValue('id', $category_id, PDO::PARAM_INT);
    $statement->execute();

    $statement = $connection->prepare('UPDATE categories SET rgt = rgt - 2 WHERE rgt > :rgt');
    $statement->bindValue('rgt', $cate['rgt'], PDO::PARAM_INT);
    $statement->execute();

    $statement = $connection->prepare('UPDATE categories SET lft = lft - 2 WHERE lft > :rgt');
    $statement->bindValue('rgt', $cate['rgt'], PDO::PARAM_INT);
    $statement->execute();

    $connection->commit();
    ab_request_redirect("/categories");
  }
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_category($category, $categories, $parent_id, $errors);
ab_template_render_footer();
