<?php
/**
 * app/Views/client/cinema/detail.php - Chi tiết phim & Suất chiếu
 */
$movieId = (int)($_GET['id'] ?? 1);
$pageTitle = 'Chi Tiết Phim & Lịch Chiếu - MovieGo';
include __DIR__ . '/layouts/header.php';

// Mock movie data fallback
$movie = [
  'id' => $movieId,
  'title' => 'Avengers: Doomsday',
  'poster' => 'https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDhRkZoNNsM4hXx.jpg',
  'backdrop' => 'https://image.tmdb.org/t/p/original/9BBTo63ANSmhC4e6r62OJFuK2GL.jpg',
  'genre' => ['Hành động', 'Phiêu lưu', 'Khoa học viễn tưởng'],
  'duration' => 165,
  'rating' => 8.5,
  'releaseDate' => '01/05/2026',
  'director' => 'Joe Russo, Anthony Russo',
  'cast' => ['Robert Downey Jr.', 'Chris Evans', 'Scarlett Johansson', 'Tom Holland'],
  'description' => 'Các siêu anh hùng Avengers tập hợp lần cuối cùng để đối đầu với mối đe dọa lớn nhất từ trước đến nay. Khi Doctor Doom xuất hiện với sức mạnh vô song, cả vũ trụ đứng trước nguy cơ bị hủy diệt hoàn toàn. Một trận chiến định mệnh sẽ định hình tương lai của toàn bộ đa vũ trụ.',
  'status' => 'now_showing',
  'ageRating' => 'T13',
  'language' => 'Tiếng Anh - Phụ đề Tiếng Việt'
];

$cinemas = [
  ['id' => 1, 'name' => 'MovieGo Quận 1', 'address' => '271 Nguyễn Trãi, Quận 1, TP.HCM'],
  ['id' => 2, 'name' => 'MovieGo Quận 7', 'address' => 'Lotte Mart, 469 Nguyễn Hữu Thọ, Quận 7, TP.HCM'],
  ['id' => 3, 'name' => 'MovieGo Hà Nội', 'address' => 'Vincom Center, 191 Bà Triệu, Hai Bà Trưng, Hà Nội'],
];

$showtimes = [
  ['id' => 1, 'cinemaId' => 1, 'screen' => 'Phòng 1', 'time' => '10:00', 'format' => '2D', 'price' => 75000],
  ['id' => 2, 'cinemaId' => 1, 'screen' => 'Phòng 2', 'time' => '13:30', 'format' => '3D', 'price' => 95000],
  ['id' => 3, 'cinemaId' => 1, 'screen' => 'Phòng 1', 'time' => '16:00', 'format' => 'IMAX', 'price' => 120000],
  ['id' => 4, 'cinemaId' => 1, 'screen' => 'Phòng 3', 'time' => '19:00', 'format' => '2D', 'price' => 85000],
  ['id' => 5, 'cinemaId' => 1, 'screen' => 'Phòng 2', 'time' => '21:30', 'format' => '3D', 'price' => 95000],
  ['id' => 6, 'cinemaId' => 2, 'screen' => 'Phòng 1', 'time' => '11:00', 'format' => '2D', 'price' => 75000],
  ['id' => 7, 'cinemaId' => 2, 'screen' => 'Phòng 4', 'time' => '14:00', 'format' => 'IMAX', 'price' => 130000],
  ['id' => 8, 'cinemaId' => 2, 'screen' => 'Phòng 2', 'time' => '20:00', 'format' => '3D', 'price' => 95000],
];
?>

<!-- ─── Movie Banner ─── -->
<div class="relative min-h-[380px] sm:min-h-[460px] overflow-hidden">
  <img src="<?= htmlspecialchars($movie['backdrop']) ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/80 to-transparent"></div>
  <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/70 to-transparent"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex flex-col md:flex-row gap-8 items-start md:items-end">
    <!-- Poster -->
    <div class="w-44 sm:w-56 shrink-0 rounded-2xl overflow-hidden shadow-2xl border-2 border-gray-700/80">
      <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="w-full h-auto">
    </div>

    <!-- Info -->
    <div class="flex-1 space-y-3">
      <div class="flex items-center gap-2">
        <span class="px-2.5 py-0.5 rounded-full bg-red-600 text-white font-extrabold text-xs"><?= $movie['ageRating'] ?></span>
        <span class="flex items-center gap-1 text-xs bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2.5 py-0.5 rounded-full font-bold">
          <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i>
          <?= $movie['rating'] ?>/10
        </span>
        <span class="text-xs text-gray-400">• <?= $movie['duration'] ?> phút</span>
      </div>

      <h1 class="text-2xl sm:text-4xl font-black text-white"><?= htmlspecialchars($movie['title']) ?></h1>

      <div class="flex flex-wrap gap-2 text-xs">
        <?php foreach ($movie['genre'] as $g): ?>
          <span class="rounded-full bg-gray-800/80 border border-gray-700 px-3 py-1 text-gray-300"><?= htmlspecialchars($g) ?></span>
        <?php endforeach; ?>
      </div>

      <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
        <?= htmlspecialchars($movie['description']) ?>
      </p>

      <div class="grid grid-cols-2 gap-4 text-xs text-gray-400 pt-2 border-t border-gray-800/80">
        <div><strong class="text-white">Đạo diễn:</strong> <?= $movie['director'] ?></div>
        <div><strong class="text-white">Khởi chiếu:</strong> <?= $movie['releaseDate'] ?></div>
        <div class="col-span-2"><strong class="text-white">Diễn viên:</strong> <?= implode(', ', $movie['cast']) ?></div>
      </div>
    </div>
  </div>
