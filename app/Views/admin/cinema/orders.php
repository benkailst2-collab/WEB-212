<?php
/**
 * app/Views/admin/cinema/orders.php - Quản lý Đơn hàng MovieGo Admin
 */
$pageTitle = 'Quản Lý Đơn Hàng - MovieGo Admin';
$activeMenu = 'orders';
include __DIR__ . '/layouts/sidebar.php';

$orders = [
  ['code' => 'ORD-20261101-001', 'movie' => 'Avengers: Doomsday', 'cinema' => 'MovieGo Quận 1', 'customer' => 'Nguyễn Văn An', 'phone' => '0912345678', 'seats' => 'D5, D6', 'combos' => 'Combo Couple (x1)', 'total' => 299000, 'status' => 'completed', 'method' => 'MoMo', 'time' => '01/11/2026 08:30'],
  ['code' => 'ORD-20261101-002', 'movie' => 'Dune: Phần Ba', 'cinema' => 'MovieGo Quận 7', 'customer' => 'Trần Thị Bích', 'phone' => '0987654321', 'seats' => 'E3, E4, E5', 'combos' => 'Combo Family (x1)', 'total' => 484000, 'status' => 'completed', 'method' => 'VNPAY', 'time' => '01/11/2026 10:15'],
  ['code' => 'ORD-20261102-001', 'movie' => 'Lật Mặt 9', 'cinema' => 'MovieGo Quận 1', 'customer' => 'Lê Minh Tuấn', 'phone' => '0903112233', 'seats' => 'B7, B8', 'combos' => 'Combo Solo (x2)', 'total' => 298000, 'status' => 'pending', 'method' => 'Thẻ ATM', 'time' => '02/11/2026 07:45'],
  ['code' => 'ORD-20261102-002', 'movie' => 'Mai - Phần 2', 'cinema' => 'MovieGo Quận 1', 'customer' => 'Phạm Hoàng Yến', 'phone' => '0934556677', 'seats' => 'C4, C5', 'combos' => 'Không', 'total' => 569000, 'status' => 'cancelled', 'method' => 'ZaloPay', 'time' => '02/11/2026 11:00'],
];
?>

<div class="space-y-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Quản lý Đơn Hàng & Vé</h1>
    <p class="text-xs text-gray-500 mt-0.5">Tra cứu danh sách giao dịch, trạng thái thanh toán và thông tin vé đặt.</p>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
          <tr>
            <th class="py-3 px-4">Mã đơn</th>
            <th class="py-3 px-4">Phim & Rạp</th>
            <th class="py-3 px-4">Khách hàng</th>
            <th class="py-3 px-4">Ghế ngồi</th>
            <th class="py-3 px-4">Tổng tiền</th>
            <th class="py-3 px-4">Thanh toán</th>
            <th class="py-3 px-4">Trạng thái</th>
            <th class="py-3 px-4 text-right">Chi tiết</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($orders as $od): ?>
            <tr class="hover:bg-gray-50/80 transition-colors">
              <td class="py-3.5 px-4 font-mono font-bold text-blue-600"><?= $od['code'] ?></td>
              <td class="py-3.5 px-4">
                <span class="font-bold text-gray-900 block"><?= htmlspecialchars($od['movie']) ?></span>
                <span class="text-gray-400 text-[11px]"><?= htmlspecialchars($od['cinema']) ?></span>
              </td>
              <td class="py-3.5 px-4">
                <span class="font-semibold text-gray-800 block"><?= htmlspecialchars($od['customer']) ?></span>
                <span class="text-gray-400 text-[11px]"><?= $od['phone'] ?></span>
              </td>
              <td class="py-3.5 px-4 font-semibold text-gray-700"><?= $od['seats'] ?></td>
              <td class="py-3.5 px-4 font-bold text-gray-900"><?= number_format($od['total'], 0, ',', '.') ?>₫</td>
              <td class="py-3.5 px-4">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 uppercase"><?= $od['method'] ?></span>
              </td>
              <td class="py-3.5 px-4">
                <?php if ($od['status'] === 'completed'): ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Thành công</span>
                <?php elseif ($od['status'] === 'pending'): ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">Chờ xử lý</span>
                <?php else: ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Đã hủy</span>
                <?php endif; ?>
              </td>
              <td class="py-3.5 px-4 text-right">
                <button type="button" onclick="showOrderDetail('<?= $od['code'] ?>', '<?= addslashes($od['movie']) ?>', '<?= addslashes($od['customer']) ?>', '<?= $od['seats'] ?>', '<?= addslashes($od['combos']) ?>', '<?= number_format($od['total'], 0, ',', '.') ?>₫')" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Chi Tiết Đơn -->
<div id="order-modal" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
      <h3 class="font-bold text-sm text-gray-900" id="m-code">Chi tiết đơn hàng</h3>
      <button type="button" onclick="document.getElementById('order-modal').classList.add('hidden')" class="p-1 text-gray-400 hover:text-gray-600">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <div class="space-y-2.5">
      <div class="flex justify-between py-1 border-b border-gray-50"><span class="text-gray-400">Phim:</span><strong class="text-gray-900" id="m-movie"></strong></div>
      <div class="flex justify-between py-1 border-b border-gray-50"><span class="text-gray-400">Khách hàng:</span><span class="text-gray-800" id="m-cust"></span></div>
      <div class="flex justify-between py-1 border-b border-gray-50"><span class="text-gray-400">Vị trí ghế:</span><span class="font-bold text-blue-600" id="m-seats"></span></div>
      <div class="flex justify-between py-1 border-b border-gray-50"><span class="text-gray-400">Combo bắp nước:</span><span class="text-gray-800" id="m-combos"></span></div>
      <div class="flex justify-between py-1 border-b border-gray-50"><span class="text-gray-400">Tổng thanh toán:</span><strong class="text-sm font-black text-gray-900" id="m-total"></strong></div>
    </div>

    <div class="pt-3 border-t border-gray-100 flex justify-end">
      <button type="button" onclick="document.getElementById('order-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold">Đóng</button>
    </div>
  </div>
</div>

<script>
  function showOrderDetail(code, movie, cust, seats, combos, total) {
    document.getElementById('m-code').innerText = 'Đơn hàng: ' + code;
    document.getElementById('m-movie').innerText = movie;
    document.getElementById('m-cust').innerText = cust;
    document.getElementById('m-seats').innerText = seats;
    document.getElementById('m-combos').innerText = combos;
    document.getElementById('m-total').innerText = total;
    document.getElementById('order-modal').classList.remove('hidden');
  }
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
