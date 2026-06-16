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

<!-- Slider quảng cáo với parallax và animations -->
<section class="mt-20" aria-label="Slider quảng cáo giày">
  <style>
    .shoe-slider-track { will-change: transform; }
    .shoe-slider-parallax { will-change: transform; }
  </style>

  <div id="shoe-slider" class="relative overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 max-w-[1200px] mx-auto rounded-2xl shadow-2xl">
    <div class="shoe-slider-track flex transition-transform duration-700 ease-smooth" data-track>
      <?php
        $slides = array_values($heroProducts ?: []);
        if (!$slides) {
          $slides = [
            ['name' => 'Sneaker', 'image_path' => 'assets/images/logo/unnamed.png'],
          ];
        }
      ?>

      <?php foreach ($slides as $index => $hero): ?>
        <div class="min-w-full">
          <div class="relative h-[260px] md:h-[500px] overflow-hidden">
            <?php if (!empty($hero['image_path'])): ?>
              <div class="shoe-slider-parallax absolute inset-0 flex items-center justify-center">
                <img
                  src="<?= e(app_url($hero['image_path'])) ?>"
                  alt="<?= e($hero['name']) ?>"
                  class="max-w-full max-h-full object-contain drop-shadow-2xl transition-transform duration-700 hover:scale-105"
                  loading="eager"
                >
              </div>
            <?php endif; ?>

            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent dark:from-black/70 dark:via-black/40"></div>

            <div class="relative h-full max-w-6xl mx-auto px-4 flex items-center">
              <div class="text-white animate-on-scroll" style="animation-delay: <?= $index * 100 ?>ms">
                <p class="text-white/90 text-sm md:text-lg font-medium tracking-wide opacity-0 animate-fade-in-up" style="animation-delay: <?= $index * 100 + 200 ?>ms; animation-fill-mode: forwards;">Mua giày chính hãng tại Sneaker Daily</p>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold tracking-tight opacity-0 animate-fade-in-up" style="animation-delay: <?= $index * 100 + 300 ?>ms; animation-fill-mode: forwards;">
                  <?= e($hero['name'] ?: 'adidas x Sporty & Rich') ?>
                </h2>
                <a
                  href="<?= e(app_url('user/index.php?page=1')) ?>"
                  class="inline-flex items-center mt-5 px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-lg md:text-xl rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 btn-ripple opacity-0 animate-fade-in-up" style="animation-delay: <?= $index * 100 + 400 ?>ms; animation-fill-mode: forwards;"
                >
                  <i class="bi bi-bag mr-2"></i> MUA NGAY
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
          <div class="relative h-[260px] md:h-[500px] overflow-hidden">
            <?php if (!empty($first['image_path'])): ?>
              <div class="shoe-slider-parallax absolute inset-0 flex items-center justify-center">
                <img
                  src="<?= e(app_url($first['image_path'])) ?>"
                  alt=""
                  class="max-w-full max-h-full object-contain drop-shadow-2xl"
                  loading="eager"
                >
              </div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent dark:from-black/70 dark:via-black/40"></div>
            <div class="relative h-full max-w-6xl mx-auto px-4 flex items-center">
              <div class="text-white">
                <p class="text-white/90 text-sm md:text-lg font-medium tracking-wide">Mua giày chính hãng tại Sneaker Daily</p>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold tracking-tight"><?= e($first['name'] ?: 'Sneaker') ?></h2>
                <span class="inline-flex items-center mt-5 px-8 py-3 bg-red-600 text-white font-bold text-lg md:text-xl rounded-xl opacity-0">
                  <i class="bi bi-bag mr-2"></i> MUA NGAY
                </span>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php if (count($slides) > 1): ?>
      <!-- Progress Bar -->
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
        <div class="slider-progress h-full bg-red-600 transition-all duration-700 ease-smooth" style="width: 0%"></div>
      </div>

      <!-- Dots -->
      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3" data-dots>
        <?php for ($i = 0; $i < count($slides); $i++): ?>
          <button
            type="button"
            class="w-3 h-3 rounded-full bg-white/50 hover:bg-white/90 transition-all duration-300 hover:scale-125"
            aria-label="Chuyển tới slide <?= $i + 1 ?>"
            data-dot="<?= $i ?>"
          ></button>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Bộ lọc + Tìm kiếm -->
