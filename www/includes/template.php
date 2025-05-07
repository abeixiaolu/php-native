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
