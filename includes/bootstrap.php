<?php

declare(strict_types=1);

// Guard constant để các trang auth không bị chặn truy cập trực tiếp
if (!defined('_HIEU')) {
  define('_HIEU', true);
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/momo.php';
require_once __DIR__ . '/vnpay.php';
require_once __DIR__ . '/password_reset.php';
require_once __DIR__ . '/store_queries.php';

