<?php
namespace App\Controllers;

/**
 * app/Controllers/AdminAuthController.php
 * Đăng nhập / Đăng xuất cho QUẢN TRỊ VIÊN (chỉ tài khoản role = admin)
 */
class AdminAuthController
{
    public function login()
    {
        if (isAdmin()) {
            redirect('/admin/cinema');
        }

        $error = '';
        $old   = ['email' => ''];

        if (isPost()) {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $pass  = (string) ($_POST['password'] ?? '');
            $old['email'] = $email;

            if (!csrf_check()) {
                $error = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
            } elseif ($wait = loginLockedSeconds()) {
                $error = "Bạn nhập sai quá nhiều lần. Vui lòng thử lại sau {$wait} giây.";
            } else {
                try {
                    $user = findUserByEmail($email);
                    if ($user && $user['role'] === 'admin' && password_verify($pass, $user['password_hash'])) {
                        loginUser($user);
                        redirect('/admin/cinema');
                    }
                    loginFailed();
                    $error = 'Sai email/mật khẩu hoặc tài khoản không có quyền quản trị.';
                } catch (\PDOException $e) {
                    $error = DB_ERROR_MSG;
                }
            }
        }

        return view('admin/auth/login', compact('error', 'old'));
    }

    public function logout()
    {
        if (isPost() && csrf_check()) {
            logoutUser();
        }
        redirect('/admin/login');
    }
}
