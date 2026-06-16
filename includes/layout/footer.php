<?php // Layout footer dùng chung ?>

</main>

<footer class="mt-16 border-t border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card transition-colors duration-300">
  <div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
      <div class="lg:col-span-2 animate-on-scroll">
        <a href="<?= e(route_url('user', 'index')) ?>" class="inline-flex items-center gap-3 group">
          <img
            src="<?= e(app_url('assets/images/logo/hiele.png')) ?>"
            alt="shoe store"
            width="100"
            height="100"
            class="transition-transform duration-300 group-hover:scale-105"
          >
        </a>

        <p class="mt-4 text-sm text-gray-600 dark:text-dark-muted leading-relaxed">
          1Hieule.vn là shop chuyên cung cấp các dòng sản phẩm về giày Sneaker như
          Adidas, Nike, Vans, Converse,...
        </p>

        <div class="mt-5 space-y-3 text-sm text-gray-700 dark:text-dark-text">
          <div class="flex items-start gap-2 group">
            <span class="mt-0.5 text-gray-500 dark:text-dark-muted transition-transform duration-300 group-hover:scale-110">📍</span>
            <span class="transition-colors duration-300 group-hover:text-red-600 dark:group-hover:text-red-400">Địa chỉ: 123 Trung Kính, Cầu Giấy, Hà Nội</span>
          </div>
          <div class="flex items-start gap-2 group">
            <span class="mt-0.5 text-gray-500 dark:text-dark-muted transition-transform duration-300 group-hover:scale-110">📞</span>
            <span class="transition-colors duration-300 group-hover:text-red-600 dark:group-hover:text-red-400">Hotline: (+84) 3 358 684</span>
          </div>
          <div class="flex items-start gap-2 group">
            <span class="mt-0.5 text-gray-500 dark:text-dark-muted transition-transform duration-300 group-hover:scale-110">✉️</span>
            <span class="transition-colors duration-300 group-hover:text-red-600 dark:group-hover:text-red-400">Email: hieule@gmail.com</span>
          </div>
        </div>
        
        <!-- Social Icons -->
        <div class="mt-6 flex gap-3">
          <a href="#" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-dark-muted hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300 hover:scale-110">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="#" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-dark-muted hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300 hover:scale-110">
            <i class="bi bi-instagram"></i>
          </a>
          <a href="#" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-dark-muted hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300 hover:scale-110">
            <i class="bi bi-twitter-x"></i>
          </a>
          <a href="#" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-dark-muted hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300 hover:scale-110">
            <i class="bi bi-youtube"></i>
          </a>
        </div>
      </div>

      <div class="animate-on-scroll" style="animation-delay: 100ms">
        <div class="text-sm font-semibold tracking-wide text-gray-900 dark:text-dark-text uppercase mb-4">Chính sách</div>
        <ul class="space-y-3 text-sm text-gray-600 dark:text-dark-muted">
          <li><a href="<?= e(app_url('includes/layout/contentFooter/size.php')) ?>" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Chọn size</a></li>
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Giới thiệu</a></li>
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Hướng dẫn mua hàng</a></li>
        </ul>
      </div>

      <div class="animate-on-scroll" style="animation-delay: 200ms">
        <div class="text-sm font-semibold tracking-wide text-gray-900 dark:text-dark-text uppercase mb-4">Thông tin</div>
        <ul class="space-y-3 text-sm text-gray-600 dark:text-dark-muted">
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Về chúng tôi</a></li>
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Liên hệ</a></li>
        </ul>
      </div>

      <div class="animate-on-scroll" style="animation-delay: 300ms">
        <div class="text-sm font-semibold tracking-wide text-gray-900 dark:text-dark-text uppercase mb-4">Thông tin</div>
        <ul class="space-y-3 text-sm text-gray-600 dark:text-dark-muted">
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Về chúng tôi</a></li>
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Câu hỏi thường gặp</a></li>
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Tin tức</a></li>
          <li><a href="#" class="hover:text-red-600 dark:hover:text-red-400 transition link-underline">Liên hệ</a></li>
        </ul>

        <div class="mt-8">
          <div class="text-sm font-semibold tracking-wide text-gray-900 dark:text-dark-text uppercase mb-4">Đơn vị vận chuyển</div>
          <div class="flex flex-wrap gap-3">
            <img width="50" height="50" class="rounded-full transition-transform duration-300 hover:scale-110 hover:shadow-md" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTe40xGC8F5daSBeV8k5HTVtn-shEsKhMzByA&s" alt="GHTK">
            <img width="50" height="50" class="rounded-full transition-transform duration-300 hover:scale-110 hover:shadow-md" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSalhFozbhPebauvK49k5FTD-EPLX6KOy-dSg&s" alt="GHTK">
            <img width="50" height="50" class="rounded-full transition-transform duration-300 hover:scale-110 hover:shadow-md" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT17MhJQlKG1wbChrQejf-KFXXxHGQlCQKdrQ&s" alt="GHTK">
          </div>
        </div>
      </div>
    </div>
    
    <!-- Copyright -->
    <div class="mt-12 pt-8 border-t border-gray-200 dark:border-dark-border text-center text-sm text-gray-600 dark:text-dark-muted animate-on-scroll">
      <p>&copy; <?= date('Y') ?> 1Hieule.vn. Tất cả quyền được bảo lưu.</p>
    </div>
  </div>
