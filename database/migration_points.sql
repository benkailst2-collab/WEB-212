-- =====================================================================
-- migration_points.sql - Thêm hệ thống TÍCH ĐIỂM & KHUYẾN MÃI cho MovieGo
-- Chạy 1 LẦN trên DB cinema_db cũ (phpMyAdmin -> chọn cinema_db -> Import).
-- Cài mới từ đầu: file cinema_db.sql đã gồm sẵn phần này, không cần chạy file này.
-- =====================================================================
USE cinema_db;

ALTER TABLE users
  ADD COLUMN points_balance INT UNSIGNED NOT NULL DEFAULT 0 AFTER role,
  ADD COLUMN total_spent    BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER points_balance;

ALTER TABLE promotions
  ADD COLUMN description VARCHAR(255) NULL AFTER code;

ALTER TABLE orders
  ADD COLUMN points_used     INT UNSIGNED NOT NULL DEFAULT 0 AFTER discount,
  ADD COLUMN points_discount INT UNSIGNED NOT NULL DEFAULT 0 AFTER points_used,
  ADD COLUMN points_earned   INT UNSIGNED NOT NULL DEFAULT 0 AFTER points_discount;

CREATE TABLE IF NOT EXISTS point_transactions (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED NOT NULL,
  order_id      INT UNSIGNED NULL,
  type          ENUM('earn','redeem','refund','adjust') NOT NULL,
  points        INT NOT NULL COMMENT 'dương = cộng, âm = trừ',
  balance_after INT UNSIGNED NOT NULL,
  note          VARCHAR(255) NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pt_user (user_id, created_at),
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB;

UPDATE users u SET total_spent = (
  SELECT COALESCE(SUM(o.total), 0) FROM orders o WHERE o.user_id = u.id AND o.status = 'completed'
);

DELETE os FROM order_seats os JOIN orders o ON o.id = os.order_id WHERE o.status = 'cancelled';
