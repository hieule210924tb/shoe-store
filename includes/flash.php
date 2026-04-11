<?php

declare(strict_types=1);

/**
 * Flash message: hiển thị thông báo thành công/lỗi sau redirect.
 */

function set_flash(string $type, string $message): void
{
  $_SESSION['flash'] = [
    'type' => $type,
    'message' => $message,
  ];
}

function get_flash(): ?array
{
  if (empty($_SESSION['flash'])) {
    return null;
  }
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
  return $flash;
}

