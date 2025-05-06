<?php
declare(strict_types=1);

ob_start();
function ab_exception_handler(Throwable $exception): void
{
    ob_end_clean();
    http_response_code(500);
    header('Content-Type: text/plain');
    print("Error: " . $exception);
    exit();
}

function ab_error_handler(int $errno, string $errstr, string $errfile, int $errline): void
{
    throw new Exception($errstr);
}

set_exception_handler('ab_exception_handler');
set_error_handler('ab_error_handler');