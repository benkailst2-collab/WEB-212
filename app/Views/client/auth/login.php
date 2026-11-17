<?php
/**
 * app/Views/client/auth/login.php - Đăng nhập khách hàng
 * Biến: $error (string), $old (['email'])
 */
$pageTitle = 'Đăng nhập - MovieGo';
include __DIR__ . '/../cinema/layouts/header.php';
?>

<div class="max-w-md mx-auto px-4 py-12">
  <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
    <div class="text-center mb-6">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 mb-3">
        <i data-lucide="log-in" class="w-6 h-6"></i>
      </div>
      <h1 class="text-2xl font-black text-white">Đăng nhập</h1>
      <p class="text-xs text-gray-400 mt-1">Đăng nhập để đặt vé và xem lại vé của bạn.</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-medium">
        <?= $h($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="/login" class="space-y-4 text-xs" novalidate>
      <input type="hidden" name="csrf" value="<?= $h(csrf_token()) ?>">

      <div>
        <label for="email" class="block font-semibold text-gray-300 mb-1.5">Email</label>
        <input type="email" id="email" name="email" value="<?= $h($old['email'] ?? '') ?>" required autofocus autocomplete="email"
          placeholder="ten@email.com"
          class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
      </div>

      <div>
        <label for="password" class="block font-semibold text-gray-300 mb-1.5">Mật khẩu</label>
        <div class="relative">
          <input type="password" id="password" name="password" required autocomplete="current-password"
            class="w-full bg-gray-950 border border-gray-800 rounded-xl pl-4 pr-10 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
          <button type="button" onclick="togglePw('password', this)" aria-label="Hiện/ẩn mật khẩu"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
            <i data-lucide="eye" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      <button type="submit"
        class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-gray-950 font-black text-sm shadow-lg transition-all">
        Đăng nhập
      </button>
    </form>

    <p class="text-center text-xs text-gray-400 mt-6">
      Chưa có tài khoản?
      <a href="/register" class="font-bold text-amber-400 hover:text-amber-300">Đăng ký ngay</a>
    </p>
  </div>
</div>

<script>
  function togglePw(id, btn) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
  }
</script>

<?php include __DIR__ . '/../cinema/layouts/footer.php'; ?>
