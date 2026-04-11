<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Dashboard';
$stats = admin_stats();

$adminActive = 'dashboard';
$adminHeading = 'Dashboard';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Dashboard', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?= (int)$stats['total_orders'] ?></h3>
        <p>Đơn đã thanh toán</p>
      </div>
      <div class="icon"><i class="fas fa-shopping-bag"></i></div>
      <a href="<?= e(route_url('admin', 'orders')) ?>" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?= e(number_format((float)$stats['total_revenue'], 0, ',', '.')) ?></h3>
        <p>Tổng doanh thu (VND)</p>
      </div>
      <div class="icon"><i class="fas fa-chart-bar"></i></div>
      <a href="<?= e(route_url('admin', 'orders')) ?>" class="small-box-footer">Xem đơn <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3><?= (int)$stats['total_users'] ?></h3>
        <p>Tài khoản đăng ký</p>
      </div>
      <div class="icon"><i class="fas fa-user-plus"></i></div>
      <a href="<?= e(route_url('admin', 'accounts')) ?>" class="small-box-footer">Quản lý tài khoản <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3><?= (int)$stats['total_products'] ?></h3>
        <p>Sản phẩm trong kho</p>
      </div>
      <div class="icon"><i class="fas fa-boxes"></i></div>
      <a href="<?= e(route_url('admin', 'products')) ?>" class="small-box-footer">Quản lý sản phẩm <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<div class="row">
  <section class="col-lg-7">
    <div class="card">
      <div class="card-header border-0">
        <h3 class="card-title"><i class="fas fa-chart-area mr-1"></i> Doanh thu theo tháng (mẫu)</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-sm btn-light active" id="sales-area-btn">Vùng</button>
          <button type="button" class="btn btn-sm btn-light" id="sales-line-btn">Đường</button>
        </div>
      </div>
      <div class="card-body">
        <div class="position-relative mb-4" style="height: 250px;">
          <canvas id="sales-chart" height="250" style="height: 250px;"></canvas>
        </div>
      </div>
    </div>
  </section>
  <section class="col-lg-5">
    <div class="card bg-gradient-primary">
      <div class="card-header border-0">
        <h3 class="card-title"><i class="fas fa-map-marker-alt mr-1"></i> Khách theo khu vực (mẫu)</h3>
      </div>
      <div class="card-body" style="height: 250px; overflow: hidden;">
        <div id="vmap-usa" style="height: 220px; width: 100%;"></div>
      </div>
      <div class="card-footer bg-transparent">
        <div class="row">
          <div class="col-4 text-center">
            <div class="text-white">Đơn chờ</div>
            <div class="text-white text-lg font-weight-bold"><?= (int)$stats['pending_orders'] ?></div>
          </div>
          <div class="col-4 text-center">
            <div class="text-white">Đã trả tiền</div>
            <div class="text-white text-lg font-weight-bold"><?= (int)$stats['total_orders'] ?></div>
          </div>
          <div class="col-4 text-center">
            <div class="text-white">SP</div>
            <div class="text-white text-lg font-weight-bold"><?= (int)$stats['total_products'] ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Sản phẩm bán chạy (Top 5)</h3>
      </div>
      <div class="card-body p-0">
        <?php if (!$stats['top_products']): ?>
          <p class="p-3 text-muted mb-0">Chưa có dữ liệu bán hàng.</p>
        <?php else: ?>
          <table class="table table-striped mb-0">
            <thead>
            <tr>
              <th>Sản phẩm</th>
              <th>SL bán</th>
              <th>Doanh thu</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($stats['top_products'] as $p): ?>
              <tr>
                <td><?= e($p['name']) ?></td>
                <td><?= (int)$p['total_qty'] ?></td>
                <td><?= number_format((float)$p['total_revenue'], 0, ',', '.') ?> VND</td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
$adminExtraScripts = <<<'HTML'
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
(function () {
  var months = ['T1','T2','T3','T4','T5','T6','T7'];
  var areaData = [1000, 1200, 1700, 1500, 2100, 1900, 2300];
  var areaData2 = [800, 950, 1400, 1200, 1800, 1600, 2000];
  var ctx = document.getElementById('sales-chart');
  var chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Doanh thu A',
          data: areaData,
          borderColor: 'rgba(60,141,188,1)',
          backgroundColor: 'rgba(60,141,188,0.25)',
          fill: true,
          tension: 0.3
        },
        {
          label: 'Doanh thu B',
          data: areaData2,
          borderColor: 'rgba(210,214,222,1)',
          backgroundColor: 'rgba(210,214,222,0.35)',
          fill: true,
          tension: 0.3
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: {
        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
        x: { grid: { display: false } }
      }
    }
  });
  document.getElementById('sales-area-btn').addEventListener('click', function () {
    chart.config.type = 'line';
    chart.data.datasets.forEach(function (ds) { ds.fill = true; });
    chart.update();
    this.classList.add('active');
    document.getElementById('sales-line-btn').classList.remove('active');
  });
  document.getElementById('sales-line-btn').addEventListener('click', function () {
    chart.config.type = 'line';
    chart.data.datasets.forEach(function (ds) { ds.fill = false; });
    chart.update();
    this.classList.add('active');
    document.getElementById('sales-area-btn').classList.remove('active');
  });
  if (window.jQuery && jQuery.fn.vectorMap) {
    jQuery('#vmap-usa').vectorMap({
      map: 'usa_en',
      backgroundColor: 'transparent',
      regionStyle: { initial: { fill: '#fff', 'fill-opacity': 0.85 } }
    });
  }
})();
</script>
HTML;

require_once __DIR__ . '/includes/layout_end.php';
