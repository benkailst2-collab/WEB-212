<?php
/**
 * app/Views/client/home/index.php  -  Trang chủ NAM VISION
 *
 * Controller truyền vào (nếu không có sẽ dùng dữ liệu demo bên dưới):
 *   $heroSlides   [ ['title','text','cta_label','cta_url','image'], ... ]
 *   $categories   [ ['name','slug','count','image'], ... ]
 *   $bestSellers  [ product, ... ]
 *   $newArrivals  [ product, ... ]
 *   product = ['id','name','slug','category','price','sale_price'(nullable),'image','image_hover']
 *
 * Quy ước layout: header.php mở tài liệu (<html>, <head>, <body>) + thanh điều hướng,
 * footer.php đóng </body></html> và include ai_widget.php.
 */

if (!function_exists('e')) {
    function e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('vnd')) {
    function vnd($n): string { return number_format((float) $n, 0, ',', '.') . '₫'; }
}

/* ---------- Dữ liệu demo (xóa khi đã nối Model) ---------- */
$ph = fn(int $w, int $h, string $t) => "https://placehold.co/{$w}x{$h}/1e293b/d9c08a?text=" . urlencode($t);

$heroSlides = $heroSlides ?? [
    ['title' => 'Bộ sưu tập Thu Đông 2026', 'text' => 'Len mịn, dạ ấm, phom đứng. Những thiết kế gọn gàng cho mùa lạnh.', 'cta_label' => 'Khám phá BST Mới', 'cta_url' => '/collections/new', 'image' => $ph(1600, 900, 'BST Thu Dong 2026')],
    ['title' => 'Blazer cho ngày làm việc dài', 'text' => 'Vai gọn, lót mềm, mặc được từ buổi họp sáng đến bữa tối.', 'cta_label' => 'Xem Blazer', 'cta_url' => '/products?category=blazer', 'image' => $ph(1600, 900, 'Blazer')],
    ['title' => 'Suit may sẵn, vừa người ngay lần thử đầu', 'text' => 'Ba phom dáng, mười hai cỡ, chỉnh sửa miễn phí tại cửa hàng.', 'cta_label' => 'Xem Suits', 'cta_url' => '/products?category=suit', 'image' => $ph(1600, 900, 'Suits')],
];

$categories = $categories ?? [
    ['name' => 'Áo sơ mi', 'slug' => 'ao-so-mi', 'count' => 64, 'image' => $ph(600, 800, 'Ao so mi')],
    ['name' => 'Blazer',   'slug' => 'blazer',   'count' => 28, 'image' => $ph(600, 800, 'Blazer')],
    ['name' => 'Quần âu',  'slug' => 'quan-au',  'count' => 41, 'image' => $ph(600, 800, 'Quan au')],
    ['name' => 'Suits',    'slug' => 'suits',    'count' => 19, 'image' => $ph(600, 800, 'Suits')],
];

$bestSellers = $bestSellers ?? [
    ['id' => 1, 'name' => 'Sơ mi Oxford trắng',      'slug' => 'so-mi-oxford-trang',  'category' => 'Áo sơ mi', 'price' => 690000,  'sale_price' => 552000,  'image' => $ph(600, 800, 'Oxford'),  'image_hover' => $ph(600, 800, 'Oxford 2')],
    ['id' => 2, 'name' => 'Blazer len xanh navy',     'slug' => 'blazer-len-navy',     'category' => 'Blazer',   'price' => 2490000, 'sale_price' => null,    'image' => $ph(600, 800, 'Blazer'),  'image_hover' => $ph(600, 800, 'Blazer 2')],
    ['id' => 3, 'name' => 'Quần âu ống đứng xám',     'slug' => 'quan-au-xam',         'category' => 'Quần âu',  'price' => 1190000, 'sale_price' => 949000,  'image' => $ph(600, 800, 'Quan au'), 'image_hover' => $ph(600, 800, 'Quan au 2')],
    ['id' => 4, 'name' => 'Suit đen một cúc',         'slug' => 'suit-den-mot-cuc',    'category' => 'Suits',    'price' => 4990000, 'sale_price' => null,    'image' => $ph(600, 800, 'Suit'),    'image_hover' => $ph(600, 800, 'Suit 2')],
];

