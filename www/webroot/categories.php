<?php

declare(strict_types=1);

require("../includes/errors.php");
require("../includes/authentication.php");
require("../includes/authorization.php");
require("../includes/database.php");
require("../includes/request.php");
require("../includes/template.php");

function ab_render_categories(array $categories): void
{
  require("../templates/categories.php");
}
ab_request_method_assert('GET');

$connection = ab_database_get_connection();
$statement = $connection->prepare("SELECT
    node.id,
    node.name,
    node.lft,
    node.rgt,
    (COUNT(parent.id) - 1) AS depth
   FROM
    categories AS node,
    categories AS parent
   WHERE
    node.lft BETWEEN parent.lft AND parent.rgt
   GROUP BY
    node.id, node.name, node.lft, node.rgt
   ORDER BY
    node.lft");
$statement->execute();
$categories = ab_escape_array_of_arrays($statement->fetchAll(PDO::FETCH_ASSOC));

ab_template_render_header();
ab_template_render_sidebar();
ab_render_categories($categories);
ab_template_render_footer();
