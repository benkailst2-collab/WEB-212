<?php
namespace App\Controllers;

use App\Core\Database;
use PDO;

/**
 * app/Controllers/AdminCinemaController.php
 * Controller xử lý Quản trị Hệ thống MovieGo Admin kết nối CSDL MySQL thật.
 */
class AdminCinemaController
{
    /**
     * Dashboard Thống kê thời gian thực từ CSDL
     */
    public function index()
    {
        $pdo = Database::pdo();

        // 1. Chỉ số tổng hợp
        $totalRevenue = (float)$pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'completed'")->fetchColumn();
        $totalOrders  = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $totalTickets = (int)$pdo->query(
            "SELECT COUNT(*) FROM order_seats os 
             JOIN orders o ON o.id = os.order_id 
             WHERE o.status = 'completed'"
        )->fetchColumn();
        $totalUsers   = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
        $totalMovies  = (int)$pdo->query("SELECT COUNT(*) FROM movies WHERE status = 'now_showing'")->fetchColumn();

        // 2. Đơn hàng gần đây
        $recentOrders = $pdo->query(
            "SELECT o.*, m.title AS movie_title, s.starts_at, c.name AS cinema_name
             FROM orders o
             JOIN showtimes s ON s.id = o.showtime_id
             JOIN movies m ON m.id = s.movie_id
             JOIN screens sc ON sc.id = s.screen_id
             JOIN cinemas c ON c.id = sc.cinema_id
             ORDER BY o.created_at DESC LIMIT 6"
        )->fetchAll();

        // 3. Dữ liệu biểu đồ doanh thu 7 ngày gần nhất
        $revenue7Days = $pdo->query(
            "SELECT DATE(created_at) AS order_date, COALESCE(SUM(total), 0) AS daily_total
             FROM orders
             WHERE status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
             GROUP BY DATE(created_at)
             ORDER BY order_date ASC"
        )->fetchAll();

        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $chartLabels[] = date('d/m', strtotime($d));
            $found = false;
            foreach ($revenue7Days as $r) {
                if ($r['order_date'] === $d) {
                    $chartData[] = (int)$r['daily_total'];
                    $found = true;
                    break;
                }
            }
            if (!$found) $chartData[] = 0;
        }

        return view('admin/cinema/dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalTickets',
            'totalUsers',
            'totalMovies',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Quản lý Phim: Xem danh sách, Thêm mới, Chỉnh sửa, Xóa phim vào CSDL
     */
    public function movies()
    {
        $pdo = Database::pdo();
        $msg = '';

        if (isPost()) {
            $action = $_POST['action'] ?? '';

            if ($action === 'add') {
                $title       = trim($_POST['title'] ?? '');
                $poster      = trim($_POST['poster'] ?? '');
                $backdrop    = trim($_POST['backdrop'] ?? '');
                $duration    = (int)($_POST['duration'] ?? 120);
                $rating      = (float)($_POST['rating'] ?? 8.0);
                $ageRating   = trim($_POST['age_rating'] ?? 'P');
                $director    = trim($_POST['director'] ?? '');
                $castList    = trim($_POST['cast_list'] ?? '');
                $language    = trim($_POST['language'] ?? 'Tiếng Việt');
                $status      = trim($_POST['status'] ?? 'now_showing');
                $description = trim($_POST['description'] ?? '');

                if ($title !== '') {
                    $stmt = $pdo->prepare(
                        "INSERT INTO movies (title, poster, backdrop, duration, rating, age_rating, director, cast_list, language, status, description)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->execute([
                        $title, $poster ?: null, $backdrop ?: null, $duration, $rating, $ageRating,
                        $director ?: null, $castList ?: null, $language, $status, $description
                    ]);
                    $msg = 'Đã thêm phim mới thành công!';
                }
            } elseif ($action === 'edit') {
                $id          = (int)($_POST['id'] ?? 0);
                $title       = trim($_POST['title'] ?? '');
                $duration    = (int)($_POST['duration'] ?? 120);
                $rating      = (float)($_POST['rating'] ?? 8.0);
                $ageRating   = trim($_POST['age_rating'] ?? 'P');
                $status      = trim($_POST['status'] ?? 'now_showing');
                $poster      = trim($_POST['poster'] ?? '');
                $description = trim($_POST['description'] ?? '');

                if ($id && $title !== '') {
                    $stmt = $pdo->prepare(
                        "UPDATE movies SET title = ?, duration = ?, rating = ?, age_rating = ?, status = ?, poster = ?, description = ? WHERE id = ?"
                    );
                    $stmt->execute([$title, $duration, $rating, $ageRating, $status, $poster ?: null, $description, $id]);
                    $msg = 'Đã cập nhật thông tin phim!';
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
                    $stmt->execute([$id]);
                    $msg = 'Đã xóa phim thành công!';
                }
            }
        }

        // Lấy danh sách phim kèm số lượng suất chiếu
        $movies = $pdo->query(
            "SELECT m.*, GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genre_names,
                    COUNT(DISTINCT s.id) AS showtimes_count
             FROM movies m
             LEFT JOIN movie_genres mg ON mg.movie_id = m.id
             LEFT JOIN genres g ON g.id = mg.genre_id
             LEFT JOIN showtimes s ON s.movie_id = m.id
             GROUP BY m.id
             ORDER BY m.id DESC"
        )->fetchAll();

        return view('admin/cinema/movies', compact('movies', 'msg'));
    }

