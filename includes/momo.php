<?php

declare(strict_types=1);

function momo_build_raw_signature(array $params): string
{
  $parts = [];
  foreach ($params as $key => $value) {
    $parts[] = $key . '=' . (string)$value;
  }
  return implode('&', $parts);
}

function momo_sign(array $params): string
{
  return hash_hmac('sha256', momo_build_raw_signature($params), MOMO_SECRET_KEY);
}

function momo_http_post_json(string $url, array $payload): array
{
  $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  if ($json === false) {
    throw new RuntimeException('Không thể mã hoá dữ liệu gửi tới MoMo.');
  }

  if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json),
      ],
      CURLOPT_POSTFIELDS => $json,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
      $message = curl_error($ch);
      curl_close($ch);
      throw new RuntimeException('Không gọi được MoMo: ' . $message);
    }
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  } else {
    $context = stream_context_create([
      'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n" .
          'Content-Length: ' . strlen($json) . "\r\n",
        'content' => $json,
        'timeout' => 30,
        'ignore_errors' => true,
      ],
    ]);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
      throw new RuntimeException('Không gọi được MoMo qua HTTP client.');
    }
    $status = 200;
    if (!empty($http_response_header) && preg_match('/\s(\d{3})\s/', (string)$http_response_header[0], $matches)) {
      $status = (int)$matches[1];
    }
  }

  $decoded = json_decode($response, true);
  if (!is_array($decoded)) {
    throw new RuntimeException('MoMo trả về dữ liệu không hợp lệ.');
  }
  if ($status >= 400) {
    $message = (string)($decoded['message'] ?? ('HTTP ' . $status));
    throw new RuntimeException('MoMo từ chối yêu cầu: ' . $message);
  }

  return $decoded;
}

function momo_create_payment_request(array $payload): array
{
  return momo_http_post_json(MOMO_ENDPOINT . '/v2/gateway/api/create', $payload);
}

function momo_verify_return_signature(array $data): bool
{
  if (!isset($data['signature'])) {
    return false;
  }

  $signature = (string)$data['signature'];
  $requiredCommon = [
    'amount', 'extraData', 'message', 'orderId', 'orderInfo',
    'orderType', 'partnerCode', 'payType', 'requestId', 'responseTime',
    'resultCode', 'transId',
  ];
  foreach ($requiredCommon as $key) {
    if (!array_key_exists($key, $data)) {
      return false;
    }
  }

  // MoMo callback thực tế có môi trường trả accessKey, có môi trường không trả.
  $paramsWithLocalAccessKey = [
    'accessKey' => MOMO_ACCESS_KEY,
    'amount' => (string)$data['amount'],
    'extraData' => (string)$data['extraData'],
    'message' => (string)$data['message'],
    'orderId' => (string)$data['orderId'],
    'orderInfo' => (string)$data['orderInfo'],
    'orderType' => (string)$data['orderType'],
    'partnerCode' => (string)$data['partnerCode'],
    'payType' => (string)$data['payType'],
    'requestId' => (string)$data['requestId'],
    'responseTime' => (string)$data['responseTime'],
    'resultCode' => (string)$data['resultCode'],
    'transId' => (string)$data['transId'],
  ];

  $paramsWithPayloadAccessKey = $paramsWithLocalAccessKey;
  if (isset($data['accessKey'])) {
    $paramsWithPayloadAccessKey['accessKey'] = (string)$data['accessKey'];
  }

  $sign1 = momo_sign($paramsWithLocalAccessKey);
  $sign2 = momo_sign($paramsWithPayloadAccessKey);
  return hash_equals($sign1, $signature) || hash_equals($sign2, $signature);
}

function momo_decode_extra_data(?string $extraData): array
{
  if ($extraData === null || $extraData === '') {
    return [];
  }
  $decoded = base64_decode($extraData, true);
  if ($decoded === false || $decoded === '') {
    return [];
  }
  $json = json_decode($decoded, true);
  return is_array($json) ? $json : [];
}
