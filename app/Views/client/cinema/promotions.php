<?php
/**
 * app/Views/client/cinema/promotions.php - Danh sách Ưu Đãi & Chính sách Điểm Thưởng MovieGo
 */
$pageTitle = 'Ưu Đãi & Khuyến Mãi - MovieGo Cinema';
include __DIR__ . '/layouts/header.php';

use App\Core\Loyalty;

$promotions = $promotions ?? [];
$user       = $user ?? (function_exists('currentUser') ? currentUser() : null);
$loyalty    = $loyalty ?? null;
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <!-- Banner Ưu Đãi -->
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-600 via-amber-500 to-yellow-500 p-8 sm:p-12 text-gray-950 shadow-2xl mb-12">
    <div class="relative z-10 max-w-2xl space-y-3">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-950/20 text-gray-950 text-xs font-black uppercase tracking-wider">
        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> MovieGo Rewards & Deals
      </span>
      <h1 class="text-3xl sm:text-5xl font-black leading-tight">
        Khuyến Mãi & Tích Điểm Đổi Vé
      </h1>
      <p class="text-xs sm:text-sm text-gray-950/80 font-medium leading-relaxed">
        Thưởng thức những siêu phẩm điện ảnh với giá cực hời! Áp dụng voucher giảm giá hoặc dùng điểm thưởng thành viên (1 điểm = <?= number_format(Loyalty::POINT_VALUE, 0, ',', '.') ?>₫) để giảm trực tiếp khi đặt vé.
      </p>
      
      <?php if ($loyalty): ?>
        <div class="pt-2 flex flex-wrap items-center gap-2">
          <a href="/cinema/points" class="text-xs font-bold bg-gray-950 text-amber-400 px-4 py-2 rounded-xl shadow flex items-center gap-2 hover:bg-gray-900 transition-colors">
            <span>⭐</span>
            <span>Hạng <?= htmlspecialchars($loyalty['tier']) ?> • Số dư: <strong><?= number_format($loyalty['balance'], 0, ',', '.') ?> điểm</strong> (≈ <?= number_format($loyalty['balance'] * Loyalty::POINT_VALUE, 0, ',', '.') ?>₫)</span>
          </a>
        </div>
      <?php else: ?>
        <div class="pt-2">
          <a href="/register" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-950 text-amber-400 text-xs font-bold hover:bg-gray-900 transition-colors">
            <span>Đăng ký thành viên để tích điểm & nhận ưu đãi &rarr;</span>
          </a>
        </div>
      <?php endif; ?>
    </div>

    <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
  </div>

  <!-- ─── Hướng dẫn Tích Điểm & Dùng Điểm ─── -->
  <div class="mb-14">
    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
      <i data-lucide="star" class="w-5 h-5 text-amber-400"></i>
      Chính Sách Tích Điểm Thành Viên MovieGo
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gray-900/80 border border-gray-800 rounded-2xl p-6">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center font-black text-lg mb-4">
          1
        </div>
        <h3 class="text-white text-base font-bold mb-2">Tích Điểm Tự Động</h3>
        <p class="text-xs text-gray-400 leading-relaxed">
          Mỗi khi đặt vé thành công, bạn được tích ngay <strong class="text-amber-400"><?= Loyalty::EARN_TICKET_PCT ?>% giá trị tiền vé</strong> và <strong class="text-amber-400"><?= Loyalty::EARN_COMBO_PCT ?>% bắp nước</strong> (tính trên số tiền thực trả).
        </p>
      </div>

      <div class="bg-gray-900/80 border border-gray-800 rounded-2xl p-6">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center font-black text-lg mb-4">
          2
        </div>
        <h3 class="text-white text-base font-bold mb-2">1 Điểm = <?= number_format(Loyalty::POINT_VALUE, 0, ',', '.') ?>₫</h3>
        <p class="text-xs text-gray-400 leading-relaxed">
          Dùng tối thiểu <strong class="text-emerald-400"><?= Loyalty::MIN_REDEEM ?> điểm</strong> mỗi lần thanh toán để giảm tối đa <strong class="text-emerald-400"><?= Loyalty::MAX_REDEEM_PERCENT ?>% giá trị đơn</strong>. Hủy đơn được hoàn điểm tự động.
        </p>
      </div>

      <div class="bg-gray-900/80 border border-gray-800 rounded-2xl p-6">
        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center font-black text-lg mb-4">
          3
        </div>
        <h3 class="text-white text-base font-bold mb-2">Dùng Chung Với Voucher</h3>
        <p class="text-xs text-gray-400 leading-relaxed">
          Bạn vừa có thể áp dụng <strong class="text-blue-400">Mã giảm giá Voucher</strong> vừa trừ thêm <strong class="text-blue-400">Điểm thưởng</strong> trên cùng một đơn đặt vé để tối ưu chi phí!
        </p>
      </div>
    </div>
  </div>

  <!-- ─── Danh sách Voucher / Khuyến mãi hiện có ─── -->
  <div>
    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
      <i data-lucide="tag" class="w-5 h-5 text-amber-400"></i>
      Mã Khuyến Mãi Đang Hoạt Động (<?= count($promotions) ?>)
    </h2>

    <?php if (empty($promotions)): ?>
      <div class="text-center py-12 bg-gray-900/40 rounded-3xl border border-gray-800">
        <p class="text-xs text-gray-400">Hiện tại chưa có mã khuyến mãi nào mới. Hãy quay lại sau nhé!</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($promotions as $pr): 
          $discountText = $pr['discount_type'] === 'percent' 
            ? 'GIẢM ' . $pr['discount_value'] . '%' 
            : 'GIẢM ' . number_format($pr['discount_value'], 0, ',', '.') . '₫';
        ?>
          <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden shadow-xl flex flex-col justify-between hover:border-amber-500/40 transition-all">
            <div class="p-6">
              <div class="flex items-center justify-between gap-3 mb-3">
                <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-black">
                  <?= $discountText ?>
                </span>
                <span class="text-[11px] text-gray-400 flex items-center gap-1">
                  <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                  <?= $pr['expires_at'] ? date('d/m/Y', strtotime($pr['expires_at'])) : 'Không thời hạn' ?>
                </span>
              </div>

              <h3 class="text-lg font-mono font-black text-white tracking-wider mb-2">
                <?= htmlspecialchars($pr['code']) ?>
              </h3>
              
              <p class="text-xs text-gray-300 leading-relaxed mb-4">
                <?= htmlspecialchars($pr['description'] ?: 'Mã giảm giá áp dụng khi đặt vé online tại hệ thống rạp MovieGo.') ?>
              </p>
            </div>

            <div class="p-4 bg-gray-950/60 border-t border-gray-800/80 flex items-center justify-between">
              <button type="button" onclick="copyCode('<?= htmlspecialchars($pr['code']) ?>', this)" 
                      class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-amber-400 font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                <span>Sao chép mã</span>
              </button>

              <a href="/cinema" class="text-xs font-bold text-white hover:text-amber-400 transition-colors flex items-center gap-1">
                <span>Đặt vé ngay</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
      const originalHTML = btn.innerHTML;
      btn.innerHTML = '<span>Đã sao chép!</span>';
      btn.classList.add('bg-emerald-600', 'text-white');
      setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('bg-emerald-600', 'text-white');
      }, 2000);
    });
  }
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
