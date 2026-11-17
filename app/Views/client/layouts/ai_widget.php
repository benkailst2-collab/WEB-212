<?php
/**
 * app/Views/client/layouts/ai_widget.php
 * Widget chat "AI Stylist" nổi ở góc dưới bên phải.
 *
 * Cách dùng: include ở cuối footer.php, ngay trước </body>
 *   <?php require __DIR__ . '/ai_widget.php'; ?>
 *
 * Biến PHP controller có thể truyền vào (đều có giá trị mặc định):
 *   $aiEndpoint     URL nhận tin nhắn. POST JSON {"message": "..."} -> trả JSON {"reply": "..."}
 *   $csrfToken      CSRF token, gửi qua header X-CSRF-Token
 *   $aiGreeting     Lời chào đầu tiên của AI
 *   $aiQuickPrompts Mảng câu gợi ý nhanh
 *
 * Mở widget từ nơi khác (VD: banner trang chủ): window.openAiStylist()
 */
$aiEndpoint = $aiEndpoint ?? '/ai/stylist';
$csrfToken  = $csrfToken ?? '';
$aiGreeting = $aiGreeting ?? 'Chào bạn, mình là AI Stylist của NAM VISION. Cho mình biết chiều cao, cân nặng và dịp bạn cần mặc, mình sẽ gợi ý bộ đồ phù hợp.';
$aiQuickPrompts = $aiQuickPrompts ?? [
    'Phối đồ 1m70',
    'Trang phục đi cưới',
    'Đi làm văn phòng',
    'Hẹn hò cuối tuần',
];
$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<div id="ai-widget"
     class="fixed bottom-4 right-4 z-50 flex flex-col items-end gap-3 sm:bottom-6 sm:right-6"
     data-endpoint="<?= $h($aiEndpoint) ?>"
     data-csrf="<?= $h($csrfToken) ?>"
     data-greeting="<?= $h($aiGreeting) ?>">

  <!-- ===== Khung chat ===== -->
  <section id="ai-panel" role="dialog" aria-label="Trò chuyện với AI Stylist"
           class="hidden h-[32rem] max-h-[calc(100vh-7rem)] w-[calc(100vw-2rem)] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10 sm:w-96">

    <!-- Header -->
    <header class="flex items-center gap-3 bg-slate-900 px-4 py-3.5 text-white">
      <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold text-slate-900">
        <i data-lucide="bot" class="h-5 w-5"></i>
      </span>
      <div class="min-w-0 flex-1">
        <p class="font-display text-lg leading-tight">AI Stylist</p>
        <p class="truncate text-xs text-slate-300">Phối đồ theo vóc dáng và dịp mặc</p>
      </div>
      <button id="ai-close" type="button" aria-label="Đóng khung chat"
              class="rounded-full p-2 text-slate-300 transition hover:bg-white/10 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
        <i data-lucide="x" class="h-5 w-5"></i>
      </button>
    </header>

    <!-- Tin nhắn -->
    <div id="ai-messages" role="log" aria-live="polite"
         class="flex-1 space-y-3 overflow-y-auto bg-slate-50 px-4 py-4"></div>

    <!-- Gợi ý nhanh -->
    <div id="ai-quick" class="flex flex-wrap gap-2 border-t border-slate-200 bg-slate-50 px-4 py-3">
      <?php foreach ($aiQuickPrompts as $prompt): ?>
        <button type="button" data-prompt="<?= $h($prompt) ?>"
                class="rounded-full border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:border-gold hover:text-gold-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
          <?= $h($prompt) ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Form gửi (AJAX, không submit trang) -->
    <form id="ai-form" class="flex items-center gap-2 border-t border-slate-200 bg-white p-3" autocomplete="off">
      <label for="ai-input" class="sr-only">Tin nhắn cho AI Stylist</label>
      <input id="ai-input" type="text" maxlength="500" required placeholder="Nhập câu hỏi về phối đồ..."
             class="min-w-0 flex-1 rounded-full border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
      <button id="ai-send" type="submit" aria-label="Gửi tin nhắn"
              class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-900 text-white transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold">
        <i data-lucide="send" class="h-4 w-4"></i>
      </button>
    </form>
  </section>

  <!-- ===== Nút tròn mở chat ===== -->
  <button id="ai-toggle" type="button" aria-expanded="false" aria-controls="ai-panel"
          class="flex items-center gap-2.5 rounded-full bg-slate-900 py-2.5 pl-2.5 pr-5 text-white shadow-xl shadow-slate-900/30 ring-1 ring-white/10 transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold">
    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold text-slate-900">
      <i data-lucide="bot" class="h-5 w-5"></i>
    </span>
    <span class="text-sm font-semibold">AI Stylist</span>
  </button>
</div>

