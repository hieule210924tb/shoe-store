<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$pageTitle = 'Chat với User';
$adminActive = 'chat';
$adminHeading = 'Chat với User';
$adminBreadcrumbs = [
  ['label' => 'Trang chủ', 'url' => route_url('admin', 'dashboard')],
  ['label' => 'Chat với User', 'url' => null],
];
require_once __DIR__ . '/includes/layout_start.php';
?>
<div class="row">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách user</h3>
        <div class="card-tools">
          <button type="button" id="ss-admin-chat-refresh" class="btn btn-tool" title="Tải lại">
            <i class="fas fa-sync"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0" style="max-height: 70vh; overflow:auto;">
        <div id="ss-admin-chat-threads" class="list-group list-group-flush"></div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title" id="ss-admin-chat-title">Chọn một user để chat</h3>
      </div>
      <div class="card-body" style="height: 60vh; overflow:auto;">
        <div id="ss-admin-chat-messages" class="direct-chat-messages" style="height:auto; overflow:visible;"></div>
      </div>
      <div class="card-footer">
        <form id="ss-admin-chat-form" class="input-group">
          <input id="ss-admin-chat-input" type="text" class="form-control" placeholder="Nhập tin nhắn..." disabled>
          <span class="input-group-append">
            <button type="submit" class="btn btn-primary" disabled id="ss-admin-chat-send">Gửi</button>
          </span>
        </form>
        <small id="ss-admin-chat-hint" class="text-muted d-block mt-2"></small>
      </div>
    </div>
  </div>
</div>

