<?php
/**
 * app/Views/admin/layouts/sidebar.php
 * MỞ tài liệu HTML + <div> bao ngoài, in sidebar tối.
 * Trang con tự đóng: </main></div></div></body></html> (xem dashboard/index.php).
 *
 * Biến tùy chọn: $pageTitle, $activeMenu (dashboard|products|categories|orders|users)
 */
$pageTitle = $pageTitle ?? 'Quản trị - NAM VISION';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH) ?: '/admin';
$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

$menu = [
    'dashboard'  => ['Dashboard',          '/admin',            'layout-dashboard'],
    'products'   => ['Quản lý Sản phẩm',   '/admin/products',   'shirt'],
    'categories' => ['Quản lý Danh mục',   '/admin/categories', 'layers'],
    'orders'     => ['Quản lý Đơn hàng',   '/admin/orders',     'shopping-cart'],
    'users'      => ['Quản lý User',       '/admin/users',      'users'],
];

// Xác định mục đang chọn: ưu tiên $activeMenu, nếu không thì so khớp theo URL
if (!isset($activeMenu)) {
    $activeMenu = 'dashboard';
    foreach ($menu as $key => [, $url]) {
        if ($key !== 'dashboard' && strpos($path, $url) === 0) { $activeMenu = $key; }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $h($pageTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        colors: { gold: { DEFAULT: '#B8975A', light: '#D9C08A', dark: '#7F6428' } },
        fontFamily: { display: ['"Cormorant Garamond"', 'serif'], sans: ['Manrope', 'sans-serif'] }
      } }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.469.0/dist/umd/lucide.min.js"></script>
</head>
<body class="bg-slate-100 font-sans text-slate-900 antialiased">
<div class="min-h-screen">

  <!-- Nền mờ khi mở sidebar trên mobile -->
  <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/50 lg:hidden" aria-hidden="true"></div>

  <aside id="admin-sidebar" aria-label="Menu quản trị"
         class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-slate-900 text-slate-300 transition-transform duration-200 motion-reduce:transition-none lg:translate-x-0">
    <div class="flex h-16 items-center gap-2 border-b border-slate-800 px-6">
      <span class="font-display text-xl font-bold tracking-[0.18em] text-white">NAM VISION</span>
      <span class="rounded bg-gold px-1.5 py-0.5 text-[11px] font-bold text-slate-900">Admin</span>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
      <?php foreach ($menu as $key => [$label, $url, $icon]):
          $active = $key === $activeMenu; ?>
        <a href="<?= $h($url) ?>" <?= $active ? 'aria-current="page"' : '' ?>
           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold <?= $active ? 'bg-gold text-slate-900' : 'hover:bg-slate-800 hover:text-white' ?>">
          <i data-lucide="<?= $h($icon) ?>" class="h-5 w-5"></i>
          <?= $h($label) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="border-t border-slate-800 p-3">
      <a href="/" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-slate-800 hover:text-white">
        <i data-lucide="external-link" class="h-5 w-5"></i> Xem website
      </a>
    </div>
  </aside>
