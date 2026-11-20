<?php
namespace App\Controllers;

use App\Core\Database;
use PDO;

/**
 * app/Controllers/CinemaController.php
 * Controller xử lý toàn bộ logic cho giao diện Khách hàng MovieGo kết nối CSDL MySQL thật.
 */
class CinemaController
{
    /**
     * Trang chủ MovieGo: Lấy danh sách phim thật từ CSDL, thể loại, phim nổi bật
     */
    public function index()
    {
        $pdo = Database::pdo();
        $search = trim($_GET['q'] ?? '');
        $selectedGenre = trim($_GET['genre'] ?? '');

        // Lấy tất cả thể loại cho bộ lọc
        $genresList = $pdo->query("SELECT * FROM genres ORDER BY name ASC")->fetchAll();

        // Truy vấn phim kèm thể loại
        $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') AS genre_names
                FROM movies m
                LEFT JOIN movie_genres mg ON mg.movie_id = m.id
                LEFT JOIN genres g ON g.id = mg.genre_id";
        
        $params = [];
        $where = [];

        if ($search !== '') {
            $where[] = "(m.title LIKE ? OR m.description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($selectedGenre !== '') {
            $where[] = "g.name = ?";
            $params[] = $selectedGenre;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " GROUP BY m.id ORDER BY m.rating DESC, m.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rawMovies = $stmt->fetchAll();

        $movies = [];
        foreach ($rawMovies as $row) {
            $genres = !empty($row['genre_names']) ? explode(', ', $row['genre_names']) : ['Chưa phân loại'];
            $movies[] = [
                'id'          => (int)$row['id'],
                'title'       => $row['title'],
                'poster'      => $row['poster'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=500',
                'backdrop'    => $row['backdrop'] ?: $row['poster'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1200',
                'genre'       => $genres,
                'duration'    => (int)$row['duration'],
                'rating'      => (float)$row['rating'],
                'status'      => $row['status'],
                'ageRating'   => $row['age_rating'] ?: 'P',
                'description' => $row['description'] ?: 'Chưa có mô tả.',
            ];
        }

        // Tách phim đang chiếu và sắp chiếu
        $nowShowing = array_values(array_filter($movies, fn($m) => $m['status'] === 'now_showing'));
        $comingSoon = array_values(array_filter($movies, fn($m) => $m['status'] === 'coming_soon'));

        // Phim nổi bật ở banner Hero
        $featured = !empty($nowShowing) ? $nowShowing[0] : (!empty($movies) ? $movies[0] : null);

        return view('client/cinema/home', compact(
            'movies',
            'nowShowing',
            'comingSoon',
            'featured',
            'genresList',
            'search',
            'selectedGenre'
        ));
    }

    /**
     * Chi tiết phim & danh sách suất chiếu theo từng rạp
     */
    public function detail()
    {
        $pdo = Database::pdo();
        $movieId = (int)($_GET['id'] ?? 1);

        // Lấy thông tin phim
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ? LIMIT 1");
        $stmt->execute([$movieId]);
        $row = $stmt->fetch();

        if (!$row) {
            redirect('/cinema');
        }

        // Lấy danh sách thể loại của phim
        $genreStmt = $pdo->prepare("SELECT g.name FROM genres g JOIN movie_genres mg ON mg.genre_id = g.id WHERE mg.movie_id = ?");
        $genreStmt->execute([$movieId]);
        $genres = $genreStmt->fetchAll(PDO::FETCH_COLUMN) ?: ['Điện ảnh'];

        $movie = [
            'id'          => (int)$row['id'],
            'title'       => $row['title'],
            'poster'      => $row['poster'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=500',
            'backdrop'    => $row['backdrop'] ?: $row['poster'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1200',
            'genre'       => $genres,
            'duration'    => (int)$row['duration'],
            'rating'      => (float)$row['rating'],
            'releaseDate' => $row['release_date'] ? date('d/m/Y', strtotime($row['release_date'])) : 'Đang cập nhật',
            'director'    => $row['director'] ?: 'Đang cập nhật',
            'cast'        => !empty($row['cast_list']) ? explode(', ', $row['cast_list']) : ['Đang cập nhật'],
            'description' => $row['description'] ?: 'Chưa có tóm tắt nội dung.',
            'status'      => $row['status'],
            'ageRating'   => $row['age_rating'] ?: 'P',
            'language'    => $row['language'] ?: 'Tiếng Việt - Phụ đề',
        ];

        // Lấy danh sách cụm rạp
        $cinemas = $pdo->query("SELECT * FROM cinemas ORDER BY id ASC")->fetchAll();

        // Lấy toàn bộ suất chiếu của phim này
        $showStmt = $pdo->prepare(
            "SELECT s.*, sc.name AS screen_name, c.id AS cinema_id, c.name AS cinema_name
             FROM showtimes s
             JOIN screens sc ON sc.id = s.screen_id
             JOIN cinemas c ON c.id = sc.cinema_id
             WHERE s.movie_id = ?
             ORDER BY s.starts_at ASC"
        );
        $showStmt->execute([$movieId]);
        $rawShows = $showStmt->fetchAll();

        $showtimes = [];
        foreach ($rawShows as $s) {
            $showtimes[] = [
                'id'       => (int)$s['id'],
                'cinemaId' => (int)$s['cinema_id'],
                'screen'   => $s['screen_name'],
                'time'     => date('H:i', strtotime($s['starts_at'])),
                'date'     => date('d/m/Y', strtotime($s['starts_at'])),
                'format'   => $s['format'],
                'price'    => (int)$s['base_price'],
            ];
        }

        return view('client/cinema/detail', compact('movie', 'cinemas', 'showtimes'));
    }

    /**
     * Sơ đồ chọn ghế & combo bắp nước
     */
    public function seats()
    {
        $pdo = Database::pdo();
        $showtimeId = (int)($_GET['showtime_id'] ?? 1);

        // Lấy thông tin suất chiếu
        $stmt = $pdo->prepare(
            "SELECT s.*, m.title AS movie_title, m.poster, m.age_rating,
                    sc.id AS screen_id, sc.name AS screen_name,
                    c.id AS cinema_id, c.name AS cinema_name
             FROM showtimes s
             JOIN movies m ON m.id = s.movie_id
             JOIN screens sc ON sc.id = s.screen_id
             JOIN cinemas c ON c.id = sc.cinema_id
             WHERE s.id = ? LIMIT 1"
        );
        $stmt->execute([$showtimeId]);
        $showtime = $stmt->fetch();

        if (!$showtime) {
            redirect('/cinema');
        }

        // Lấy danh sách ghế đã bán của suất chiếu này từ order_seats
        $soldStmt = $pdo->prepare(
            "SELECT CONCAT(se.row_label, se.seat_number) AS code
             FROM order_seats os
             JOIN seats se ON se.id = os.seat_id
             JOIN orders o ON o.id = os.order_id
             WHERE os.showtime_id = ? AND o.status != 'cancelled'"
        );
        $soldStmt->execute([$showtimeId]);
        $soldSeats = $soldStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        // Lấy danh sách combo bắp nước
        $combos = $pdo->query("SELECT id, name, description AS `desc`, icon, price FROM combos WHERE is_active = 1 ORDER BY id ASC")->fetchAll();

        return view('client/cinema/seats', compact('showtime', 'showtimeId', 'soldSeats', 'combos'));
    }

    /**
     * Thanh toán & Xử lý đặt vé thật lưu vào CSDL
     */
    public function checkout()
    {
        $pdo = Database::pdo();
        $user = currentUser();

        // ── XỬ LÝ POST: KHÁCH HÀNG XÁC NHẬN ĐẶT VÉ & THANH TOÁN ──
        if (isPost()) {
            $showtimeId     = (int)($_POST['showtime_id'] ?? 0);
            $seatsParam     = trim($_POST['seats'] ?? '');
            $combosJson     = trim($_POST['combos'] ?? '{}');
            $customerName   = trim($_POST['customer_name'] ?? '');
            $customerPhone  = trim($_POST['customer_phone'] ?? '');
            $customerEmail  = trim($_POST['customer_email'] ?? '');
            $paymentMethod  = trim($_POST['payment_method'] ?? 'momo');
            $promoCode      = strtoupper(trim($_POST['promo_code'] ?? ''));

            if (!$showtimeId || empty($seatsParam) || empty($customerName) || empty($customerPhone)) {
                redirect('/cinema');
            }

            // Lấy thông tin suất chiếu
            $stmt = $pdo->prepare("SELECT s.*, sc.id as screen_id FROM showtimes s JOIN screens sc ON sc.id = s.screen_id WHERE s.id = ?");
            $stmt->execute([$showtimeId]);
            $showtime = $stmt->fetch();
            if (!$showtime) {
                redirect('/cinema');
            }

            // Tách danh sách ghế đã chọn (ví dụ: D5, D6)
            $seatCodes = array_values(array_filter(array_map('trim', explode(',', $seatsParam))));

            // Tìm thông tin các ghế trong phòng chiếu này
            $placeholders = implode(',', array_fill(0, count($seatCodes), '?'));
            $seatQuery = $pdo->prepare(
                "SELECT se.id, se.row_label, se.seat_number, se.seat_type,
                        st.name AS type_name, st.surcharge
                 FROM seats se
                 JOIN seat_types st ON st.code = se.seat_type
                 WHERE se.screen_id = ? AND CONCAT(se.row_label, se.seat_number) IN ($placeholders)"
            );
            $seatQuery->execute(array_merge([$showtime['screen_id']], $seatCodes));
            $selectedSeats = $seatQuery->fetchAll();

            if (empty($selectedSeats)) {
                redirect('/cinema/seats?showtime_id=' . $showtimeId);
            }

            // Kiểm tra trùng ghế (Ghế đã bị người khác đặt chưa)
            $seatIds = array_column($selectedSeats, 'id');
            $seatIdPlaceholders = implode(',', array_fill(0, count($seatIds), '?'));
            $checkSoldStmt = $pdo->prepare(
                "SELECT se.row_label, se.seat_number
                 FROM order_seats os
                 JOIN seats se ON se.id = os.seat_id
                 JOIN orders o ON o.id = os.order_id
                 WHERE os.showtime_id = ? AND os.seat_id IN ($seatIdPlaceholders) AND o.status != 'cancelled'"
            );
            $checkSoldStmt->execute(array_merge([$showtimeId], $seatIds));
            $conflictSeats = $checkSoldStmt->fetchAll();

            if (!empty($conflictSeats)) {
                $conflictList = implode(', ', array_map(fn($s) => $s['row_label'] . $s['seat_number'], $conflictSeats));
                $_SESSION['checkout_error'] = "Ghế ({$conflictList}) vừa có người đặt. Vui lòng chọn ghế khác.";
                redirect("/cinema/seats?showtime_id={$showtimeId}");
            }

            // Tính tiền vé
            $ticketsTotal = 0;
            $seatsWithPrice = [];
            foreach ($selectedSeats as $s) {
                $price = (int)$showtime['base_price'] + (int)$s['surcharge'];
                $ticketsTotal += $price;
                $seatsWithPrice[] = [
                    'seat_id' => $s['id'],
                    'price'   => $price,
                ];
            }

            // Tính tiền combo
            $combosTotal = 0;
            $combosData = json_decode($combosJson, true) ?: [];
            $combosToInsert = [];
            if (!empty($combosData)) {
                $activeCombos = $pdo->query("SELECT id, name, price FROM combos WHERE is_active = 1")->fetchAll();
                $combosById = array_column($activeCombos, null, 'id');

                foreach ($combosData as $cId => $item) {
                    $qty = (int)($item['qty'] ?? 0);
                    if ($qty > 0 && isset($combosById[$cId])) {
                        $price = (int)$combosById[$cId]['price'];
                        $combosTotal += $price * $qty;
                        $combosToInsert[] = [
                            'combo_id' => $cId,
                            'quantity' => $qty,
                            'price'    => $price,
                        ];
                    }
                }
            }

            // Kiểm tra mã giảm giá
            $discount = 0;
            $promotionId = null;
            if ($promoCode !== '') {
                $promoStmt = $pdo->prepare("SELECT * FROM promotions WHERE code = ? AND is_active = 1 LIMIT 1");
                $promoStmt->execute([$promoCode]);
                $promo = $promoStmt->fetch();
                if ($promo) {
                    $promotionId = $promo['id'];
                    if ($promo['discount_type'] === 'percent') {
                        $discount = (int)round(($ticketsTotal + $combosTotal) * ($promo['discount_value'] / 100));
                    } else {
                        $discount = (int)$promo['discount_value'];
                    }
                }
            }

            $grandTotal = max(0, $ticketsTotal + $combosTotal - $discount);

            // Sinh mã đơn hàng duy nhất
            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));

            // Bắt đầu Transaction lưu CSDL
            $pdo->beginTransaction();
            try {
                $orderStmt = $pdo->prepare(
                    "INSERT INTO orders (code, user_id, showtime_id, customer_name, customer_phone, customer_email,
                                         promotion_id, tickets_total, combos_total, discount, total, payment_method, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')"
                );
                $orderStmt->execute([
                    $orderCode,
                    $user ? $user['id'] : null,
                    $showtimeId,
                    $customerName,
                    $customerPhone,
                    $customerEmail ?: null,
                    $promotionId,
                    $ticketsTotal,
                    $combosTotal,
                    $discount,
                    $grandTotal,
                    $paymentMethod,
                ]);
                $orderId = (int)$pdo->lastInsertId();

                // Lưu danh sách ghế vào order_seats
                $seatInsert = $pdo->prepare("INSERT INTO order_seats (order_id, showtime_id, seat_id, price) VALUES (?, ?, ?, ?)");
                foreach ($seatsWithPrice as $swp) {
                    $seatInsert->execute([$orderId, $showtimeId, $swp['seat_id'], $swp['price']]);
                }

                // Lưu danh sách combo vào order_combos
                if (!empty($combosToInsert)) {
                    $comboInsert = $pdo->prepare("INSERT INTO order_combos (order_id, combo_id, quantity, price) VALUES (?, ?, ?, ?)");
                    foreach ($combosToInsert as $cti) {
                        $comboInsert->execute([$orderId, $cti['combo_id'], $cti['quantity'], $cti['price']]);
                    }
                }

                $pdo->commit();

                // Đặt vé thành công, chuyển hướng sang xem vé điện tử
                redirect('/cinema/ticket?code=' . urlencode($orderCode));
            } catch (\Exception $e) {
                $pdo->rollBack();
                $_SESSION['checkout_error'] = 'Lỗi trong quá trình tạo đơn: ' . $e->getMessage();
                redirect("/cinema/seats?showtime_id={$showtimeId}");
            }
        }

        // ── XỬ LÝ GET: HIỂN THỊ TRANG XÁC NHẬN THANH TOÁN ──
        $showtimeId = (int)($_GET['showtime_id'] ?? 1);
        $seatsParam = $_GET['seats'] ?? 'D5, D6';
        $combosParam = $_GET['combos'] ?? '{}';

        // Lấy thông tin suất chiếu
        $stmt = $pdo->prepare(
            "SELECT s.*, m.title AS movie_title, m.poster, m.age_rating,
                    sc.id AS screen_id, sc.name AS screen_name,
                    c.id AS cinema_id, c.name AS cinema_name, c.address AS cinema_address
             FROM showtimes s
             JOIN movies m ON m.id = s.movie_id
             JOIN screens sc ON sc.id = s.screen_id
             JOIN cinemas c ON c.id = sc.cinema_id
             WHERE s.id = ? LIMIT 1"
        );
        $stmt->execute([$showtimeId]);
        $showtime = $stmt->fetch();

        if (!$showtime) {
            redirect('/cinema');
        }

        // Tính tiền vé từ danh sách ghế
        $seatList = array_values(array_filter(array_map('trim', explode(',', $seatsParam)), fn($s) => preg_match('/^[A-Z][0-9]{1,2}$/', $s)));
        if (empty($seatList)) $seatList = ['D5', 'D6'];

        $ticketTotal = 0;
        if (!empty($seatList)) {
            $placeholders = implode(',', array_fill(0, count($seatList), '?'));
            $sQuery = $pdo->prepare("SELECT se.*, st.surcharge FROM seats se JOIN seat_types st ON st.code = se.seat_type WHERE se.screen_id = ? AND CONCAT(se.row_label, se.seat_number) IN ($placeholders)");
            $sQuery->execute(array_merge([$showtime['screen_id']], $seatList));
            $foundSeats = $sQuery->fetchAll();
            foreach ($foundSeats as $fs) {
                $ticketTotal += (int)$showtime['base_price'] + (int)$fs['surcharge'];
            }
        }
        if ($ticketTotal === 0) {
            $ticketTotal = count($seatList) * (int)$showtime['base_price'];
        }

        // Tính tiền combo từ combosParam
        $comboTotal = 0;
        $combosArray = json_decode($combosParam, true) ?: [];
        $comboSummary = [];
        if (!empty($combosArray)) {
            $cRows = $pdo->query("SELECT id, name, price FROM combos WHERE is_active = 1")->fetchAll();
            $cById = array_column($cRows, null, 'id');
            foreach ($combosArray as $cId => $item) {
                $qty = (int)($item['qty'] ?? 0);
                if ($qty > 0 && isset($cById[$cId])) {
                    $price = (int)$cById[$cId]['price'];
                    $comboTotal += $price * $qty;
                    $comboSummary[] = $cById[$cId]['name'] . " (x{$qty})";
                }
            }
        }

        $grandTotal = $ticketTotal + $comboTotal;

        return view('client/cinema/checkout', compact(
            'showtime',
            'showtimeId',
            'seatList',
            'seatsParam',
            'combosParam',
            'comboSummary',
            'ticketTotal',
            'comboTotal',
            'grandTotal',
            'user'
        ));
    }

    /**
     * Vé điện tử & Tra cứu vé theo mã hoặc số điện thoại
     */
    public function ticket()
    {
        $pdo = Database::pdo();
        $code = trim($_GET['code'] ?? $_POST['search_code'] ?? '');
        $phone = trim($_POST['search_phone'] ?? '');

        $order = null;
        $seats = [];
        $combos = [];
        $searchResults = [];
        $searched = false;

        // Nếu tra cứu theo số điện thoại
        if ($phone !== '') {
            $searched = true;
            $stmt = $pdo->prepare(
                "SELECT o.*, m.title AS movie_title, s.starts_at, c.name AS cinema_name
                 FROM orders o
                 JOIN showtimes s ON s.id = o.showtime_id
                 JOIN movies m ON m.id = s.movie_id
                 JOIN screens sc ON sc.id = s.screen_id
                 JOIN cinemas c ON c.id = sc.cinema_id
                 WHERE o.customer_phone = ?
                 ORDER BY o.created_at DESC"
            );
            $stmt->execute([$phone]);
            $searchResults = $stmt->fetchAll();
            if (!empty($searchResults)) {
                $code = $searchResults[0]['code'];
            }
        }

        // Nếu có mã đơn
        if ($code !== '') {
            $searched = true;
            $stmt = $pdo->prepare(
                "SELECT o.*, s.starts_at, s.format, m.title AS movie_title, m.poster, m.age_rating,
                        sc.name AS screen_name, c.name AS cinema_name, c.address AS cinema_address
                 FROM orders o
                 JOIN showtimes s ON s.id = o.showtime_id
                 JOIN movies m ON m.id = s.movie_id
                 JOIN screens sc ON sc.id = s.screen_id
                 JOIN cinemas c ON c.id = sc.cinema_id
                 WHERE o.code = ? LIMIT 1"
            );
            $stmt->execute([$code]);
            $order = $stmt->fetch();

            if ($order) {
                // Lấy ghế của đơn này
                $sStmt = $pdo->prepare(
                    "SELECT os.*, se.row_label, se.seat_number, se.seat_type
                     FROM order_seats os
                     JOIN seats se ON se.id = os.seat_id
                     WHERE os.order_id = ?
                     ORDER BY se.row_label, se.seat_number"
                );
                $sStmt->execute([$order['id']]);
                $seats = $sStmt->fetchAll();

                // Lấy combo của đơn này
                $cStmt = $pdo->prepare(
                    "SELECT oc.*, c.name, c.icon
                     FROM order_combos oc
                     JOIN combos c ON c.id = oc.combo_id
                     WHERE oc.order_id = ?"
                );
                $cStmt->execute([$order['id']]);
                $combos = $cStmt->fetchAll();
            }
        }

        return view('client/cinema/ticket', compact(
            'order',
            'seats',
            'combos',
            'code',
            'phone',
            'searchResults',
            'searched'
        ));
    }

    /**
     * Tùy chọn: Chạy toàn bộ ứng dụng bằng React SPA
     */
    public function spa()
    {
        return view('client/cinema/react_spa');
    }
}
