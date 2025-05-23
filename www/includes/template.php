<?php

declare(strict_types=1);

function ab_escape_array(array $array): array
{
    return array_map("htmlspecialchars", $array);
}


function ab_escape_array_of_arrays(array $array): array
{
    return array_map("ab_escape_array", $array);
}


function ab_template_render_header()
{
    require "../templates/header.php";
}

function ab_template_render_footer()
{
    require "../templates/footer.php";
}

function ab_template_render_pager($url, $page, $total_page)
{
    require "../templates/pager.php";
}

function ab_template_render_sidebar()
{
    require "../templates/sidebar.php";
}
