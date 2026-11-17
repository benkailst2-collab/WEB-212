<?php
/**
 * app/Views/client/layouts/header.php
 * Mở tài liệu HTML (<html>, <head>, <body>), in thanh điều hướng và MỞ <main>.
 * footer.php sẽ đóng </main>, </body>, </html>.
 *
 * Biến tùy chọn từ controller:
 *   $pageTitle      Tiêu đề trang
 *   $cartCount      Số sản phẩm trong giỏ (hiện ở badge)
 *   $navCategories  [ [nhãn, url], ... ]
 */
$pageTitle = $pageTitle ?? 'NAM VISION - Thời trang nam';
$cartCount = (int) ($cartCount ?? 0);
$navCategories = $navCategories ?? [
    ['Áo', '/products?category=ao'],
    ['Quần', '/products?category=quan'],
    ['Phụ kiện', '/products?category=phu-kien'],
    ['BST mới', '/collections/new'],
];
$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$searchQuery = $_GET['q'] ?? '';
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
  <!-- Pin phiên bản để còn đủ icon thương hiệu (facebook, instagram, youtube) -->
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.469.0/dist/umd/lucide.min.js"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:px-4 focus:py-2">Bỏ qua điều hướng</a>

<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
  <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">

    <!-- Nút menu (mobile) -->
    <button type="button" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Mở menu"
            class="-ml-2 p-2 text-slate-900 lg:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
      <i data-lucide="menu" class="h-6 w-6"></i>
    </button>

    <!-- Logo -->
    <a href="/" class="font-display text-2xl font-bold tracking-[0.18em] text-slate-900">NAM VISION</a>

    <!-- Menu danh mục (desktop) -->
    <nav aria-label="Danh mục chính" class="ml-6 hidden items-center gap-7 lg:flex">
      <?php foreach ($navCategories as [$label, $url]): ?>
        <a href="<?= $h($url) ?>" class="text-sm font-medium text-slate-700 transition hover:text-gold-dark"><?= $h($label) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="ml-auto flex items-center gap-1 sm:gap-2">
      <!-- Tìm kiếm nhanh (desktop) -->
      <form action="/products" method="get" role="search" class="hidden md:block">
        <label for="search-desktop" class="sr-only">Tìm sản phẩm</label>
        <div class="relative">
          <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
          <input id="search-desktop" type="search" name="q" value="<?= $h($searchQuery) ?>" placeholder="Tìm sản phẩm"
                 class="w-56 rounded-full border border-slate-300 bg-slate-50 py-2 pl-9 pr-4 text-sm placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>
      </form>

      <a href="/account" aria-label="Tài khoản"
         class="p-2 text-slate-900 transition hover:text-gold-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
        <i data-lucide="user" class="h-5 w-5"></i>
      </a>

      <a href="/cart" aria-label="Giỏ hàng" class="relative p-2 text-slate-900 transition hover:text-gold-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
        <i data-lucide="shopping-bag" class="h-5 w-5"></i>
        <!-- JS cập nhật badge qua [data-cart-count] -->
        <span data-cart-count
              class="<?= $cartCount > 0 ? '' : 'hidden' ?> absolute -right-0.5 -top-0.5 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-gold px-1 text-[11px] font-bold text-slate-900"><?= $cartCount ?></span>
      </a>
    </div>
  </div>

  <!-- Menu mobile -->
  <div id="mobile-menu" class="hidden border-t border-slate-200 bg-white lg:hidden">
    <form action="/products" method="get" role="search" class="px-4 pt-4">
      <label for="search-mobile" class="sr-only">Tìm sản phẩm</label>
      <input id="search-mobile" type="search" name="q" value="<?= $h($searchQuery) ?>" placeholder="Tìm sản phẩm"
             class="w-full rounded-full border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
    </form>
    <nav aria-label="Danh mục (mobile)" class="flex flex-col px-4 py-3">
      <?php foreach ($navCategories as [$label, $url]): ?>
        <a href="<?= $h($url) ?>" class="border-b border-slate-100 py-3 text-sm font-medium text-slate-800 last:border-0"><?= $h($label) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>

<main id="main">
