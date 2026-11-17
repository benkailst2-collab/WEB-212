<?php
/**
 * app/Views/admin/dashboard/index.php  -  Dashboard quản trị NAM VISION
 *
 * Controller truyền vào (nếu không có sẽ dùng dữ liệu demo):
 *   $stats          [ 'revenue','new_orders','items_sold','new_customers' ]  mỗi mục = ['value'=>, 'change'=> % so với kỳ trước]
 *   $revenueSeries  [ '7d' => ['labels'=>[], 'data'=>[]], '30d' => ..., '12m' => ... ]
 *   $recentOrders   [ ['id','code','customer','created_at','total','status'], ... ]
 *                   status: pending | delivered | cancelled
 *
 * Quy ước layout:
 *   sidebar.php  mở tài liệu (<html>, <head>, <body>), mở <div class="flex"> và in <aside> sidebar tối.
 *   header.php   mở <div class="flex-1"> (cột nội dung), in thanh topbar và mở <main>.
 *   Cuối file này tự đóng </main>, </div>, </div>, </body>, </html>.
 *
 * API tùy chọn: GET /admin/dashboard/revenue?range=7d|30d|12m -> {labels:[], data:[]}
 */

if (!function_exists('e')) {
    function e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('vnd')) {
    function vnd($n): string { return number_format((float) $n, 0, ',', '.') . '₫'; }
}

/* ---------- Dữ liệu demo (xóa khi đã nối Model) ---------- */
$stats = $stats ?? [
    'revenue'       => ['value' => 486500000, 'change' => 12.4],
    'new_orders'    => ['value' => 128,       'change' => 8.1],
    'items_sold'    => ['value' => 342,       'change' => -3.2],
    'new_customers' => ['value' => 57,        'change' => 21.0],
];

$revenueSeries = $revenueSeries ?? [
    '7d'  => ['labels' => ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
              'data'   => [12400000, 15800000, 11200000, 18900000, 22300000, 27100000, 19600000]],
    '30d' => ['labels' => ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
              'data'   => [86000000, 102000000, 94500000, 118000000]],
    '12m' => ['labels' => ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
              'data'   => [310000000, 280000000, 350000000, 330000000, 390000000, 420000000, 405000000, 440000000, 470000000, 486000000, 0, 0]],
];

$recentOrders = $recentOrders ?? [
    ['id' => 1042, 'code' => 'NV-1042', 'customer' => 'Nguyễn Minh Khoa', 'created_at' => '2026-11-03 09:12', 'total' => 3180000, 'status' => 'pending'],
    ['id' => 1041, 'code' => 'NV-1041', 'customer' => 'Trần Quốc Bảo',    'created_at' => '2026-11-03 08:40', 'total' => 1990000, 'status' => 'pending'],
    ['id' => 1040, 'code' => 'NV-1040', 'customer' => 'Lê Hoàng Nam',     'created_at' => '2026-11-02 19:25', 'total' => 5480000, 'status' => 'delivered'],
    ['id' => 1039, 'code' => 'NV-1039', 'customer' => 'Phạm Đức Anh',    'created_at' => '2026-11-02 14:03', 'total' => 790000,  'status' => 'cancelled'],
    ['id' => 1038, 'code' => 'NV-1038', 'customer' => 'Vũ Thành Long',    'created_at' => '2026-11-01 21:50', 'total' => 2490000, 'status' => 'delivered'],
];

$statusMap = [
    'pending'   => ['Chờ xử lý', 'bg-amber-100 text-amber-800'],
    'delivered' => ['Đã giao',   'bg-emerald-100 text-emerald-800'],
    'cancelled' => ['Đã hủy',    'bg-rose-100 text-rose-800'],
];

$statCards = [
    ['label' => 'Tổng doanh thu',  'icon' => 'wallet',       'key' => 'revenue',       'money' => true],
    ['label' => 'Đơn hàng mới',    'icon' => 'shopping-bag', 'key' => 'new_orders',    'money' => false],
    ['label' => 'Sản phẩm bán ra', 'icon' => 'package',      'key' => 'items_sold',    'money' => false],
    ['label' => 'Khách hàng mới',  'icon' => 'users',        'key' => 'new_customers', 'money' => false],
];

require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/header.php';
?>

