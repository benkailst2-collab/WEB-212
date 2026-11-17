<?php
/**
 * app/Views/client/cinema/home.php - Trang chủ Đặt Vé Xem Phim MovieGo
 * Nhúng: include 'layouts/header.php' và 'layouts/footer.php'
 */
$pageTitle = 'MovieGo - Trang Chủ Đặt Vé Xem Phim';
include __DIR__ . '/layouts/header.php';

// Mock data fallback nếu controller chưa truyền vào
$movies = $movies ?? [
  [
    'id' => 1,
    'title' => 'Avengers: Doomsday',
    'poster' => 'https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDhRkZoNNsM4hXx.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/9BBTo63ANSmhC4e6r62OJFuK2GL.jpg',
    'genre' => ['Hành động', 'Khoa học viễn tưởng'],
    'duration' => 165,
    'rating' => 8.5,
    'status' => 'now_showing',
    'ageRating' => 'T13',
    'description' => 'Các siêu anh hùng Avengers tập hợp lần cuối cùng để đối đầu với mối đe dọa Doctor Doom.'
  ],
  [
    'id' => 2,
    'title' => 'Dune: Phần Ba',
    'poster' => 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/xOMo8BRK7PfcJv9JCnx7s5hj0PX.jpg',
    'genre' => ['Khoa học viễn tưởng', 'Chính kịch'],
    'duration' => 178,
    'rating' => 9.0,
    'status' => 'now_showing',
    'ageRating' => 'T16',
    'description' => 'Paul Atreides tiếp tục cuộc hành trình sử thi trên hành tinh cát Arrakis.'
  ],
  [
    'id' => 3,
    'title' => 'Lật Mặt 9: Bí Mật Gia Tộc',
    'poster' => 'https://image.tmdb.org/t/p/w500/wXqWR7dHncNRbxoEGybEy7QTe9h.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/nTPFkLUARmo1bYHfkfdNpRKgEOs.jpg',
    'genre' => ['Hành động', 'Hài hước'],
    'duration' => 135,
    'rating' => 7.8,
    'status' => 'now_showing',
    'ageRating' => 'T13',
    'description' => 'Phần tiếp theo của loạt phim bom tấn Việt Nam với nhiều bí mật gia tộc hấp dẫn.'
  ],
  [
    'id' => 4,
    'title' => 'Spider-Man: Brand New Day',
    'poster' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/9DeGfFIqjph5CBFVQrD6wv9S10v.jpg',
    'genre' => ['Hành động', 'Phiêu lưu'],
    'duration' => 148,
    'rating' => 8.2,
    'status' => 'coming_soon',
    'ageRating' => 'T13',
    'description' => 'Peter Parker đối mặt với một thực tại mới nơi không ai nhớ anh là Spider-Man.'
  ],
  [
    'id' => 5,
    'title' => 'Mai - Phần 2',
    'poster' => 'https://image.tmdb.org/t/p/w500/lqoMzCcZYEFK729d6qzt349fB4o.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/uO2yU3QiGHvVp0L5e5IatTVRkYk.jpg',
    'genre' => ['Tâm lý', 'Tình cảm'],
    'duration' => 128,
    'rating' => 7.5,
    'status' => 'now_showing',
    'ageRating' => 'T18',
    'description' => 'Tiếp nối câu chuyện đầy cảm xúc về Mai với nhiều bất ngờ và nước mắt.'
  ],
  [
    'id' => 6,
    'title' => 'The Batman: Part II',
    'poster' => 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/b0PlSFdDwbyFAJlB1qoZs2TPFQI.jpg',
    'genre' => ['Hành động', 'Tội phạm'],
    'duration' => 170,
    'rating' => 8.8,
    'status' => 'coming_soon',
    'ageRating' => 'T16',
    'description' => 'Batman tiếp tục cuộc chiến chống tổ chức ngầm Court of Owls tại Gotham City.'
  ],
  [
    'id' => 7,
    'title' => 'Frozen III',
    'poster' => 'https://image.tmdb.org/t/p/w500/kgwjIb2JDHRhNk13lmSGiOORTRH.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/tL96GaAMiIcYVZuRbcCYUhwYfqr.jpg',
    'genre' => ['Hoạt hình', 'Gia đình'],
    'duration' => 110,
    'rating' => 7.9,
    'status' => 'coming_soon',
    'ageRating' => 'P',
    'description' => 'Elsa và Anna bắt đầu một cuộc phiêu lưu mới vượt ra ngoài Arendelle.'
  ],
  [
    'id' => 8,
    'title' => 'Transformers: Khởi Đầu Mới',
    'poster' => 'https://image.tmdb.org/t/p/w500/gPbM0MK8CP8A174kI6j4LIvlJGz.jpg',
    'backdrop' => 'https://image.tmdb.org/t/p/original/8b4X3oCKXMnW4GX7BxSgnLAQq7G.jpg',
    'genre' => ['Hành động', 'Khoa học viễn tưởng'],
    'duration' => 142,
    'rating' => 7.2,
    'status' => 'now_showing',
    'ageRating' => 'T13',
    'description' => 'Cuộc chiến giữa Autobots và Decepticons trở lại với quy mô hoành tráng trên Trái Đất.'
  ]
];

