<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Admin - Đơn hàng';

$q = get_str('q', $_GET);
$status = get_str('status', $_GET);
if ($status !== '' && !in_array($status, ['paid', 'pending', 'cancelled'], true)) {
  $status = '';
}
$status = $status !== '' ? $status : null;
$q = $q !== '' ? $q : null;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$per_page = 10;

$total = admin_count_orders($q, $status);
$total_pages = (int)ceil($total / $per_page);
if ($total_pages > 0 && $page > $total_pages) {
  $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

$orders = admin_get_orders($offset, $per_page, $q, $status);

$stats = admin_stats();
$adminActive = 'orders';
$adminHeading = 'Đơn hàng';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Đơn hàng', 'url' => null],
];
$adminNavBadge = (int)$stats['pending_orders'];

require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="card card-outline card-secondary">
  <div class="card-header">
    <h3 class="card-title">Lọc đơn hàng</h3>
  </div>
  <div class="card-body">
    <form method="get" class="form-row align-items-end">
      <div class="form-group col-md-5 col-lg-4 mb-md-0">
        <label for="order-q">Tìm theo khách (tên/email)</label>
        <input
          id="order-q"
          type="text"
          name="q"
          value="<?= e($q ?? '') ?>"
          class="form-control"
          placeholder="VD: Nguyen, test@gmail.com"
        >
      </div>
      <div class="form-group col-md-4 col-lg-3 mb-md-0">
        <label for="order-status">Trạng thái</label>
        <select id="order-status" name="status" class="form-control">
          <option value="">Tất cả</option>
          <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Đã thanh toán (paid)</option>
          <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Chờ xử lý (pending)</option>
          <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Đã huỷ (cancelled)</option>
        </select>
      </div>
      <div class="form-group col-md-3 col-lg-2 mb-0">
        <label class="d-none d-md-block">&nbsp;</label>
        <button type="submit" class="btn btn-primary btn-block">Lọc</button>
      </div>
    </form>
    <p class="text-muted small mb-0 mt-2">Tổng: <strong><?= (int)$total ?></strong> đơn</p>
  </div>
</div>

<?php if (!$orders): ?>
  <div class="alert alert-info mt-3 mb-0">Chưa có đơn hàng nào phù hợp.</div>
<?php else: ?>
  <div class="card mt-3">
    <div class="card-body table-responsive p-0">
      <table class="table table-hover table-striped mb-0">
        <thead>
          <tr>
            <th>Mã</th>
            <th>Khách</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Tổng tiền</th>
            <th style="width: 130px;">Xem</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td class="font-weight-medium">#<?= (int)$o['id'] ?></td>
              <td>
                <div class="font-weight-medium"><?= e($o['user_name'] ?? '') ?></div>
                <div class="small text-muted"><?= e($o['user_email'] ?? '') ?></div>
              </td>
              <td>
                <?php
                  $st = (string)($o['status'] ?? '');
                  $badgeClass = 'badge-secondary';
                  if ($st === 'paid') {
                    $badgeClass = 'badge-success';
                  }
                  if ($st === 'pending') {
                    $badgeClass = 'badge-warning';
                  }
                  if ($st === 'cancelled') {
                    $badgeClass = 'badge-danger';
                  }
                ?>
                <span class="badge <?= e($badgeClass) ?>"><?= e($st) ?></span>
              </td>
              <td><?= e(date('d/m/Y H:i', strtotime((string)$o['created_at']))) ?></td>
              <td class="font-weight-medium"><?= number_format((float)$o['total_amount'], 0, ',', '.') ?> VND</td>
              <td>
                <a href="<?= e(app_url('admin/order_detail.php?id=' . (int)$o['id'])) ?>" class="btn btn-sm btn-outline-primary">
                  Chi tiết
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if ($total_pages > 1): ?>
    <nav class="mt-3" aria-label="Phân trang">
      <ul class="pagination justify-content-center mb-0">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php $active = ($i === $page); ?>
          <?php
            $query = ['page' => $i];
            if ($q !== null) {
              $query['q'] = $q;
            }
            if ($status !== null) {
              $query['status'] = $status;
            }
            $qs = http_build_query($query);
          ?>
          <li class="page-item <?= $active ? 'active' : '' ?>">
            <a class="page-link" href="<?= e(app_url('admin/orders.php?' . $qs)) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
