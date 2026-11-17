-- =====================================================================
-- cinema_db.sql - CSDL cho dự án MovieGo (đặt vé xem phim)
-- MySQL 8 / MariaDB 10.4+ (XAMPP, Laragon đều chạy được)
-- Cách dùng: phpMyAdmin -> Import -> chọn file này (file tự tạo DB cinema_db)
-- Dữ liệu mẫu khớp với mảng mock trong các view admin/client hiện tại.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS cinema_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinema_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_combos, order_seats, orders, promotions, combos,
                     showtimes, seats, seat_types, screens, cinemas,
                     movie_genres, genres, movies, users;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- 1. NGƯỜI DÙNG (khách hàng + admin)
-- ---------------------------------------------------------------------
CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name     VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  phone         VARCHAR(20)  NULL,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 2. PHIM + THỂ LOẠI
-- ---------------------------------------------------------------------
CREATE TABLE movies (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(200) NOT NULL,
  poster       VARCHAR(500) NULL,
  backdrop     VARCHAR(500) NULL,
  trailer_url  VARCHAR(500) NULL,
  description  TEXT NULL,
  duration     SMALLINT UNSIGNED NOT NULL COMMENT 'phút',
  rating       DECIMAL(3,1) NOT NULL DEFAULT 0.0 COMMENT 'thang 10',
  age_rating   VARCHAR(10) NOT NULL DEFAULT 'P' COMMENT 'P, T13, T16, T18',
  release_date DATE NULL,
  director     VARCHAR(150) NULL,
  cast_list    VARCHAR(500) NULL COMMENT 'diễn viên, cách nhau bằng dấu phẩy',
  language     VARCHAR(100) NULL,
  status       ENUM('now_showing','coming_soon','stopped') NOT NULL DEFAULT 'coming_soon',
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_movies_status (status)
) ENGINE=InnoDB;

