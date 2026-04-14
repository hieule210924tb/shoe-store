<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Lịch sử đơn hàng';

$uid = current_user_id();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$per_page = 6;

$total = count_user_orders($uid);
$total_pages = (int)ceil($total / $per_page);
if ($total_pages > 0 && $page > $total_pages) {
  $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

$orders = fetch_user_orders($uid, $offset, $per_page);
$paymentLabels = [
  'momo' => 'MoMo',
  'vnpay' => 'VNPay',
  'cod' => 'COD',
];

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="flex items-center justify-between gap-4">
  <h1 class="text-2xl font-bold">Đơn đã mua</h1>
  <a href="<?= e(app_url('user/index.php')) ?>" class="text-sm text-blue-700 hover:underline">Tiếp tục mua</a>
</div>

<?php if (!$orders): ?>
  <div class="mt-6 rounded border bg-white p-6 text-center text-gray-600">
    Bạn chưa có đơn hàng nào.
  </div>
<?php else: ?>
  <div class="mt-6 bg-white border rounded-lg p-4 md:p-6">
    <div class="space-y-3">
      <?php foreach ($orders as $o): ?>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 border-b last:border-b-0 pb-3 last:pb-0">
          <div class="text-sm text-gray-600">
            Mã đơn: <span class="font-medium text-gray-900">#<?= (int)$o['id'] ?></span>
            • <?= e(date('d/m/Y H:i', strtotime((string)$o['created_at']))) ?>
            • <?= e($paymentLabels[(string)($o['payment_method'] ?? '')] ?? (string)($o['payment_method'] ?? '')) ?>
          </div>
          <div class="flex items-center justify-between gap-3">
            <div class="font-bold">
              <?= number_format((float)$o['total_amount'], 0, ',', '.') ?> VND
            </div>
            <a href="<?= e(app_url('user/order_detail.php?id=' . (int)$o['id'])) ?>"
               class="text-sm px-3 py-2 rounded border hover:bg-gray-50">
              Xem chi tiết
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
      <div class="mt-6 flex items-center justify-center gap-2">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php $active = ($i === $page); ?>
          <a
            href="<?= e(app_url('user/order_history.php?page=' . $i)) ?>"
            class="px-3 py-2 rounded border text-sm
              <?= $active ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' ?>"
          >
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

