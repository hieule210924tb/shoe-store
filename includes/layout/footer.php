<?php // Layout footer dùng chung ?>

</main>

<footer class="mt-12 border-t bg-white">
  <div class="max-w-6xl mx-auto px-4 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
      <div class="lg:col-span-2">
        <a href="<?= e(route_url('user', 'index')) ?>" class="inline-flex items-center gap-3">
          <img
            src="<?= e(app_url('assets/images/logo/log2.png')) ?>"
            alt="shoe store"
            width="200"
            height="200"
          >
        </a>

        <p class="mt-4 text-sm text-gray-600 leading-relaxed">
          1Sneaker.vn là shop chuyên cung cấp các dòng sản phẩm về giày Sneaker như
          Adidas, Nike, Vans, Converse,...
        </p>

        <div class="mt-5 space-y-3 text-sm text-gray-700">
          <div class="flex items-start gap-2">
            <span class="mt-0.5 text-gray-500">📍</span>
            <span>Địa chỉ: 435 Âu Cơ, Liên Chiểu, Đà Nẵng</span>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-0.5 text-gray-500">📞</span>
            <span>Hotline: (+84) 905 692 314</span>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-0.5 text-gray-500">✉️</span>
            <span>Email: giaydep@gmail.com</span>
          </div>
        </div>
      </div>

      <div>
        <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Chính sách</div>
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
          <li><a href="<?= e(app_url('includes/layout/contentFooter/size.php')) ?>" class="hover:text-blue-700">Chọn size</a></li>
          <li><a href="#" class="hover:text-blue-700">Giới thiệu</a></li>
          <li><a href="#" class="hover:text-blue-700">Hướng dẫn mua hàng</a></li>
        </ul>
      </div>

      <div>
        <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Thông tin</div>
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
          <li><a href="#" class="hover:text-blue-700">Về chúng tôi</a></li>
          <li><a href="#" class="hover:text-blue-700">Liên hệ</a></li>
        </ul>
      </div>

      <div>
        <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Thông tin</div>
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
          <li><a href="#" class="hover:text-blue-700">Về chúng tôi</a></li>
          <li><a href="#" class="hover:text-blue-700">Câu hỏi thường gặp</a></li>
          <li><a href="#" class="hover:text-blue-700">Tin tức</a></li>
          <li><a href="#" class="hover:text-blue-700">Liên hệ</a></li>
        </ul>

        <div class="mt-8">
          <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Đơn vị vận chuyển</div>
          <div class="mt-4 flex flex-wrap gap-2">
            <span class="px-3 py-1 rounded border text-xs text-gray-700 bg-gray-50">GHN</span>
            <span class="px-3 py-1 rounded border text-xs text-gray-700 bg-gray-50">GHTK</span>
            <span class="px-3 py-1 rounded border text-xs text-gray-700 bg-gray-50">Viettel Post</span>
            <span class="px-3 py-1 rounded border text-xs text-gray-700 bg-gray-50">J&T</span>
            <span class="px-3 py-1 rounded border text-xs text-gray-700 bg-gray-50">Grab</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

</body>
</html>