</div>

<!-- ─── Showtimes Section ─── -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
    <i data-lucide="calendar" class="w-5 h-5 text-amber-400"></i>
    Lịch Chiếu & Suất Chiếu
  </h2>

  <!-- Cinema selector buttons -->
  <div class="flex flex-wrap gap-2 mb-8" id="cinema-tabs">
    <?php foreach ($cinemas as $idx => $c): ?>
      <button type="button" onclick="selectCinema(<?= $c['id'] ?>)" id="cinema-btn-<?= $c['id'] ?>" class="cinema-tab-btn px-4 py-2.5 rounded-xl border text-sm font-semibold transition-all <?= $idx === 0 ? 'bg-amber-500 text-gray-950 border-amber-500 shadow-md' : 'bg-gray-900 border-gray-800 text-gray-300 hover:border-gray-700' ?>">
        <?= htmlspecialchars($c['name']) ?>
      </button>
    <?php endforeach; ?>
  </div>

  <!-- Showtime Cards Container -->
  <div class="space-y-6">
    <?php foreach ($cinemas as $c): ?>
      <div id="cinema-content-<?= $c['id'] ?>" class="cinema-schedule bg-gray-900/60 rounded-2xl p-6 border border-gray-800">
        <div class="flex items-start justify-between border-b border-gray-800 pb-4 mb-4">
          <div>
            <h3 class="font-bold text-base text-white"><?= htmlspecialchars($c['name']) ?></h3>
            <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
              <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-400"></i>
              <?= htmlspecialchars($c['address']) ?>
            </p>
          </div>
          <span class="text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-1 rounded-full font-medium">Đang mở bán</span>
        </div>

        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Chọn suất chiếu phù hợp:</p>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
          <?php 
          $cinemaShows = array_filter($showtimes, fn($s) => $s['cinemaId'] === $c['id']);
          foreach ($cinemaShows as $show): 
          ?>
            <a href="/cinema/seats?showtime_id=<?= $show['id'] ?>" class="group p-3.5 rounded-xl bg-gray-950 border border-gray-800 hover:border-amber-500 hover:scale-[1.02] transition-all flex flex-col justify-between">
              <div class="flex items-center justify-between mb-2">
                <span class="text-lg font-black text-amber-400 group-hover:text-amber-300"><?= $show['time'] ?></span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded <?= $show['format'] === 'IMAX' ? 'bg-indigo-600 text-white' : ($show['format'] === '3D' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300') ?>">
                  <?= $show['format'] ?>
                </span>
              </div>
              <div class="flex items-center justify-between text-[11px] text-gray-400">
                <span><?= $show['screen'] ?></span>
                <span class="font-semibold text-white"><?= number_format($show['price'], 0, ',', '.') ?>₫</span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
  function selectCinema(cinemaId) {
    document.querySelectorAll('.cinema-tab-btn').forEach(btn => {
      btn.className = 'cinema-tab-btn px-4 py-2.5 rounded-xl border text-sm font-semibold transition-all bg-gray-900 border-gray-800 text-gray-300 hover:border-gray-700';
    });
    document.getElementById('cinema-btn-' + cinemaId).className = 'cinema-tab-btn px-4 py-2.5 rounded-xl border text-sm font-semibold transition-all bg-amber-500 text-gray-950 border-amber-500 shadow-md';

    document.querySelectorAll('.cinema-schedule').forEach(sec => {
      sec.style.display = 'none';
    });
    document.getElementById('cinema-content-' + cinemaId).style.display = 'block';
  }

  // Mặc định hiển thị rạp 1
  selectCinema(1);
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