$featured = $movies[0];
?>

<!-- ─── Hero Section ─── -->
<section class="relative h-[500px] sm:h-[580px] overflow-hidden">
  <img src="<?= htmlspecialchars($featured['backdrop']) ?>" alt="<?= htmlspecialchars($featured['title']) ?>" class="absolute inset-0 w-full h-full object-cover">
  <!-- Gradient overlays -->
  <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/85 to-transparent"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-gray-950/40"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
    <div class="max-w-xl space-y-4">
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/20 border border-amber-500/40 px-3 py-1 text-xs font-semibold text-amber-400">
          <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i>
          <?= $featured['rating'] ?>/10
        </span>
        <span class="rounded-full bg-red-500/20 border border-red-500/40 px-2.5 py-0.5 text-xs font-bold text-red-400">
          <?= $featured['ageRating'] ?>
        </span>
      </div>

      <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight">
        <?= htmlspecialchars($featured['title']) ?>
      </h1>

      <div class="flex flex-wrap gap-2 text-xs text-gray-300">
        <?php foreach ($featured['genre'] as $g): ?>
          <span class="rounded-full bg-gray-800/80 border border-gray-700 px-3 py-1"><?= htmlspecialchars($g) ?></span>
        <?php endforeach; ?>
        <span class="flex items-center gap-1 text-gray-400 py-1">
          <i data-lucide="clock" class="w-3.5 h-3.5"></i> <?= $featured['duration'] ?> phút
        </span>
      </div>

      <p class="text-gray-300 text-sm leading-relaxed line-clamp-3">
        <?= htmlspecialchars($featured['description']) ?>
      </p>

      <div class="flex items-center gap-3 pt-2">
        <a href="/cinema/detail?id=<?= $featured['id'] ?>" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold px-6 py-3 text-sm transition-all shadow-lg shadow-amber-500/20">
          <i data-lucide="ticket" class="w-4 h-4"></i>
          Đặt vé ngay
        </a>
        <a href="/cinema/detail?id=<?= $featured['id'] ?>" class="inline-flex items-center gap-2 rounded-xl bg-gray-900/80 hover:bg-gray-800 border border-gray-700 text-white font-medium px-5 py-3 text-sm transition-colors">
          <i data-lucide="play" class="w-4 h-4 text-amber-400"></i>
          Xem thông tin
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ─── Movies Grid Section ─── -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <!-- Tabs bar -->
  <div class="flex items-center justify-between border-b border-gray-800 pb-4 mb-8">
    <div class="flex gap-4 sm:gap-8">
      <button type="button" id="tab-now" onclick="switchTab('now_showing')" class="text-lg sm:text-xl font-bold pb-4 -mb-4 border-b-2 border-amber-500 text-amber-400 transition-colors">
        Phim đang chiếu
      </button>
      <button type="button" id="tab-soon" onclick="switchTab('coming_soon')" class="text-lg sm:text-xl font-bold pb-4 -mb-4 border-b-2 border-transparent text-gray-400 hover:text-white transition-colors">
        Phim sắp chiếu
      </button>
    </div>
    <span class="text-xs text-gray-500 hidden sm:inline">Trải nghiệm điện ảnh đỉnh cao</span>
  </div>

  <!-- Movies Grid -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6" id="movie-grid">
    <?php foreach ($movies as $m): ?>
      <article class="movie-card group bg-gray-900/60 rounded-2xl overflow-hidden border border-gray-800 hover:border-amber-500/40 transition-all duration-300 flex flex-col" data-status="<?= $m['status'] ?>">
        <!-- Poster container -->
        <a href="/cinema/detail?id=<?= $m['id'] ?>" class="relative aspect-[2/3] overflow-hidden bg-gray-800 block">
          <img src="<?= htmlspecialchars($m['poster']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
          
          <div class="absolute top-2.5 left-2.5 bg-gray-950/80 backdrop-blur px-2 py-0.5 rounded-md text-[11px] font-bold text-amber-400 flex items-center gap-1">
            <i data-lucide="star" class="w-3 h-3 fill-amber-400"></i>
            <?= $m['rating'] ?>
          </div>

          <div class="absolute top-2.5 right-2.5 bg-red-600/90 text-white px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase">
            <?= $m['ageRating'] ?>
          </div>

          <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
            <span class="w-full py-2 bg-amber-500 text-gray-950 font-bold text-xs rounded-xl text-center shadow-lg">
              Xem suất chiếu
            </span>
          </div>
        </a>

        <!-- Content -->
        <div class="p-4 flex-1 flex flex-col justify-between">
          <div>
            <p class="text-[11px] text-gray-400 mb-1 truncate"><?= implode(', ', $m['genre']) ?></p>
            <h3 class="font-bold text-sm sm:text-base text-white group-hover:text-amber-400 transition-colors line-clamp-1">
              <a href="/cinema/detail?id=<?= $m['id'] ?>"><?= htmlspecialchars($m['title']) ?></a>
            </h3>
          </div>

          <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-800/80 mt-3">
            <span class="flex items-center gap-1">
              <i data-lucide="clock" class="w-3.5 h-3.5"></i>
              <?= $m['duration'] ?>'
            </span>
            <a href="/cinema/detail?id=<?= $m['id'] ?>" class="text-amber-400 hover:underline font-medium">
              Đặt vé &rarr;
            </a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- Filter Script -->
<script>
  function switchTab(status) {
    const tabNow = document.getElementById('tab-now');
    const tabSoon = document.getElementById('tab-soon');
    const cards = document.querySelectorAll('.movie-card');

    if (status === 'now_showing') {
      tabNow.className = 'text-lg sm:text-xl font-bold pb-4 -mb-4 border-b-2 border-amber-500 text-amber-400 transition-colors';
      tabSoon.className = 'text-lg sm:text-xl font-bold pb-4 -mb-4 border-b-2 border-transparent text-gray-400 hover:text-white transition-colors';
    } else {
      tabSoon.className = 'text-lg sm:text-xl font-bold pb-4 -mb-4 border-b-2 border-amber-500 text-amber-400 transition-colors';
      tabNow.className = 'text-lg sm:text-xl font-bold pb-4 -mb-4 border-b-2 border-transparent text-gray-400 hover:text-white transition-colors';
    }

    cards.forEach(card => {
      if (card.getAttribute('data-status') === status) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }

  // Khởi tạo tab mặc định: Phim đang chiếu
  switchTab('now_showing');
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
