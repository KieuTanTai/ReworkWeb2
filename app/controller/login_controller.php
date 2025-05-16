<?php
session_start();
require_once '../model/account.php';
require_once '../config/database.php';
//require('../../public/index.php');

// Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
if (isLoggedIn()) {
    if (isStaff()) {
        header("Location: ../views/admin.php"); // Đường dẫn tới admin.php trong thư mục views
    } else {
        header("Location: ../../public/index.php");
    }
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
        // Thử đăng nhập với tài khoản nhân viên trước
        $staff_result = loginStaff($login_input, $password);
        
        if ($staff_result['success']) {
            // Đăng nhập nhân viên thành công
            header("Location: ../views/admin.php"); // Đường dẫn tới admin.php trong thư mục views
            exit();
        } else {
            // Thử đăng nhập với tài khoản khách hàng
            $result = loginUser($login_input, $password);

            if ($result['success']) {
                // Đăng nhập khách hàng thành công
                $redirect = $_SESSION['redirect_url'] ?? '../../public/index.php';
                unset($_SESSION['redirect_url']);
                header("Location: $redirect");
                exit();
            } else {
                // Đăng nhập thất bại
                $_SESSION['login_error'] = $result['message'];
            }
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