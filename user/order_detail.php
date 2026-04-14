<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Chi tiết đơn hàng';

$uid = current_user_id();
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
  set_flash('error', 'ID đơn hàng không hợp lệ.');
  redirect('user/order_history.php');
}

$detail = fetch_order_detail_for_user($order_id, $uid);
if (!$detail) {
  set_flash('error', 'Không tìm thấy đơn hàng của bạn.');
  redirect('user/order_history.php');
}

$order = $detail['order'];
$items = $detail['items'];
$paymentLabels = [
  'momo' => 'MoMo',
  'vnpay' => 'VNPay',
  'cod' => 'Thanh toán khi nhận hàng',
];

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="flex items-center justify-between gap-4">
  <h1 class="text-2xl font-bold">Chi tiết đơn #<?= (int)$order['id'] ?></h1>
  <a href="<?= e(app_url('user/order_history.php')) ?>" class="text-sm text-blue-700 hover:underline">← Quay lại</a>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="bg-white border rounded-lg p-4 md:p-6">
    <h2 class="font-semibold text-lg mb-3">Sản phẩm</h2>

    <div class="space-y-4">
      <?php foreach ($items as $it): ?>
        <div class="flex gap-3 items-center">
          <div class="w-16 h-12 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
            <?php if (!empty($it['image_path'])): ?>
              <img src="<?= e(app_url($it['image_path'])) ?>" alt="<?= e($it['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="text-gray-400 text-xs">No image</div>
            <?php endif; ?>
          </div>
          <div class="flex-1">
            <div class="font-semibold"><?= e($it['name']) ?></div>
            <div class="text-sm text-gray-600">
              Size: <?= (int)$it['shoe_size'] ?> •
              SL: <?= (int)$it['quantity'] ?> •
              Đơn giá: <?= number_format((float)$it['unit_price'], 0, ',', '.') ?> VND
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="bg-white border rounded-lg p-4 md:p-6">
    <h2 class="font-semibold text-lg mb-3">Tóm tắt</h2>

    <div class="space-y-2 text-sm text-gray-700">
      <div class="flex items-center justify-between">
        <span>SĐT</span>
        <span class="font-medium text-gray-900"><?= e((string)($order['buyer_phone'] ?? '')) ?></span>
      </div>
      <div class="flex items-start justify-between gap-4">
        <span>Địa chỉ</span>
        <span class="font-medium text-gray-900 text-right">
          <?= e(trim(
            (string)($order['addr_house'] ?? '') . ', ' .
            (string)($order['addr_hamlet'] ?? '') . ', ' .
            (string)($order['addr_commune'] ?? '') . ', ' .
            (string)($order['addr_province'] ?? '')
          )) ?>
        </span>
      </div>
      <div class="flex items-center justify-between">
        <span>Ngày tạo</span>
        <span class="font-medium text-gray-900"><?= e(date('d/m/Y H:i', strtotime((string)$order['created_at']))) ?></span>
      </div>
      <div class="flex items-center justify-between">
        <span>Trạng thái</span>
        <span class="font-medium text-gray-900"><?= e((string)$order['status']) ?></span>
      </div>
      <div class="flex items-center justify-between">
        <span>Thanh toán</span>
        <span class="font-medium text-gray-900"><?= e($paymentLabels[(string)($order['payment_method'] ?? '')] ?? (string)($order['payment_method'] ?? '')) ?></span>
      </div>
      <div class="flex items-center justify-between pt-3 border-t">
        <span>Tổng tiền</span>
        <span class="text-xl font-bold text-gray-900"><?= number_format((float)$order['total_amount'], 0, ',', '.') ?> VND</span>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