</footer>

<?php if (is_logged_in()): ?>
  <!-- Floating chat widget (User <-> Admin) -->
  <button
    id="ss-chat-fab"
    type="button"
    class="fixed bottom-6 right-6 z-[60] w-16 h-16 rounded-full bg-gradient-to-r from-red-600 to-red-700 text-white shadow-2xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 flex items-center justify-center transition-all duration-300 hover:scale-110 btn-ripple"
    aria-label="Mở chat với admin"
  >
    <i class="bi bi-chat-dots text-2xl transition-transform duration-300 group-hover:rotate-12"></i>
    <span class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white animate-pulse"></span>
  </button>

  <div
    id="ss-chat-box"
    class="fixed bottom-24 right-6 z-[60] w-[380px] max-w-[calc(100vw-3rem)] bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-200 dark:border-dark-border hidden overflow-hidden transition-all duration-300 transform scale-95 opacity-0"
    role="dialog"
    aria-label="Chat với admin"
  >
    <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-red-600 to-red-700 dark:from-red-700 dark:to-red-800 border-b border-red-500 dark:border-red-600">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
          <i class="bi bi-headset text-white text-lg"></i>
        </div>
        <div>
          <div class="font-semibold text-white text-sm">Hỗ trợ trực tuyến</div>
          <div class="text-xs text-white/80">Chúng tôi luôn sẵn sàng giúp bạn</div>
        </div>
      </div>
      <button id="ss-chat-close" type="button" class="text-white/80 hover:text-white transition-colors duration-200" aria-label="Đóng">
        <i class="bi bi-x-lg text-xl"></i>
      </button>
    </div>

    <div id="ss-chat-messages" class="h-[340px] overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-800"></div>

    <div class="p-4 border-t border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card">
      <form id="ss-chat-form" class="flex gap-2">
        <input
          id="ss-chat-input"
          type="text"
          class="flex-1 rounded-xl border-2 border-gray-200 dark:border-dark-border px-4 py-3 text-sm bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 focus:border-red-300 dark:focus:border-red-600 transition-all duration-300"
          placeholder="Nhập tin nhắn..."
          autocomplete="off"
        >
        <button
          type="submit"
          class="rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white px-5 py-3 text-sm hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 transition-all duration-300 hover:scale-105 btn-ripple"
        >
          <i class="bi bi-send"></i>
        </button>
      </form>
      <div id="ss-chat-hint" class="mt-2 text-xs text-gray-500 dark:text-dark-muted hidden"></div>
    </div>
  </div>

  <script>
    (function () {
      const fab = document.getElementById('ss-chat-fab');
      const box = document.getElementById('ss-chat-box');
      const closeBtn = document.getElementById('ss-chat-close');
      const messagesEl = document.getElementById('ss-chat-messages');
      const form = document.getElementById('ss-chat-form');
      const input = document.getElementById('ss-chat-input');
      const hint = document.getElementById('ss-chat-hint');

      const pollUrl = <?= json_encode(route_url('chat', 'poll'), JSON_UNESCAPED_SLASHES) ?>;
      const sendUrl = <?= json_encode(route_url('chat', 'send'), JSON_UNESCAPED_SLASHES) ?>;

      let opened = false;
      let threadId = null;
      let lastId = 0;
      let pollTimer = null;
      let inflight = false;

      function setHint(text, kind) {
        if (!text) {
          hint.classList.add('hidden');
          hint.textContent = '';
          return;
        }
        hint.classList.remove('hidden');
        hint.textContent = text;
        hint.className = 'mt-2 text-xs ' + (kind === 'error' ? 'text-red-600' : 'text-gray-500');
      }

      function formatTime(ts) {
        try {
          const d = new Date(ts.replace(' ', 'T'));
          return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } catch (_) {
          return '';
        }
      }

      function appendMessage(m) {
        const isMe = m.sender_role === 'user';
        const row = document.createElement('div');
        row.className = 'flex w-full ' + (isMe ? 'justify-end' : 'justify-start');

        const wrap = document.createElement('div');
        wrap.className = 'flex flex-col ' + (isMe ? 'items-end' : 'items-start') + ' max-w-[60%] min-w-0 w-full';

        const bubble = document.createElement('div');
        bubble.className =
          (isMe ? 'bg-gradient-to-r from-red-600 to-red-700 text-white' : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-dark-text border border-gray-200 dark:border-dark-border') +
          ' rounded-2xl px-4 py-3 text-sm leading-relaxed shadow-sm transition-all duration-300 hover:shadow-md break-words overflow-wrap-anywhere word-break-break-word whitespace-normal w-full';
        bubble.textContent = m.body;

        const meta = document.createElement('div');
        meta.className = 'mt-1 text-[11px] ' + (isMe ? 'text-red-100 text-right' : 'text-gray-500 text-left');
        meta.textContent = formatTime(m.created_at);

        wrap.appendChild(bubble);
        wrap.appendChild(meta);

        row.appendChild(wrap);
        messagesEl.appendChild(row);
      }

      function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
      }

      async function pollOnce() {
        if (!opened || inflight) return;
        inflight = true;
        try {
          const url = pollUrl + '&after_id=' + encodeURIComponent(String(lastId)) + '&limit=80';
          const res = await fetch(url, { credentials: 'same-origin' });
          const data = await res.json();
          if (!data.ok) throw new Error(data.error || 'Poll failed');

          threadId = data.thread_id;
          const msgs = data.messages || [];
          if (msgs.length) {
            msgs.forEach(m => {
              lastId = Math.max(lastId, parseInt(m.id, 10) || 0);
              appendMessage(m);
            });
            scrollToBottom();
          }
          setHint('', 'info');
        } catch (e) {
          setHint('Không thể tải tin nhắn. Vui lòng thử lại.', 'error');
        } finally {
          inflight = false;
        }
      }

      function startPolling() {
        if (pollTimer) return;
        pollTimer = setInterval(pollOnce, 1500);
      }

      function stopPolling() {
        if (pollTimer) clearInterval(pollTimer);
        pollTimer = null;
      }

      function openBox() {
        opened = true;
        box.classList.remove('hidden');
        setTimeout(() => {
          box.classList.remove('scale-95', 'opacity-0');
          box.classList.add('scale-100', 'opacity-100');
        }, 10);
        setTimeout(() => input && input.focus(), 50);
        pollOnce();
        startPolling();
      }

      function closeBox() {
        opened = false;
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
        setTimeout(() => box.classList.add('hidden'), 300);
        stopPolling();
      }

      fab.addEventListener('click', function () {
        if (opened) return closeBox();
        openBox();
      });
      closeBtn.addEventListener('click', closeBox);

      form.addEventListener('submit', async function (ev) {
        ev.preventDefault();
        const text = (input.value || '').trim();
        if (!text) return;
        input.value = '';

        try {
          const fd = new FormData();
          fd.append('body', text);
          const res = await fetch(sendUrl, { method: 'POST', body: fd, credentials: 'same-origin' });
          const data = await res.json();
          if (!data.ok) throw new Error(data.error || 'Send failed');

          const m = data.message;
          if (m) {
            lastId = Math.max(lastId, parseInt(m.id, 10) || 0);
            appendMessage(m);
            scrollToBottom();
          } else {
            // fallback: poll
            pollOnce();
          }
          setHint('', 'info');
        } catch (e) {
          setHint('Gửi tin nhắn thất bại. Vui lòng thử lại.', 'error');
        }
      });
    })();
  </script>
<?php endif; ?>

</body>
</html>

