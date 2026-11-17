<?php
/**
 * app/Views/admin/cinema/movies.php - Quản lý Phim MovieGo Admin
 */
$pageTitle = 'Quản Lý Phim - MovieGo Admin';
$activeMenu = 'movies';
include __DIR__ . '/layouts/sidebar.php';

$movies = [
  ['id' => 1, 'title' => 'Avengers: Doomsday', 'genre' => 'Hành động, Viễn tưởng', 'duration' => 165, 'rating' => 8.5, 'status' => 'now_showing', 'poster' => 'https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDhRkZoNNsM4hXx.jpg'],
  ['id' => 2, 'title' => 'Dune: Phần Ba', 'genre' => 'Khoa học viễn tưởng', 'duration' => 178, 'rating' => 9.0, 'status' => 'now_showing', 'poster' => 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg'],
  ['id' => 3, 'title' => 'Lật Mặt 9: Bí Mật Gia Tộc', 'genre' => 'Hành động, Hài', 'duration' => 135, 'rating' => 7.8, 'status' => 'now_showing', 'poster' => 'https://image.tmdb.org/t/p/w500/wXqWR7dHncNRbxoEGybEy7QTe9h.jpg'],
  ['id' => 4, 'title' => 'Spider-Man: Brand New Day', 'genre' => 'Hành động, Phiêu lưu', 'duration' => 148, 'rating' => 8.2, 'status' => 'coming_soon', 'poster' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg'],
  ['id' => 5, 'title' => 'Mai - Phần 2', 'genre' => 'Tâm lý, Tình cảm', 'duration' => 128, 'rating' => 7.5, 'status' => 'now_showing', 'poster' => 'https://image.tmdb.org/t/p/w500/lqoMzCcZYEFK729d6qzt349fB4o.jpg'],
  ['id' => 6, 'title' => 'The Batman: Part II', 'genre' => 'Hành động, Tội phạm', 'duration' => 170, 'rating' => 8.8, 'status' => 'coming_soon', 'poster' => 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg'],
];
?>

<div class="space-y-6">
  <!-- Top bar -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-xl sm:text-2xl font-black text-gray-900">Quản lý Phim Chiếu</h1>
      <p class="text-xs text-gray-500 mt-0.5">Danh sách các bộ phim đang và sắp công chiếu tại cụm rạp.</p>
    </div>
    <button type="button" onclick="openMovieModal('add')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-colors">
      <i data-lucide="plus" class="w-4 h-4"></i> Thêm phim mới
    </button>
  </div>

  <!-- Filter & Search -->
  <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
      <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
      <input type="text" id="movie-search" oninput="filterMovies()" placeholder="Tìm kiếm tên phim..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2 text-xs text-gray-800 focus:outline-none focus:border-blue-600">
    </div>
    <select id="movie-status-filter" onchange="filterMovies()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-700 focus:outline-none focus:border-blue-600">
      <option value="all">Tất cả trạng thái</option>
      <option value="now_showing">Đang chiếu</option>
      <option value="coming_soon">Sắp chiếu</option>
    </select>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs" id="movies-table">
        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
          <tr>
            <th class="py-3 px-4">Poster</th>
            <th class="py-3 px-4">Tên phim</th>
            <th class="py-3 px-4">Thể loại</th>
            <th class="py-3 px-4">Thời lượng</th>
            <th class="py-3 px-4">Đánh giá</th>
            <th class="py-3 px-4">Trạng thái</th>
            <th class="py-3 px-4 text-right">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($movies as $mv): ?>
            <tr class="movie-row hover:bg-gray-50/80 transition-colors" data-title="<?= strtolower($mv['title']) ?>" data-status="<?= $mv['status'] ?>">
              <td class="py-3 px-4">
                <img src="<?= htmlspecialchars($mv['poster']) ?>" class="w-10 h-14 object-cover rounded-lg shadow-sm border border-gray-200">
              </td>
              <td class="py-3 px-4 font-bold text-gray-900"><?= htmlspecialchars($mv['title']) ?></td>
              <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($mv['genre']) ?></td>
              <td class="py-3 px-4 text-gray-600 font-medium"><?= $mv['duration'] ?> phút</td>
              <td class="py-3 px-4 font-bold text-amber-600">⭐ <?= $mv['rating'] ?>/10</td>
              <td class="py-3 px-4">
                <?php if ($mv['status'] === 'now_showing'): ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Đang chiếu</span>
                <?php else: ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">Sắp chiếu</span>
                <?php endif; ?>
              </td>
              <td class="py-3 px-4 text-right">
                <button type="button" onclick="openMovieModal('edit', '<?= addslashes($mv['title']) ?>')" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50">
                  <i data-lucide="edit-3" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="confirmDeleteMovie('<?= addslashes($mv['title']) ?>')" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50">
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

<!-- Modal Thêm/Sửa Phim -->
<div id="movie-modal" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
      <h3 class="font-bold text-base text-gray-900" id="modal-title">Thêm phim mới</h3>
      <button type="button" onclick="closeMovieModal()" class="p-1 text-gray-400 hover:text-gray-600">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form onsubmit="handleSaveMovie(event)" class="space-y-3 text-xs">
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Tên phim</label>
        <input type="text" id="form-movie-title" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-600">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block font-semibold text-gray-700 mb-1">Thời lượng (phút)</label>
          <input type="number" id="form-movie-duration" value="120" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-600">
        </div>
        <div>
          <label class="block font-semibold text-gray-700 mb-1">Trạng thái</label>
          <select id="form-movie-status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-600">
            <option value="now_showing">Đang chiếu</option>
            <option value="coming_soon">Sắp chiếu</option>
          </select>
        </div>
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Thể loại</label>
        <input type="text" id="form-movie-genre" value="Hành động, Phiêu lưu" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-600">
      </div>

      <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
        <button type="button" onclick="closeMovieModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs">Hủy</button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md">Lưu dữ liệu</button>
      </div>
    </form>
  </div>
</div>

<script>
  function filterMovies() {
    const q = document.getElementById('movie-search').value.toLowerCase().trim();
    const st = document.getElementById('movie-status-filter').value;
    document.querySelectorAll('.movie-row').forEach(row => {
      const matchTitle = row.getAttribute('data-title').includes(q);
      const matchStatus = (st === 'all' || row.getAttribute('data-status') === st);
      row.style.display = (matchTitle && matchStatus) ? '' : 'none';
    });
  }

  function openMovieModal(mode, title = '') {
    document.getElementById('modal-title').innerText = mode === 'add' ? 'Thêm phim mới' : 'Chỉnh sửa: ' + title;
    if (mode === 'edit') {
      document.getElementById('form-movie-title').value = title;
    } else {
      document.getElementById('form-movie-title').value = '';
    }
    document.getElementById('movie-modal').classList.remove('hidden');
  }

  function closeMovieModal() {
    document.getElementById('movie-modal').classList.add('hidden');
  }

  function handleSaveMovie(e) {
    e.preventDefault();
    alert('Đã cập nhật thông tin phim thành công!');
    closeMovieModal();
  }

  function confirmDeleteMovie(title) {
    if (confirm('Bạn có chắc chắn muốn xóa phim: ' + title + '?')) {
      alert('Đã xóa phim thành công!');
    }
  }
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
