<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Trang chủ';

$categories = fetch_all_categories();

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$q = get_str('q', $_GET);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$per_page = 12;
$total = count_products($category_id ?: null, $q);
$total_pages = (int)ceil($total / $per_page);

if ($total_pages > 0 && $page > $total_pages) {
  $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

$products = fetch_products($category_id ?: null, $q, $offset, $per_page);
$heroProducts = fetch_products(null, '', 0, 3);

require_once __DIR__ . '/../includes/layout/header.php';
?>

<!-- Slider quảng cáo (1200px + căn giữa + auto chuyển) -->
<section class="mt-14" aria-label="Slider quảng cáo giày">
  <style>
    .shoe-slider-track { will-change: transform; }
  </style>

  <div id="shoe-slider" class="relative overflow-hidden bg-[#d4ced4] max-w-[1200px] mx-auto rounded-xl">
    <div class="shoe-slider-track flex transition-transform duration-700 ease-in-out" data-track>
      <?php
        $slides = array_values($heroProducts ?: []);
        if (!$slides) {
          $slides = [
            ['name' => 'Sneaker', 'image_path' => 'assets/images/logo/unnamed.png'],
          ];
        }
      ?>

      <?php foreach ($slides as $hero): ?>
        <div class="min-w-full">
          <div class="relative h-[260px] md:h-[500px]">
            <?php if (!empty($hero['image_path'])): ?>
              <img
                src="<?= e(app_url($hero['image_path'])) ?>"
                alt="<?= e($hero['name']) ?>"
                class="absolute inset-0 w-full h-full object-contain drop-shadow-sm"
                loading="eager"
              >
            <?php endif; ?>

            <div class="absolute inset-0 bg-gradient-to-r from-black/35 via-black/10 to-transparent"></div>

            <div class="relative h-full max-w-6xl mx-auto px-4 flex items-center">
              <div class="text-white mt-[240px]">
                <p class="text-white/90 text-sm md:text-lg">Mua giày chính hãng tại Sneaker Daily</p>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold tracking-tight">
                  <?= e($hero['name'] ?: 'adidas x Sporty & Rich') ?>
                </h2>
                <a
                  href="<?= e(app_url('user/index.php?page=1')) ?>"
                  class="inline-flex items-center mt-5 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-lg md:text-xl shadow-sm"
                >
                  MUA NGAY
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Clone slide đầu để chuyển vòng mượt -->
      <?php if (count($slides) > 1): ?>
        <?php $first = $slides[0]; ?>
        <div class="min-w-full" data-clone="1" aria-hidden="true">
          <div class="relative h-[260px] md:h-[380px]">
            <?php if (!empty($first['image_path'])): ?>
              <img
                src="<?= e(app_url($first['image_path'])) ?>"
                alt=""
                class="absolute inset-0 w-full h-full object-contain drop-shadow-sm"
                loading="eager"
              >
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-r from-black/35 via-black/10 to-transparent"></div>
            <div class="relative h-full max-w-6xl mx-auto px-4 flex items-center">
              <div class="text-white">
                <p class="text-white/90 text-sm md:text-lg">Mua giày chính hãng tại Sneaker Daily</p>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold tracking-tight"><?= e($first['name'] ?: 'Sneaker') ?></h2>
                <span class="inline-flex items-center mt-5 px-6 py-2.5 bg-red-600 text-white font-bold text-lg md:text-xl opacity-0">
                  MUA NGAY
                </span>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php if (count($slides) > 1): ?>
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2" data-dots>
        <?php for ($i = 0; $i < count($slides); $i++): ?>
          <button
            type="button"
            class="w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/90 transition"
            aria-label="Chuyển tới slide <?= $i + 1 ?>"
            data-dot="<?= $i ?>"
          ></button>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Bộ lọc + Tìm kiếm -->
<div class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
  <div class="flex flex-wrap gap-2">
    <a href="<?= e(app_url('user/index.php')) ?>"
       class="px-3 py-2 rounded border <?= (!$category_id ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-700 border-red-200 hover:bg-red-50 hover:border-red-300') ?>">
      Tất cả
    </a>
    <?php foreach ($categories as $c): ?>
      <?php $active = ((int)($category_id ?? 0) === (int)$c['id']); ?>
      <a href="<?= e(app_url('user/index.php?category_id=' . $c['id'] . '&q=' . urlencode($q) . '&page=1')) ?>"
         class="px-3 py-2 rounded border
           <?= $active ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-700 border-red-200 hover:bg-red-50 hover:border-red-300' ?>">
        <?= e($c['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <form method="GET" action="<?= e(app_url('user/index.php')) ?>" class="flex gap-2">
    <?php if ($category_id): ?>
      <input type="hidden" name="category_id" value="<?= (int)$category_id ?>">
    <?php endif; ?>
    <input
      name="q"
      value="<?= e($q) ?>"
      placeholder="Tìm theo tên giày..."
      class="w-full md:w-80 border border-red-200 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
    >
    <button class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-200" type="submit">Tìm</button>
  </form>
</div>

<!-- Danh sách sản phẩm -->
<div class="mt-6">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold text-lg">Sản phẩm</h2>
    <div class="text-sm text-gray-600">
      <?= (int)$total ?> kết quả
      <?php if ($q !== ''): ?>
        cho "<?= e($q) ?>"
      <?php endif; ?>
    </div>
  </div>

  <?php if (!$products): ?>
    <div class="rounded border bg-white p-6 text-center text-gray-600">
      Không có sản phẩm phù hợp.
    </div>
  <?php else: ?>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php foreach ($products as $p): ?>
        <div class="group bg-white border border-red-100 rounded-lg overflow-hidden relative hover:border-red-200 hover:shadow-sm transition">
            <a
              href="<?= e(app_url('user/product_detail.php?id=' . (int)$p['id'])) ?>"
              class="block relative z-0 h-44 bg-gray-50"
              aria-label="Xem chi tiết <?= e($p['name']) ?>"
            >
              <?php if (!empty($p['image_path'])): ?>
                <img src="<?= e(app_url($p['image_path'])) ?>" alt="<?= e($p['name']) ?>" class="w-full h-full object-contain p-3">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
              <?php endif; ?>
            </a>
  
              <?php if ((int)$p['stock_qty'] > 0): ?>
                <form method="POST" action="<?= e(app_url('user/cart_add.php')) ?>" class="absolute right-3 bottom-3 z-20" onclick="event.stopPropagation();">
                  <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                  <input type="hidden" name="shoe_size" value="40">
                  <button
                    class="w-10 h-10 rounded-full bg-white/95 border border-red-200 text-gray-800 shadow-sm flex items-center justify-center opacity-0 translate-y-1 transition-all duration-200 group-hover:opacity-100 group-hover:translate-y-0 focus:opacity-100 focus:translate-y-0 hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-200"
                    type="submit"
                    aria-label="Thêm vào giỏ hàng"
                    title="Thêm vào giỏ hàng"
                    onclick="event.stopPropagation();"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <circle cx="9" cy="20" r="1"></circle>
                      <circle cx="18" cy="20" r="1"></circle>
                      <path d="M3 4h2l2.1 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 7H7"></path>
                    </svg>
                  </button>
                </form>
              <?php else: ?>
                <span class="absolute right-3 bottom-3 text-xs px-2 py-1 rounded bg-gray-700 text-white z-20">Hết hàng</span>
              <?php endif; ?>
            <div class="p-3 relative z-0">
              <div class="text-sm text-gray-500"><?= e($p['category_name']) ?></div>
              <h3 class="mt-1 text-[15px] leading-5 font-medium text-gray-900 min-h-[2.5rem]">
                <a href="<?= e(app_url('user/product_detail.php?id=' . (int)$p['id'])) ?>" class="hover:text-red-600 transition">
                  <?= e($p['name']) ?>
                </a>
              </h3>
              <div class="mt-2 text-xl font-bold text-gray-900"><?= number_format((float)$p['price'], 0, ',', '.') ?> đ</div>
            </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Phân trang -->
<?php if ($total_pages > 1): ?>
  <div class="mt-6 flex items-center justify-center gap-2">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <?php $active = ($i === $page); ?>
      <a
        href="<?= e(app_url('user/index.php?category_id=' . (int)($category_id ?? 0) . '&q=' . urlencode($q) . '&page=' . $i)) ?>"
        class="px-3 py-2 rounded border text-sm
          <?= $active ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-red-200 hover:bg-red-50 hover:border-red-300' ?>"
      >
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
<?php endif; ?>
<!-- Đoạn js xử lý banner slider -->
<script>
    (function () {
      const root = document.getElementById('shoe-slider');
      if (!root) return;

      const track = root.querySelector('[data-track]');
      if (!track) return;

      const dotsWrap = root.querySelector('[data-dots]');
      const dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('[data-dot]')) : [];

      const slideCount = dots.length || Math.max(0, track.children.length);
      if (slideCount <= 1) return;

      const hasClone = !!track.querySelector('[data-clone="1"]');
      let index = 0;
      let timer = null;

      const setActiveDot = (i) => {
        if (!dots.length) return;
        dots.forEach((d, idx) => {
          d.classList.toggle('bg-white/90', idx === i);
          d.classList.toggle('bg-white/50', idx !== i);
        });
      };

      const goTo = (i, { animate = true } = {}) => {
        if (!animate) track.classList.remove('transition-transform', 'duration-700', 'ease-in-out');
        else track.classList.add('transition-transform', 'duration-700', 'ease-in-out');

        index = i;
        track.style.transform = `translate3d(${-index * 100}%, 0, 0)`;
        setActiveDot(Math.min(index, slideCount - 1));
      };

      const next = () => {
        if (hasClone && index === slideCount - 1) {
          goTo(slideCount, { animate: true }); // sang clone
          return;
        }
        goTo((index + 1) % slideCount, { animate: true });
      };

      const start = () => {
        stop();
        timer = setInterval(next, 4000);
      };

      const stop = () => {
        if (timer) clearInterval(timer);
        timer = null;
      };

      track.addEventListener('transitionend', () => {
        if (!hasClone) return;
        if (index === slideCount) {
          // reset về slide 0 không giật
          goTo(0, { animate: false });
          // re-enable transition cho lần sau
          requestAnimationFrame(() => track.classList.add('transition-transform', 'duration-700', 'ease-in-out'));
        }
      });

      if (dots.length) {
        dots.forEach((btn) => {
          btn.addEventListener('click', () => {
            const i = Number(btn.getAttribute('data-dot') || 0);
            goTo(i, { animate: true });
            start();
          });
        });
      }

      root.addEventListener('mouseenter', stop);
      root.addEventListener('mouseleave', start);

      setActiveDot(0);
      goTo(0, { animate: false });
      start();
    })();
  </script>
<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

