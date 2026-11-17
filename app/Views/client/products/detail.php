<?php
/**
 * app/Views/client/products/detail.php  -  Chi tiết sản phẩm
 *
 * Controller truyền vào $product (nếu không có sẽ dùng dữ liệu demo):
 *   id, name, sku, category, category_slug, price, sale_price (nullable), stock,
 *   short_desc, images [url,...],
 *   colors [ ['name','hex'], ... ],
 *   sizes  [ ['label','in_stock'(bool)], ... ],
 *   material, care, return_policy
 * Tùy chọn: $sizeGuide  [ ['size','height','weight'], ... ]
 *
 * API dùng:  POST /cart/add  {product_id, size, color, quantity}
 *            -> {success, cart_count, message}
 */

if (!function_exists('e')) {
    function e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('vnd')) {
    function vnd($n): string { return number_format((float) $n, 0, ',', '.') . '₫'; }
}

/* ---------- Dữ liệu demo (xóa khi đã nối Model) ---------- */
$ph = fn(string $t) => 'https://placehold.co/900x1200/1e293b/d9c08a?text=' . urlencode($t);

$product = $product ?? [
    'id' => 2,
    'name' => 'Blazer len xanh navy',
    'sku' => 'NV-BLZ-024',
    'category' => 'Blazer',
    'category_slug' => 'blazer',
    'price' => 2490000,
    'sale_price' => 1990000,
    'stock' => 8,
    'short_desc' => 'Blazer một hàng cúc, vai gọn, lót lụa mềm. Mặc được với sơ mi đi làm lẫn áo thun khi đi chơi.',
    'images' => [$ph('Blazer 1'), $ph('Blazer 2'), $ph('Blazer 3'), $ph('Blazer 4')],
    'colors' => [
        ['name' => 'Xanh navy', 'hex' => '#1e3a5f'],
        ['name' => 'Xám than',  'hex' => '#374151'],
        ['name' => 'Đen',       'hex' => '#0f172a'],
    ],
    'sizes' => [
        ['label' => 'S',  'in_stock' => true],
        ['label' => 'M',  'in_stock' => true],
        ['label' => 'L',  'in_stock' => true],
        ['label' => 'XL', 'in_stock' => false],
    ],
    'material' => '70% len merino, 28% polyester, 2% elastane. Lót trong 100% cupro.',
    'care' => 'Giặt khô là tốt nhất. Nếu giặt tay: nước lạnh, không vắt xoắn, phơi ngang nơi thoáng mát. Ủi ở nhiệt độ thấp, lót vải mỏng.',
    'return_policy' => 'Đổi size trong 30 ngày kể từ ngày nhận hàng, miễn phí lần đầu. Sản phẩm còn nguyên tem, chưa giặt.',
];

$sizeGuide = $sizeGuide ?? [
    ['size' => 'S',  'height' => '160 - 167 cm', 'weight' => '55 - 62 kg'],
    ['size' => 'M',  'height' => '168 - 173 cm', 'weight' => '63 - 70 kg'],
    ['size' => 'L',  'height' => '174 - 179 cm', 'weight' => '71 - 78 kg'],
    ['size' => 'XL', 'height' => '180 - 185 cm', 'weight' => '79 - 87 kg'],
];

$hasSale = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
$percent = $hasSale ? (int) round(100 - $product['sale_price'] / $product['price'] * 100) : 0;
$maxQty  = max(1, min(10, (int) $product['stock']));

