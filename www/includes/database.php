<?php

declare(strict_types=1);

function ab_database_get_connection(): PDO
{
  // 在 Docker 环境中，使用 mysql 作为 host 是因为 Docker Compose 中服务名（在 docker-compose.yml 中定义的服务名称）会自动解析为容器的主机名。
  return new PDO('mysql:host=mysql;dbname=php-native', 'user', 'password');
}


function ab_database_is_unique_username(PDO $connection, string $username, int $user_id): bool
{
  $statement = $connection->prepare('SELECT count(*) FROM users WHERE username = :username AND id <> :id');
  $statement->execute(['username' => $username, 'id' => $user_id]);
  return $statement->fetchColumn() === 0;
}

function ab_database_is_unique_name(PDO $connection, string $first_name, string $last_name, int $user_id): bool
{
  $statement = $connection->prepare('SELECT count(*) FROM users WHERE first_name = :first_name AND last_name = :last_name AND id <> :id');
  $statement->execute([
    'first_name' => $first_name,
    'last_name' => $last_name,
    'id' => $user_id
  ]);
  return $statement->fetchColumn() === 0;
}

function ab_database_is_unique_email(PDO $connection, string $email, int $user_id): bool
{
  $statement = $connection->prepare('SELECT count(*) FROM users WHERE email = :email AND id <> :id');
  $statement->execute(['email' => $email, 'id' => $user_id]);
  return $statement->fetchColumn() === 0;
}

function ab_database_is_unique_rolename(PDO $connection, string $name, int $user_id): bool
{
  $statement = $connection->prepare('SELECT count(*) FROM roles WHERE name = :name AND id <> :id');
  $statement->execute(['name' => $name, 'id' => $user_id]);
  return $statement->fetchColumn() === 0;
}

function ab_database_is_unique_actionname(PDO $connection, string $name, int $user_id): bool
{
  $statement = $connection->prepare('SELECT count(*) FROM actions WHERE name = :name AND id <> :id');
  $statement->execute(['name' => $name, 'id' => $user_id]);
  return $statement->fetchColumn() === 0;
}

function ab_database_is_unique_category_name(PDO $connection, string $name, int $category_id): bool
{
  $statement = $connection->prepare('SELECT count(*) FROM categories WHERE name = :name AND id <> :id');
  $statement->execute(['name' => $name, 'id' => $category_id]);
  return $statement->fetchColumn() === 0;
}