CREATE TABLE genres (
  id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE movie_genres (
  movie_id INT UNSIGNED NOT NULL,
  genre_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (movie_id, genre_id),
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
  FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 3. CỤM RẠP -> PHÒNG CHIẾU -> GHẾ
-- ---------------------------------------------------------------------
CREATE TABLE cinemas (
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name    VARCHAR(120) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city    VARCHAR(60)  NOT NULL,
  phone   VARCHAR(20)  NULL
) ENGINE=InnoDB;

CREATE TABLE screens (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cinema_id INT UNSIGNED NOT NULL,
  name      VARCHAR(50) NOT NULL COMMENT 'Phòng 1, Phòng 2...',
  UNIQUE KEY uq_screen (cinema_id, name),
  FOREIGN KEY (cinema_id) REFERENCES cinemas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Loại ghế: giá ghế = giá cơ bản của suất chiếu + phụ thu
-- (2D 75.000 -> thường 75.000 / VIP 95.000 / đôi 160.000 như seats.php)
CREATE TABLE seat_types (
  code      VARCHAR(20) PRIMARY KEY,
  name      VARCHAR(50) NOT NULL,
  surcharge INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE seats (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  screen_id   INT UNSIGNED NOT NULL,
  row_label   CHAR(1) NOT NULL,
  seat_number TINYINT UNSIGNED NOT NULL,
  seat_type   VARCHAR(20) NOT NULL DEFAULT 'standard',
  UNIQUE KEY uq_seat (screen_id, row_label, seat_number),
  FOREIGN KEY (screen_id) REFERENCES screens(id) ON DELETE CASCADE,
  FOREIGN KEY (seat_type) REFERENCES seat_types(code)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 4. SUẤT CHIẾU
-- ---------------------------------------------------------------------
CREATE TABLE showtimes (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  movie_id   INT UNSIGNED NOT NULL,
  screen_id  INT UNSIGNED NOT NULL,
  starts_at  DATETIME NOT NULL,
  format     ENUM('2D','3D','IMAX') NOT NULL DEFAULT '2D',
  base_price INT UNSIGNED NOT NULL COMMENT 'giá ghế thường (VNĐ)',
  INDEX idx_show_movie (movie_id, starts_at),
  INDEX idx_show_screen (screen_id, starts_at),
  FOREIGN KEY (movie_id)  REFERENCES movies(id)  ON DELETE CASCADE,
  FOREIGN KEY (screen_id) REFERENCES screens(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 5. BẮP NƯỚC + KHUYẾN MÃI
-- ---------------------------------------------------------------------
CREATE TABLE combos (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  description VARCHAR(255) NULL,
  icon        VARCHAR(20)  NULL,
  price       INT UNSIGNED NOT NULL,
  is_active   TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE promotions (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code           VARCHAR(30) NOT NULL UNIQUE,
  discount_type  ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
  discount_value INT UNSIGNED NOT NULL,
  expires_at     DATETIME NULL,
  is_active      TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 6. ĐƠN ĐẶT VÉ
-- ---------------------------------------------------------------------
CREATE TABLE orders (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code           VARCHAR(30) NOT NULL UNIQUE COMMENT 'vd ORD-20261101-001',
  user_id        INT UNSIGNED NULL COMMENT 'NULL = khách vãng lai',
  showtime_id    INT UNSIGNED NOT NULL,
  customer_name  VARCHAR(100) NOT NULL,
  customer_phone VARCHAR(20)  NOT NULL,
  customer_email VARCHAR(150) NULL,
  promotion_id   INT UNSIGNED NULL,
  tickets_total  INT UNSIGNED NOT NULL DEFAULT 0,
  combos_total   INT UNSIGNED NOT NULL DEFAULT 0,
  discount       INT UNSIGNED NOT NULL DEFAULT 0,
  total          INT UNSIGNED NOT NULL DEFAULT 0,
  payment_method ENUM('momo','vnpay','card','zalopay') NOT NULL DEFAULT 'momo',
  status         ENUM('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  expires_at     DATETIME NULL COMMENT 'hết hạn giữ ghế (10 phút)',
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_orders_status (status, created_at),
  FOREIGN KEY (user_id)      REFERENCES users(id)      ON DELETE SET NULL,
  FOREIGN KEY (showtime_id)  REFERENCES showtimes(id),
  FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Mỗi ghế trong 1 suất chỉ bán được 1 lần (chống đặt trùng ghế).
-- Khi hủy đơn / hết hạn giữ ghế: xóa các dòng order_seats của đơn đó để nhả ghế.
CREATE TABLE order_seats (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id    INT UNSIGNED NOT NULL,
  showtime_id INT UNSIGNED NOT NULL,
  seat_id     INT UNSIGNED NOT NULL,
  price       INT UNSIGNED NOT NULL COMMENT 'giá tại thời điểm đặt',
  UNIQUE KEY uq_showtime_seat (showtime_id, seat_id),
  FOREIGN KEY (order_id)    REFERENCES orders(id)    ON DELETE CASCADE,
  FOREIGN KEY (showtime_id) REFERENCES showtimes(id),
  FOREIGN KEY (seat_id)     REFERENCES seats(id)
) ENGINE=InnoDB;

CREATE TABLE order_combos (
  id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  combo_id INT UNSIGNED NOT NULL,
  quantity SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  price    INT UNSIGNED NOT NULL COMMENT 'đơn giá tại thời điểm đặt',
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (combo_id) REFERENCES combos(id)
) ENGINE=InnoDB;


-- =====================================================================
-- DỮ LIỆU MẪU
-- =====================================================================

-- Tài khoản mẫu: mật khẩu của cả 3 tài khoản là "password" (nhớ đổi khi triển khai thật)
INSERT INTO users (full_name, email, phone, password_hash, role) VALUES
('Quản trị viên',  'admin@moviego.vn',        '0900000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Nguyễn Văn An',  'nguyenvanan@gmail.com',    '0912345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer'),
('Trần Thị Bích',  'tranthibich@gmail.com',    '0987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');

INSERT INTO movies (id, title, poster, backdrop, description, duration, rating, age_rating, release_date, director, cast_list, language, status) VALUES
(1, 'Avengers: Doomsday',
 'https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDhRkZoNNsM4hXx.jpg',
 'https://image.tmdb.org/t/p/original/9BBTo63ANSmhC4e6r62OJFuK2GL.jpg',
 'Các siêu anh hùng Avengers tập hợp lần cuối cùng để đối đầu với mối đe dọa Doctor Doom.',
 165, 8.5, 'T13', '2026-05-01', 'Joe Russo, Anthony Russo',
 'Robert Downey Jr., Chris Evans, Scarlett Johansson, Tom Holland', 'Tiếng Anh - Phụ đề Tiếng Việt', 'now_showing'),
(2, 'Dune: Phần Ba',
 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg',
 'https://image.tmdb.org/t/p/original/xOMo8BRK7PfcJv9JCnx7s5hj0PX.jpg',
 'Paul Atreides tiếp tục cuộc hành trình sử thi trên hành tinh cát Arrakis.',
 178, 9.0, 'T16', NULL, NULL, NULL, 'Tiếng Anh - Phụ đề Tiếng Việt', 'now_showing'),
(3, 'Lật Mặt 9: Bí Mật Gia Tộc',
 'https://image.tmdb.org/t/p/w500/wXqWR7dHncNRbxoEGybEy7QTe9h.jpg',
 'https://image.tmdb.org/t/p/original/nTPFkLUARmo1bYHfkfdNpRKgEOs.jpg',
 'Phần tiếp theo của loạt phim bom tấn Việt Nam với nhiều bí mật gia tộc hấp dẫn.',
 135, 7.8, 'T13', NULL, NULL, NULL, 'Tiếng Việt', 'now_showing'),
(4, 'Spider-Man: Brand New Day',
 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
 'https://image.tmdb.org/t/p/original/9DeGfFIqjph5CBFVQrD6wv9S10v.jpg',
 'Peter Parker đối mặt với một thực tại mới nơi không ai nhớ anh là Spider-Man.',
 148, 8.2, 'T13', NULL, NULL, NULL, 'Tiếng Anh - Phụ đề Tiếng Việt', 'coming_soon'),
(5, 'Mai - Phần 2',
 'https://image.tmdb.org/t/p/w500/lqoMzCcZYEFK729d6qzt349fB4o.jpg',
 NULL, NULL,
 128, 7.5, 'T16', NULL, NULL, NULL, 'Tiếng Việt', 'now_showing'),
(6, 'The Batman: Part II',
 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg',
 NULL, NULL,
 170, 8.8, 'T16', NULL, NULL, NULL, 'Tiếng Anh - Phụ đề Tiếng Việt', 'coming_soon');

INSERT INTO genres (id, name) VALUES
(1,'Hành động'),(2,'Khoa học viễn tưởng'),(3,'Hài hước'),(4,'Phiêu lưu'),
(5,'Tâm lý'),(6,'Tình cảm'),(7,'Tội phạm'),(8,'Chính kịch');

INSERT INTO movie_genres (movie_id, genre_id) VALUES
(1,1),(1,2),(1,4),
(2,2),(2,8),
(3,1),(3,3),
(4,1),(4,4),
(5,5),(5,6),
(6,1),(6,7);

INSERT INTO cinemas (id, name, address, city) VALUES
(1, 'MovieGo Quận 1',   '271 Nguyễn Trãi, Quận 1',       'TP.HCM'),
(2, 'MovieGo Quận 7',   '469 Nguyễn Hữu Thọ, Quận 7',    'TP.HCM'),
(3, 'MovieGo Hà Nội',   'Vincom Center, 191 Bà Triệu',   'Hà Nội'),
(4, 'MovieGo Đà Nẵng',  'Vincom Plaza, 910A Ngô Quyền',  'Đà Nẵng');

-- Số phòng theo trang admin: Q1 = 8, Q7 = 10, Hà Nội = 12, Đà Nẵng = 6
INSERT INTO screens (cinema_id, name)
SELECT c.id, CONCAT('Phòng ', n.num)
FROM cinemas c
JOIN (SELECT 1 num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
      UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
      UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) n
  ON n.num <= CASE c.id WHEN 1 THEN 8 WHEN 2 THEN 10 WHEN 3 THEN 12 ELSE 6 END;

INSERT INTO seat_types (code, name, surcharge) VALUES
('standard', 'Ghế thường', 0),
('vip',      'Ghế VIP',    20000),
('couple',   'Ghế đôi',    85000);

-- Mỗi phòng: hàng A-H x 12 ghế (giống seats.php). D,E,F = VIP; H = ghế đôi
INSERT INTO seats (screen_id, row_label, seat_number, seat_type)
SELECT s.id, r.row_label, n.num,
       CASE WHEN r.row_label IN ('D','E','F') THEN 'vip'
            WHEN r.row_label = 'H'            THEN 'couple'
            ELSE 'standard' END
FROM screens s
CROSS JOIN (SELECT 'A' row_label UNION ALL SELECT 'B' UNION ALL SELECT 'C' UNION ALL SELECT 'D'
            UNION ALL SELECT 'E' UNION ALL SELECT 'F' UNION ALL SELECT 'G' UNION ALL SELECT 'H') r
CROSS JOIN (SELECT 1 num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
            UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
            UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) n;

-- Suất chiếu mẫu (ngày chiếu = hôm nay lúc import)
INSERT INTO showtimes (id, movie_id, screen_id, starts_at, format, base_price) VALUES
(1, 1, (SELECT id FROM screens WHERE cinema_id=1 AND name='Phòng 1'), CONCAT(CURDATE(),' 10:00:00'), '2D',   75000),
(2, 1, (SELECT id FROM screens WHERE cinema_id=1 AND name='Phòng 2'), CONCAT(CURDATE(),' 13:30:00'), '3D',   95000),
(3, 1, (SELECT id FROM screens WHERE cinema_id=1 AND name='Phòng 1'), CONCAT(CURDATE(),' 16:00:00'), 'IMAX', 120000),
(4, 2, (SELECT id FROM screens WHERE cinema_id=1 AND name='Phòng 3'), CONCAT(CURDATE(),' 09:30:00'), 'IMAX', 120000),
(5, 3, (SELECT id FROM screens WHERE cinema_id=1 AND name='Phòng 4'), CONCAT(CURDATE(),' 10:00:00'), '2D',   70000),
(6, 5, (SELECT id FROM screens WHERE cinema_id=2 AND name='Phòng 5'), CONCAT(CURDATE(),' 14:30:00'), '2D',   65000),
(7, 2, (SELECT id FROM screens WHERE cinema_id=2 AND name='Phòng 2'), CONCAT(CURDATE(),' 19:00:00'), '2D',   75000);

INSERT INTO combos (id, name, description, icon, price) VALUES
(1, 'Combo Solo',    '1 Bắp lớn + 1 Nước lớn',    '🍿',   79000),
(2, 'Combo Couple',  '1 Bắp lớn + 2 Nước lớn',    '🍿🥤',  109000),
(3, 'Combo Family',  '2 Bắp lớn + 4 Nước ngọt',   '🍿🍿🥤', 199000),
(4, 'Nước ngọt lớn', 'Coca-Cola / Pepsi / Sprite', '🥤',   35000);

-- Mã giảm giá gợi ý ở checkout.php (CINEMA20, VIP10) - mức giảm là giả định, sửa theo ý bạn
INSERT INTO promotions (code, discount_type, discount_value) VALUES
('CINEMA20', 'percent', 20),
('VIP10',    'percent', 10);

-- Đơn hàng mẫu (4 đơn như trang admin)
INSERT INTO orders (code, user_id, showtime_id, customer_name, customer_phone, customer_email,
                    tickets_total, combos_total, total, payment_method, status, created_at) VALUES
('ORD-20261101-001', 2, 1, 'Nguyễn Văn An', '0912345678', 'nguyenvanan@gmail.com', 190000, 109000, 299000, 'momo',    'completed', '2026-11-01 08:30:00'),
('ORD-20261101-002', 3, 7, 'Trần Thị Bích', '0987654321', 'tranthibich@gmail.com', 285000, 199000, 484000, 'vnpay',   'completed', '2026-11-01 10:15:00'),
('ORD-20261102-001', NULL, 5, 'Lê Minh Tuấn',    '0903112233', NULL,                  140000, 158000, 298000, 'card',    'pending',   '2026-11-02 07:45:00'),
('ORD-20261102-002', NULL, 6, 'Phạm Hoàng Yến',  '0934556677', NULL,                  130000,      0, 130000, 'zalopay', 'cancelled', '2026-11-02 11:00:00');

-- Ghế của từng đơn (giá = giá cơ bản suất chiếu + phụ thu loại ghế)
-- Đơn ...-002 (đã hủy) không giữ ghế để ghế được nhả lại
INSERT INTO order_seats (order_id, showtime_id, seat_id, price)
SELECT o.id, o.showtime_id, se.id, st.base_price + sty.surcharge
FROM orders o
JOIN showtimes  st  ON st.id = o.showtime_id
JOIN seats      se  ON se.screen_id = st.screen_id
JOIN seat_types sty ON sty.code = se.seat_type
WHERE (o.code = 'ORD-20261101-001' AND CONCAT(se.row_label, se.seat_number) IN ('D5','D6'))
   OR (o.code = 'ORD-20261101-002' AND CONCAT(se.row_label, se.seat_number) IN ('E3','E4','E5'))
   OR (o.code = 'ORD-20261102-001' AND CONCAT(se.row_label, se.seat_number) IN ('B7','B8'));

INSERT INTO order_combos (order_id, combo_id, quantity, price)
SELECT o.id, v.combo_id, v.qty, c.price
FROM (SELECT 'ORD-20261101-001' code, 2 combo_id, 1 qty
      UNION ALL SELECT 'ORD-20261101-002', 3, 1
      UNION ALL SELECT 'ORD-20261102-001', 1, 2) v
JOIN orders o ON o.code = v.code
JOIN combos c ON c.id = v.combo_id;
