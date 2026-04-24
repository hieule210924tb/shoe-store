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

    <div class="mx-auto mt-16 max-w-6xl px-4">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-10">

            <!-- LEFT -->
            <div class="lg:col-span-7 space-y-5">

                <!-- Thông tin người mua -->
                <div class="rounded-xl border bg-white p-5 shadow-sm">
                    <h2 class="mb-5 pb-5 text-lg border-b-2 border-[#ced4da] font-semibold">
                        <i class="bi bi-geo-alt-fill mr-2"></i>Thông tin người mua
                    </h2>
                    <div class="space-y-5">
                        <!-- Số điện thoại -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input name="buyer_phone" value="<?= e($old_phone) ?>" required class="w-full rounded-xl border border-gray-300 bg-gray-100 
                       px-4 py-3 text-base 
                       focus:bg-white focus:ring-2 focus:ring-fuchsia-500 
                       transition" placeholder="Nhập số điện thoại">
                        </div>

                        <!-- House + Hamlet -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Số nhà <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_house" value="<?= e($old_house) ?>" required class="w-full rounded-xl border border-gray-300 bg-gray-100 
                           px-4 py-3 text-base 
                           focus:bg-white focus:ring-2 focus:ring-fuchsia-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Thôn <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_hamlet" value="<?= e($old_hamlet) ?>" required class="w-full rounded-xl border border-gray-300 bg-gray-100 
                           px-4 py-3 text-base 
                           focus:bg-white focus:ring-2 focus:ring-fuchsia-500">
                            </div>

                        </div>

                        <!-- Commune + Province -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Xã <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_commune" value="<?= e($old_commune) ?>" required class="w-full rounded-xl border border-gray-300 bg-gray-100 
                           px-4 py-3 text-base 
                           focus:bg-white focus:ring-2 focus:ring-fuchsia-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tỉnh <span class="text-red-500">*</span>
                                </label>
                                <input name="addr_province" value="<?= e($old_province) ?>" required class="w-full rounded-xl border border-gray-300 bg-gray-100 
                           px-4 py-3 text-base 
                           focus:bg-white focus:ring-2 focus:ring-fuchsia-500">
                            </div>
                        </div>
                        <!-- Ghi chú đơn hàng -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú đơn hàng
                            </label>

                            <textarea name="order_note" rows="3" class="w-full rounded-xl border border-gray-300 bg-gray-100 
                                    px-4 py-3 text-base resize-none
                                    focus:bg-white focus:outline-none 
                                    focus:ring-2 focus:ring-fuchsia-500 
                                    transition" placeholder="Nhập ghi chú cho shop"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="rounded-xl border bg-white p-5 shadow-sm">
                    <h2 class="mb-5 pb-5 text-lg border-b-2 border-[#ced4da] font-semibold">Phương thức thanh toán</h2>

                    <div class="space-y-3">

                        <!-- MoMo -->
                        <label
                            class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-fuchsia-500">
                            <input type="radio" name="payment_method" value="momo"
                                <?= $old_payment_method === 'momo' ? 'checked' : '' ?>>

                            <img src="https://cdn.tgdd.vn/2020/03/GameApp/Untitled-2-200x200.jpg"
                                class="h-10 w-10 object-contain">

                            <div>
                                <div class="font-medium">MoMo sandbox</div>
                                <div class="text-sm text-gray-500">Thanh toán qua MoMo</div>
                            </div>
                        </label>

                        <!-- VNPay -->
                        <label
                            class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-blue-500">
                            <input type="radio" name="payment_method" value="vnpay"
                                <?= $old_payment_method === 'vnpay' ? 'checked' : '' ?>>

                            <img src="https://cdn.tgdd.vn/GameApp/2/320280/Screentshots/vnpay-nang-cao-trai-nghiem-thanh-toan-voi-vi-dien-tu-logo-13-12-2023.png"
                                class="h-10 w-10 object-contain">

                            <div>
                                <div class="font-medium">VNPay sandbox</div>
                                <div class="text-sm text-gray-500">Thanh toán qua VNPay</div>
                            </div>
                        </label>

                        <!-- COD -->
                        <label
                            class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-green-500">
                            <input type="radio" name="payment_method" value="cod"
                                <?= $old_payment_method === 'cod' ? 'checked' : '' ?>>

                            <img src="https://cdn-icons-png.freepik.com/512/7630/7630510.png" alt=""
                                class="h-10 w-10 object-contain">

                            <div>
                                <div class="font-medium">Thanh toán khi nhận hàng</div>
                                <div class="text-sm text-gray-500">Trả tiền khi nhận hàng</div>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="lg:col-span-3">
                <div class="rounded-xl border bg-white p-5 shadow-sm sticky top-5">

                    <h2 class="mb-5 pb-5 text-lg border-b-2 border-[#ced4da] font-semibold"><i
                            class="bi bi-cart-check mr-2"></i>Đơn hàng của bạn</h2>
                    <hr>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        <?php foreach ($items as $it): ?>
                        <div class="flex justify-between text-sm border-b pb-2">
                            <div>
                                <div><?= e($it['name']) ?></div>
                                <div class="text-gray-500">
                                    Size <?= (int)$it['shoe_size'] ?> × <?= (int)$it['quantity'] ?>
                                </div>
                            </div>
                            <div class="font-medium">
                                <?= number_format(((float)$it['price']) * (int)$it['quantity'], 0, ',', '.') ?>₫
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 flex justify-between font-semibold text-lg">
                        <span>Tổng</span>
                        <span class="text-fuchsia-700">
                            <?= number_format((float)$total, 0, ',', '.') ?>₫
                        </span>
                    </div>

                    <button class="w-full mt-3 rounded-lg py-3 text-white font-medium 
                                bg-gradient-to-br from-red-500 to-red-600 
                                hover:from-red-600 hover:to-red-700 
                                transition duration-200">
                        Thanh toán
                    </button>

                    <a href="<?= e(app_url('user/cart.php')) ?>" class="block text-center mt-4 
                                    text-[var(--checkout-text-muted)] 
                                    text-sm no-underline 
                                    transition-colors duration-200 
                                    hover:text-gray-800">
                        <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                    </a>

                </div>
            </div>

        </div>
    </div>

</form>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>