<?php
/**
 * app/Views/admin/cinema/dashboard.php - Thống kê Dashboard MovieGo
 */
$pageTitle = 'Dashboard Thống Kê - MovieGo Admin';
$activeMenu = 'dashboard';
include __DIR__ . '/layouts/sidebar.php';

// Mock Data
$totalRevenue = 45890000;
$totalTickets = 534;
$totalMovies = 8;
$totalCinemas = 4;

$recentOrders = [
  ['code' => 'ORD-20261101-001', 'movie' => 'Avengers: Doomsday', 'customer' => 'Nguyễn Văn An', 'seats' => 'D5, D6', 'total' => 299000, 'status' => 'completed', 'method' => 'MoMo'],
  ['code' => 'ORD-20261101-002', 'movie' => 'Dune: Phần Ba', 'customer' => 'Trần Thị Bích', 'seats' => 'E3, E4, E5', 'total' => 484000, 'status' => 'completed', 'method' => 'VNPAY'],
  ['code' => 'ORD-20261102-001', 'movie' => 'Lật Mặt 9', 'customer' => 'Lê Minh Tuấn', 'seats' => 'B7, B8', 'total' => 298000, 'status' => 'pending', 'method' => 'Thẻ ATM'],
  ['code' => 'ORD-20261102-002', 'movie' => 'Mai - Phần 2', 'customer' => 'Phạm Hoàng Yến', 'seats' => 'C4, C5', 'total' => 569000, 'status' => 'cancelled', 'method' => 'ZaloPay'],
];
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Tổng quan Hoạt động</h1>
    <p class="text-xs text-gray-500 mt-0.5">Dữ liệu doanh thu & lượng vé bán ra cập nhật theo thời gian thực.</p>
  </div>

  <!-- ─── 4 KPI Cards ─── -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-gray-400">Tổng doanh thu</p>
        <p class="text-xl font-black text-gray-900 mt-1"><?= number_format($totalRevenue, 0, ',', '.') ?>₫</p>
        <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-1">
          <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +14.2% so với tuần trước
        </span>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
        <i data-lucide="dollar-sign" class="w-6 h-6"></i>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-gray-400">Vé đã bán</p>
        <p class="text-xl font-black text-gray-900 mt-1"><?= number_format($totalTickets) ?> vé</p>
        <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-1">
          <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +8.5% hôm nay
        </span>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
        <i data-lucide="ticket" class="w-6 h-6"></i>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-gray-400">Phim đang chiếu</p>
        <p class="text-xl font-black text-gray-900 mt-1"><?= $totalMovies ?> phim</p>
        <span class="text-[11px] text-gray-500 flex items-center gap-1 mt-1">
          3 phim sắp ra mắt
        </span>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
        <i data-lucide="film" class="w-6 h-6"></i>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-gray-400">Cụm rạp hoạt động</p>
        <p class="text-xl font-black text-gray-900 mt-1"><?= $totalCinemas ?> rạp</p>
        <span class="text-[11px] text-gray-500 flex items-center gap-1 mt-1">
          36 phòng chiếu phim
        </span>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
        <i data-lucide="building-2" class="w-6 h-6"></i>
      </div>
    </div>
  </div>

  <!-- ─── Biểu đồ thống kê ─── -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Doanh thu 7 ngày (8 cols) -->
    <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
      <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <h3 class="font-bold text-sm text-gray-900">Doanh thu 7 ngày gần nhất</h3>
        <span class="text-xs text-blue-600 font-semibold bg-blue-50 px-2.5 py-1 rounded-lg">Đơn vị: VNĐ</span>
      </div>
      <div class="h-64">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>

    <!-- Top Phim (4 cols) -->
    <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
      <div class="border-b border-gray-100 pb-3">
        <h3 class="font-bold text-sm text-gray-900">Top Phim Doanh Thu Cao</h3>
      </div>
      <div class="h-64">
        <canvas id="topMoviesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- ─── Bảng đơn hàng gần đây & Tỉ lệ lấp đầy ghế ─── -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Recent Orders (8 cols) -->
    <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-sm text-gray-900">Đơn đặt vé gần đây</h3>
        <a href="/admin/cinema/orders" class="text-xs text-blue-600 font-semibold hover:underline">Xem tất cả &rarr;</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
            <tr>
              <th class="py-3 px-4">Mã đơn</th>
              <th class="py-3 px-4">Phim</th>
              <th class="py-3 px-4">Ghế</th>
              <th class="py-3 px-4">Tổng tiền</th>
              <th class="py-3 px-4">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($recentOrders as $od): ?>
              <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="py-3 px-4 font-mono font-bold text-blue-600"><?= $od['code'] ?></td>
                <td class="py-3 px-4 font-semibold text-gray-800"><?= $od['movie'] ?></td>
                <td class="py-3 px-4 text-gray-500"><?= $od['seats'] ?></td>
                <td class="py-3 px-4 font-bold text-gray-900"><?= number_format($od['total'], 0, ',', '.') ?>₫</td>
                <td class="py-3 px-4">
                  <?php if ($od['status'] === 'completed'): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Thành công</span>
                  <?php elseif ($od['status'] === 'pending'): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">Chờ xử lý</span>
                  <?php else: ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Đã hủy</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Occupancy (4 cols) -->
    <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
      <h3 class="font-bold text-sm text-gray-900 border-b border-gray-100 pb-3">Tỉ lệ lấp đầy rạp</h3>
      <div class="space-y-4 text-xs">
        <div>
          <div class="flex justify-between font-semibold mb-1">
            <span>MovieGo Quận 1</span>
            <span class="text-blue-600">78%</span>
          </div>
          <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width: 78%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between font-semibold mb-1">
            <span>MovieGo Quận 7</span>
            <span class="text-emerald-600">65%</span>
          </div>
          <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full" style="width: 65%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between font-semibold mb-1">
            <span>MovieGo Hà Nội</span>
            <span class="text-purple-600">82%</span>
          </div>
          <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-purple-600 rounded-full" style="width: 82%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between font-semibold mb-1">
            <span>MovieGo Đà Nẵng</span>
            <span class="text-amber-600">58%</span>
          </div>
          <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-500 rounded-full" style="width: 58%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js initialization -->
<script>
  // Revenue Area Chart
  const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
  new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: ['27/10', '28/10', '29/10', '30/10', '31/10', '01/11', '02/11'],
      datasets: [{
        label: 'Doanh thu',
        data: [5200000, 6800000, 4500000, 7200000, 8900000, 6300000, 6990000],
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37, 99, 235, 0.08)',
        fill: true,
        tension: 0.35,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { grid: { color: '#f3f4f6' } },
        x: { grid: { display: false } }
      }
    }
  });

  // Top Movies Bar Chart
  const ctxMovies = document.getElementById('topMoviesChart').getContext('2d');
  new Chart(ctxMovies, {
    type: 'bar',
    data: {
      labels: ['Avengers', 'Dune 3', 'Lật Mặt 9', 'Mai 2'],
      datasets: [{
        label: 'Doanh thu (Tr.đ)',
        data: [15.2, 12.8, 8.5, 4.2],
        backgroundColor: '#3b82f6',
        borderRadius: 8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#f3f4f6' } }
      }
    }
  });
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
