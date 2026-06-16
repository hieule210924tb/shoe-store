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

<div class="flex items-center justify-between gap-4 mt-[100px] animate-on-scroll">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-dark-text">Giỏ hàng</h1>
    <a href="<?= e(app_url('user/index.php')) ?>"
        class="inline-flex items-center text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium transition link-underline">
        <i class="bi bi-arrow-left mr-2"></i> Tiếp tục mua
    </a>
</div>

<?php if (!$items): ?>
<div class="mt-8 rounded-2xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card p-12 text-center text-gray-500 dark:text-dark-muted shadow-lg animate-fade-in">
    <i class="bi bi-cart-x text-6xl mb-4 block opacity-50"></i>
    <p class="text-lg font-medium mb-2">Giỏ hàng của bạn đang trống.</p>
    <a href="<?= e(app_url('user/index.php')) ?>" class="inline-flex items-center mt-4 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 btn-ripple">
        <i class="bi bi-bag mr-2"></i> Bắt đầu mua sắm
    </a>
</div>
<?php else: ?>
<div class="space-y-4 mt-6">

    <?php foreach ($items as $index => $it): ?>
    <?php $line = ((float)$it['price']) * (int)$it['quantity']; ?>

    <div class="flex flex-col lg:flex-row gap-4 rounded-2xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card p-5 shadow-sm card-hover animate-on-scroll" style="animation-delay: <?= $index * 100 ?>ms">

        <!-- IMAGE -->
        <div class="w-full lg:w-32 h-28 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden flex items-center justify-center img-zoom-container">
            <?php if (!empty($it['image_path'])): ?>
            <img src="<?= e(app_url($it['image_path'])) ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
            <?php else: ?>
            <span class="text-gray-400 text-xs">No image</span>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="flex-1">
            <div class="font-semibold text-base text-gray-900 dark:text-dark-text">
                <?= e($it['name']) ?>
            </div>

            <div class="text-sm text-gray-500 dark:text-dark-muted mt-1 flex items-center gap-2">
                <i class="bi bi-rulers"></i>
                Size: <strong><?= (int)$it['shoe_size'] ?></strong>
            </div>

            <div class="text-sm mt-2 flex items-center gap-2">
                <span class="text-gray-600 dark:text-dark-muted">Giá:</span>
                <span class="font-bold text-red-600 dark:text-red-400 text-lg">
                    <?= number_format((float)$it['price'], 0, ',', '.') ?>₫
                </span>
            </div>
        </div>

        <!-- ACTION -->
        <div class="flex flex-col gap-3 lg:w-72">

            <form method="POST" action="<?= e(app_url('user/cart_update.php')) ?>">

                <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">

                <!-- Control box -->
                <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-800 border-2 border-gray-200 dark:border-dark-border rounded-xl p-2">

                    <!-- Size -->
                    <select name="shoe_size"
                        class="flex-1 border-0 rounded-lg px-3 py-2 text-sm bg-white dark:bg-dark-card focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 transition">
                        <?php foreach (get_shoe_sizes() as $size): ?>
                        <option value="<?= $size ?>" <?= $size == $it['shoe_size'] ? 'selected' : '' ?>>
                            <?= $size ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Quantity -->
                    <div class="quantity-selector flex items-center border-2 border-gray-200 dark:border-dark-border rounded-lg overflow-hidden">
                        <button type="button" class="quantity-minus px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <i class="bi bi-dash text-sm"></i>
                        </button>
                        <input type="number" name="quantity" min="1" max="<?= (int)$it['stock_qty'] ?>"
                            value="<?= (int)$it['quantity'] ?>"
                            class="w-16 text-center border-0 bg-white dark:bg-dark-card text-sm focus:outline-none">
                        <button type="button" class="quantity-plus px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <i class="bi bi-plus text-sm"></i>
                        </button>
                    </div>

                    <!-- Update button -->
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-all duration-300 hover:scale-105 btn-ripple">
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
               transition-all duration-300 hover:scale-105 hover:shadow-lg btn-ripple">
                    <i class="bi bi-trash mr-2"></i> Xoá sản phẩm
                </button>
            </form>

        </div>

        <!-- PRICE -->
        <div class="text-right font-semibold text-gray-900 dark:text-dark-text lg:w-36">
            <div class="text-2xl font-bold text-red-600 dark:text-red-400"><?= number_format($line, 0, ',', '.') ?>₫</div>
        </div>

    </div>
    <?php endforeach; ?>

    <!-- TOTAL -->
    <div class="flex justify-between items-center mt-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-dark-border animate-on-scroll">
        <div class="flex items-center gap-3">
            <i class="bi bi-cart-check text-2xl text-red-600 dark:text-red-400"></i>
            <span class="text-lg font-medium text-gray-700 dark:text-dark-text">Tổng tiền</span>
        </div>
        <span class="text-3xl font-bold text-red-600 dark:text-red-400 animate-fade-in">
            <?= number_format($total, 0, ',', '.') ?>₫
        </span>
    </div>

    <!-- CHECKOUT -->
    <div class="mt-6 animate-on-scroll">
        <a href="<?= e(app_url('user/checkout.php')) ?>" class="block w-full text-center rounded-xl py-4 font-bold text-lg
               text-white
               bg-gradient-to-r from-red-600 to-red-700
               hover:from-red-700 hover:to-red-800
               transition-all duration-300 hover:scale-105 hover:shadow-xl btn-ripple">
            <i class="bi bi-credit-card mr-2"></i> Thanh toán ngay
        </a>
    </div>

</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>