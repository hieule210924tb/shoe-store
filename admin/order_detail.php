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
$paymentLabels = [
  'momo' => 'MoMo',
  'vnpay' => 'VNPay',
  'cod' => 'Thanh toán khi nhận hàng',
];

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

<div class="row">
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card card-outline card-secondary h-100">
      <div class="card-body">
        <div class="text-muted small text-uppercase">Khách hàng</div>
        <div class="mt-2 font-weight-bold"><?= e($order['user_name'] ?? '') ?></div>
        <div class="small text-muted mt-1"><?= e($order['user_email'] ?? '') ?></div>
        <?php if (!empty($order['buyer_phone'])): ?>
          <div class="small text-muted mt-1">SĐT: <?= e((string)$order['buyer_phone']) ?></div>
        <?php endif; ?>
        <?php
          $addr = trim(
            (string)($order['addr_house'] ?? '') . ', ' .
            (string)($order['addr_hamlet'] ?? '') . ', ' .
            (string)($order['addr_commune'] ?? '') . ', ' .
            (string)($order['addr_province'] ?? '')
          );
        ?>
        <?php if ($addr !== ', , ,'): ?>
          <div class="small text-muted mt-1">Đ/c: <?= e($addr) ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card card-outline card-secondary h-100">
      <div class="card-body">
        <div class="text-muted small text-uppercase">Trạng thái</div>
        <?php
          $st = (string)($order['status'] ?? '');
          $badgeClass = 'badge-secondary';
          if ($st === 'paid') {
            $badgeClass = 'badge-success';
          }
          if ($st === 'pending') {
            $badgeClass = 'badge-warning';
          }
          if ($st === 'cancelled') {
            $badgeClass = 'badge-danger';
          }
        ?>
        <div class="mt-3">
          <span class="badge <?= e($badgeClass) ?> badge-pill px-3 py-2"><?= e($st) ?></span>
        </div>
        <div class="small text-muted mt-2">
          Thanh toán: <?= e($paymentLabels[(string)($order['payment_method'] ?? '')] ?? (string)($order['payment_method'] ?? '')) ?>
        </div>
        <div class="small text-muted mt-3 mb-0">
          Ngày tạo: <?= e(date('d/m/Y H:i', strtotime((string)$order['created_at']))) ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-outline card-primary h-100">
      <div class="card-body">
        <div class="text-muted small text-uppercase">Tổng tiền</div>
        <div class="h3 font-weight-bold text-dark mb-0 mt-2">
          <?= number_format((float)$order['total_amount'], 0, ',', '.') ?> VND
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header">
    <h3 class="card-title mb-0">Sản phẩm trong đơn</h3>
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-striped mb-0">
      <thead>
        <tr>
          <th>Sản phẩm</th>
          <th class="text-nowrap" style="width: 90px;">Size</th>
          <th class="text-nowrap" style="width: 80px;">SL</th>
          <th class="text-nowrap">Đơn giá</th>
          <th class="text-nowrap">Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$items): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              Đơn hàng này chưa có item.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($items as $it): ?>
            <?php $qty = (int)($it['quantity'] ?? 0); ?>
            <?php $unit = (float)($it['unit_price'] ?? 0); ?>
            <?php $lineTotal = $unit * $qty; ?>
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="mr-3 bg-light rounded overflow-hidden d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 40px;">
                    <?php if (!empty($it['image_path'])): ?>
                      <img src="<?= e(app_url($it['image_path'])) ?>" alt="<?= e($it['product_name'] ?? '') ?>" class="img-fluid" style="max-height: 40px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                      <i class="fas fa-image text-muted"></i>
                    <?php endif; ?>
                  </div>
                  <div>
                    <div class="font-weight-medium"><?= e($it['product_name'] ?? '') ?></div>
                    <div class="small text-muted">ID: <?= (int)($it['product_id'] ?? 0) ?></div>
                  </div>
                </div>
              </td>
              <td><?= (int)($it['shoe_size'] ?? 0) ?></td>
              <td><?= $qty ?></td>
              <td class="font-weight-medium"><?= number_format($unit, 0, ',', '.') ?> VND</td>
              <td class="font-weight-bold"><?= number_format($lineTotal, 0, ',', '.') ?> VND</td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
