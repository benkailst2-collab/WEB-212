<?php
namespace App\Controllers;

/**
 * app/Controllers/CinemaController.php
 * Controller mẫu điều hướng toàn bộ giao diện Đặt vé xem phim MovieGo (Client)
 */
class CinemaController
{
    // Trang chủ MovieGo
    public function index()
    {
        return view('client/cinema/home');
    }

    // Chi tiết phim & suất chiếu
    public function detail()
    {
        return view('client/cinema/detail');
    }

    // Sơ đồ chọn ghế & combo bắp nước
    public function seats()
    {
        return view('client/cinema/seats');
    }

    // Trang thanh toán
    public function checkout()
    {
        return view('client/cinema/checkout');
    }

    // Vé điện tử & QR Code check-in
    public function ticket()
    {
        return view('client/cinema/ticket');
    }

    // Tùy chọn: Chạy toàn bộ ứng dụng bằng React SPA
    public function spa()
    {
        return view('client/cinema/react_spa');
    }
}
