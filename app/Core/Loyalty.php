<?php
namespace App\Core;

use PDO;

/**
 * app/Core/Loyalty.php - Quy tắc & xử lý TÍCH ĐIỂM (mô phỏng chương trình thành viên kiểu CGV)
 * Muốn đổi tỉ lệ chỉ cần sửa các hằng số ở đầu class.
 */
class Loyalty
{
    const POINT_VALUE        = 1000;  // 1 điểm = 1.000đ khi dùng để trừ tiền
    const EARN_TICKET_PCT    = 5;     // tích 5% giá trị tiền vé
    const EARN_COMBO_PCT     = 3;     // tích 3% giá trị bắp nước
    const MIN_REDEEM         = 10;    // dùng tối thiểu 10 điểm / lần
    const MAX_REDEEM_PERCENT = 50;    // chỉ được trừ tối đa 50% giá trị đơn

    const TIERS = [
        ['code' => 'member', 'name' => 'Member', 'min' => 0],
        ['code' => 'vip',    'name' => 'VIP',    'min' => 4000000],
        ['code' => 'vvip',   'name' => 'VVIP',   'min' => 8000000],
    ];

    /** Hạng thành viên theo tổng chi tiêu: ['code','name','min','next'=>?array] */
    public static function tier(int $spent): array
    {
        $cur = self::TIERS[0];
        $next = null;
        foreach (self::TIERS as $i => $t) {
            if ($spent >= $t['min']) {
                $cur  = $t;
                $next = self::TIERS[$i + 1] ?? null;
            }
        }
        $cur['next'] = $next;
        return $cur;
    }

    /** Số điểm tối đa được dùng cho đơn có giá trị $payable với số dư $balance */
    public static function maxRedeem(int $payable, int $balance): int
    {
        $cap = intdiv(intdiv($payable * self::MAX_REDEEM_PERCENT, 100), self::POINT_VALUE);
        $max = min($balance, $cap);
        return $max >= self::MIN_REDEEM ? $max : 0;
    }

    /** Điểm tích được: % theo từng loại, tính trên số tiền thực trả (sau giảm giá) */
    public static function calcEarn(int $tickets, int $combos, int $paid): int
    {
        $base = $tickets + $combos;
        if ($base <= 0 || $paid <= 0) {
            return 0;
        }
        $raw = ($tickets * self::EARN_TICKET_PCT + $combos * self::EARN_COMBO_PCT) * $paid; // đơn vị: đ * 100 * base
        return intdiv($raw, $base * 100 * self::POINT_VALUE);
    }

    /** Khóa dòng user và trả về số dư điểm (null nếu user không tồn tại). Gọi TRONG transaction. */
    public static function lockBalance(PDO $pdo, int $userId): ?int
    {
        $st = $pdo->prepare('SELECT points_balance FROM users WHERE id = ? FOR UPDATE');
        $st->execute([$userId]);
        $v = $st->fetchColumn();
        return $v === false ? null : (int) $v;
    }

    /** Ghi nhận điểm dùng + điểm tích cho đơn vừa tạo. Gọi TRONG transaction, sau lockBalance(). */
    public static function settleOrder(PDO $pdo, int $userId, int $orderId, string $orderCode, int $balance, int $used, int $earned, int $paid): int
    {
        if ($used > 0) {
            $balance -= $used;
            self::addTx($pdo, $userId, $orderId, 'redeem', -$used, $balance, "Dùng điểm cho đơn {$orderCode}");
        }
        if ($earned > 0) {
            $balance += $earned;
            self::addTx($pdo, $userId, $orderId, 'earn', $earned, $balance, "Tích điểm đơn {$orderCode}");
        }
        $pdo->prepare('UPDATE users SET points_balance = ?, total_spent = total_spent + ? WHERE id = ?')
            ->execute([$balance, $paid, $userId]);
        return $balance;
    }

    /** Admin điều chỉnh số dư điểm của tài khoản (kèm lưu lịch sử point_transactions) */
    public static function adjustPoints(PDO $pdo, int $userId, int $newBalance, string $note = 'Quản trị viên điều chỉnh điểm'): void
    {
        $newBalance = max(0, $newBalance);
        $pdo->beginTransaction();
        try {
            $curBalance = self::lockBalance($pdo, $userId);
            if ($curBalance === null) {
                $pdo->rollBack();
                return;
            }
            $delta = $newBalance - $curBalance;
            if ($delta !== 0) {
                $pdo->prepare('UPDATE users SET points_balance = ? WHERE id = ?')->execute([$newBalance, $userId]);
                self::addTx($pdo, $userId, null, 'adjust', $delta, $newBalance, $note);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /** Admin hủy đơn đã hoàn tất: trả lại điểm đã dùng, thu hồi điểm đã tích, trừ chi tiêu. Chỉ chạy 1 lần / đơn. */
    public static function refundOrder(PDO $pdo, int $orderId): void
    {
        $st = $pdo->prepare('SELECT id, code, user_id, total, points_used, points_earned FROM orders WHERE id = ? FOR UPDATE');
        $st->execute([$orderId]);
        $o = $st->fetch();
        if (!$o || !$o['user_id']) {
            return;
        }
        $done = $pdo->prepare("SELECT COUNT(*) FROM point_transactions WHERE order_id = ? AND type = 'refund'");
        $done->execute([$orderId]);
        if ((int) $done->fetchColumn() > 0) {
            return;
        }
        $uid = (int) $o['user_id'];
        $balance = self::lockBalance($pdo, $uid);
        if ($balance === null) {
            return;
        }
        $used = (int) $o['points_used'];
        $earned = (int) $o['points_earned'];
        if ($used > 0) {
            $balance += $used;
            self::addTx($pdo, $uid, $orderId, 'refund', $used, $balance, "Hoàn điểm do hủy đơn {$o['code']}");
        }
        if ($earned > 0) {
            $take = min($earned, $balance);   // không để số dư âm
            $balance -= $take;
            self::addTx($pdo, $uid, $orderId, 'adjust', -$take, $balance, "Thu hồi điểm tích do hủy đơn {$o['code']}");
        }
        if ($used === 0 && $earned === 0) {
            self::addTx($pdo, $uid, $orderId, 'refund', 0, $balance, "Hủy đơn {$o['code']} (không có điểm)");
        }
        $pdo->prepare('UPDATE users SET points_balance = ?, total_spent = GREATEST(0, CAST(total_spent AS SIGNED) - ?) WHERE id = ?')
            ->execute([$balance, (int) $o['total'], $uid]);
    }

    private static function addTx(PDO $pdo, int $userId, ?int $orderId, string $type, int $points, int $balanceAfter, string $note): void
    {
        $pdo->prepare('INSERT INTO point_transactions (user_id, order_id, type, points, balance_after, note) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$userId, $orderId, $type, $points, $balanceAfter, $note]);
    }
}
