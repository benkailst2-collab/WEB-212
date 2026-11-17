<?php
/**
 * app/Views/admin/cinema/cinemas.php - Quản lý Cụm Rạp
 */
$pageTitle = 'Quản Lý Cụm Rạp - MovieGo Admin';
$activeMenu = 'cinemas';
include __DIR__ . '/layouts/sidebar.php';

$cinemas = [
  ['id' => 1, 'name' => 'MovieGo Quận 1', 'address' => '271 Nguyễn Trãi, Quận 1', 'city' => 'TP.HCM', 'screens' => 8, 'seats' => 1200],
  ['id' => 2, 'name' => 'MovieGo Quận 7', 'address' => '469 Nguyễn Hữu Thọ, Quận 7', 'city' => 'TP.HCM', 'screens' => 10, 'seats' => 1500],
  ['id' => 3, 'name' => 'MovieGo Hà Nội', 'address' => 'Vincom Center, 191 Bà Triệu', 'city' => 'Hà Nội', 'screens' => 12, 'seats' => 1800],
  ['id' => 4, 'name' => 'MovieGo Đà Nẵng', 'address' => 'Vincom Plaza, 910A Ngô Quyền', 'city' => 'Đà Nẵng', 'screens' => 6, 'seats' => 900],
];
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-xl sm:text-2xl font-black text-gray-900">Quản lý Cụm Rạp Chiếu</h1>
      <p class="text-xs text-gray-500 mt-0.5">Danh sách các cơ sở rạp, phòng chiếu và sức chứa.</p>
    </div>
    <button type="button" onclick="alert('Mở modal thêm cụm rạp')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-colors">
      <i data-lucide="plus" class="w-4 h-4"></i> Thêm cụm rạp
    </button>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
          <tr>
            <th class="py-3 px-4">Tên rạp</th>
            <th class="py-3 px-4">Địa chỉ</th>
            <th class="py-3 px-4">Khu vực</th>
            <th class="py-3 px-4">Số phòng</th>
            <th class="py-3 px-4">Tổng ghế</th>
            <th class="py-3 px-4 text-right">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($cinemas as $cn): ?>
            <tr class="hover:bg-gray-50/80 transition-colors">
              <td class="py-3.5 px-4 font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-blue-600"></i>
                <?= htmlspecialchars($cn['name']) ?>
              </td>
              <td class="py-3.5 px-4 text-gray-600"><?= htmlspecialchars($cn['address']) ?></td>
              <td class="py-3.5 px-4">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700"><?= $cn['city'] ?></span>
              </td>
              <td class="py-3.5 px-4 font-semibold text-gray-800"><?= $cn['screens'] ?> phòng</td>
              <td class="py-3.5 px-4 font-bold text-gray-900"><?= number_format($cn['seats']) ?> chỗ</td>
              <td class="py-3.5 px-4 text-right">
                <button type="button" onclick="alert('Chỉnh sửa rạp: <?= addslashes($cn['name']) ?>')" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50">
                  <i data-lucide="edit-3" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="if(confirm('Xóa rạp?')) alert('Đã xóa!')" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50">
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
