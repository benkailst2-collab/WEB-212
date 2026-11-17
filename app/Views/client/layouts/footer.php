<?php
/**
 * app/Views/client/layouts/footer.php
 * Đóng </main>, in footer, nhúng ai_widget.php, khởi tạo icon, rồi đóng </body></html>.
 * Newsletter: POST /newsletter/subscribe {email} -> {success, message}
 */
$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
</main>

<footer class="bg-slate-900 text-slate-300">
  <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

      <!-- Thương hiệu + mạng xã hội -->
      <div>
        <p class="font-display text-2xl font-bold tracking-[0.18em] text-white">NAM VISION</p>
        <p class="mt-4 max-w-xs text-sm leading-relaxed">
          Thời trang nam tối giản, đường may gọn và chất liệu bền. Mặc đẹp mà không cần cầu kỳ.
        </p>
        <div class="mt-5 flex gap-3">
          <a href="https://facebook.com" aria-label="Facebook" class="p-2 text-slate-300 transition hover:text-gold"><i data-lucide="facebook" class="h-5 w-5"></i></a>
          <a href="https://instagram.com" aria-label="Instagram" class="p-2 text-slate-300 transition hover:text-gold"><i data-lucide="instagram" class="h-5 w-5"></i></a>
          <a href="https://youtube.com" aria-label="YouTube" class="p-2 text-slate-300 transition hover:text-gold"><i data-lucide="youtube" class="h-5 w-5"></i></a>
        </div>
      </div>

      <!-- Chính sách -->
      <nav aria-label="Chính sách">
        <h2 class="text-sm font-semibold text-white">Chính sách</h2>
        <ul class="mt-4 space-y-2.5 text-sm">
          <li><a href="/policy/returns" class="hover:text-gold">Chính sách đổi trả</a></li>
          <li><a href="/policy/shipping" class="hover:text-gold">Vận chuyển và giao hàng</a></li>
          <li><a href="/policy/warranty" class="hover:text-gold">Bảo hành và sửa đồ</a></li>
          <li><a href="/policy/privacy" class="hover:text-gold">Bảo mật thông tin</a></li>
        </ul>
      </nav>

      <!-- Hệ thống cửa hàng -->
      <div>
        <h2 class="text-sm font-semibold text-white">Hệ thống cửa hàng</h2>
        <ul class="mt-4 space-y-2.5 text-sm">
          <li>12 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội</li>
          <li>88 Nguyễn Huệ, Quận 1, TP.HCM</li>
          <li><a href="/stores" class="underline underline-offset-4 hover:text-gold">Xem tất cả cửa hàng</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div>
        <h2 class="text-sm font-semibold text-white">Nhận tin từ NAM VISION</h2>
        <p class="mt-4 text-sm">Bộ sưu tập mới và ưu đãi dành riêng cho thành viên.</p>
        <form id="newsletter-form" class="mt-4 flex" novalidate>
          <label for="newsletter-email" class="sr-only">Email của bạn</label>
          <input id="newsletter-email" type="email" required placeholder="Email của bạn"
                 class="min-w-0 flex-1 border border-slate-600 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder:text-slate-400 focus:border-gold focus:outline-none">
          <button type="submit"
                  class="bg-gold px-5 text-sm font-semibold text-slate-900 transition hover:bg-gold-light focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white disabled:opacity-60">
            Đăng ký
          </button>
        </form>
        <p id="newsletter-msg" role="status" class="mt-2 min-h-[1.25rem] text-sm"></p>
      </div>
    </div>

    <div class="mt-12 border-t border-slate-700 pt-6 text-sm text-slate-400">
      &copy; <?= date('Y') ?> NAM VISION. Đã đăng ký bản quyền.
    </div>
  </div>
</footer>

<!-- Widget AI Stylist -->
<?php require __DIR__ . '/ai_widget.php'; ?>

<script>
(function () {
  /* Menu mobile */
  const btn = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');
  if (btn && menu) {
    btn.addEventListener('click', () => {
      const open = menu.classList.toggle('hidden') === false;
      btn.setAttribute('aria-expanded', String(open));
    });
  }

  /* Newsletter */
  const form = document.getElementById('newsletter-form');
  const msg = document.getElementById('newsletter-msg');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('newsletter-email');
      const submit = form.querySelector('button');
      if (!email.checkValidity()) {
        msg.className = 'mt-2 min-h-[1.25rem] text-sm text-rose-300';
        msg.textContent = 'Email chưa hợp lệ.';
        return;
      }
      submit.disabled = true;
      try {
        const res = await fetch('/newsletter/subscribe', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ email: email.value })
        });
        const data = await res.json();
        if (!res.ok || data.success === false) throw new Error(data.message || 'Đăng ký chưa thành công.');
        msg.className = 'mt-2 min-h-[1.25rem] text-sm text-emerald-300';
        msg.textContent = data.message || 'Đăng ký thành công.';
        form.reset();
      } catch (err) {
        msg.className = 'mt-2 min-h-[1.25rem] text-sm text-rose-300';
        msg.textContent = err.message;
      } finally {
        submit.disabled = false;
      }
    });
  }

  if (window.lucide) window.lucide.createIcons();
})();
</script>
</body>
</html>
