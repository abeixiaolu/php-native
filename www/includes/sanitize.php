<?php

declare(strict_types=1);


function ab_sanitize_username(string $username): string
{
    return mb_strtolower(mb_trim($username));
}

function ab_sanitize_name(string $name): string
{
    return mb_convert_case(mb_trim($name), MB_CASE_TITLE_SIMPLE);
}

function ab_sanitize_email(string $email): string
{
    return mb_strtolower(mb_trim($email));
}
