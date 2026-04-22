<?php // Layout footer dùng chung ?>

</main>

<footer class="mt-12 border-t bg-white">
  <div class="max-w-6xl mx-auto px-4 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
      <div class="lg:col-span-2">
        <a href="<?= e(route_url('user', 'index')) ?>" class="inline-flex items-center gap-3">
          <img
            src="<?= e(app_url('assets/images/logo/hiele.png')) ?>"
            alt="shoe store"
            width="100"
            height="100"
          >
        </a>

        <p class="mt-4 text-sm text-gray-600 leading-relaxed">
          1Hieule.vn là shop chuyên cung cấp các dòng sản phẩm về giày Sneaker như
          Adidas, Nike, Vans, Converse,...
        </p>

        <div class="mt-5 space-y-3 text-sm text-gray-700">
          <div class="flex items-start gap-2">
            <span class="mt-0.5 text-gray-500">📍</span>
            <span>Địa chỉ: 123 Trung Kính, Cầu Giấy, Hà Nội</span>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-0.5 text-gray-500">📞</span>
            <span>Hotline: (+84) 3 358 684</span>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-0.5 text-gray-500">✉️</span>
            <span>Email: hieule@gmail.com</span>
          </div>
        </div>
      </div>

      <div>
        <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Chính sách</div>
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
          <li><a href="<?= e(app_url('includes/layout/contentFooter/size.php')) ?>" class="hover:text-red-700">Chọn size</a></li>
          <li><a href="#" class="hover:text-red-700">Giới thiệu</a></li>
          <li><a href="#" class="hover:text-red-700">Hướng dẫn mua hàng</a></li>
        </ul>
      </div>

      <div>
        <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Thông tin</div>
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
          <li><a href="#" class="hover:text-red-700">Về chúng tôi</a></li>
          <li><a href="#" class="hover:text-red-700">Liên hệ</a></li>
        </ul>
      </div>

      <div>
        <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Thông tin</div>
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
          <li><a href="#" class="hover:text-red-700">Về chúng tôi</a></li>
          <li><a href="#" class="hover:text-red-700">Câu hỏi thường gặp</a></li>
          <li><a href="#" class="hover:text-red-700">Tin tức</a></li>
          <li><a href="#" class="hover:text-red-700">Liên hệ</a></li>
        </ul>

        <div class="mt-8">
          <div class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Đơn vị vận chuyển</div>
          <div class="mt-4 flex flex-wrap gap-2">
            <img width="50" height="50" class="rounded-full" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTe40xGC8F5daSBeV8k5HTVtn-shEsKhMzByA&s" alt="GHTK">
            <img width="50" height="50" class="rounded-full" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSalhFozbhPebauvK49k5FTD-EPLX6KOy-dSg&s" alt="GHTK">
            <img width="50" height="50" class="rounded-full" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT17MhJQlKG1wbChrQejf-KFXXxHGQlCQKdrQ&s" alt="GHTK">
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

</body>
</html>

