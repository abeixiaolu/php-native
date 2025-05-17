<?php
require "../includes/template.php";
require("../includes/authorization.php");

function ab_render_403(): void
{
  require("../templates/403.php");
}

ab_template_render_header();
ab_template_render_sidebar();
ab_render_403();
ab_template_render_footer();
