<?php
namespace App\Controllers;

/**
 * app/Controllers/AdminCinemaController.php
 * Controller mẫu điều hướng toàn bộ giao diện Quản trị MovieGo Admin
 */
class AdminCinemaController
{
    // Dashboard Thống kê
    public function index()
    {
        return view('admin/cinema/dashboard');
    }

    // Quản lý danh sách phim
    public function movies()
    {
        return view('admin/cinema/movies');
    }

    // Quản lý cụm rạp
    public function cinemas()
    {
        return view('admin/cinema/cinemas');
    }

    // Quản lý lịch & suất chiếu
    public function showtimes()
    {
        return view('admin/cinema/showtimes');
    }

    // Quản lý đơn hàng đặt vé
    public function orders()
    {
        return view('admin/cinema/orders');
    }
}