<div class="space-y-6">
  <div>
    <h1 class="font-display text-3xl font-semibold text-slate-900">Tổng quan</h1>
    <p class="mt-1 text-sm text-slate-500">Số liệu 30 ngày gần nhất, so với 30 ngày trước đó.</p>
  </div>

  <!-- ===================== 4 THẺ CHỈ SỐ ===================== -->
  <section aria-label="Chỉ số chính" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <?php foreach ($statCards as $card):
        $s = $stats[$card['key']];
        $up = $s['change'] >= 0; ?>
      <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-500"><?= e($card['label']) ?></p>
          <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-gold">
            <i data-lucide="<?= e($card['icon']) ?>" class="h-4 w-4"></i>
          </span>
        </div>
        <p class="mt-3 text-2xl font-semibold text-slate-900">
          <?= $card['money'] ? vnd($s['value']) : number_format($s['value'], 0, ',', '.') ?>
        </p>
        <p class="mt-2 flex items-center gap-1 text-sm <?= $up ? 'text-emerald-700' : 'text-rose-700' ?>">
          <i data-lucide="<?= $up ? 'trending-up' : 'trending-down' ?>" class="h-4 w-4"></i>
          <span><?= $up ? '+' : '' ?><?= number_format($s['change'], 1, ',', '.') ?>%</span>
          <span class="text-slate-500">so với kỳ trước</span>
        </p>
      </article>
    <?php endforeach; ?>
  </section>

  <!-- ===================== BIỂU ĐỒ DOANH THU ===================== -->
  <section aria-labelledby="chart-title" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <h2 id="chart-title" class="text-lg font-semibold text-slate-900">Doanh thu</h2>
      <div role="group" aria-label="Khoảng thời gian" class="inline-flex overflow-hidden rounded-lg ring-1 ring-slate-300">
        <?php foreach (['7d' => '7 ngày', '30d' => '30 ngày', '12m' => '12 tháng'] as $key => $label): ?>
          <button type="button" data-range="<?= $key ?>" aria-pressed="<?= $key === '7d' ? 'true' : 'false' ?>"
                  class="px-3.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-gold aria-pressed:bg-slate-900 aria-pressed:text-white">
            <?= $label ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Canvas cho Chart.js -->
    <div class="relative mt-5 h-72 sm:h-80">
      <canvas id="revenue-chart" role="img" aria-label="Biểu đồ doanh thu theo thời gian"></canvas>
    </div>
    <p id="chart-error" class="mt-3 hidden text-sm text-rose-700">Không tải được biểu đồ. Kiểm tra kết nối mạng và thử lại.</p>
  </section>

  <!-- ===================== ĐƠN HÀNG GẦN ĐÂY ===================== -->
  <section aria-labelledby="orders-title" class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
      <h2 id="orders-title" class="text-lg font-semibold text-slate-900">Đơn hàng gần đây</h2>
      <a href="/admin/orders" class="text-sm font-medium text-slate-900 underline underline-offset-4 hover:text-gold-dark">Xem tất cả</a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full min-w-[40rem] text-left text-sm">
        <thead class="border-y border-slate-200 bg-slate-50 text-slate-500">
          <tr>
            <th scope="col" class="px-5 py-3 font-medium sm:px-6">Mã đơn</th>
            <th scope="col" class="px-5 py-3 font-medium">Khách hàng</th>
            <th scope="col" class="px-5 py-3 font-medium">Ngày đặt</th>
            <th scope="col" class="px-5 py-3 text-right font-medium">Tổng tiền</th>
            <th scope="col" class="px-5 py-3 font-medium sm:pr-6">Trạng thái</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (empty($recentOrders)): ?>
            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Chưa có đơn hàng nào.</td></tr>
          <?php endif; ?>
          <?php foreach ($recentOrders as $o):
              [$label, $cls] = $statusMap[$o['status']] ?? [$o['status'], 'bg-slate-100 text-slate-700']; ?>
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-medium sm:px-6">
                <a href="/admin/orders/<?= (int) $o['id'] ?>" class="text-slate-900 hover:text-gold-dark"><?= e($o['code']) ?></a>
              </td>
              <td class="px-5 py-3.5 text-slate-700"><?= e($o['customer']) ?></td>
              <td class="whitespace-nowrap px-5 py-3.5 text-slate-500"><?= e(date('d/m/Y H:i', strtotime($o['created_at']))) ?></td>
              <td class="whitespace-nowrap px-5 py-3.5 text-right font-medium text-slate-900"><?= vnd($o['total']) ?></td>
              <td class="px-5 py-3.5 sm:pr-6">
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium <?= $cls ?>"><?= e($label) ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<!-- Đóng các thẻ do header.php và sidebar.php mở -->
</main>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
(function () {
  const canvas = document.getElementById('revenue-chart');
  const errorEl = document.getElementById('chart-error');
  if (!canvas) return;
  if (typeof Chart === 'undefined') { errorEl.classList.remove('hidden'); return; }

  // Dữ liệu ban đầu do PHP render (dùng làm dự phòng khi gọi API lỗi)
  const initial = <?= json_encode($revenueSeries, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  const nf = new Intl.NumberFormat('vi-VN');

  const ctx = canvas.getContext('2d');
  const fill = ctx.createLinearGradient(0, 0, 0, 320);
  fill.addColorStop(0, 'rgba(184, 151, 90, 0.30)');
  fill.addColorStop(1, 'rgba(184, 151, 90, 0)');

  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: initial['7d'].labels,
      datasets: [{
        label: 'Doanh thu',
        data: initial['7d'].data,
        borderColor: '#B8975A',
        backgroundColor: fill,
        borderWidth: 2.5,
        pointRadius: 3,
        pointBackgroundColor: '#0F172A',
        tension: 0.35,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: (c) => ' ' + nf.format(c.parsed.y) + '₫' } }
      },
      scales: {
        x: { grid: { display: false } },
        y: {
          beginAtZero: true,
          ticks: { callback: (v) => (v >= 1e6 ? nf.format(v / 1e6) + ' tr' : nf.format(v)) }
        }
      }
    }
  });

  function render(series) {
    chart.data.labels = series.labels;
    chart.data.datasets[0].data = series.data;
    chart.update();
  }

  /* ---------- Đổi khoảng thời gian: ưu tiên API, lỗi thì dùng dữ liệu PHP ---------- */
  const buttons = [...document.querySelectorAll('[data-range]')];
  buttons.forEach((btn) => btn.addEventListener('click', async () => {
    const range = btn.dataset.range;
    buttons.forEach((b) => b.setAttribute('aria-pressed', String(b === btn)));
    errorEl.classList.add('hidden');

    try {
      const res = await fetch('/admin/dashboard/revenue?range=' + encodeURIComponent(range), {
        headers: { 'Accept': 'application/json' }
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      render(await res.json());
    } catch (err) {
      if (initial[range]) render(initial[range]);
      else errorEl.classList.remove('hidden');
    }
  }));

  if (window.lucide) window.lucide.createIcons();
})();
</script>
</body>
</html>
