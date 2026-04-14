<?php

declare(strict_types=1);

function vnpay_hash_data(array $params): string
{
  // VNPay yêu cầu:
  // - sort key tăng dần
  // - KHÔNG đưa vnp_SecureHash, vnp_SecureHashType vào dữ liệu ký
  // - encode theo kiểu urlencode (space => '+') tương đương RFC1738
  unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

  // Chỉ ký các key vnp_ và bỏ giá trị null/'' để tránh lệch chữ ký
  $filtered = [];
  foreach ($params as $k => $v) {
    $k = (string)$k;
    if (strpos($k, 'vnp_') !== 0) {
      continue;
    }
    if ($v === null) {
      continue;
    }
    $v = (string)$v;
    if ($v === '') {
      continue;
    }
    $filtered[$k] = $v;
  }
  $params = $filtered;
  ksort($params);
  return http_build_query($params, '', '&', PHP_QUERY_RFC1738);
}

function vnpay_sign(array $params): string
{
  return hash_hmac('sha512', vnpay_hash_data($params), VNPAY_HASH_SECRET);
}

function vnpay_create_payment_url(array $params): string
{
  $signedParams = $params;
  $signedParams['vnp_SecureHashType'] = 'HmacSHA512';
  $signedParams['vnp_SecureHash'] = vnpay_sign($params);
  return VNPAY_URL . '?' . http_build_query($signedParams, '', '&', PHP_QUERY_RFC1738);
}

function vnpay_verify_signature(array $data): bool
{
  if (!isset($data['vnp_SecureHash'])) {
    return false;
  }

  $signature = (string)$data['vnp_SecureHash'];
  return hash_equals(vnpay_sign($data), $signature);
}
