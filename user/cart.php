<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Giỏ hàng';

$uid = current_user_id();
$items = cart_get_items($uid);
$total = 0.0;
foreach ($items as $it) {
  $total += ((float)$it['price']) * (int)$it['quantity'];
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="flex items-center justify-between gap-4 mt-[80px]">
    <h1 class="text-2xl font-bold">Giỏ hàng</h1>
    <a href="<?= e(app_url('user/index.php')) ?>"
        class="text-sm text-[var(--checkout-text-muted)] hover:underline transition">
        ← Tiếp tục mua
    </a>
</div>

<?php if (!$items): ?>
<div class="mt-6 rounded-xl border bg-white p-8 text-center text-gray-500 shadow-sm">
    Giỏ hàng của bạn đang trống.
</div>
<?php else: ?>
<div class="space-y-4 mt-3">

    <?php foreach ($items as $it): ?>
    <?php $line = ((float)$it['price']) * (int)$it['quantity']; ?>

    <div class="flex flex-col lg:flex-row gap-4 rounded-xl border bg-white p-4 shadow-sm">

        <!-- IMAGE -->
        <div class="w-full lg:w-28 h-24 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
            <?php if (!empty($it['image_path'])): ?>
            <img src="<?= e(app_url($it['image_path'])) ?>" class="w-full h-full object-cover">
            <?php else: ?>
            <span class="text-gray-400 text-xs">No image</span>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="flex-1">
            <div class="font-semibold text-base text-gray-900">
                <?= e($it['name']) ?>
            </div>

            <div class="text-sm text-gray-500 mt-1">
                Size: <?= (int)$it['shoe_size'] ?>
            </div>

            <div class="text-sm mt-1">
                Giá:
                <span class="font-medium text-red-600">
                    <?= number_format((float)$it['price'], 0, ',', '.') ?>₫
                </span>
            </div>
        </div>

        <!-- ACTION -->
        <div class="flex flex-col gap-3 lg:w-64">

            <form method="POST" action="<?= e(app_url('user/cart_update.php')) ?>">

                <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">

                <!-- Control box -->
                <div class="flex items-center gap-2 bg-gray-50 border rounded-lg p-2">

                    <!-- Size -->
                    <select name="shoe_size"
                        class="flex-1 border rounded px-2 py-1 text-sm bg-white focus:ring-1 focus:ring-fuchsia-500">
                        <?php foreach (get_shoe_sizes() as $size): ?>
                        <option value="<?= $size ?>" <?= $size == $it['shoe_size'] ? 'selected' : '' ?>>
                            <?= $size ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Quantity -->
                    <input type="number" name="quantity" min="1" max="<?= (int)$it['stock_qty'] ?>"
                        value="<?= (int)$it['quantity'] ?>"
                        class="w-16 border rounded px-2 py-1 text-sm text-center bg-white focus:ring-1 focus:ring-fuchsia-500">

                    <!-- Update button -->
                    <button type="submit"
                        class="px-3 py-1.5 rounded text-sm font-medium text-white bg-blue-500 hover:bg-blue-600  transition">
                        Cập nhật
                    </button>

                </div>
            </form>

            <!-- Delete -->
            <form method="POST" action="<?= e(app_url('user/cart_remove.php')) ?>">
                <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">

                <button type="submit" class="w-full text-sm text-center rounded-xl py-3 font-medium text-white
               bg-gradient-to-br from-red-500 to-red-600
               hover:from-red-600 hover:to-red-700
               transition">
                    <i class="bi bi-trash mr-2"></i> Xoá sản phẩm
                </button>
            </form>

        </div>

        <!-- PRICE -->
        <div class="text-right font-semibold text-gray-900 lg:w-32">
            <?= number_format($line, 0, ',', '.') ?>₫
        </div>

    </div>
    <?php endforeach; ?>

    <!-- TOTAL -->
    <div class="flex justify-between items-center mt-6 border-t pt-4">
        <span class="text-gray-600">Tổng tiền</span>
        <span class="text-xl font-bold text-red-600">
            <?= number_format($total, 0, ',', '.') ?>₫
        </span>
    </div>

    <!-- CHECKOUT -->
    <div class="mt-4">
        <a href="<?= e(app_url('user/checkout.php')) ?>" class="block text-center rounded-xl py-3 font-medium
               text-white
               bg-gradient-to-br from-red-500 to-red-600
               hover:from-red-600 hover:to-red-700
               transition">
            Thanh toán
        </a>
    </div>

</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>