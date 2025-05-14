<?php

declare(strict_types=1);

function ab_validate_username(string $username, string $field_name): ?string
{
    $length = mb_strlen($username);

    if ($length === 0) {
        return "$field_name is required";
    }

    if ($length < 6 || $length > 16) {
        return "$field_name must be between 6 and 16 characters";
    }

    if (preg_match("/^[[:alnum:]]+$/u", $username) !== 1) {
        return "$field_name must contain only letters and numbers";
    }

    return null;
}

function ab_validate_name(string $name, string $field_name): ?string
{
    $length = mb_strlen($name);

    if ($length === 0) {
        return "$field_name is required";
    }

    if ($length < 2 || $length > 32) {
        return "$field_name must be between 2 and 32 characters";
    }

    if (preg_match("/^[[:alpha:]]+$/u", $name) !== 1) {
        return "$field_name must contain only letters";
    }

    return null;
}

function ab_validate_email(string $email, string $field_name): ?string
{
    $length = mb_strlen($email);

    if ($length === 0) {
        return "$field_name is required";
    }

    if ($length < 5 || $length > 128) {
        return "$field_name must be between 5 and 128 characters";
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) !== $email) {
        return "$field_name must be a valid email address";
    }

    return null;
}

function ab_validate_has_errors(array $errors): bool
{
    foreach ($errors as $error) {
        if ($error !== null) {
            return true;
        }
    }
    return false;
}

function ab_validate_rolename(string $name, string $field_name): ?string
{
    $length = mb_strlen($name);

    if ($length === 0) {
        return "$field_name is required";
    }

    if ($length < 4 || $length > 32) {
        return "$field_name must be between 4 and 32 characters";
    }

    if (preg_match("/^[[:alpha:]]+$/u", $name) !== 1) {
        return "$field_name must contain only letters";
    }

    return null;
}

function ab_validate_description(string $description, string $field_name): ?string
{
    $length = mb_strlen($description);

    if ($length === 0) {
        return "$field_name is required";
    }

    if ($length < 10 || $length > 1024) {
        return "$field_name must be between 10 and 1024 characters";
    }

    if (preg_match("/^[[:print:]]+$/u", $description) !== 1) {
        return "$field_name can only contain printable characters";
    }

    return null;
}


function ab_validate_actionname(string $name, string $field_name): ?string
{
    $length = mb_strlen($name);

    if ($length === 0) {
        return "$field_name is required";
    }

    if ($length < 4 || $length > 32) {
        return "$field_name must be between 4 and 32 characters";
    }

    if (preg_match("/^[[:alpha:]]+$/u", $name) !== 1) {
        return "$field_name must contain only letters";
    }

    return null;
}

function ab_validate_login_username(string $username, string $field_name): ?string
{
    if (mb_strlen($username) === 0) {
        return "$field_name is required";
    }

    return null;
}

function ab_validate_login_password(string $password, string $field_name): ?string
{
    if (mb_strlen($password) === 0) {
        return "$field_name is required";
    }

    return null;
}
