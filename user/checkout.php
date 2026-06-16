<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Thanh toán';

$uid = current_user_id();
$items = cart_get_items($uid);

$old = $_SESSION['checkout_buyer'] ?? [];
$old_phone = is_array($old) ? (string)($old['buyer_phone'] ?? '') : '';
$old_house = is_array($old) ? (string)($old['addr_house'] ?? '') : '';
$old_hamlet = is_array($old) ? (string)($old['addr_hamlet'] ?? '') : '';
$old_commune = is_array($old) ? (string)($old['addr_commune'] ?? '') : '';
$old_province = is_array($old) ? (string)($old['addr_province'] ?? '') : '';
$old_payment_method = is_array($old) ? (string)($old['payment_method'] ?? 'momo') : 'momo';

$total = 0.0;
foreach ($items as $it) {
    $total += ((float)$it['price']) * (int)$it['quantity'];
}

if (!$items) {
    set_flash('error', 'Giỏ hàng của bạn đang trống.');
    redirect('user/cart.php');
}

require_once __DIR__ . '/../includes/layout/header.php';
?>

<form method="POST" action="<?= e(app_url('user/checkout_confirm.php')) ?>">

    <div class="mx-auto mt-24 max-w-6xl px-4 animate-on-scroll">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-dark-text mb-8">Thanh toán</h1>
        
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-10">

            <!-- LEFT -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Thông tin người mua -->
                <div class="rounded-2xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card p-6 shadow-lg card-hover">
                    <h2 class="mb-6 pb-4 text-xl font-semibold text-gray-900 dark:text-dark-text border-b border-gray-200 dark:border-dark-border">
                        <i class="bi bi-geo-alt-fill mr-2 text-red-600 dark:text-red-400"></i>Thông tin người mua
                    </h2>
                    <div class="space-y-5">
                        <!-- Số điện thoại -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input name="buyer_phone" value="<?= e($old_phone) ?>" required class="w-full rounded-xl border-2 border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-gray-800 
                       px-4 py-3 text-base 
                       focus:bg-white dark:focus:bg-dark-card focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 
                       transition-all duration-300" placeholder="Nhập số điện thoại">
                        </div>

                        <!-- House + Hamlet -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                                    Số nhà <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_house" value="<?= e($old_house) ?>" required class="w-full rounded-xl border-2 border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-gray-800 
                           px-4 py-3 text-base 
                           focus:bg-white dark:focus:bg-dark-card focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                                    Thôn <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_hamlet" value="<?= e($old_hamlet) ?>" required class="w-full rounded-xl border-2 border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-gray-800 
                           px-4 py-3 text-base 
                           focus:bg-white dark:focus:bg-dark-card focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300">
                            </div>

                        </div>

                        <!-- Commune + Province -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                                    Xã <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_commune" value="<?= e($old_commune) ?>" required class="w-full rounded-xl border-2 border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-gray-800 
                           px-4 py-3 text-base 
                           focus:bg-white dark:focus:bg-dark-card focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                                    Tỉnh <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_province" value="<?= e($old_province) ?>" required class="w-full rounded-xl border-2 border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-gray-800 
                           px-4 py-3 text-base 
                           focus:bg-white dark:focus:bg-dark-card focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300">
                            </div>

                        </div>
                        <!-- Ghi chú đơn hàng -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                                Ghi chú đơn hàng
                            </label>

                            <textarea name="order_note" rows="3" class="w-full rounded-xl border-2 border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-gray-800 
                                    px-4 py-3 text-base resize-none
                                    focus:bg-white dark:focus:bg-dark-card focus:outline-none 
                                    focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 
                                    transition-all duration-300" placeholder="Nhập ghi chú cho shop"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="rounded-2xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card p-6 shadow-lg card-hover">
                    <h2 class="mb-6 pb-4 text-xl font-semibold text-gray-900 dark:text-dark-text border-b border-gray-200 dark:border-dark-border">Phương thức thanh toán</h2>

                    <div class="space-y-3">

                        <!-- MoMo -->
                        <label
                            class="flex items-center gap-4 border-2 border-gray-200 dark:border-dark-border rounded-xl p-4 cursor-pointer hover:border-fuchsia-500 hover:shadow-md transition-all duration-300 group">
                            <input type="radio" name="payment_method" value="momo"
                                class="w-5 h-5 text-fuchsia-600 focus:ring-fuchsia-500"
                                <?= $old_payment_method === 'momo' ? 'checked' : '' ?>>

                            <img src="https://cdn.tgdd.vn/2020/03/GameApp/Untitled-2-200x200.jpg"
                                class="h-12 w-12 object-contain transition-transform duration-300 group-hover:scale-110">

                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-dark-text">MoMo sandbox</div>
                                <div class="text-sm text-gray-500 dark:text-dark-muted">Thanh toán qua MoMo</div>
                            </div>
                            <i class="bi bi-check-circle text-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </label>

                        <!-- VNPay -->
                        <label
                            class="flex items-center gap-4 border-2 border-gray-200 dark:border-dark-border rounded-xl p-4 cursor-pointer hover:border-blue-500 hover:shadow-md transition-all duration-300 group">
                            <input type="radio" name="payment_method" value="vnpay"
                                class="w-5 h-5 text-blue-600 focus:ring-blue-500"
                                <?= $old_payment_method === 'vnpay' ? 'checked' : '' ?>>

                            <img src="https://cdn.tgdd.vn/GameApp/2/320280/Screentshots/vnpay-nang-cao-trai-nghiem-thanh-toan-voi-vi-dien-tu-logo-13-12-2023.png"
                                class="h-12 w-12 object-contain transition-transform duration-300 group-hover:scale-110">

                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-dark-text">VNPay sandbox</div>
                                <div class="text-sm text-gray-500 dark:text-dark-muted">Thanh toán qua VNPay</div>
                            </div>
                            <i class="bi bi-check-circle text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </label>

                        <!-- COD -->
                        <label
                            class="flex items-center gap-4 border-2 border-gray-200 dark:border-dark-border rounded-xl p-4 cursor-pointer hover:border-green-500 hover:shadow-md transition-all duration-300 group">
                            <input type="radio" name="payment_method" value="cod"
                                class="w-5 h-5 text-green-600 focus:ring-green-500"
                                <?= $old_payment_method === 'cod' ? 'checked' : '' ?>>

                            <img src="https://cdn-icons-png.freepik.com/512/7630/7630510.png" alt=""
                                class="h-12 w-12 object-contain transition-transform duration-300 group-hover:scale-110">

                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-dark-text">Thanh toán khi nhận hàng</div>
                                <div class="text-sm text-gray-500 dark:text-dark-muted">Trả tiền khi nhận hàng</div>
                            </div>
                            <i class="bi bi-check-circle text-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </label>

                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card p-6 shadow-lg sticky top-24 animate-on-scroll" style="animation-delay: 100ms">

                    <h2 class="mb-6 pb-4 text-xl font-semibold text-gray-900 dark:text-dark-text border-b border-gray-200 dark:border-dark-border"><i
                            class="bi bi-cart-check mr-2 text-red-600 dark:text-red-400"></i>Đơn hàng của bạn</h2>
                    <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                        <?php foreach ($items as $it): ?>
                        <div class="flex justify-between text-sm border-b border-gray-100 dark:border-dark-border pb-3">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-dark-text"><?= e($it['name']) ?></div>
                                <div class="text-gray-500 dark:text-dark-muted mt-1">
                                    Size <?= (int)$it['shoe_size'] ?> × <?= (int)$it['quantity'] ?>
                                </div>
                            </div>
                            <div class="font-semibold text-red-600 dark:text-red-400 ml-4">
                                <?= number_format(((float)$it['price']) * (int)$it['quantity'], 0, ',', '.') ?>₫
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 pt-4 border-t-2 border-gray-200 dark:border-dark-border flex justify-between font-bold text-xl">
                        <span class="text-gray-900 dark:text-dark-text">Tổng</span>
                        <span class="text-red-600 dark:text-red-400">
                            <?= number_format((float)$total, 0, ',', '.') ?>₫
                        </span>
                    </div>

                    <button class="w-full mt-6 rounded-xl py-4 text-white font-bold text-lg 
                                bg-gradient-to-r from-red-600 to-red-700 
                                hover:from-red-700 hover:to-red-800 
                                transition-all duration-300 hover:scale-105 hover:shadow-xl btn-ripple">
                        <i class="bi bi-credit-card mr-2"></i> Thanh toán ngay
                    </button>

                    <a href="<?= e(app_url('user/cart.php')) ?>" class="block text-center mt-4 
                                    text-red-600 dark:text-red-400 
                                    text-sm font-medium 
                                    transition-all duration-300 hover:text-red-700 dark:hover:text-red-300 link-underline">
                        <i class="bi bi-arrow-left mr-1"></i> Quay lại giỏ hàng
                    </a>

                </div>
            </div>

        </div>
    </div>

</form>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>