    /**
     * Quản lý Cụm Rạp: Thêm, xóa, xem danh sách
     */
    public function cinemas()
    {
        $pdo = Database::pdo();
        $msg = '';

        if (isPost()) {
            $action = $_POST['action'] ?? '';
            if ($action === 'add') {
                $name    = trim($_POST['name'] ?? '');
                $address = trim($_POST['address'] ?? '');
                $city    = trim($_POST['city'] ?? '');
                $phone   = trim($_POST['phone'] ?? '');

                if ($name !== '' && $address !== '') {
                    $stmt = $pdo->prepare("INSERT INTO cinemas (name, address, city, phone) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $address, $city, $phone]);
                    $cinemaId = $pdo->lastInsertId();

                    // Tự động tạo 2 phòng chiếu mẫu
                    $screenStmt = $pdo->prepare("INSERT INTO screens (cinema_id, name) VALUES (?, ?)");
                    $screenStmt->execute([$cinemaId, 'Phòng 1']);
                    $screenStmt->execute([$cinemaId, 'Phòng 2']);

                    $msg = 'Đã thêm cụm rạp mới thành công!';
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM cinemas WHERE id = ?");
                    $stmt->execute([$id]);
                    $msg = 'Đã xóa cụm rạp!';
                }
            }
        }

        // Lấy danh sách rạp kèm số lượng phòng chiếu và tổng số ghế
        $cinemas = $pdo->query(
            "SELECT c.*, COUNT(DISTINCT sc.id) AS screens_count, COUNT(DISTINCT se.id) AS seats_count
             FROM cinemas c
             LEFT JOIN screens sc ON sc.cinema_id = c.id
             LEFT JOIN seats se ON se.screen_id = sc.id
             GROUP BY c.id
             ORDER BY c.id ASC"
        )->fetchAll();

        return view('admin/cinema/cinemas', compact('cinemas', 'msg'));
    }

    /**
     * Quản lý Lịch & Suất Chiếu
     */
    public function showtimes()
    {
        $pdo = Database::pdo();
        $msg = '';

        if (isPost()) {
            $action = $_POST['action'] ?? '';
            if ($action === 'add') {
                $movieId   = (int)($_POST['movie_id'] ?? 0);
                $screenId  = (int)($_POST['screen_id'] ?? 0);
                $startsAt  = trim($_POST['starts_at'] ?? '');
                $format    = trim($_POST['format'] ?? '2D');
                $basePrice = (int)($_POST['base_price'] ?? 75000);

                if ($movieId && $screenId && !empty($startsAt)) {
                    $stmt = $pdo->prepare(
                        "INSERT INTO showtimes (movie_id, screen_id, starts_at, format, base_price)
                         VALUES (?, ?, ?, ?, ?)"
                    );
                    $stmt->execute([$movieId, $screenId, $startsAt, $format, $basePrice]);
                    $msg = 'Đã tạo suất chiếu mới thành công!';
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM showtimes WHERE id = ?");
                    $stmt->execute([$id]);
                    $msg = 'Đã xóa suất chiếu!';
                }
            }
        }

        // Lấy danh sách suất chiếu
        $showtimes = $pdo->query(
            "SELECT s.*, m.title AS movie_title, c.name AS cinema_name, sc.name AS screen_name
             FROM showtimes s
             JOIN movies m ON m.id = s.movie_id
             JOIN screens sc ON sc.id = s.screen_id
             JOIN cinemas c ON c.id = sc.cinema_id
             ORDER BY s.starts_at DESC"
        )->fetchAll();

        // Lấy danh sách phim và phòng chiếu để dùng trong Modal tạo suất chiếu
        $moviesList = $pdo->query("SELECT id, title FROM movies ORDER BY title ASC")->fetchAll();
        $screensList = $pdo->query(
            "SELECT sc.id, sc.name AS screen_name, c.name AS cinema_name
             FROM screens sc
             JOIN cinemas c ON c.id = sc.cinema_id
             ORDER BY c.id, sc.name ASC"
        )->fetchAll();

        return view('admin/cinema/showtimes', compact('showtimes', 'moviesList', 'screensList', 'msg'));
    }

    /**
     * Quản lý Đơn hàng: Cập nhật trạng thái, Hủy đơn
     */
    public function orders()
    {
        $pdo = Database::pdo();
        $msg = '';

        if (isPost()) {
            $action = $_POST['action'] ?? '';
            if ($action === 'update_status') {
                $id = (int)($_POST['id'] ?? 0);
                $status = trim($_POST['status'] ?? 'completed');
                if ($id && in_array($status, ['pending', 'completed', 'cancelled'])) {
                    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $id]);
                    $msg = "Đã cập nhật trạng thái đơn sang '{$status}'!";
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
                    $stmt->execute([$id]);
                    $msg = 'Đã xóa đơn hàng!';
                }
            }
        }

