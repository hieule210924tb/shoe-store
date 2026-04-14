<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$uid = current_user_id();

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new RuntimeException('Yêu cầu không hợp lệ.');
  }

  $buyer_phone = preg_replace('/\s+/', '', (string)($_POST['buyer_phone'] ?? ''));
  $addr_house = trim((string)($_POST['addr_house'] ?? ''));
  $addr_hamlet = trim((string)($_POST['addr_hamlet'] ?? ''));
  $addr_commune = trim((string)($_POST['addr_commune'] ?? ''));
  $addr_province = trim((string)($_POST['addr_province'] ?? ''));
  $payment_method = trim((string)($_POST['payment_method'] ?? 'momo'));

  if ($buyer_phone === '' || !preg_match('/^[0-9+\-().]{8,20}$/', $buyer_phone)) {
    throw new RuntimeException('SĐT không hợp lệ.');
  }
  if ($addr_house === '' || $addr_hamlet === '' || $addr_commune === '' || $addr_province === '') {
    throw new RuntimeException('Vui lòng nhập đầy đủ địa chỉ (số nhà, thôn, xã, tỉnh).');
  }
  if (!is_valid_payment_method($payment_method)) {
    throw new RuntimeException('Phương thức thanh toán không hợp lệ.');
  }

  $buyer = [
    'buyer_phone' => $buyer_phone,
    'addr_house' => $addr_house,
    'addr_hamlet' => $addr_hamlet,
    'addr_commune' => $addr_commune,
    'addr_province' => $addr_province,
    'payment_method' => $payment_method,
  ];
  $_SESSION['checkout_buyer'] = $buyer;

  $orderId = cart_create_pending_order_from_cart($uid, $buyer, $payment_method);
  $order = fetch_order_by_id($orderId);
  if (!$order) {
    throw new RuntimeException('Không thể tạo đơn hàng chờ thanh toán.');
  }

  if ($payment_method === 'cod') {
    reserve_order_for_cod($orderId);
    set_flash('success', 'Đã đặt hàng thành công. Bạn sẽ thanh toán khi nhận hàng.');
    redirect('user/order_detail.php?id=' . $orderId);
  }

  if ($payment_method === 'vnpay') {
    $now = new DateTimeImmutable('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
    $expireAt = $now->modify('+15 minutes');
    $txnRef = 'VNPAY_' . $orderId . '_' . time();
    $amount = (int)round((float)$order['total_amount'] * 100);
    $params = [
      'vnp_Version' => '2.1.0',
      'vnp_Command' => 'pay',
      'vnp_TmnCode' => VNPAY_TMN_CODE,
      'vnp_Amount' => (string)$amount,
      'vnp_CreateDate' => $now->format('YmdHis'),
      'vnp_ExpireDate' => $expireAt->format('YmdHis'),
      'vnp_CurrCode' => 'VND',
      'vnp_IpAddr' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
      'vnp_Locale' => 'vn',
      'vnp_OrderInfo' => 'Thanh toan don hang #' . $orderId,
      'vnp_OrderType' => 'other',
      'vnp_ReturnUrl' => VNPAY_RETURN_URL,
      'vnp_TxnRef' => $txnRef,
    ];
    $paymentUrl = vnpay_create_payment_url($params);
    create_vnpay_transaction($orderId, $txnRef, (float)$order['total_amount'], $paymentUrl);
    header('Location: ' . $paymentUrl);
    exit;
  }

  $requestId = 'MOMO_REQ_' . $orderId . '_' . time();
  $momoOrderId = 'ORDER_' . $orderId . '_' . time();
  $extraData = base64_encode(json_encode([
    'order_id' => $orderId,
    'user_id' => $uid,
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

  $signatureData = [
    'accessKey' => MOMO_ACCESS_KEY,
    'amount' => (string)(int)round((float)$order['total_amount']),
    'extraData' => $extraData,
    'ipnUrl' => MOMO_IPN_URL,
    'orderId' => $momoOrderId,
    'orderInfo' => 'Thanh toan don hang #' . $orderId,
    'partnerCode' => MOMO_PARTNER_CODE,
    'redirectUrl' => MOMO_REDIRECT_URL,
    'requestId' => $requestId,
    'requestType' => 'captureWallet',
  ];

  $payload = $signatureData + [
    'lang' => 'vi',
    'partnerName' => 'ShoeStore',
    'storeId' => 'ShoeStore',
    'autoCapture' => true,
    'signature' => momo_sign($signatureData),
  ];

  $response = momo_create_payment_request($payload);
  if ((int)($response['resultCode'] ?? -1) !== 0) {
    mark_order_cancelled($orderId);
    throw new RuntimeException((string)($response['message'] ?? 'Không tạo được thanh toán MoMo.'));
  }

  create_momo_transaction($orderId, $requestId, $momoOrderId, (float)$order['total_amount'], $response);

  $payUrl = trim((string)($response['payUrl'] ?? ''));
  if ($payUrl === '') {
    throw new RuntimeException('MoMo không trả về đường dẫn thanh toán.');
  }

  header('Location: ' . $payUrl);
  exit;
} catch (Throwable $e) {
  set_flash('error', $e->getMessage() ?: 'Có lỗi khi thanh toán.');
  redirect('user/checkout.php');
}