require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-10">

  <!-- Breadcrumb -->
  <nav aria-label="Đường dẫn" class="text-sm text-slate-500">
    <ol class="flex flex-wrap items-center gap-2">
      <li><a href="/" class="hover:text-slate-900">Trang chủ</a></li>
      <li aria-hidden="true">/</li>
      <li><a href="/products?category=<?= e($product['category_slug']) ?>" class="hover:text-slate-900"><?= e($product['category']) ?></a></li>
      <li aria-hidden="true">/</li>
      <li aria-current="page" class="text-slate-900"><?= e($product['name']) ?></li>
    </ol>
  </nav>

  <div class="mt-6 grid gap-8 lg:grid-cols-2 lg:gap-14">

    <!-- ===================== CỘT TRÁI: GALLERY ===================== -->
    <div class="lg:sticky lg:top-24 lg:self-start">
      <div class="aspect-[3/4] overflow-hidden bg-slate-200">
        <img id="gallery-main" src="<?= e($product['images'][0]) ?>" alt="<?= e($product['name']) ?>"
             class="h-full w-full object-cover">
      </div>

      <!-- Thumbnail: bấm để đổi ảnh chính -->
      <ul class="mt-3 grid grid-cols-4 gap-3" aria-label="Ảnh sản phẩm">
        <?php foreach ($product['images'] as $i => $img): ?>
          <li>
            <button type="button" data-thumb="<?= e($img) ?>" aria-label="Xem ảnh <?= $i + 1 ?>"
                    aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
                    class="aspect-[3/4] w-full overflow-hidden bg-slate-200 outline-offset-2 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold aria-[current=true]:ring-2 aria-[current=true]:ring-slate-900 aria-[current=false]:opacity-70 aria-[current=false]:hover:opacity-100">
              <img src="<?= e($img) ?>" alt="" loading="lazy" class="h-full w-full object-cover">
            </button>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- ===================== CỘT PHẢI: THÔNG TIN ===================== -->
    <div>
      <h1 class="font-display text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl"><?= e($product['name']) ?></h1>
      <p class="mt-1 text-sm text-slate-500">Mã: <?= e($product['sku']) ?></p>

      <!-- Giá -->
      <p class="mt-5 flex flex-wrap items-baseline gap-3">
        <?php if ($hasSale): ?>
          <span class="text-3xl font-semibold text-slate-900"><?= vnd($product['sale_price']) ?></span>
          <span class="text-lg text-slate-400 line-through"><?= vnd($product['price']) ?></span>
          <span class="bg-slate-900 px-2 py-1 text-xs font-semibold text-white">-<?= $percent ?>%</span>
        <?php else: ?>
          <span class="text-3xl font-semibold text-slate-900"><?= vnd($product['price']) ?></span>
        <?php endif; ?>
      </p>

      <p class="mt-5 max-w-prose leading-relaxed text-slate-600"><?= e($product['short_desc']) ?></p>

      <form id="product-form" class="mt-8 space-y-7" novalidate>
        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

        <!-- Chọn màu -->
        <fieldset>
          <legend class="text-sm font-medium text-slate-900">
            Màu sắc: <span id="color-label" class="font-normal text-slate-600"><?= e($product['colors'][0]['name']) ?></span>
          </legend>
          <div class="mt-3 flex flex-wrap gap-3">
            <?php foreach ($product['colors'] as $i => $c): ?>
              <div>
                <input type="radio" name="color" id="color-<?= $i ?>" value="<?= e($c['name']) ?>"
                       class="peer sr-only" <?= $i === 0 ? 'checked' : '' ?>>
                <label for="color-<?= $i ?>" title="<?= e($c['name']) ?>"
                       class="block h-10 w-10 cursor-pointer rounded-full border border-slate-300 ring-offset-2 transition peer-checked:ring-2 peer-checked:ring-slate-900 peer-focus-visible:ring-2 peer-focus-visible:ring-gold"
                       style="background-color: <?= e($c['hex']) ?>">
                  <span class="sr-only"><?= e($c['name']) ?></span>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </fieldset>

        <!-- Chọn size -->
        <fieldset>
          <div class="flex items-center justify-between">
            <legend class="text-sm font-medium text-slate-900">Kích cỡ</legend>
            <button type="button" id="open-size-guide"
                    class="text-sm text-slate-600 underline underline-offset-4 hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
              Hướng dẫn chọn size
            </button>
          </div>
          <div class="mt-3 flex flex-wrap gap-3">
            <?php foreach ($product['sizes'] as $i => $s): ?>
              <div>
                <input type="radio" name="size" id="size-<?= $i ?>" value="<?= e($s['label']) ?>"
                       class="peer sr-only" <?= $s['in_stock'] ? '' : 'disabled' ?>>
                <label for="size-<?= $i ?>"
                       class="flex h-11 min-w-[3.25rem] cursor-pointer items-center justify-center border border-slate-300 bg-white px-4 text-sm font-medium text-slate-900 transition hover:border-slate-900 peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-gold peer-disabled:cursor-not-allowed peer-disabled:text-slate-400 peer-disabled:line-through peer-disabled:hover:border-slate-300">
                  <?= e($s['label']) ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
          <p id="size-error" role="alert" class="mt-2 hidden text-sm text-red-600">Bạn chưa chọn kích cỡ.</p>
        </fieldset>

        <!-- Số lượng -->
        <div>
          <p class="text-sm font-medium text-slate-900" id="qty-label">Số lượng</p>
          <div class="mt-3 flex items-center gap-4">
            <div class="inline-flex items-center border border-slate-300 bg-white" role="group" aria-labelledby="qty-label">
              <button type="button" id="qty-minus" aria-label="Giảm số lượng"
                      class="flex h-11 w-11 items-center justify-center text-slate-900 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
                <i data-lucide="minus" class="h-4 w-4"></i>
              </button>
              <input id="qty" name="quantity" type="text" inputmode="numeric" value="1" readonly aria-live="polite"
                     class="h-11 w-12 border-x border-slate-300 bg-white text-center text-sm font-medium text-slate-900 focus:outline-none">
              <button type="button" id="qty-plus" aria-label="Tăng số lượng" data-max="<?= $maxQty ?>"
                      class="flex h-11 w-11 items-center justify-center text-slate-900 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
                <i data-lucide="plus" class="h-4 w-4"></i>
              </button>
            </div>
            <?php if ((int) $product['stock'] > 0 && (int) $product['stock'] <= 10): ?>
              <p class="text-sm text-slate-600">Chỉ còn <?= (int) $product['stock'] ?> sản phẩm</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Nút hành động -->
        <div class="grid gap-3 sm:grid-cols-2">
          <button type="button" id="btn-add"
                  class="bg-slate-900 px-6 py-4 text-sm font-semibold text-white transition hover:bg-slate-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold disabled:opacity-60">
            THÊM VÀO GIỎ
          </button>
          <button type="button" id="btn-buy"
                  class="border border-slate-900 bg-transparent px-6 py-4 text-sm font-semibold text-slate-900 transition hover:bg-slate-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold disabled:opacity-60">
            MUA NGAY
          </button>
        </div>
        <p id="form-message" role="status" class="min-h-[1.25rem] text-sm text-slate-600"></p>
      </form>

      <!-- Accordion thông tin (dùng <details>, không cần JS) -->
      <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200">
        <?php
        $accordion = [
            ['Chất liệu', $product['material']],
            ['Hướng dẫn bảo quản', $product['care']],
            ['Chính sách đổi trả', $product['return_policy']],
        ];
        foreach ($accordion as $n => [$title, $body]): ?>
          <details class="group py-4" <?= $n === 0 ? 'open' : '' ?>>
            <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-medium text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold [&::-webkit-details-marker]:hidden">
              <?= e($title) ?>
              <i data-lucide="chevron-down" class="h-4 w-4 transition-transform group-open:rotate-180 motion-reduce:transition-none"></i>
            </summary>
            <p class="mt-3 max-w-prose text-sm leading-relaxed text-slate-600"><?= e($body) ?></p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- ===================== MODAL HƯỚNG DẪN CHỌN SIZE ===================== -->
