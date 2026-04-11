      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>ShoeStore</strong> — Bảng quản trị.
    <div class="float-right d-none d-sm-inline-block">
      <a href="<?= e(route_url('user', 'index')) ?>">Cửa hàng</a>
    </div>
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<?php if (!empty($adminExtraScripts)): ?>
  <?= $adminExtraScripts ?>
<?php endif; ?>
</body>
</html>
