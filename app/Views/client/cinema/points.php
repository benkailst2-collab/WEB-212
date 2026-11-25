<?php
/**
 * app/Views/client/cinema/points.php - Điểm thưởng & hạng thành viên
 * Biến: $row (full_name), $balance, $spent, $tier (code,name,min,next), $history
 */
$pageTitle = 'Điểm thưởng - MovieGo';
include __DIR__ . '/layouts/header.php';

use App\Core\Loyalty;

$next     = $tier['next'];
$progress = $next ? min(100, (int)floor(($spent - $tier['min']) * 100 / ($next['min'] - $tier['min']))) : 100;
$typeLabel = ['earn' => 'Tích điểm', 'redeem' => 'Dùng điểm', 'refund' => 'Hoàn điểm', 'adjust' => 'Điều chỉnh'];
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10 space-y-6">
  <div class="bg-gradient-to-br from-amber-500/20 via-gray-900 to-gray-900 border border-amber-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl">
    <p class="text-xs text-gray-400">Xin chào, <?= $h($row['full_name']) ?></p>
    <div class="flex flex-wrap items-end justify-between gap-4 mt-2">
      <div>
        <p class="text-xs text-gray-400">Điểm hiện có</p>
        <p class="text-5xl font-black text-amber-400"><?= number_format($balance, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-500 mt-1">≈ <?= number_format($balance * Loyalty::POINT_VALUE, 0, ',', '.') ?>₫ khi quy đổi</p>
      </div>
      <span class="px-4 py-1.5 rounded-full bg-amber-500 text-gray-950 text-sm font-black"><?= $h($tier['name']) ?></span>
    </div>

    <div class="mt-6">
      <div class="h-2 rounded-full bg-gray-800 overflow-hidden">
        <div class="h-full bg-amber-400" style="width: <?= $progress ?>%"></div>
      </div>
      <p class="text-[11px] text-gray-400 mt-2">
        Tổng chi tiêu: <?= number_format($spent, 0, ',', '.') ?>₫
        <?php if ($next): ?>
          • Còn <?= number_format($next['min'] - $spent, 0, ',', '.') ?>₫ để lên hạng <?= $h($next['name']) ?>
        <?php else: ?>
          • Bạn đã đạt hạng cao nhất 🎉
        <?php endif; ?>
      </p>
    </div>
  </div>

  <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 text-xs text-gray-400 space-y-1">
    <p class="text-sm font-bold text-white mb-2">Cách tích & dùng điểm</p>
    <p>• Tích <?= Loyalty::EARN_TICKET_PCT ?>% giá trị tiền vé và <?= Loyalty::EARN_COMBO_PCT ?>% giá trị bắp nước (tính trên số tiền thực trả), 1 điểm = <?= number_format(Loyalty::POINT_VALUE, 0, ',', '.') ?>₫.</p>
    <p>• Dùng điểm ở bước thanh toán: tối thiểu <?= Loyalty::MIN_REDEEM ?> điểm, tối đa <?= Loyalty::MAX_REDEEM_PERCENT ?>% giá trị đơn.</p>
    <p>• Hạng: Member → VIP (từ <?= number_format(Loyalty::TIERS[1]['min'], 0, ',', '.') ?>₫) → VVIP (từ <?= number_format(Loyalty::TIERS[2]['min'], 0, ',', '.') ?>₫) theo tổng chi tiêu.</p>
    <p>• Hủy đơn sẽ hoàn lại điểm đã dùng và thu hồi điểm đã tích.</p>
  </div>

  <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6">
    <p class="text-sm font-bold text-white mb-4">Lịch sử điểm</p>
    <?php if (empty($history)): ?>
      <p class="text-xs text-gray-500">Chưa có giao dịch điểm nào. Đặt vé đầu tiên để bắt đầu tích điểm!</p>
    <?php else: ?>
      <div class="divide-y divide-gray-800">
        <?php foreach ($history as $t): ?>
          <div class="py-3 flex items-center justify-between gap-4 text-xs">
            <div>
              <p class="text-white font-medium"><?= $h($typeLabel[$t['type']] ?? $t['type']) ?>
                <?php if (!empty($t['order_code'])): ?>
                  <a href="/cinema/ticket?code=<?= urlencode($t['order_code']) ?>" class="text-amber-400 hover:underline ml-1"><?= $h($t['order_code']) ?></a>
                <?php endif; ?>
              </p>
              <p class="text-gray-500"><?= date('H:i d/m/Y', strtotime($t['created_at'])) ?><?= !empty($t['note']) ? ' • ' . $h($t['note']) : '' ?> • Số dư sau: <?= (int)$t['balance_after'] ?></p>
            </div>
            <span class="font-black text-sm <?= $t['points'] >= 0 ? 'text-emerald-400' : 'text-red-400' ?>">
              <?= $t['points'] >= 0 ? '+' : '' ?><?= (int)$t['points'] ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
