<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Tạo PDO singleton để tái sử dụng trong toàn project.
 */
function db(): PDO
{
  static $pdo = null;
  if ($pdo instanceof PDO) {
    return $pdo;
  }

  $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
  $pdo = new PDO(
    $dsn,
    DB_USER,
    DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
  return $pdo;
}

/**
 * ===== CRUD helpers dùng chung (PDO) =====
 * Lưu ý: với các hàm insert/update/delete theo $table, chỉ cho phép tên bảng/cột dạng [a-zA-Z0-9_]
 * để giảm rủi ro injection qua identifier.
 */

function db_ident(string $name): string
{
  if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
    throw new InvalidArgumentException('Invalid identifier: ' . $name);
  }
  return $name;
}

function getAll(string $sql, array $params = []): array
{
  $stm = db()->prepare($sql);
  $stm->execute($params);
  return $stm->fetchAll();
}

function getRows(string $sql, array $params = []): int
{
  $stm = db()->prepare($sql);
  $stm->execute($params);
  return $stm->rowCount();
}

function getOne(string $sql, array $params = []): ?array
{
  $stm = db()->prepare($sql);
  $stm->execute($params);
  $row = $stm->fetch();
  return $row === false ? null : $row;
}

function insertRow(string $table, array $data): bool
{
  $table = db_ident($table);
  if ($data === []) {
    throw new InvalidArgumentException('insertRow: data is empty');
  }

  $keys = array_keys($data);
  $cols = [];
  $placeholders = [];
  foreach ($keys as $k) {
    $k = (string)$k;
    $cols[] = db_ident($k);
    $placeholders[] = ':' . $k;
  }

  $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', $placeholders) . ')';
  $stm = db()->prepare($sql);
  return (bool)$stm->execute($data);
}

function updateRow(string $table, array $data, string $condition = '', array $whereParams = []): bool
{
  $table = db_ident($table);
  if ($data === []) {
    throw new InvalidArgumentException('updateRow: data is empty');
  }

  $sets = [];
  foreach ($data as $key => $_value) {
    $key = (string)$key;
    $sets[] = db_ident($key) . '=:' . $key;
  }

  $sql = 'UPDATE ' . $table . ' SET ' . implode(',', $sets);
  if ($condition !== '') {
    $sql .= ' WHERE ' . $condition;
  }

  $stm = db()->prepare($sql);
  return (bool)$stm->execute(array_merge($data, $whereParams));
}

function deleteRow(string $table, string $condition = '', array $params = []): bool
{
  $table = db_ident($table);
  $sql = 'DELETE FROM ' . $table;
  if ($condition !== '') {
    $sql .= ' WHERE ' . $condition;
  }
  $stm = db()->prepare($sql);
  return (bool)$stm->execute($params);
}

