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
$canRateProducts = ((string)($order['status'] ?? '') === 'paid');
$reviewedProductIds = [];
if ($canRateProducts) {
  foreach ($items as $item) {
    $productId = (int)($item['product_id'] ?? 0);
    if ($productId <= 0) {
      continue;
    }

    if (fetch_user_product_review($productId, $uid)) {
      $reviewedProductIds[$productId] = true;
    }
  }
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="flex items-center justify-between gap-4 mt-[60px]">
    <h1 class="text-2xl font-bold">
        Chi tiết đơn #<?= (int)$order['id'] ?>
    </h1>

    <a href="<?= e(app_url('user/order_history.php')) ?>"
        class="text-sm text-[var(--checkout-text-muted)] hover:underline transition">
        ← Quay lại
    </a>
</div>

<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT: PRODUCTS -->
    <div class="lg:col-span-2 bg-white border rounded-xl p-5 shadow-sm">

        <h2 class="font-semibold text-lg mb-4 border-b pb-3">
            Sản phẩm
        </h2>

        <div class="space-y-4">

            <?php foreach ($items as $it): ?>
            <div class="flex gap-4 items-center border-b pb-4 last:border-b-0">

                <!-- IMAGE -->
                <div class="w-20 h-16 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                    <?php if (!empty($it['image_path'])): ?>
                    <img src="<?= e(app_url($it['image_path'])) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                    <span class="text-gray-400 text-xs">No image</span>
                    <?php endif; ?>
                </div>

                <!-- INFO -->
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">
                        <?= e($it['name']) ?>
                    </div>

                    <div class="text-sm text-gray-500 mt-1">
                        Size: <?= (int)$it['shoe_size'] ?> •
                        SL: <?= (int)$it['quantity'] ?>
                    </div>

                    <div class="text-sm text-gray-600 mt-1">
                        Đơn giá:
                        <span class="text-red-600 font-medium">
                            <?= number_format((float)$it['unit_price'], 0, ',', '.') ?>₫
                        </span>
                    </div>
                </div>

                <!-- ACTION -->
                <div class="shrink-0">
                    <?php if ($canRateProducts): ?>
                    <?php $hasReviewed = !empty($reviewedProductIds[(int)$it['product_id']]); ?>

                    <a href="<?= e(app_url('user/product_rate.php?id=' . (int)$it['product_id'])) ?>" class="px-3 py-2 rounded-lg text-sm font-medium transition
                           <?= $hasReviewed
                              ? 'border border-gray-300 text-gray-700 hover:bg-gray-100'
                              : 'bg-blue-600 text-white hover:bg-blue-700' ?>">
                        <?= $hasReviewed ? 'Đã đánh giá' : 'Đánh giá' ?>
                    </a>

                    <?php else: ?>
                    <span class="text-xs text-gray-400">
                        Chưa thể đánh giá
                    </span>
                    <?php endif; ?>
                </div>

            </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- RIGHT: SUMMARY -->
    <div class="bg-white border rounded-xl p-5 shadow-sm h-fit">

        <h2 class="font-semibold text-lg mb-4 border-b pb-3">
            Tóm tắt đơn hàng
        </h2>

        <div class="space-y-3 text-sm">

            <div class="flex justify-between">
                <span class="text-gray-500">SĐT</span>
                <span class="font-medium"><?= e((string)$order['buyer_phone']) ?></span>
            </div>

            <div class="flex justify-between items-start gap-3">
                <span class="text-gray-500">Địa chỉ</span>
                <span class="font-medium text-right">
                    <?= e(trim(
            (string)($order['addr_house'] ?? '') . ', ' .
              (string)($order['addr_hamlet'] ?? '') . ', ' .
              (string)($order['addr_commune'] ?? '') . ', ' .
              (string)($order['addr_province'] ?? '')
          )) ?>
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Ngày tạo</span>
                <span class="font-medium">
                    <?= e(date('d/m/Y H:i', strtotime((string)$order['created_at']))) ?>
                </span>
            </div>

            <!-- STATUS -->
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Trạng thái</span>

                <?php
                $orderStatus = (string)($order['status'] ?? '');
                $orderPayment = (string)($order['payment_method'] ?? '');
                ?>
                <?php if ($orderStatus === 'paid'): ?>
                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600">
                    Đã thanh toán
                </span>
                <?php elseif ($orderStatus === 'cancelled'): ?>
                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-600">
                    Đơn hàng đã hủy
                </span>
                <?php elseif ($orderStatus === 'pending' && $orderPayment === 'cod'): ?>
                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600">
                    Chờ xử lý
                </span>
                <?php elseif ($orderStatus === 'pending'): ?>
                <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-800">
                    Chờ thanh toán
                </span>
                <?php else: ?>
                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                    <?= e($orderStatus !== '' ? $orderStatus : '—') ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Thanh toán</span>
                <span class="font-medium">
                    <?= e($paymentLabels[(string)($order['payment_method'] ?? '')] ?? '') ?>
                </span>
            </div>

            <!-- TOTAL -->
            <div class="flex justify-between items-center pt-4 border-t mt-4">
                <span class="text-gray-700">Tổng tiền</span>
                <span class="text-xl font-bold text-red-600">
                    <?= number_format((float)$order['total_amount'], 0, ',', '.') ?>₫
                </span>
            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>