<?php
namespace App\Controllers;

use App\Core\Database;

/**
 * app/Controllers/AuthController.php
 * Đăng ký / Đăng nhập / Đăng xuất cho KHÁCH HÀNG
 */
class AuthController
{
    public function login()
    {
        if (currentUser()) {
            redirect(isAdmin() ? '/admin/cinema' : '/cinema');
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
                    if ($user && password_verify($pass, $user['password_hash'])) {
                        loginUser($user);
                        redirect($user['role'] === 'admin' ? '/admin/cinema' : '/cinema');
                    }
                    loginFailed();
                    $error = 'Email hoặc mật khẩu không đúng.';
                } catch (\PDOException $e) {
                    $error = DB_ERROR_MSG;
                }
            }
        }

        return view('client/auth/login', compact('error', 'old'));
    }

    public function register()
    {
        if (currentUser()) {
            redirect('/cinema');
        }

        $errors = [];
        $old    = ['full_name' => '', 'email' => '', 'phone' => ''];

        if (isPost()) {
            $old['full_name'] = trim($_POST['full_name'] ?? '');
            $old['email']     = strtolower(trim($_POST['email'] ?? ''));
            $old['phone']     = preg_replace('/\s+/', '', $_POST['phone'] ?? '');
            $pass             = (string) ($_POST['password'] ?? '');
            $pass2            = (string) ($_POST['password_confirm'] ?? '');

            if (!csrf_check()) {
                $errors['form'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
            }
            $len = str_len($old['full_name']);
            if ($len < 2 || $len > 100) {
                $errors['full_name'] = 'Họ tên phải từ 2 đến 100 ký tự.';
            }
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || strlen($old['email']) > 150) {
                $errors['email'] = 'Email không hợp lệ.';
            }
            if ($old['phone'] !== '' && !preg_match('/^0\d{9}$/', $old['phone'])) {
                $errors['phone'] = 'Số điện thoại phải gồm 10 số, bắt đầu bằng 0.';
            }
            if (strlen($pass) < 6) {
                $errors['password'] = 'Mật khẩu tối thiểu 6 ký tự.';
            } elseif ($pass !== $pass2) {
                $errors['password_confirm'] = 'Mật khẩu nhập lại không khớp.';
            }

            if (!$errors) {
                try {
                    if (findUserByEmail($old['email'])) {
                        $errors['email'] = 'Email này đã được đăng ký.';
                    } else {
                        $pdo = Database::pdo();
                        $st  = $pdo->prepare(
                            "INSERT INTO users (full_name, email, phone, password_hash, role)
                             VALUES (?, ?, ?, ?, 'customer')"
                        );
                        $st->execute([
                            $old['full_name'],
                            $old['email'],
                            $old['phone'] !== '' ? $old['phone'] : null,
                            password_hash($pass, PASSWORD_DEFAULT),
                        ]);
                        loginUser([
                            'id'        => $pdo->lastInsertId(),
                            'full_name' => $old['full_name'],
                            'email'     => $old['email'],
                            'role'      => 'customer',
                        ]);
                        redirect('/cinema');
                    }
                } catch (\PDOException $e) {
                    $errors['form'] = ($e->getCode() === '23000')
                        ? 'Email này đã được đăng ký.'
                        : DB_ERROR_MSG;
                }
            }
        }

        return view('client/auth/register', compact('errors', 'old'));
    }

    public function logout()
    {
        if (isPost() && csrf_check()) {
            logoutUser();
        }
        redirect('/cinema');
    }
}
