<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$pageTitle = 'Thanh toán MoMo (demo)';

$uid = current_user_id();
$items = cart_get_items($uid);

$total = 0.0;
foreach ($items as $it) {
  $total += ((float)$it['price']) * (int)$it['quantity'];
}

if (!$items) {
  set_flash('error', 'Giỏ hàng của bạn đang trống.');
  redirect('user/cart.php');
}

// Tạo mã tham chiếu + thời gian hết hạn hiển thị (demo).
// (Logic trừ kho/tạo order thật vẫn diễn ra ở checkout_confirm.php)
$requestId = 'ORD' . date('Ymd') . strtoupper(substr(sha1((string)$uid . microtime(true)), 0, 8));
$expireSeconds = 14 * 60 + 51; // hiển thị giống ảnh demo: 14 phút 51 giây
$_SESSION['checkout_ref'] = $requestId;
$_SESSION['checkout_expire_at'] = time() + $expireSeconds;

// QR demo: dữ liệu mô phỏng.
// Vì đây là "giả lập", không tích hợp MoMo API thật nên ứng dụng MoMo có thể không xử lý được,
// nhưng QR vẫn phải "quét đọc" được (demo luồng bằng nút "Tôi đã thanh toán").
$qrData = sprintf('momo://pay?amount=%0.2f&ref=%s&user=%d', $total, $requestId, $uid);

$expiredAt = $_SESSION['checkout_expire_at'];
$startMin = intdiv($expireSeconds, 60);
$startSec = $expireSeconds % 60;

require_once __DIR__ . '/../includes/layout/header.php';
?>