        // Lấy danh sách toàn bộ đơn hàng
        $orders = $pdo->query(
            "SELECT o.*, m.title AS movie_title, c.name AS cinema_name, s.starts_at,
                    GROUP_CONCAT(CONCAT(se.row_label, se.seat_number) SEPARATOR ', ') AS seats_list
             FROM orders o
             JOIN showtimes s ON s.id = o.showtime_id
             JOIN movies m ON m.id = s.movie_id
             JOIN screens sc ON sc.id = s.screen_id
             JOIN cinemas c ON c.id = sc.cinema_id
             LEFT JOIN order_seats os ON os.order_id = o.id
             LEFT JOIN seats se ON se.id = os.seat_id
             GROUP BY o.id
             ORDER BY o.created_at DESC"
        )->fetchAll();

        return view('admin/cinema/orders', compact('orders', 'msg'));
    }

    /**
     * Quản lý Người dùng & Khách hàng
     */
    public function users()
    {
        $pdo = Database::pdo();
        $msg = '';

        if (isPost()) {
            $action = $_POST['action'] ?? '';
            $id = (int)($_POST['id'] ?? 0);

            if ($action === 'change_role' && $id) {
                $role = trim($_POST['role'] ?? 'customer');
                if (in_array($role, ['customer', 'admin'])) {
                    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
                    $stmt->execute([$role, $id]);
                    $msg = 'Đã cập nhật vai trò người dùng!';
                }
            } elseif ($action === 'delete' && $id) {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $msg = 'Đã xóa người dùng!';
            }
        }

        $users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

        return view('admin/cinema/users', compact('users', 'msg'));
    }
}
