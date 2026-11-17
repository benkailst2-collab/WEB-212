<?php
/**
 * app/Views/client/cinema/checkout.php - Trang Thanh Toán Đặt Vé
 */
$pageTitle = 'Thanh Toán Vé Xem Phim - MovieGo';
include __DIR__ . '/layouts/header.php';

$seatsParam = $_GET['seats'] ?? 'D5, D6';
$seatList = array_filter(array_map('trim', explode(',', $seatsParam)));
if (empty($seatList)) $seatList = ['D5', 'D6'];
$ticketTotal = count($seatList) * 95000;
$comboTotal = 109000;
$grandTotal = $ticketTotal + $comboTotal;
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- ─── Top Countdown & Stepper ─── -->
  <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-xs text-gray-400 hover:text-white">
      <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại chọn ghế
    </a>

    <div class="flex items-center gap-2 bg-amber-500/10 border border-amber-500/30 text-amber-400 px-4 py-1.5 rounded-full text-xs font-semibold">
      <i data-lucide="clock" class="w-4 h-4 animate-pulse"></i>
      <span>Thời gian giữ vé còn:</span>
      <span class="font-mono text-sm font-bold" id="timer">10:00</span>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- ─── Cột Form Trái (7 cols) ─── -->
    <div class="lg:col-span-7 space-y-6">
      <!-- 1. Thông tin người nhận -->
      <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 shadow-xl">
        <h2 class="text-base font-bold text-white mb-2 flex items-center gap-2">
          <i data-lucide="user" class="w-5 h-5 text-amber-400"></i>
          Thông tin nhận vé điện tử
        </h2>
        <p class="text-xs text-gray-400 mb-5">Vé và mã QR check-in sẽ được gửi tới Email & SMS của bạn.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-300 mb-1.5">Họ và tên <span class="text-red-400">*</span></label>
            <input type="text" value="Nguyễn Văn An" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-300 mb-1.5">Số điện thoại <span class="text-red-400">*</span></label>
            <input type="tel" value="0912 345 678" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-300 mb-1.5">Email nhận vé <span class="text-red-400">*</span></label>
            <input type="email" value="nguyenvanan@gmail.com" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
          </div>
        </div>
      </div>

      <!-- 2. Phương thức thanh toán -->
      <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 shadow-xl">
        <h2 class="text-base font-bold text-white mb-4 flex items-center gap-2">
          <i data-lucide="credit-card" class="w-5 h-5 text-amber-400"></i>
          Phương thức thanh toán
        </h2>

        <div class="space-y-3">
          <label class="flex items-center justify-between p-4 rounded-2xl border border-pink-500/50 bg-pink-950/20 cursor-pointer">
            <div class="flex items-center gap-3">
              <input type="radio" name="payment" value="momo" checked class="accent-pink-500 w-4 h-4">
              <span class="w-9 h-9 rounded-xl bg-pink-600/20 border border-pink-500/30 flex items-center justify-center font-bold text-pink-400 text-xs">MoMo</span>
              <div>
                <p class="text-xs font-bold text-white">Ví MoMo</p>
                <p class="text-[11px] text-gray-400">Quét mã QR hoặc thanh toán một chạm</p>
              </div>
            </div>
            <span class="text-[10px] bg-pink-500/20 text-pink-400 px-2 py-0.5 rounded-full font-bold">Khuyên dùng</span>
          </label>

          <label class="flex items-center justify-between p-4 rounded-2xl border border-gray-800 hover:border-gray-700 bg-gray-950/60 cursor-pointer">
            <div class="flex items-center gap-3">
              <input type="radio" name="payment" value="vnpay" class="accent-blue-500 w-4 h-4">
              <span class="w-9 h-9 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center font-bold text-blue-400 text-xs">VNPAY</span>
              <div>
                <p class="text-xs font-bold text-white">Cổng VNPAY</p>
                <p class="text-[11px] text-gray-400">Ứng dụng ngân hàng & VNPAY-QR</p>
              </div>
            </div>
          </label>

          <label class="flex items-center justify-between p-4 rounded-2xl border border-gray-800 hover:border-gray-700 bg-gray-950/60 cursor-pointer">
            <div class="flex items-center gap-3">
              <input type="radio" name="payment" value="card" class="accent-amber-500 w-4 h-4">
              <span class="w-9 h-9 rounded-xl bg-amber-600/20 border border-amber-500/30 flex items-center justify-center font-bold text-amber-400 text-xs">CARD</span>
              <div>
                <p class="text-xs font-bold text-white">Thẻ Quốc Tế / Thẻ ATM</p>
                <p class="text-[11px] text-gray-400">Visa, Mastercard, Napas</p>
              </div>
            </div>
          </label>
        </div>
      </div>

      <!-- 3. Mã giảm giá -->
      <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 shadow-xl">
        <h2 class="text-base font-bold text-white mb-3 flex items-center gap-2">
          <i data-lucide="tag" class="w-5 h-5 text-amber-400"></i>
          Mã giảm giá / Voucher
        </h2>
        <div class="flex gap-2">
          <input type="text" id="promo-input" placeholder="Nhập CINEMA20 hoặc VIP10" class="flex-1 bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white uppercase focus:outline-none focus:border-amber-500">
          <button type="button" onclick="applyPromo()" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-amber-400 text-xs font-bold rounded-xl border border-gray-700">Áp dụng</button>
        </div>
        <p id="promo-feedback" class="text-xs mt-2 hidden"></p>
      </div>
    </div>

    <!-- ─── Cột Tóm tắt Đơn hàng Phải (5 cols) ─── -->
    <div class="lg:col-span-5 space-y-6">
      <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 shadow-2xl sticky top-24">
        <h2 class="text-base font-bold text-white mb-4 border-b border-gray-800 pb-3">Tóm tắt đơn hàng</h2>

        <!-- Movie card -->
        <div class="flex gap-4 mb-4">
          <img src="https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDhRkZoNNsM4hXx.jpg" class="w-16 h-24 object-cover rounded-xl border border-gray-800">
          <div class="space-y-1 text-xs">
            <h3 class="font-bold text-white text-sm">Avengers: Doomsday</h3>
            <p class="text-gray-400">MovieGo Quận 1 • Phòng 1</p>
            <p class="text-amber-400 font-medium">16:00 - Hôm nay (IMAX 2D)</p>
          </div>
        </div>

        <div class="py-3 border-t border-gray-800 text-xs">
          <div class="flex justify-between mb-1.5">
            <span class="text-gray-400">Ghế đã chọn:</span>
            <span class="font-bold text-white"><?= number_format($ticketTotal, 0, ',', '.') ?>₫</span>
          </div>
          <div class="flex flex-wrap gap-1">
            <?php foreach ($seatList as $st): ?>
              <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 font-bold"><?= $st ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="py-3 border-t border-gray-800 text-xs space-y-1.5">
          <div class="flex justify-between text-gray-400">
            <span>Combo Couple (x1)</span>
            <span class="text-white"><?= number_format($comboTotal, 0, ',', '.') ?>₫</span>
          </div>
          <div class="flex justify-between text-emerald-400 font-medium hidden" id="discount-row">
            <span>Giảm giá Voucher:</span>
            <span id="discount-val">-0₫</span>
          </div>
        </div>

        <div class="pt-4 border-t border-gray-800 mb-6">
          <div class="flex justify-between items-baseline">
            <span class="text-xs font-bold text-gray-300">Tổng thanh toán:</span>
            <span class="text-2xl font-black text-amber-400" id="final-total"><?= number_format($grandTotal, 0, ',', '.') ?>₫</span>
          </div>
        </div>

        <a href="/cinema/ticket" class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-gray-950 font-extrabold text-sm transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
          <i data-lucide="lock" class="w-4 h-4"></i>
          Xác nhận thanh toán
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  // Countdown 10:00
  let timeLeft = 600;
  setInterval(() => {
    if (timeLeft <= 0) return;
    timeLeft--;
    const mins = Math.floor(timeLeft / 60);
    const secs = timeLeft % 60;
    document.getElementById('timer').innerText = 
      String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
  }, 1000);

  // Voucher
  let originalTotal = <?= $grandTotal ?>;
  function applyPromo() {
    const code = document.getElementById('promo-input').value.trim().toUpperCase();
    const fb = document.getElementById('promo-feedback');
    const discountRow = document.getElementById('discount-row');
    const discountVal = document.getElementById('discount-val');
    const finalTotal = document.getElementById('final-total');

    if (code === 'CINEMA20') {
      fb.className = 'text-xs mt-2 text-emerald-400';
      fb.innerText = 'Áp dụng thành công mã giảm 20.000₫!';
      discountRow.classList.remove('hidden');
      discountVal.innerText = '-20.000₫';
      finalTotal.innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalTotal - 20000);
    } else if (code === 'VIP10') {
      const d = Math.round(originalTotal * 0.1);
      fb.className = 'text-xs mt-2 text-emerald-400';
      fb.innerText = 'Áp dụng thành công mã VIP giảm 10%!';
      discountRow.classList.remove('hidden');
      discountVal.innerText = '-' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(d);
      finalTotal.innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalTotal - d);
    } else {
      fb.className = 'text-xs mt-2 text-red-400';
      fb.innerText = 'Mã khuyến mãi không hợp lệ.';
    }
  }
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