<script>
(function () {
  const root = document.getElementById('ai-widget');
  if (!root) return;

  const toggle   = root.querySelector('#ai-toggle');
  const panel    = root.querySelector('#ai-panel');
  const closeBtn = root.querySelector('#ai-close');
  const log      = root.querySelector('#ai-messages');
  const quick    = root.querySelector('#ai-quick');
  const form     = root.querySelector('#ai-form');
  const input    = root.querySelector('#ai-input');
  const sendBtn  = root.querySelector('#ai-send');

  const endpoint = root.dataset.endpoint;
  const csrf     = root.dataset.csrf;
  let busy = false;
  let greeted = false;

  /* ---------- Mở / đóng ---------- */
  function setOpen(open) {
    panel.classList.toggle('hidden', !open);
    panel.classList.toggle('flex', open);
    toggle.setAttribute('aria-expanded', String(open));
    if (open) {
      if (!greeted) { addMessage('ai', root.dataset.greeting); greeted = true; }
      input.focus();
      scrollToBottom();
    }
  }
  toggle.addEventListener('click', () => setOpen(panel.classList.contains('hidden')));
  closeBtn.addEventListener('click', () => { setOpen(false); toggle.focus(); });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !panel.classList.contains('hidden')) { setOpen(false); toggle.focus(); }
  });
  window.openAiStylist = () => setOpen(true); // cho banner / nút ngoài widget gọi

  /* ---------- Hiển thị tin nhắn (dùng textContent -> an toàn XSS) ---------- */
  function scrollToBottom() { log.scrollTop = log.scrollHeight; }

  function addMessage(role, text) {
    const row = document.createElement('div');
    const bubble = document.createElement('div');
    bubble.textContent = text;
    bubble.className = 'max-w-[85%] whitespace-pre-wrap break-words px-3.5 py-2.5 text-sm leading-relaxed ';

    if (role === 'user') {
      row.className = 'flex justify-end';
      bubble.className += 'rounded-2xl rounded-br-sm bg-slate-900 text-white';
    } else {
      row.className = 'flex items-start gap-2';
      const avatar = document.createElement('span');
      avatar.className = 'mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gold text-xs font-bold text-slate-900';
      avatar.textContent = 'AI';
      avatar.setAttribute('aria-hidden', 'true');
      row.appendChild(avatar);
      bubble.className += 'rounded-2xl rounded-bl-sm bg-white text-slate-800 ring-1 ring-slate-200';
    }
    row.appendChild(bubble);
    log.appendChild(row);
    scrollToBottom();
    return row;
  }

  function addTyping() {
    const row = document.createElement('div');
    row.className = 'flex items-start gap-2';
    row.innerHTML =
      '<span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gold text-xs font-bold text-slate-900" aria-hidden="true">AI</span>' +
      '<div class="flex gap-1 rounded-2xl rounded-bl-sm bg-white px-4 py-3.5 ring-1 ring-slate-200" role="status" aria-label="AI Stylist đang trả lời">' +
      '<span class="h-1.5 w-1.5 animate-pulse rounded-full bg-slate-400 motion-reduce:animate-none"></span>' +
      '<span class="h-1.5 w-1.5 animate-pulse rounded-full bg-slate-400 [animation-delay:150ms] motion-reduce:animate-none"></span>' +
      '<span class="h-1.5 w-1.5 animate-pulse rounded-full bg-slate-400 [animation-delay:300ms] motion-reduce:animate-none"></span>' +
      '</div>';
    log.appendChild(row);
    scrollToBottom();
    return row;
  }

  /* ---------- Gửi tin nhắn qua Fetch ---------- */
  async function send(text) {
    text = text.trim();
    if (!text || busy) return;

    busy = true;
    sendBtn.disabled = true;
    quick.classList.add('hidden');
    addMessage('user', text);
    input.value = '';
    const typing = addTyping();

    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 20000);

    try {
      const res = await fetch(endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-Token': csrf
        },
        body: JSON.stringify({ message: text }),
        signal: controller.signal
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();
      typing.remove();
      addMessage('ai', data.reply || 'Mình chưa có gợi ý phù hợp, bạn mô tả thêm giúp mình nhé.');
    } catch (err) {
      typing.remove();
      addMessage('ai', 'AI Stylist đang bận, bạn thử lại sau ít phút nhé.');
    } finally {
      clearTimeout(timeout);
      busy = false;
      sendBtn.disabled = false;
      input.focus();
    }
  }

  form.addEventListener('submit', (e) => { e.preventDefault(); send(input.value); });
  quick.addEventListener('click', (e) => {
    const chip = e.target.closest('[data-prompt]');
    if (chip) send(chip.dataset.prompt);
  });

  if (window.lucide) window.lucide.createIcons();
})();
</script>
