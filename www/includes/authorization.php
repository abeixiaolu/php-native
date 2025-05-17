<?php

declare(strict_types=1);

function ab_auth_assert_session(): void
{
  if (!isset($_SESSION['actions']) || !isset($_SESSION['user_id']) || intval($_SESSION['user_id']) < 1) {
    http_response_code(403);
    exit();
  }
}

function ab_auth_redirect_to_403(): void
{
  header('Location: /403');
  exit();
}

function ab_auth_is_authorized(string $action): bool
{
  ab_auth_assert_session();
  return in_array($action, $_SESSION['actions'], true);
}

function ab_auth_is_authorized_all(array $actions): bool
{
  ab_auth_assert_session();

  return count(array_intersect($actions, $_SESSION['actions'])) === count($actions);
}

function ab_auth_is_authorized_any(array $actions): bool
{
  ab_auth_assert_session();
  return count(array_intersect($actions, $_SESSION['actions'])) > 0;
}

function ab_auth_assert_authorized(string $action): void
{
  if (!ab_auth_is_authorized($action)) {
    ab_auth_redirect_to_403();
  }
}

function ab_auth_assert_authorized_all(array $actions): void
{
  if (!ab_auth_is_authorized_all($actions)) {
    ab_auth_redirect_to_403();
  }
}

function ab_auth_assert_authorized_any(array $actions): void
{
  if (!ab_auth_is_authorized_any($actions)) {
    ab_auth_redirect_to_403();
  }
}