<?php
$adminExtraScripts = ($adminExtraScripts ?? '') . "\n" . '<script>
(function () {
  var threadsUrl = ' . json_encode(route_url('chat', 'admin_threads'), JSON_UNESCAPED_SLASHES) . ';
  var pollUrl = ' . json_encode(route_url('chat', 'admin_poll'), JSON_UNESCAPED_SLASHES) . ';
  var sendUrl = ' . json_encode(route_url('chat', 'admin_send'), JSON_UNESCAPED_SLASHES) . ';

  var $threads = $("#ss-admin-chat-threads");
  var $messages = $("#ss-admin-chat-messages");
  var $title = $("#ss-admin-chat-title");
  var $input = $("#ss-admin-chat-input");
  var $sendBtn = $("#ss-admin-chat-send");
  var $hint = $("#ss-admin-chat-hint");

  var activeThreadId = null;
  var lastId = 0;
  var pollTimer = null;
  var inflight = false;

  function setHint(text) { $hint.text(text || ""); }

  function escapeHtml(s) {
    return String(s || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/\'/g, "&#039;");
  }

  function formatTime(ts) {
    try {
      var d = new Date(String(ts).replace(" ", "T"));
      return d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    } catch (e) { return ""; }
  }

  function renderThreadItem(t) {
    var unread = parseInt(t.unread_for_admin, 10) || 0;
    var badge = unread > 0 ? \'<span class="badge badge-danger ml-2">\'+(unread>99?99:unread)+\'</span>\' : "";
    var active = (String(activeThreadId) === String(t.id)) ? "active" : "";
    var subtitle = escapeHtml(t.user_email || "");
    return (
      \'<button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center \' + active + \'" data-thread-id="\' + t.id + \'">\'
      + \'<div>\'
      + \'<div class="font-weight-bold">\'+ escapeHtml(t.user_name || ("User #" + t.user_id)) + badge + \'</div>\'
      + \'<div class="small text-muted">\'+ subtitle + \'</div>\'
      + \'</div>\'
      + \'<div class="small text-muted">\'+ (t.last_message_at ? escapeHtml(formatTime(t.last_message_at)) : "") + \'</div>\'
      + \'</button>\'
    );
  }

  function appendMessage(m) {
    var isAdmin = m.sender_role === "admin";
    var html = \'\'
      + \'<div class="direct-chat-msg \' + (isAdmin ? "right" : "") + \'">\'
      + \'<div class="direct-chat-infos clearfix">\'
      + (isAdmin
          ? \'<span class="direct-chat-name float-right">Admin</span>\'
          : \'<span class="direct-chat-name float-left">User</span>\')
      + \'<span class="direct-chat-timestamp \' + (isAdmin ? "float-left" : "float-right") + \'">\'
      + escapeHtml(formatTime(m.created_at))
      + \'</span>\'
      + \'</div>\'
      + \'<div class="direct-chat-text">\'+ escapeHtml(m.body) + \'</div>\'
      + \'</div>\';
    $messages.append(html);
  }

  function scrollBottom() {
    var el = $messages.closest(".card-body")[0];
    if (el) el.scrollTop = el.scrollHeight;
  }

  async function loadThreads() {
    try {
      var res = await fetch(threadsUrl, { credentials: "same-origin" });
      var data = await res.json();
      if (!data.ok) throw new Error(data.error || "threads failed");
      var items = (data.threads || []).map(renderThreadItem).join("");
      $threads.html(items || \'<div class="p-3 text-muted">Chưa có cuộc trò chuyện.</div>\');
    } catch (e) {
      setHint("Không thể tải danh sách chat.");
    }
  }

  async function pollOnce() {
    if (!activeThreadId || inflight) return;
    inflight = true;
    try {
      var url = pollUrl + "&thread_id=" + encodeURIComponent(String(activeThreadId)) + "&after_id=" + encodeURIComponent(String(lastId)) + "&limit=120";
      var res = await fetch(url, { credentials: "same-origin" });
      var data = await res.json();
      if (!data.ok) throw new Error(data.error || "poll failed");

      if (data.thread) {
        $title.text("Đang chat với: " + (data.thread.user_name || ("User #" + data.thread.user_id)));
      }

      var msgs = data.messages || [];
      if (msgs.length) {
        msgs.forEach(function (m) {
          lastId = Math.max(lastId, parseInt(m.id, 10) || 0);
          appendMessage(m);
        });
        scrollBottom();
      }
      setHint("");
      // refresh list to update unread badges / ordering
      loadThreads();
    } catch (e) {
      setHint("Không thể tải tin nhắn.");
    } finally {
      inflight = false;
    }
  }

  function startPolling() {
    if (pollTimer) return;
    pollTimer = setInterval(pollOnce, 1200);
  }
  function stopPolling() {
    if (pollTimer) clearInterval(pollTimer);
    pollTimer = null;
  }

  async function openThread(threadId) {
    activeThreadId = threadId;
    lastId = 0;
    $messages.html("");
    $input.prop("disabled", false);
    $sendBtn.prop("disabled", false);
    await pollOnce();
    startPolling();
    loadThreads();
  }

  $threads.on("click", "[data-thread-id]", function () {
    var tid = $(this).data("thread-id");
    openThread(tid);
  });

  $("#ss-admin-chat-refresh").on("click", function () {
    loadThreads();
    pollOnce();
  });

  $("#ss-admin-chat-form").on("submit", async function (ev) {
    ev.preventDefault();
    if (!activeThreadId) return;
    var text = String($input.val() || "").trim();
    if (!text) return;
    $input.val("");
    try {
      var fd = new FormData();
      fd.append("thread_id", String(activeThreadId));
      fd.append("body", text);
      var res = await fetch(sendUrl, { method: "POST", body: fd, credentials: "same-origin" });
      var data = await res.json();
      if (!data.ok) throw new Error(data.error || "send failed");
      if (data.message) {
        lastId = Math.max(lastId, parseInt(data.message.id, 10) || 0);
        appendMessage(data.message);
        scrollBottom();
      } else {
        pollOnce();
      }
      setHint("");
      loadThreads();
    } catch (e) {
      setHint("Gửi tin nhắn thất bại.");
    }
  });

  // init
  loadThreads();
})();
</script>';

require_once __DIR__ . '/includes/layout_end.php';
?>