<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$_SESSION = [];
if (session_status() === PHP_SESSION_ACTIVE) {
  session_destroy();
}

set_flash('info', 'Bạn đã đăng xuất.');
redirect_route('user', 'index');

