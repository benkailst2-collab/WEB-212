<?php
/**
 * app/Views/admin/cinema/showtimes.php - Quản lý Suất chiếu
 */
$pageTitle = 'Quản Lý Suất Chiếu - MovieGo Admin';
$activeMenu = 'showtimes';
include __DIR__ . '/layouts/sidebar.php';

$showtimes = [
  ['id' => 1, 'movie' => 'Avengers: Doomsday', 'cinema' => 'MovieGo Quận 1', 'screen' => 'Phòng 1', 'date' => 'Hôm nay', 'time' => '10:00', 'format' => '2D', 'price' => 75000],
  ['id' => 2, 'movie' => 'Avengers: Doomsday', 'cinema' => 'MovieGo Quận 1', 'screen' => 'Phòng 2', 'date' => 'Hôm nay', 'time' => '13:30', 'format' => '3D', 'price' => 95000],
  ['id' => 3, 'movie' => 'Avengers: Doomsday', 'cinema' => 'MovieGo Quận 1', 'screen' => 'Phòng 1', 'date' => 'Hôm nay', 'time' => '16:00', 'format' => 'IMAX', 'price' => 120000],
  ['id' => 4, 'movie' => 'Dune: Phần Ba', 'cinema' => 'MovieGo Quận 1', 'screen' => 'Phòng 3', 'date' => 'Hôm nay', 'time' => '09:30', 'format' => 'IMAX', 'price' => 120000],
  ['id' => 5, 'movie' => 'Lật Mặt 9: Bí Mật Gia Tộc', 'cinema' => 'MovieGo Quận 1', 'screen' => 'Phòng 4', 'date' => 'Hôm nay', 'time' => '10:00', 'format' => '2D', 'price' => 70000],
  ['id' => 6, 'movie' => 'Mai - Phần 2', 'cinema' => 'MovieGo Quận 7', 'screen' => 'Phòng 5', 'date' => 'Hôm nay', 'time' => '14:30', 'format' => '2D', 'price' => 65000],
];
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-xl sm:text-2xl font-black text-gray-900">Quản lý Lịch & Suất Chiếu</h1>
      <p class="text-xs text-gray-500 mt-0.5">Sắp xếp khung giờ, định dạng phòng chiếu (2D/3D/IMAX) và giá vé.</p>
    </div>
    <button type="button" onclick="alert('Mở modal thêm suất chiếu')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-colors">
      <i data-lucide="plus" class="w-4 h-4"></i> Tạo suất chiếu mới
    </button>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
          <tr>
            <th class="py-3 px-4">Tên phim</th>
            <th class="py-3 px-4">Cụm rạp</th>
            <th class="py-3 px-4">Phòng chiếu</th>
            <th class="py-3 px-4">Giờ chiếu</th>
            <th class="py-3 px-4">Định dạng</th>
            <th class="py-3 px-4">Giá vé cơ bản</th>
            <th class="py-3 px-4 text-right">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($showtimes as $st): ?>
            <tr class="hover:bg-gray-50/80 transition-colors">
              <td class="py-3.5 px-4 font-bold text-gray-900"><?= htmlspecialchars($st['movie']) ?></td>
              <td class="py-3.5 px-4 text-gray-600"><?= htmlspecialchars($st['cinema']) ?></td>
              <td class="py-3.5 px-4 font-semibold text-gray-800"><?= $st['screen'] ?></td>
              <td class="py-3.5 px-4 font-black text-blue-600 text-sm"><?= $st['time'] ?></td>
              <td class="py-3.5 px-4">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $st['format'] === 'IMAX' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : ($st['format'] === '3D' ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-gray-100 text-gray-700') ?>">
                  <?= $st['format'] ?>
                </span>
              </td>
              <td class="py-3.5 px-4 font-bold text-gray-900"><?= number_format($st['price'], 0, ',', '.') ?>₫</td>
              <td class="py-3.5 px-4 text-right">
                <button type="button" onclick="alert('Chỉnh sửa suất chiếu')" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50">
                  <i data-lucide="edit-3" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="if(confirm('Xóa suất chiếu?')) alert('Đã xóa!')" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50">
                  <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