<div class="mt-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between animate-on-scroll">
  <div class="flex flex-wrap gap-2">
    <a href="<?= e(app_url('user/index.php')) ?>"
       class="px-4 py-2 rounded-full border-2 transition-all duration-300 hover:scale-105 link-underline <?= (!$category_id ? 'bg-red-600 text-white border-red-600 shadow-md' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-dark-text border-red-200 dark:border-dark-border hover:bg-red-50 dark:hover:bg-gray-800 hover:border-red-300') ?>">
      Tất cả
    </a>
    <?php foreach ($categories as $c): ?>
      <?php $active = ((int)($category_id ?? 0) === (int)$c['id']); ?>
      <a href="<?= e(app_url('user/index.php?category_id=' . $c['id'] . '&q=' . urlencode($q) . '&page=1')) ?>"
         class="px-4 py-2 rounded-full border-2 transition-all duration-300 hover:scale-105 link-underline
           <?= $active ? 'bg-red-600 text-white border-red-600 shadow-md' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-dark-text border-red-200 dark:border-dark-border hover:bg-red-50 dark:hover:bg-gray-800 hover:border-red-300' ?>">
        <?= e($c['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <form method="GET" action="<?= e(app_url('user/index.php')) ?>" class="flex gap-2">
    <?php if ($category_id): ?>
      <input type="hidden" name="category_id" value="<?= (int)$category_id ?>">
    <?php endif; ?>
    <div class="relative flex-1 md:w-80">
      <input
        name="q"
        value="<?= e($q) ?>"
        placeholder="Tìm theo tên giày..."
        class="w-full border-2 border-red-200 dark:border-dark-border rounded-full px-4 py-2 pl-10 bg-white dark:bg-dark-card focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300"
      >
      <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
      <?php if ($q !== ''): ?>
        <button type="button" onclick="this.form.q.value=''; this.form.submit();" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 transition">
          <i class="bi bi-x"></i>
        </button>
      <?php endif; ?>
    </div>
    <button class="px-6 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-300 hover:scale-105 hover:shadow-md btn-ripple">
      <i class="bi bi-search mr-1"></i> Tìm
    </button>
  </form>
</div>

<!-- Danh sách sản phẩm -->
<div class="mt-8">
  <div class="flex items-center justify-between mb-4 animate-on-scroll">
    <h2 class="font-semibold text-xl text-gray-900 dark:text-dark-text">Sản phẩm</h2>
    <div class="text-sm text-gray-600 dark:text-dark-muted">
      <?= (int)$total ?> kết quả
      <?php if ($q !== ''): ?>
        cho "<?= e($q) ?>"
      <?php endif; ?>
    </div>
  </div>

  <?php if (!$products): ?>
    <div class="rounded-xl border bg-white dark:bg-dark-card p-8 text-center text-gray-500 dark:text-dark-muted shadow-sm animate-fade-in">
      <i class="bi bi-search text-4xl mb-3 block"></i>
      Không có sản phẩm phù hợp.
    </div>
  <?php else: ?>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($products as $index => $p): ?>
        <div class="group bg-white dark:bg-dark-card border border-gray-100 dark:border-dark-border rounded-2xl overflow-hidden relative card-hover animate-on-scroll" style="animation-delay: <?= $index * 50 ?>ms">
            <a
              href="<?= e(app_url('user/product_detail.php?id=' . (int)$p['id'])) ?>"
              class="block relative z-0 h-52 bg-gray-50 dark:bg-gray-800 img-zoom-container"
              aria-label="Xem chi tiết <?= e($p['name']) ?>"
            >
              <?php if (!empty($p['image_path'])): ?>
                <img src="<?= e(app_url($p['image_path'])) ?>" alt="<?= e($p['name']) ?>" class="w-full h-full object-contain p-4">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
              <?php endif; ?>
            </a>
  
              <?php if ((int)$p['stock_qty'] > 0): ?>
                <form method="POST" action="<?= e(app_url('user/cart_add.php')) ?>" class="absolute right-4 bottom-4 z-20" onclick="event.stopPropagation();">
                  <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                  <input type="hidden" name="shoe_size" value="40">
                  <button
                    class="w-12 h-12 rounded-full bg-white/95 dark:bg-dark-card border-2 border-red-200 dark:border-dark-border text-gray-800 dark:text-dark-text shadow-lg flex items-center justify-center opacity-0 translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0 focus:opacity-100 focus:translate-y-0 hover:bg-red-600 hover:text-white hover:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-200 btn-ripple"
                    type="submit"
                    aria-label="Thêm vào giỏ hàng"
                    title="Thêm vào giỏ hàng"
                    onclick="event.stopPropagation();"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <circle cx="9" cy="20" r="1"></circle>
                      <circle cx="18" cy="20" r="1"></circle>
                      <path d="M3 4h2l2.1 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 7H7"></path>
                    </svg>
                  </button>
                </form>
              <?php else: ?>
                <span class="absolute right-4 bottom-4 text-xs px-3 py-1.5 rounded-full bg-gray-700 text-white z-20 shadow-md">Hết hàng</span>
              <?php endif; ?>
            <div class="p-4 relative z-0">
              <div class="text-sm text-gray-500 dark:text-dark-muted font-medium"><?= e($p['category_name']) ?></div>
              <h3 class="mt-1 text-base leading-5 font-semibold text-gray-900 dark:text-dark-text min-h-[2.5rem]">
                <a href="<?= e(app_url('user/product_detail.php?id=' . (int)$p['id'])) ?>" class="hover:text-red-600 transition link-underline">
                  <?= e($p['name']) ?>
                </a>
              </h3>
              <div class="mt-3 text-xl font-bold text-red-600 dark:text-red-400"><?= number_format((float)$p['price'], 0, ',', '.') ?> đ</div>
            </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Phân trang -->
<?php if ($total_pages > 1): ?>
  <div class="mt-8 flex items-center justify-center gap-2 animate-on-scroll">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <?php $active = ($i === $page); ?>
      <a
        href="<?= e(app_url('user/index.php?category_id=' . (int)($category_id ?? 0) . '&q=' . urlencode($q) . '&page=' . $i)) ?>"
        class="w-10 h-10 flex items-center justify-center rounded-xl border text-sm font-medium transition-all duration-300 hover:scale-110
          <?= $active ? 'bg-red-600 text-white border-red-600 shadow-md' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-dark-text border-red-200 dark:border-dark-border hover:bg-red-50 dark:hover:bg-gray-800 hover:border-red-300' ?>"
      >
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
<?php endif; ?>
<!-- Đoạn js xử lý banner slider với parallax và progress bar -->
<script>
    (function () {
      const root = document.getElementById('shoe-slider');
      if (!root) return;

      const track = root.querySelector('[data-track]');
      if (!track) return;

      const dotsWrap = root.querySelector('[data-dots]');
      const dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('[data-dot]')) : [];
      const progressBar = root.querySelector('.slider-progress');

      const slideCount = dots.length || Math.max(0, track.children.length);
      if (slideCount <= 1) return;

      const hasClone = !!track.querySelector('[data-clone="1"]');
      let index = 0;
      let timer = null;
      let progressTimer = null;

      const setActiveDot = (i) => {
        if (!dots.length) return;
        dots.forEach((d, idx) => {
          d.classList.toggle('bg-white/90', idx === i);
          d.classList.toggle('scale-125', idx === i);
          d.classList.toggle('bg-white/50', idx !== i);
          d.classList.toggle('scale-100', idx !== i);
        });
      };

      const updateProgress = () => {
        if (!progressBar) return;
        progressBar.style.width = '0%';
        progressBar.style.transition = 'none';
        setTimeout(() => {
          progressBar.style.transition = 'width 4000ms linear';
          progressBar.style.width = '100%';
        }, 50);
      };

      const goTo = (i, { animate = true } = {}) => {
        if (!animate) track.classList.remove('transition-transform', 'duration-700', 'ease-smooth');
        else track.classList.add('transition-transform', 'duration-700', 'ease-smooth');

        index = i;
        track.style.transform = `translate3d(${-index * 100}%, 0, 0)`;
        setActiveDot(Math.min(index, slideCount - 1));
        updateProgress();
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
        updateProgress();
      };

      const stop = () => {
        if (timer) clearInterval(timer);
        if (progressTimer) clearInterval(progressTimer);
        timer = null;
        progressTimer = null;
        if (progressBar) {
          progressBar.style.transition = 'none';
          progressBar.style.width = '0%';
        }
      };

      track.addEventListener('transitionend', () => {
        if (!hasClone) return;
        if (index === slideCount) {
          // reset về slide 0 không giật
          goTo(0, { animate: false });
          // re-enable transition cho lần sau
          requestAnimationFrame(() => track.classList.add('transition-transform', 'duration-700', 'ease-smooth'));
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

      // Parallax effect on scroll
      window.addEventListener('scroll', () => {
        const parallaxElements = root.querySelectorAll('.shoe-slider-parallax');
        const rect = root.getBoundingClientRect();
        const scrollPercent = (rect.top / window.innerHeight) * 100;
        
        parallaxElements.forEach(el => {
          const translateY = Math.max(-50, Math.min(50, scrollPercent * 0.5));
          el.style.transform = `translateY(${translateY}px)`;
        });
      });

      setActiveDot(0);
      goTo(0, { animate: false });
      start();
    })();
  </script>
<?php require_once __DIR__ . '/../includes/layout/footer.php'; ?>