$newArrivals = $newArrivals ?? [
    ['id' => 5, 'name' => 'Sơ mi linen be',           'slug' => 'so-mi-linen-be',      'category' => 'Áo sơ mi', 'price' => 790000,  'sale_price' => null,    'image' => $ph(600, 800, 'Linen'),   'image_hover' => $ph(600, 800, 'Linen 2')],
    ['id' => 6, 'name' => 'Blazer kẻ sọc nhỏ',        'slug' => 'blazer-ke-soc',       'category' => 'Blazer',   'price' => 2790000, 'sale_price' => null,    'image' => $ph(600, 800, 'Soc'),     'image_hover' => $ph(600, 800, 'Soc 2')],
    ['id' => 7, 'name' => 'Quần âu cạp cao nâu',      'slug' => 'quan-au-nau',         'category' => 'Quần âu',  'price' => 1290000, 'sale_price' => 1032000, 'image' => $ph(600, 800, 'Nau'),     'image_hover' => $ph(600, 800, 'Nau 2')],
    ['id' => 8, 'name' => 'Áo khoác dạ dáng dài',     'slug' => 'ao-khoac-da',         'category' => 'Áo khoác', 'price' => 3890000, 'sale_price' => null,    'image' => $ph(600, 800, 'Da'),      'image_hover' => $ph(600, 800, 'Da 2')],
];

/* ---------- Card sản phẩm dùng lại cho cả 2 section ---------- */
function nv_product_card(array $p): void
{
    $hasSale = !empty($p['sale_price']) && $p['sale_price'] < $p['price'];
    $percent = $hasSale ? (int) round(100 - $p['sale_price'] / $p['price'] * 100) : 0;
    ?>
    <article class="group">
      <div class="relative aspect-[3/4] overflow-hidden bg-slate-200">
        <a href="/products/<?= e($p['slug']) ?>" class="absolute inset-0" aria-label="Xem <?= e($p['name']) ?>">
          <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"
               class="absolute inset-0 h-full w-full object-cover">
          <!-- Ảnh thứ 2 hiện khi hover -->
          <img src="<?= e($p['image_hover'] ?? $p['image']) ?>" alt="" aria-hidden="true" loading="lazy"
               class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100 motion-reduce:transition-none">
        </a>

        <?php if ($hasSale): ?>
          <span class="absolute left-3 top-3 bg-slate-900 px-2 py-1 text-xs font-semibold text-white">-<?= $percent ?>%</span>
        <?php endif; ?>

        <!-- Hiện khi hover (desktop) hoặc luôn hiện trên màn cảm ứng -->
        <button type="button" data-quick-add="<?= (int) $p['id'] ?>"
                class="absolute inset-x-3 bottom-3 translate-y-2 bg-white/95 py-2.5 text-sm font-semibold text-slate-900 opacity-0 shadow-md transition hover:bg-slate-900 hover:text-white focus-visible:translate-y-0 focus-visible:opacity-100 group-hover:translate-y-0 group-hover:opacity-100 disabled:opacity-60 motion-reduce:transition-none [@media(hover:none)]:translate-y-0 [@media(hover:none)]:opacity-100">
          Thêm nhanh vào giỏ
        </button>
      </div>

      <div class="mt-3 space-y-1">
        <p class="text-xs text-slate-500"><?= e($p['category']) ?></p>
        <h3 class="text-sm font-medium text-slate-900">
          <a href="/products/<?= e($p['slug']) ?>" class="hover:text-gold-dark"><?= e($p['name']) ?></a>
        </h3>
        <p class="flex items-baseline gap-2 text-sm">
          <?php if ($hasSale): ?>
            <span class="font-semibold text-slate-900"><?= vnd($p['sale_price']) ?></span>
            <span class="text-slate-400 line-through"><?= vnd($p['price']) ?></span>
          <?php else: ?>
            <span class="font-semibold text-slate-900"><?= vnd($p['price']) ?></span>
          <?php endif; ?>
        </p>
      </div>
    </article>
    <?php
}

require __DIR__ . '/../layouts/header.php';
?>

<h1 class="sr-only">NAM VISION - Thời trang nam</h1>

