<?php
/**
 * app/Views/admin/auth/login.php - Đăng nhập Quản trị (Light Mode, trang độc lập)
 * Biến: $error (string), $old (['email'])
 */
$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập Quản trị - MovieGo Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.469.0/dist/umd/lucide.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-sm">
    <div class="text-center mb-6">
      <div class="inline-flex items-center gap-2 font-black text-xl text-gray-900">
        <i data-lucide="film" class="w-7 h-7 text-blue-600"></i>
        <span>MovieGo <span class="text-xs text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Admin</span></span>
      </div>
      <p class="text-xs text-gray-500 mt-2">Đăng nhập để vào trang quản trị hệ thống.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 sm:p-8">
      <?php if (!empty($error)): ?>
        <div class="mb-4 px-4 py-3 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-xs font-medium">
          <?= $h($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="/admin/login" class="space-y-4 text-xs" novalidate>
        <input type="hidden" name="csrf" value="<?= $h(csrf_token()) ?>">

        <div>
          <label for="email" class="block font-semibold text-gray-700 mb-1">Email quản trị</label>
          <input type="email" id="email" name="email" value="<?= $h($old['email'] ?? '') ?>" required autofocus autocomplete="username"
            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:border-blue-600">
        </div>

        <div>
          <label for="password" class="block font-semibold text-gray-700 mb-1">Mật khẩu</label>
          <input type="password" id="password" name="password" required autocomplete="current-password"
            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:border-blue-600">
        </div>

        <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition-colors">
          Đăng nhập
        </button>
      </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-5">
      <a href="/cinema" class="hover:text-gray-600">&larr; Về trang khách hàng</a>
    </p>
  </div>

  <script>if (window.lucide) lucide.createIcons();</script>
</body>
</html>
