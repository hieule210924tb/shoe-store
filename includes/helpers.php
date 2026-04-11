<?php

declare(strict_types=1);

/**
 * Helper hàm dùng chung (redirect, escape, validate input...).
 */

function app_url(string $path = ''): string
{
  $path = ltrim($path, '/');
  $base = defined('BASE_URL') ? BASE_URL : '';
  if ($base === '') {
    return '/' . $path;
  }
  return rtrim($base, '/') . '/' . $path;
}

function redirect(string $path): void
{
  header('Location: ' . app_url($path));
  exit;
}

function route_url(string $module, string $action = 'index', array $params = []): string
{
  $query = array_merge(['module' => $module, 'action' => $action], $params);
  $qs = http_build_query($query);
  return app_url('?' . $qs);
}

function redirect_route(string $module, string $action = 'index', array $params = []): void
{
  header('Location: ' . route_url($module, $action, $params));
  exit;
}

function e(?string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function get_int(string $key, array $source, int $default): int
{
  if (!array_key_exists($key, $source)) {
    return $default;
  }
  $v = $source[$key];
  if ($v === '' || $v === null) {
    return $default;
  }
  if (!is_numeric((string)$v)) {
    return $default;
  }
  return (int)$v;
}

function get_str(string $key, array $source, string $default = ''): string
{
  if (!array_key_exists($key, $source)) {
    return $default;
  }
  $v = $source[$key];
  if ($v === null) {
    return $default;
  }
  return trim((string)$v);
}

function is_logged_in(): bool
{
  return !empty($_SESSION['user']);
}

function current_user_id(): ?int
{
  $id = $_SESSION['user']['id'] ?? null;
  if ($id === null) {
    return null;
  }
  if (is_int($id)) {
    return $id;
  }
  if (is_string($id)) {
    $id = trim($id);
    if ($id === '') {
      return null;
    }
    if (ctype_digit($id)) {
      return (int)$id;
    }
  }
  if (is_float($id) && is_finite($id) && $id == (int)$id) {
    return (int)$id;
  }
  return null;
}

function current_role(): ?string
{
  return $_SESSION['user']['role'] ?? null;
}

