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

<?php if (is_logged_in()): ?>
  <!-- Floating chat widget (User <-> Admin) -->
  <button
    id="ss-chat-fab"
    type="button"
    class="fixed bottom-6 right-6 z-[60] w-14 h-14 rounded-full bg-red-600 text-white shadow-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-200 flex items-center justify-center"
    aria-label="Mở chat với admin"
  >
    <i class="bi bi-chat-dots text-2xl"></i>
  </button>

  <div
    id="ss-chat-box"
    class="fixed bottom-24 right-6 z-[60] w-[360px] max-w-[calc(100vw-3rem)] bg-white rounded-2xl shadow-2xl border border-gray-200 hidden overflow-hidden"
    role="dialog"
    aria-label="Chat với admin"
  >
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b">
      <div class="font-semibold text-sm text-gray-900">Chat với Admin</div>
      <button id="ss-chat-close" type="button" class="text-gray-500 hover:text-gray-800" aria-label="Đóng">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div id="ss-chat-messages" class="h-[320px] overflow-y-auto p-3 space-y-2 bg-white"></div>

    <div class="p-3 border-t bg-white">
      <form id="ss-chat-form" class="flex gap-2">
        <input
          id="ss-chat-input"
          type="text"
          class="flex-1 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-200"
          placeholder="Nhập tin nhắn..."
          autocomplete="off"
        >
        <button
          type="submit"
          class="rounded-xl bg-red-600 text-white px-4 py-2 text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-200"
        >Gửi</button>
      </form>
      <div id="ss-chat-hint" class="mt-2 text-xs text-gray-500 hidden"></div>
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
        row.className = 'flex ' + (isMe ? 'justify-end' : 'justify-start');

        const bubble = document.createElement('div');
        bubble.className =
          (isMe ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-900') +
          ' max-w-[80%] rounded-2xl px-3 py-2 text-sm leading-relaxed';
        bubble.textContent = m.body;

        const meta = document.createElement('div');
        meta.className = 'mt-1 text-[11px] ' + (isMe ? 'text-red-100' : 'text-gray-500');
        meta.textContent = formatTime(m.created_at);

        const wrap = document.createElement('div');
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
        setTimeout(() => input && input.focus(), 50);
        pollOnce();
        startPolling();
      }

      function closeBox() {
        opened = false;
        box.classList.add('hidden');
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