<!-- ===================== HERO SLIDER ===================== -->
<section id="hero" aria-roledescription="carousel" aria-label="Bộ sưu tập nổi bật"
         class="relative isolate overflow-hidden bg-slate-900 text-white">
  <div class="relative h-[78vh] max-h-[46rem] min-h-[28rem]">
    <?php foreach ($heroSlides as $i => $s): ?>
      <div data-slide role="group" aria-roledescription="slide" aria-label="<?= $i + 1 ?> trên <?= count($heroSlides) ?>"
           <?= $i === 0 ? '' : 'aria-hidden="true"' ?>
           class="absolute inset-0 transition-opacity duration-700 motion-reduce:transition-none <?= $i === 0 ? 'opacity-100' : 'pointer-events-none opacity-0' ?>">
        <img src="<?= e($s['image']) ?>" alt=""
             <?= $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
             class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/55 to-transparent"></div>

        <div class="relative mx-auto flex h-full max-w-7xl items-center px-4 sm:px-6 lg:px-8">
          <div class="max-w-xl">
            <h2 class="font-display text-4xl font-semibold leading-[1.05] sm:text-6xl"><?= e($s['title']) ?></h2>
            <p class="mt-5 max-w-md text-base leading-relaxed text-slate-200 sm:text-lg"><?= e($s['text']) ?></p>
            <a href="<?= e($s['cta_url']) ?>"
               class="mt-8 inline-flex items-center gap-2 bg-gold px-7 py-3.5 text-sm font-semibold text-slate-900 transition hover:bg-gold-light focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
              <?= e($s['cta_label']) ?>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Điều khiển -->
    <div class="absolute inset-x-0 bottom-6 mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <div class="flex items-center gap-2">
        <?php foreach ($heroSlides as $i => $s): ?>
          <button type="button" data-dot="<?= $i ?>" aria-label="Chuyển đến slide <?= $i + 1 ?>"
                  aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
                  class="h-2 rounded-full transition-all <?= $i === 0 ? 'w-8 bg-gold' : 'w-2 bg-white/50' ?> focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"></button>
        <?php endforeach; ?>
      </div>
      <div class="flex gap-2">
        <button type="button" data-prev aria-label="Slide trước"
                class="flex h-10 w-10 items-center justify-center border border-white/40 text-white transition hover:bg-white hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white">
          <i data-lucide="chevron-left" class="h-5 w-5"></i>
        </button>
        <button type="button" data-next aria-label="Slide sau"
                class="flex h-10 w-10 items-center justify-center border border-white/40 text-white transition hover:bg-white hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white">
          <i data-lucide="chevron-right" class="h-5 w-5"></i>
        </button>
      </div>
    </div>
  </div>
</section>

<!-- ===================== DANH MỤC NỔI BẬT ===================== -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8" aria-labelledby="cat-title">
  <h2 id="cat-title" class="font-display text-3xl font-semibold text-slate-900 sm:text-4xl">Mua theo danh mục</h2>

  <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
    <?php foreach ($categories as $c): ?>
      <a href="/products?category=<?= e($c['slug']) ?>"
         class="group relative block aspect-[3/4] overflow-hidden bg-slate-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold">
        <img src="<?= e($c['image']) ?>" alt="" loading="lazy"
             class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105 motion-reduce:transition-none">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/10 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 p-4 text-white sm:p-5">
          <h3 class="font-display text-2xl font-semibold"><?= e($c['name']) ?></h3>
          <p class="mt-0.5 text-sm text-slate-200"><?= (int) $c['count'] ?> sản phẩm</p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===================== SẢN PHẨM BÁN CHẠY ===================== -->
<section class="bg-white py-16" aria-labelledby="best-title">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4">
      <h2 id="best-title" class="font-display text-3xl font-semibold text-slate-900 sm:text-4xl">Sản phẩm bán chạy</h2>
      <a href="/products?sort=best_selling" class="shrink-0 text-sm font-medium text-slate-900 underline underline-offset-4 hover:text-gold-dark">Xem tất cả</a>
    </div>
    <div class="mt-8 grid grid-cols-2 gap-x-3 gap-y-8 sm:gap-x-5 lg:grid-cols-4">
      <?php foreach ($bestSellers as $p) { nv_product_card($p); } ?>
    </div>
  </div>
</section>

<!-- ===================== BANNER AI STYLIST ===================== -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8" aria-labelledby="ai-title">
  <div class="grid items-center gap-10 overflow-hidden bg-slate-900 p-8 text-white sm:p-12 lg:grid-cols-2">
    <div>
      <h2 id="ai-title" class="font-display text-3xl font-semibold leading-tight sm:text-5xl">Không biết mặc gì? Hỏi AI Stylist.</h2>
      <p class="mt-5 max-w-md text-base leading-relaxed text-slate-300">
        Nhập chiều cao, cân nặng và dịp bạn cần mặc. AI Stylist chọn sẵn bộ đồ từ kho NAM VISION và cho bạn thêm vào giỏ ngay.
      </p>
      <button type="button" id="open-ai-banner"
              class="mt-8 inline-flex items-center gap-2 bg-gold px-7 py-3.5 text-sm font-semibold text-slate-900 transition hover:bg-gold-light focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
        <i data-lucide="bot" class="h-4 w-4"></i> Hỏi AI Stylist
      </button>
    </div>

    <!-- Ví dụ hội thoại (tĩnh, chỉ để minh họa) -->
    <div class="space-y-3 text-sm" aria-hidden="true">
      <div class="flex justify-end">
        <p class="max-w-[85%] rounded-2xl rounded-br-sm bg-white/10 px-4 py-3 text-white">Mình cao 1m70, nặng 65kg, cuối tuần đi đám cưới bạn.</p>
      </div>
      <div class="flex items-start gap-2">
        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gold text-xs font-bold text-slate-900">AI</span>
        <p class="max-w-[85%] rounded-2xl rounded-bl-sm bg-white px-4 py-3 text-slate-800">
          Bạn hợp blazer xanh navy phom gọn, sơ mi trắng và quần âu ống đứng. Mình đã chọn 3 mẫu còn đủ size M cho bạn.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== HÀNG MỚI VỀ ===================== -->
