<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Chi tiết đơn hàng';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
  set_flash('error', 'ID đơn hàng không hợp lệ.');
  redirect('admin/orders.php');
}

$detail = admin_fetch_order_detail($order_id);
if (!$detail) {
  set_flash('error', 'Không tìm thấy đơn hàng.');
  redirect('admin/orders.php');
}

$order = $detail['order'];
$items = $detail['items'];

$stats = admin_stats();
$adminActive = 'orders';
$adminHeading = 'Chi tiết đơn #' . (int)$order['id'];
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Đơn hàng', 'url' => route_url('admin', 'orders')],
  ['label' => 'Chi tiết', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="mb-3">
  <a href="<?= e(route_url('admin', 'orders')) ?>" class="btn btn-outline-secondary btn-sm">← Quay lại danh sách</a>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="bg-white border rounded-lg p-4 md:col-span-1">
    <div class="text-sm text-gray-600">Khách hàng</div>
    <div class="mt-2 font-semibold"><?= e($order['user_name'] ?? '') ?></div>
    <div class="text-sm text-gray-500 mt-1"><?= e($order['user_email'] ?? '') ?></div>
  </div>

  <div class="bg-white border rounded-lg p-4 md:col-span-1">
    <div class="text-sm text-gray-600">Trạng thái</div>
    <?php
      $st = (string)($order['status'] ?? '');
      $badge = 'bg-gray-100 text-gray-700 border-gray-200';
      if ($st === 'paid') $badge = 'bg-green-50 text-green-800 border-green-200';
      if ($st === 'pending') $badge = 'bg-yellow-50 text-yellow-800 border-yellow-200';
      if ($st === 'cancelled') $badge = 'bg-red-50 text-red-800 border-red-200';
    ?>
    <div class="mt-3">
      <span class="inline-flex items-center px-3 py-1 rounded border text-sm <?= $badge ?>">
        <?= e($st) ?>
      </span>
    </div>
    <div class="text-sm text-gray-500 mt-3">
      Ngày tạo: <?= e(date('d/m/Y H:i', strtotime((string)$order['created_at'])) ) ?>
    </div>
  </div>

  <div class="bg-white border rounded-lg p-4 md:col-span-1">
    <div class="text-sm text-gray-600">Tổng tiền</div>
    <div class="text-3xl font-bold text-gray-900 mt-2">
      <?= number_format((float)$order['total_amount'], 0, ',', '.') ?> VND
    </div>
  </div>
</div>

<div class="mt-6 bg-white border rounded-lg overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-600 border-b">
        <tr>
          <th class="py-3 px-3 text-left">Sản phẩm</th>
          <th class="py-3 px-3 text-left">SL</th>
          <th class="py-3 px-3 text-left">Đơn giá</th>
          <th class="py-3 px-3 text-left">Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$items): ?>
          <tr>
            <td colspan="4" class="py-6 text-center text-gray-600">
              Đơn hàng này chưa có item.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($items as $it): ?>
            <?php $qty = (int)($it['quantity'] ?? 0); ?>
            <?php $unit = (float)($it['unit_price'] ?? 0); ?>
            <?php $lineTotal = $unit * $qty; ?>
            <tr class="border-b last:border-b-0">
              <td class="py-3 px-3">
                <div class="flex items-center gap-3">
                  <div class="w-14 h-10 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                    <?php if (!empty($it['image_path'])): ?>
                      <img src="<?= e(app_url($it['image_path'])) ?>" alt="<?= e($it['product_name'] ?? '') ?>" class="w-full h-full object-cover">
                    <?php endif; ?>
                  </div>
                  <div>
                    <div class="font-medium"><?= e($it['product_name'] ?? '') ?></div>
                    <div class="text-xs text-gray-500">ID: <?= (int)($it['product_id'] ?? 0) ?></div>
                  </div>
                </div>
              </td>
              <td class="py-3 px-3"><?= $qty ?></td>
              <td class="py-3 px-3 font-medium"><?= number_format($unit, 0, ',', '.') ?> VND</td>
              <td class="py-3 px-3 font-bold"><?= number_format($lineTotal, 0, ',', '.') ?> VND</td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>

