<?php
/**
 * app/Views/admin/layouts/header.php
 * Include SAU sidebar.php. MỞ <div> cột nội dung, in topbar và MỞ <main>.
 * Biến tùy chọn: $adminName, $notificationCount
 * Đăng xuất: POST /admin/logout (kèm $csrfToken nếu có)
 */
$adminName = $adminName ?? 'Admin';
$notificationCount = (int) ($notificationCount ?? 0);
$csrfToken = $csrfToken ?? '';
$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$initial = mb_strtoupper(mb_substr($adminName, 0, 1, 'UTF-8'), 'UTF-8');
?>
<div class="lg:pl-64">
  <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8">

    <button type="button" id="sidebar-toggle" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Mở menu"
            class="-ml-2 p-2 text-slate-700 lg:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
      <i data-lucide="menu" class="h-6 w-6"></i>
    </button>

    <div class="ml-auto flex items-center gap-2 sm:gap-4">
      <!-- Thông báo -->
      <a href="/admin/orders?status=pending" aria-label="Thông báo<?= $notificationCount ? ', ' . $notificationCount . ' mới' : '' ?>"
         class="relative p-2 text-slate-600 transition hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
        <i data-lucide="bell" class="h-5 w-5"></i>
        <?php if ($notificationCount > 0): ?>
          <span class="absolute right-0.5 top-0.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-bold text-white"><?= $notificationCount ?></span>
        <?php endif; ?>
      </a>

      <!-- Tên admin -->
      <div class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-gold" aria-hidden="true"><?= $h($initial) ?></span>
        <span class="hidden text-sm font-medium text-slate-900 sm:block"><?= $h($adminName) ?></span>
      </div>

      <!-- Đăng xuất -->
      <form action="/admin/logout" method="post">
        <input type="hidden" name="csrf_token" value="<?= $h($csrfToken) ?>">
        <button type="submit"
                class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
          <i data-lucide="log-out" class="h-4 w-4"></i>
          <span class="hidden sm:inline">Đăng xuất</span>
        </button>
      </form>
    </div>
  </header>

  <main id="main" class="p-4 sm:p-6 lg:p-8">

<script>
(function () {
  const sidebar = document.getElementById('admin-sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  const btn = document.getElementById('sidebar-toggle');

  function setOpen(open) {
    sidebar.classList.toggle('-translate-x-full', !open);
    overlay.classList.toggle('hidden', !open);
    btn.setAttribute('aria-expanded', String(open));
  }
  btn.addEventListener('click', () => setOpen(sidebar.classList.contains('-translate-x-full')));
  overlay.addEventListener('click', () => setOpen(false));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setOpen(false); });
})();
</script>
