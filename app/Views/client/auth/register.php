<?php
/**
 * app/Views/client/auth/register.php - Đăng ký khách hàng
 * Biến: $errors (mảng lỗi theo trường + 'form'), $old (['full_name','email','phone'])
 */
$pageTitle = 'Đăng ký - MovieGo';
include __DIR__ . '/../cinema/layouts/header.php';

$inputCls = 'w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500';
$err = fn($k) => !empty($errors[$k]) ? '<p class="text-[11px] text-red-400 mt-1">' . $h($errors[$k]) . '</p>' : '';
?>

<div class="max-w-md mx-auto px-4 py-12">
  <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
    <div class="text-center mb-6">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 mb-3">
        <i data-lucide="user-plus" class="w-6 h-6"></i>
      </div>
      <h1 class="text-2xl font-black text-white">Tạo tài khoản</h1>
      <p class="text-xs text-gray-400 mt-1">Đăng ký miễn phí để đặt vé nhanh hơn.</p>
    </div>

    <?php if (!empty($errors['form'])): ?>
      <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-medium">
        <?= $h($errors['form']) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="/register" class="space-y-4 text-xs" novalidate>
      <input type="hidden" name="csrf" value="<?= $h(csrf_token()) ?>">

      <div>
        <label for="full_name" class="block font-semibold text-gray-300 mb-1.5">Họ và tên</label>
        <input type="text" id="full_name" name="full_name" value="<?= $h($old['full_name'] ?? '') ?>" required autofocus autocomplete="name" class="<?= $inputCls ?>">
        <?= $err('full_name') ?>
      </div>

      <div>
        <label for="email" class="block font-semibold text-gray-300 mb-1.5">Email</label>
        <input type="email" id="email" name="email" value="<?= $h($old['email'] ?? '') ?>" required autocomplete="email" placeholder="ten@email.com" class="<?= $inputCls ?>">
        <?= $err('email') ?>
      </div>

      <div>
        <label for="phone" class="block font-semibold text-gray-300 mb-1.5">Số điện thoại <span class="text-gray-500 font-normal">(không bắt buộc)</span></label>
        <input type="tel" id="phone" name="phone" value="<?= $h($old['phone'] ?? '') ?>" autocomplete="tel" placeholder="0912345678" class="<?= $inputCls ?>">
        <?= $err('phone') ?>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="password" class="block font-semibold text-gray-300 mb-1.5">Mật khẩu</label>
          <input type="password" id="password" name="password" required autocomplete="new-password" class="<?= $inputCls ?>">
          <?= $err('password') ?>
        </div>
        <div>
          <label for="password_confirm" class="block font-semibold text-gray-300 mb-1.5">Nhập lại mật khẩu</label>
          <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password" class="<?= $inputCls ?>">
          <?= $err('password_confirm') ?>
        </div>
      </div>

      <button type="submit"
        class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-gray-950 font-black text-sm shadow-lg transition-all">
        Đăng ký
      </button>
    </form>

    <p class="text-center text-xs text-gray-400 mt-6">
      Đã có tài khoản?
      <a href="/login" class="font-bold text-amber-400 hover:text-amber-300">Đăng nhập</a>
    </p>
  </div>
</div>

<?php include __DIR__ . '/../cinema/layouts/footer.php'; ?>
