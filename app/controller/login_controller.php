<?php
session_start();
require_once '../model/account.php';
require_once '../config/database.php';
//require('../../public/index.php');

// Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
if (isLoggedIn()) {
    header("Location: ../../public/index.php");
    exit();
}

// Xử lý form đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['customer-login']);
    $password = $_POST['customer-password-login'];

    // Xác thực đầu vào
    if (empty($login_input)) {
        $_SESSION['login_error'] = "Vui lòng nhập email hoặc số điện thoại!";
    } elseif (empty($password)) {
        $_SESSION['login_error'] = "Vui lòng nhập mật khẩu!";
    } else {
        // Thực hiện đăng nhập - không cần truyền $conn vì đã được sử dụng toàn cục trong model
        $result = loginUser($login_input, $password);

        if ($result['success']) {
            // Gán thông tin khách hàng để truyền qua JS
            $_SESSION['login_info'] = [
                'makh' => $result['user']['makh'],
                'tenkhachhang' => $result['user']['tenkhachhang'],
            ];

            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                header("Location: ../admin/admin.php");
            } else {
                $redirect = $_SESSION['redirect_url'] ?? '../../public/index.php';
                unset($_SESSION['redirect_url']);
                header("Location: $redirect");
            }
            exit();
        } else {
            // Đăng nhập thất bại
            $_SESSION['login_error'] = $result['message'];
        }
    }

    // Nếu có lỗi, chuyển hướng lại form đăng nhập
    header("Location: ../views/login.php");
    exit();
}

// Chuyển hướng về trang đăng nhập nếu truy cập trực tiếp
header("Location: ../views/login.php");
exit();
?>