<section class="bg-white pb-20 pt-4" aria-labelledby="new-title">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4">
      <h2 id="new-title" class="font-display text-3xl font-semibold text-slate-900 sm:text-4xl">Hàng mới về</h2>
      <a href="/products?sort=newest" class="shrink-0 text-sm font-medium text-slate-900 underline underline-offset-4 hover:text-gold-dark">Xem tất cả</a>
    </div>
    <div class="mt-8 grid grid-cols-2 gap-x-3 gap-y-8 sm:gap-x-5 lg:grid-cols-4">
      <?php foreach ($newArrivals as $p) { nv_product_card($p); } ?>
    </div>
  </div>
</section>

<!-- Thông báo nhỏ (toast) cho thêm giỏ hàng -->
<div id="toast" role="status" aria-live="polite"
     class="pointer-events-none fixed bottom-4 left-4 z-50 translate-y-4 bg-slate-900 px-4 py-3 text-sm text-white opacity-0 shadow-lg transition motion-reduce:transition-none"></div>

<script>
(function () {
  /* ---------- Hero slider ---------- */
  const hero = document.getElementById('hero');
  if (hero) {
    const slides = [...hero.querySelectorAll('[data-slide]')];
    const dots = [...hero.querySelectorAll('[data-dot]')];
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let idx = 0, timer = null;

    function show(n) {
      idx = (n + slides.length) % slides.length;
      slides.forEach((s, i) => {
        const on = i === idx;
        s.classList.toggle('opacity-100', on);
        s.classList.toggle('opacity-0', !on);
        s.classList.toggle('pointer-events-none', !on);
        s.setAttribute('aria-hidden', String(!on));
      });
      dots.forEach((d, i) => {
        const on = i === idx;
        d.setAttribute('aria-current', String(on));
        d.classList.toggle('w-8', on);
        d.classList.toggle('bg-gold', on);
        d.classList.toggle('w-2', !on);
        d.classList.toggle('bg-white/50', !on);
      });
    }
    const start = () => { if (!reduceMotion && !timer) timer = setInterval(() => show(idx + 1), 6000); };
    const stop = () => { clearInterval(timer); timer = null; };

    hero.querySelector('[data-prev]').addEventListener('click', () => show(idx - 1));
    hero.querySelector('[data-next]').addEventListener('click', () => show(idx + 1));
    dots.forEach((d) => d.addEventListener('click', () => show(Number(d.dataset.dot))));
    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    hero.addEventListener('focusin', stop);
    hero.addEventListener('focusout', start);
    start();
  }

  /* ---------- Toast + badge giỏ hàng ---------- */
  const toast = document.getElementById('toast');
  let toastTimer;
  function showToast(msg) {
    toast.textContent = msg;
    toast.classList.remove('translate-y-4', 'opacity-0');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.add('translate-y-4', 'opacity-0'), 2500);
  }
  // [data-cart-count] là badge số lượng trong header.php
  function updateCartBadge(n) {
    document.querySelectorAll('[data-cart-count]').forEach((el) => {
      el.textContent = n;
      el.classList.toggle('hidden', !n);
    });
  }

  /* ---------- Thêm nhanh vào giỏ (POST /cart/add) ---------- */
  document.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('[data-quick-add]');
    if (!btn) return;
    btn.disabled = true;
    try {
      const res = await fetch('/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ product_id: Number(btn.dataset.quickAdd), quantity: 1 })
      });
      const data = await res.json();
      if (!res.ok || data.success === false) throw new Error(data.message || 'Không thêm được vào giỏ hàng.');
      updateCartBadge(data.cart_count);
      showToast('Đã thêm vào giỏ hàng');
    } catch (err) {
      showToast(err.message || 'Có lỗi xảy ra, bạn thử lại nhé.');
    } finally {
      btn.disabled = false;
    }
  });

  /* ---------- Banner mở widget AI ---------- */
  const aiBtn = document.getElementById('open-ai-banner');
  if (aiBtn) aiBtn.addEventListener('click', () => window.openAiStylist && window.openAiStylist());

  if (window.lucide) window.lucide.createIcons();
})();
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
