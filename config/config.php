<?php


declare(strict_types=1);
date_default_timezone_set('Asia/Ho_Chi_Minh');

define('DB_HOST', 'localhost');
define('DB_NAME', 'shoe-store');
define('DB_USER', 'root');
define('DB_PASS', '');


define('MAIL_ENABLED', true);
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_EMAIL', 'no-reply@example.com');
define('MAIL_FROM_NAME', 'ShoeStore');


$projectName = basename(dirname(__DIR__)); //'shoe_store'
$reqUri = $_SERVER['REQUEST_URI'] ?? '';
$baseUrl = '';
$needle = '/' . $projectName;
if (is_string($reqUri) && $reqUri !== '') {
  $pos = strrpos($reqUri, $needle);
  if ($pos !== false) {
    $baseUrl = substr($reqUri, 0, $pos + strlen($needle));
  }
}
define('BASE_URL', $baseUrl);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$siteUrl = $scheme . '://' . $httpHost;
if ($baseUrl !== '') {
  $siteUrl .= rtrim($baseUrl, '/');
}
$siteUrl = rtrim($siteUrl, '/') . '/';
define('SITEURL', $siteUrl);

// MoMo sandbox
if (!defined('MOMO_ENDPOINT')) define('MOMO_ENDPOINT', 'https://test-payment.momo.vn');
if (!defined('MOMO_PARTNER_CODE')) define('MOMO_PARTNER_CODE', 'MOMONPMB20210629');
if (!defined('MOMO_ACCESS_KEY')) define('MOMO_ACCESS_KEY', 'Q2XhhSdgpKUlQ4Ky');
if (!defined('MOMO_SECRET_KEY')) define('MOMO_SECRET_KEY', 'k6B53GQKSjktZGJBK2MyrDa7w9S6RyCf');
if (!defined('MOMO_REDIRECT_URL')) define('MOMO_REDIRECT_URL', SITEURL . 'user/momo-return.php');
if (!defined('MOMO_IPN_URL')) define('MOMO_IPN_URL', SITEURL . 'api/momo-ipn.php');

// VNPay sandbox
if (!defined('VNPAY_TMN_CODE')) define('VNPAY_TMN_CODE', 'U1Q149D8');
if (!defined('VNPAY_HASH_SECRET')) define('VNPAY_HASH_SECRET', '5GM0WI0CINW73KI4EGBME7ITBHB2HZMH');
if (!defined('VNPAY_URL')) define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
if (!defined('VNPAY_RETURN_URL')) define('VNPAY_RETURN_URL', SITEURL . 'user/vnpay-return.php');
if (!defined('VNPAY_IPN_URL')) define('VNPAY_IPN_URL', SITEURL . 'api/vnpay-ipn.php');
