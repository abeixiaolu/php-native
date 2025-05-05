<?php
declare(strict_types=1);

function ab_database_get_connection(): PDO
{
  // 在 Docker 环境中，使用 mysql 作为 host 是因为 Docker Compose 中服务名（在 docker-compose.yml 中定义的服务名称）会自动解析为容器的主机名。
  return new PDO('mysql:host=mysql;dbname=php-native', 'user', 'password');
}
