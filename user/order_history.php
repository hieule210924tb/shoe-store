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

<div class="flex items-center justify-between gap-4 mt-[60px]">
    <h1 class="text-2xl font-bold">Đơn đã mua</h1>
    <a href="<?= e(app_url('user/index.php')) ?>"
        class="text-sm text-[var(--checkout-text-muted)] hover:underline transition">
        ← Tiếp tục mua
    </a>
</div>

<?php if (!$orders): ?>
<div class="mt-6 rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">
    Bạn chưa có đơn hàng nào.
</div>
<?php else: ?>

<div class="mt-6 space-y-4">

    <?php foreach ($orders as $o): ?>

    <div class="rounded-xl border bg-white p-5 shadow-sm hover:shadow-md transition">

        <!-- TOP -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

            <div class="text-sm text-gray-500">
                <span class="font-medium text-gray-900">
                    #<?= (int)$o['id'] ?>
                </span>
                • <?= e(date('d/m/Y H:i', strtotime((string)$o['created_at']))) ?>
                • <?= e($paymentLabels[(string)($o['payment_method'] ?? '')] ?? (string)($o['payment_method'] ?? '')) ?>
            </div>

            <!-- STATUS -->
            <div>
                <?php if ((string)($o['status'] ?? '') === 'paid'): ?>
                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600 font-medium">
                    Đã thanh toán
                </span>
                <?php else: ?>
                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600 font-medium">
                    Chờ xử lý
                </span>
                <?php endif; ?>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-4 gap-3">

            <!-- TOTAL -->
            <div class="text-lg font-bold text-red-600">
                <?= number_format((float)$o['total_amount'], 0, ',', '.') ?>₫
            </div>

            <!-- ACTION -->
            <div class="flex items-center gap-2">

                <?php if ((string)($o['status'] ?? '') === 'paid'): ?>
                <a href="<?= e(app_url('user/order_detail.php?id=' . (int)$o['id'])) ?>" class="px-4 py-2 rounded-lg text-sm font-medium
                          text-blue-600 bg-blue-50
                          hover:bg-blue-100 transition">
                    Đánh giá
                </a>
                <?php endif; ?>

                <a href="<?= e(app_url('user/order_detail.php?id=' . (int)$o['id'])) ?>" class="px-4 py-2 rounded-lg text-sm font-medium
                          border border-gray-300 text-gray-700
                          hover:bg-gray-100 transition">
                    Xem chi tiết
                </a>

            </div>

        </div>

    </div>

    <?php endforeach; ?>

</div>

<!-- PAGINATION -->
<?php if ($total_pages > 1): ?>
<div class="mt-8 flex items-center justify-center gap-2 flex-wrap">

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
    <?php $active = ($i === $page); ?>

    <a href="<?= e(app_url('user/order_history.php?page=' . $i)) ?>" class="px-3 py-2 text-sm rounded-lg transition
       <?= $active
          ? 'bg-gray-900 text-white'
          : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100' ?>">
        <?= $i ?>
    </a>

    <?php endfor; ?>

</div>
<?php endif; ?>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>