<dialog id="size-guide"
        class="w-[calc(100%-2rem)] max-w-lg bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-slate-900/60">
  <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
    <h2 class="font-display text-2xl font-semibold">Hướng dẫn chọn size</h2>
    <button type="button" id="close-size-guide" aria-label="Đóng"
            class="p-1 text-slate-500 hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold">
      <i data-lucide="x" class="h-5 w-5"></i>
    </button>
  </div>
  <div class="px-6 py-5">
    <table class="w-full text-left text-sm">
      <caption class="sr-only">Bảng size theo chiều cao và cân nặng</caption>
      <thead>
        <tr class="border-b border-slate-200 text-slate-500">
          <th scope="col" class="py-2 font-medium">Size</th>
          <th scope="col" class="py-2 font-medium">Chiều cao</th>
          <th scope="col" class="py-2 font-medium">Cân nặng</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($sizeGuide as $row): ?>
          <tr>
            <th scope="row" class="py-3 font-semibold"><?= e($row['size']) ?></th>
            <td class="py-3"><?= e($row['height']) ?></td>
            <td class="py-3"><?= e($row['weight']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p class="mt-4 text-sm leading-relaxed text-slate-600">
      Số đo chỉ mang tính tham khảo. Nếu bạn nằm giữa hai size, hãy chọn size lớn hơn. Chưa chắc chắn? Hỏi AI Stylist để được tư vấn theo vóc dáng.
    </p>
  </div>
</dialog>

<script>
(function () {
  /* ---------- Gallery: bấm thumbnail đổi ảnh chính ---------- */
  const main = document.getElementById('gallery-main');
  const thumbs = [...document.querySelectorAll('[data-thumb]')];
  thumbs.forEach((t) => t.addEventListener('click', () => {
    main.src = t.dataset.thumb;
    thumbs.forEach((x) => x.setAttribute('aria-current', String(x === t)));
  }));

  /* ---------- Tên màu đang chọn ---------- */
  const colorLabel = document.getElementById('color-label');
  document.querySelectorAll('input[name="color"]').forEach((r) =>
    r.addEventListener('change', () => { colorLabel.textContent = r.value; }));

  /* ---------- Modal size (thẻ <dialog> gốc của trình duyệt) ---------- */
  const dlg = document.getElementById('size-guide');
  document.getElementById('open-size-guide').addEventListener('click', () => dlg.showModal());
  document.getElementById('close-size-guide').addEventListener('click', () => dlg.close());
  dlg.addEventListener('click', (e) => { if (e.target === dlg) dlg.close(); }); // bấm nền tối để đóng

  /* ---------- Số lượng ---------- */
  const qty = document.getElementById('qty');
  const max = Number(document.getElementById('qty-plus').dataset.max);
  document.getElementById('qty-minus').addEventListener('click', () => { qty.value = Math.max(1, Number(qty.value) - 1); });
  document.getElementById('qty-plus').addEventListener('click', () => { qty.value = Math.min(max, Number(qty.value) + 1); });

  /* ---------- Thêm giỏ / Mua ngay ---------- */
  const form = document.getElementById('product-form');
  const sizeError = document.getElementById('size-error');
  const msg = document.getElementById('form-message');
  const btnAdd = document.getElementById('btn-add');
  const btnBuy = document.getElementById('btn-buy');

  // [data-cart-count] là badge số lượng trong header.php
  function updateCartBadge(n) {
    document.querySelectorAll('[data-cart-count]').forEach((el) => {
      el.textContent = n;
      el.classList.toggle('hidden', !n);
    });
  }

  async function addToCart() {
    const size = form.querySelector('input[name="size"]:checked');
    if (!size) {
      sizeError.classList.remove('hidden');
      form.querySelector('input[name="size"]:not(:disabled)')?.focus();
      return false;
    }
    sizeError.classList.add('hidden');
    msg.textContent = '';
    btnAdd.disabled = btnBuy.disabled = true;

    try {
      const res = await fetch('/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          product_id: Number(form.product_id.value),
          size: size.value,
          color: form.querySelector('input[name="color"]:checked')?.value ?? null,
          quantity: Number(qty.value)
        })
      });
      const data = await res.json();
      if (!res.ok || data.success === false) throw new Error(data.message || 'Không thêm được vào giỏ hàng.');
      updateCartBadge(data.cart_count);
      msg.textContent = 'Đã thêm vào giỏ hàng.';
      return true;
    } catch (err) {
      msg.textContent = err.message || 'Có lỗi xảy ra, bạn thử lại nhé.';
      return false;
    } finally {
      btnAdd.disabled = btnBuy.disabled = false;
    }
  }

  btnAdd.addEventListener('click', addToCart);
  btnBuy.addEventListener('click', async () => { if (await addToCart()) window.location.href = '/checkout'; });
  form.querySelectorAll('input[name="size"]').forEach((r) =>
    r.addEventListener('change', () => sizeError.classList.add('hidden')));

  if (window.lucide) window.lucide.createIcons();
})();
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
