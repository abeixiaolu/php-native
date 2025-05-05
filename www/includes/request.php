<?php
declare(strict_types=1);

function ab_request_method_assert(string $method) {
  if(!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== $method) {
    http_response_code(403);
    exit();
  }
}