<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../config/db.php';

function require_login(): void
{
  if (!is_logged_in()) {
    set_flash('error', 'Bạn cần đăng nhập để thực hiện chức năng này.');
    redirect_route('auth', 'login');
  }
}

function require_admin(): void
{
  // Không chỉ dựa vào role trong session (có thể lệch),
  // mà kiểm tra lại role trong DB theo user_id.
  if (!is_logged_in()) {
    set_flash('error', 'Bạn không có quyền truy cập trang này.');
    redirect_route('user', 'index');
  }

  $uid = current_user_id();
  if (!$uid) {
    set_flash('error', 'Bạn không có quyền truy cập trang này.');
    redirect_route('user', 'index');
  }

  $roleRow = getOne('SELECT role FROM users WHERE id = ? LIMIT 1', [(int)$uid]);
  $role = $roleRow['role'] ?? null;

  if ($role !== 'admin') {
    set_flash('error', 'Bạn không có quyền truy cập trang này.');
    redirect_route('user', 'index');
  }
}

function is_admin(): bool
{
  if (!is_logged_in()) {
    return false;
  }
  $uid = current_user_id();
  if (!$uid) {
    return false;
  }
  $roleRow = getOne('SELECT role FROM users WHERE id = ? LIMIT 1', [(int)$uid]);
  $role = $roleRow['role'] ?? null;
  return $role === 'admin';
}

