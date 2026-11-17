<?php
/**
 * app/Views/client/cinema/ticket.php - Vé Xem Phim Điện Tử & Mã QR Check-in
 */
$pageTitle = 'Vé Điện Tử MovieGo - Đặt Vé Thành Công';
include __DIR__ . '/layouts/header.php';

$orderId = 'CINE-' . rand(100000, 999999);
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
  <!-- ─── Banner chúc mừng ─── -->
  <div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 mb-3 animate-bounce">
      <i data-lucide="check-circle" class="w-8 h-8"></i>
    </div>
    <span class="inline-block px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold mb-2">
      Đặt vé thành công!
    </span>
    <h1 class="text-2xl sm:text-3xl font-black text-white">Vé Xem Phim Điện Tử</h1>
    <p class="text-xs text-gray-400 mt-1">Xuất trình mã QR dưới đây tại quầy vé hoặc cửa soát vé tự động.</p>
  </div>

  <!-- ─── Thẻ Vé Phim Cao Cấp ─── -->
  <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden shadow-2xl relative">
    <!-- Ribbon top -->
    <div class="bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 px-6 py-3 flex items-center justify-between text-gray-950">
      <div class="flex items-center gap-2 font-black text-xs uppercase tracking-wider">
        <i data-lucide="ticket" class="w-4 h-4"></i>
        <span>MovieGo E-Ticket</span>
      </div>
      <span class="font-mono font-bold text-xs bg-gray-950/20 px-2 py-0.5 rounded">MÃ: <?= $orderId ?></span>
    </div>

    <!-- Thân vé -->
    <div class="p-6 sm:p-8">
      <div class="flex flex-col sm:flex-row gap-6 items-start">
        <img src="https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDhRkZoNNsM4hXx.jpg" class="w-24 sm:w-28 rounded-xl object-cover shadow-lg border border-gray-800 shrink-0">
        
        <div class="space-y-3 flex-1">
          <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-indigo-600/30 text-indigo-400 border border-indigo-500/40">IMAX 2D</span>
          <h2 class="text-xl font-black text-white leading-tight">Avengers: Doomsday</h2>

          <div class="grid grid-cols-2 gap-3 text-xs text-gray-400 pt-2 border-y border-gray-800/80">
            <div><span class="block text-gray-500">Rạp chiếu</span><strong class="text-white">MovieGo Quận 1</strong></div>
            <div><span class="block text-gray-500">Phòng chiếu</span><strong class="text-amber-400">Phòng 1 (IMAX)</strong></div>
            <div><span class="block text-gray-500">Suất chiếu</span><strong class="text-white">16:00 - Hôm nay</strong></div>
            <div><span class="block text-gray-500">Bắp nước</span><strong class="text-white">Combo Couple (x1)</strong></div>
          </div>

          <div>
            <span class="text-xs text-gray-500 block mb-1">Ghế ngồi:</span>
            <div class="flex gap-2">
              <span class="px-3 py-1 rounded-lg bg-amber-500 text-gray-950 font-black text-xs shadow">D5</span>
              <span class="px-3 py-1 rounded-lg bg-amber-500 text-gray-950 font-black text-xs shadow">D6</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Đường đứt xé vé (Perforated line with cutouts) -->
    <div class="relative my-2">
      <div class="absolute -left-4 -top-3.5 w-7 h-7 rounded-full bg-gray-950 border-r border-gray-800"></div>
      <div class="border-b-2 border-dashed border-gray-800 w-full"></div>
      <div class="absolute -right-4 -top-3.5 w-7 h-7 rounded-full bg-gray-950 border-l border-gray-800"></div>
    </div>

    <!-- QR Section -->
    <div class="p-6 sm:p-8 bg-gray-950/60 flex flex-col sm:flex-row items-center justify-between gap-6">
      <div class="space-y-1 text-center sm:text-left text-xs">
        <p class="text-gray-400">Người đặt: <strong class="text-white">Nguyễn Văn An (0912 345 678)</strong></p>
        <p class="text-gray-500">Vui lòng có mặt tại rạp trước giờ chiếu 15 phút.</p>
        <p class="text-amber-400 font-bold pt-1">Tổng tiền: 299.000₫ (Đã thanh toán MoMo)</p>
      </div>

      <!-- Live SVG QR Code simulation -->
      <div class="flex flex-col items-center bg-white p-3 rounded-2xl shadow-xl">
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="black">
          <!-- QR pattern -->
          <rect x="5" y="5" width="30" height="30" fill="none" stroke="black" stroke-width="6"/>
          <rect x="15" y="15" width="10" height="10"/>
          <rect x="65" y="5" width="30" height="30" fill="none" stroke="black" stroke-width="6"/>
          <rect x="75" y="15" width="10" height="10"/>
          <rect x="5" y="65" width="30" height="30" fill="none" stroke="black" stroke-width="6"/>
          <rect x="15" y="75" width="10" height="10"/>
          <!-- Data dots -->
          <rect x="42" y="10" width="8" height="8"/><rect x="52" y="20" width="8" height="8"/>
          <rect x="42" y="32" width="8" height="8"/><rect x="10" y="45" width="8" height="8"/>
          <rect x="25" y="45" width="8" height="8"/><rect x="42" y="52" width="8" height="8"/>
          <rect x="62" y="45" width="8" height="8"/><rect x="80" y="52" width="8" height="8"/>
          <rect x="42" y="75" width="8" height="8"/><rect x="62" y="70" width="8" height="8"/>
          <rect x="75" y="80" width="8" height="8"/>
        </svg>
        <span class="text-[10px] font-mono text-gray-800 font-bold mt-1"><?= $orderId ?></span>
      </div>
    </div>
  </div>

  <!-- Nút Thao tác -->
  <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
    <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-white font-medium text-xs border border-gray-700 flex items-center gap-2">
      <i data-lucide="printer" class="w-4 h-4 text-amber-400"></i> In / Lưu vé PDF
    </button>
    <a href="/cinema" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold text-xs flex items-center gap-2 shadow-lg shadow-amber-500/20">
      <i data-lucide="home" class="w-4 h-4"></i> Về trang chủ
    </a>
  </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
