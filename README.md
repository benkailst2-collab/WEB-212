# HƯỚNG DẪN TÍCH HỢP GIAO DIỆN MOVIEGO VÀO PROJECT BACKEND (PHP MVC / CODEIGNITER)

Dự án này đã được nhúng và đồng bộ hóa 100% vào cấu trúc thư mục backend của bạn.

---

## 1. Cấu trúc thư mục đã tích hợp

```
project/
├── app/
│   ├── Controllers/
│   │   ├── CinemaController.php          # Controller Client (Trang chủ, Chi tiết, Chọn ghế, Thanh toán, Vé)
│   │   └── AdminCinemaController.php     # Controller Admin (Dashboard, Phim, Rạp, Suất chiếu, Đơn hàng)
│   └── Views/
│       ├── client/
│       │   └── cinema/
│       │       ├── layouts/
│       │       │   ├── header.php        # Dark Mode Navbar & Head
│       │       │   └── footer.php        # Dark Mode Footer & Lucide init
│       │       ├── home.php              # Hero Banner + Tab Đang/Sắp chiếu + Movie Grid
│       │       ├── detail.php            # Chi tiết phim, trailer, chọn rạp & suất chiếu
│       │       ├── seats.php             # Sơ đồ ghế tương tác đổi màu + Modal bắp nước
│       │       ├── checkout.php          # Countdown 10p, form thông tin, cổng thanh toán
│       │       ├── ticket.php            # Vé răng cưa cao cấp, mã QR SVG động, in PDF
│       │       └── react_spa.php         # Tùy chọn: Nhúng nguyên khối React SPA
│       └── admin/
│           └── cinema/
│               ├── layouts/
│               │   ├── sidebar.php       # Light Mode Sidebar & Head
│               │   └── footer.php        # Light Mode Footer & scripts
│               ├── dashboard.php         # 4 KPI cards, biểu đồ doanh thu 7 ngày (Chart.js), top phim
│               ├── movies.php            # Bảng quản lý phim + Modal Thêm/Sửa/Xóa
│               ├── cinemas.php           # Bảng quản lý rạp + Modal Thêm/Sửa/Xóa
│               ├── showtimes.php         # Bảng lịch chiếu (2D/3D/IMAX) + Modal
│               └── orders.php            # Bảng đơn hàng + Modal chi tiết đơn
└── public/
    └── cinema/                           # Thư mục chứa tài nguyên build React SPA
        ├── assets/
        └── index.html
```

---

## 2. Hai cách sử dụng linh hoạt:

### Cách A: Dùng trực tiếp Native PHP Views (Khuyên dùng)
Gọi hàm `return view(...)` trong controller:
```php
// Client
return view('client/cinema/home');
return view('client/cinema/detail');
return view('client/cinema/seats');
return view('client/cinema/checkout');
return view('client/cinema/ticket');

// Admin
return view('admin/cinema/dashboard');
return view('admin/cinema/movies');
return view('admin/cinema/cinemas');
return view('admin/cinema/showtimes');
return view('admin/cinema/orders');
```

### Cách B: Dùng React SPA Build
Chỉ cần 1 route trong controller:
```php
return view('client/cinema/react_spa');
```
Trang sẽ tự động tải toàn bộ bundle React 19 + Tailwind CSS v4 mà không cần chạy `npm run dev`.

---

## 3. Đăng ký / Đăng nhập

| Đường dẫn | Chức năng |
|---|---|
| `/register` | Khách hàng đăng ký (tự đăng nhập sau khi đăng ký) |
| `/login` | Khách hàng đăng nhập |
| `/logout` | Đăng xuất khách (POST, nút trên header) |
| `/admin/login` | Đăng nhập quản trị (chỉ tài khoản `role = admin`) |
| `/admin/logout` | Đăng xuất quản trị (POST) |

- Mọi trang `/admin/cinema/*` yêu cầu đăng nhập admin, nếu chưa sẽ chuyển về `/admin/login`.
- Cấu hình kết nối CSDL: `app/Config/database.php` (mặc định `root`, không mật khẩu).
- Import `database/cinema_db.sql` trước. Tài khoản mẫu: `admin@moviego.vn` / `password`.
- Mật khẩu lưu bằng `password_hash`; form có CSRF; sai 5 lần bị khóa 60 giây.