<div class="mt-2 flex items-center justify-center">
  <div class="w-full max-w-4xl">
    <!-- Top bar MoMo (demo) -->
    <div class="flex items-center justify-center gap-3 mb-6 text-xs text-gray-700">
      <div class="w-10 h-10 rounded bg-fuchsia-700 flex items-center justify-center text-white font-bold tracking-wide">
        momo
      </div>
      <div class="leading-tight">
        <div class="font-semibold text-gray-800">Cổng thanh toán MoMo</div>
        <div class="text-gray-500">Demo thanh toán QR</div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
      <!-- Left panel: thông tin đơn -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 md:p-5">
        <h2 class="text-sm font-semibold text-gray-900 mb-2">Thông tin đơn hàng</h2>

        <div class="space-y-1 text-[13px] text-gray-600">
          <div class="flex justify-between gap-3">
            <span class="text-gray-500">Nhà cung cấp</span>
            <span class="text-gray-800 font-medium">ShoeStore</span>
          </div>
          <div class="flex justify-between gap-3">
            <span class="text-gray-500">Tên doanh nghiệp</span>
            <span class="text-gray-800 font-medium">ShoeStore Demo</span>
          </div>
          <div class="flex justify-between gap-3">
            <span class="text-gray-500">SDK</span>
            <span class="text-gray-800 font-medium">SDK4ME</span>
          </div>
          <div class="flex justify-between gap-3">
            <span class="text-gray-500">Mã đơn hàng</span>
            <span class="text-gray-800 font-medium break-all text-right"><?= e($requestId) ?></span>
          </div>
        </div>

        <div class="mt-3 border-t pt-3 space-y-1 text-[13px] text-gray-600">
          <div class="flex justify-between gap-3">
            <span class="text-gray-500">Mô tả</span>
            <span class="text-gray-800 font-medium text-right">Thanh toán đơn hàng</span>
          </div>
          <div class="flex justify-between gap-3">
            <span class="text-gray-500">Số tiền</span>
            <span class="text-gray-900 font-bold text-right">
              <?= number_format((float)$total, 0, ',', '.') ?>đ
            </span>
          </div>
        </div>

        <!-- Expire box -->
        <div class="mt-4 bg-pink-50 border border-pink-100 rounded-lg p-3">
          <div class="text-xs text-pink-700 font-medium">
            Đơn hàng sẽ hết hạn sau:
          </div>
          <div class="mt-2 flex gap-3 items-center">
            <div class="flex-1">
              <div class="text-center bg-white border border-pink-200 rounded-lg py-2">
                <div class="text-xl font-bold text-fuchsia-700 leading-none">
                  <span id="remain_min"><?= (int)$startMin ?></span>
                </div>
                <div class="text-[10px] text-gray-500">phút</div>
              </div>
            </div>
            <div class="flex-1">
              <div class="text-center bg-white border border-pink-200 rounded-lg py-2">
                <div class="text-xl font-bold text-fuchsia-700 leading-none">
                  <span id="remain_sec"><?= (int)$startSec ?></span>
                </div>
                <div class="text-[10px] text-gray-500">giây</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Button confirm -->
        <form method="POST" action="<?= e(app_url('user/checkout_confirm.php')) ?>" class="mt-4">
          <button
            type="submit"
            class="w-full bg-fuchsia-700 text-white rounded-lg px-4 py-2 hover:bg-fuchsia-800"
          >
            Tôi đã thanh toán
          </button>
        </form>

        <div class="mt-3 text-center">
          <a href="<?= e(app_url('user/cart.php')) ?>"
             class="text-xs text-fuchsia-700 hover:underline">
            Quay về
          </a>
        </div>
      </div>

      <!-- Right panel: QR -->
      <div class="rounded-xl overflow-hidden shadow-sm border border-fuchsia-200">
        <div class="bg-gradient-to-b from-fuchsia-700 to-pink-700 p-5 md:p-6 text-white">
          <div class="flex items-start justify-center">
            <div class="bg-white/15 rounded-xl px-4 py-2 text-center text-[12px] leading-tight w-full max-w-[340px]">
              <div class="flex justify-center items-center gap-2">
                <span class="text-xl">😊</span>
                <span class="font-semibold">Tích huỷ đủ yêu cầu thế giới</span>
              </div>
              <div class="mt-1 text-white/90 text-[11px]">
                Nhấn nút bên trái để hoàn tất (demo)
              </div>
            </div>
          </div>

          <div class="mt-4 bg-white rounded-2xl p-4 flex items-center justify-center">
            <div class="relative">
              <!-- QR生成 ngay ở client -->
              <div id="qrcode" class="w-64 h-64 flex items-center justify-center"></div>
            </div>
          </div>

          <div class="mt-3 text-center text-xs text-white/90">
            Sử dụng App MoMo quét QR để thanh toán đơn hàng
          </div>
          <div class="mt-1 text-center text-[11px] text-white/70">
            Gõ khăn khi thanh toán. Xác nhận: "Tôi đã thanh toán"
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QR code generator (tạo QR client-side, tránh phụ thuộc API ngoài) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  (function () {
    var end = <?= (int)$expiredAt ?> * 1000;
    var minEl = document.getElementById('remain_min');
    var secEl = document.getElementById('remain_sec');
    if (!minEl || !secEl) return;

    function pad2(n) { return String(n).padStart(2, '0'); }

    function tick() {
      var now = Date.now();
      var diff = Math.max(0, end - now);
      var totalSec = Math.floor(diff / 1000);
      var m = Math.floor(totalSec / 60);
      var s = totalSec % 60;
      minEl.textContent = String(m);
      secEl.textContent = pad2(s);
      if (diff <= 0) {
        clearInterval(timer);
      }
    }

    tick();
    var timer = setInterval(tick, 1000);
  })();

  // Render QR sau khi DOM sẵn sàng
  (function () {
    var el = document.getElementById('qrcode');
    if (!el || typeof QRCode === 'undefined') return;
    var qrText = <?= json_encode($qrData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

    el.innerHTML = '';
    // QRCode.js tự tạo canvas hoặc table bên trong container
    new QRCode(el, {
      text: qrText,
      width: 256,
      height: 256,
      // Tăng mức sửa lỗi để vẫn quét được dù có badge đè giữa (demo giống MoMo)
      correctLevel: QRCode.CorrectLevel.H
    });

    // Badge "momo" đè lên giữa (demo)
    var badge = document.createElement('div');
    badge.className = 'absolute flex items-center justify-center';
    badge.style.left = '50%';
    badge.style.top = '50%';
    badge.style.transform = 'translate(-50%, -50%)';
    badge.style.width = '34px';
    badge.style.height = '34px';
    badge.style.borderRadius = '9999px';
    badge.style.background = '#c026d3';
    badge.style.color = '#fff';
    badge.style.fontSize = '10px';
    badge.style.fontWeight = '700';
    badge.style.display = 'flex';
    badge.style.alignItems = 'center';
    badge.style.justifyContent = 'center';
    badge.style.pointerEvents = 'none';
    badge.textContent = 'momo';
    // container #qrcode đang là flex; ta thêm badge vào parent tương đối
    el.style.position = 'relative';
    el.appendChild(badge);
  })();
</script>

<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

