<?php
declare (strict_types = 1);

function ab_request_terminate(int $http_code): void
{
    http_response_code($http_code);
    exit();
}

function ab_request_method_assert(string $method)
{
    if (! isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== $method) {
        ab_request_terminate(403);
    }
}

function ab_request_query_get_integer(string $parameter_name, int $min, int $max, ?int $default = null): int
{
    $value = filter_input(
        INPUT_GET,
        $parameter_name,
        FILTER_VALIDATE_INT,
        [
            "options" => ["min_range" => $min, "max_range" => $max, "default" => $default],
            "flags"   => FILTER_NULL_ON_FAILURE,
        ]
    );

    if ($value === false || $value === null) {
        ab_request_terminate(400);
    }

    return $value;
}
