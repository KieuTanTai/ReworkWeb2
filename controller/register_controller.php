<?php
session_start();
require_once '../model/account.php';
require_once '../config/database.php';
// Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
if (isLoggedIn()) {
    header("Location: ../../index.php");
    exit();
}

// Xử lý form đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenkhachhang = isset($_POST['customer-full-name']) ? trim($_POST['customer-full-name']) : '';
    $sdt = isset($_POST['customer-phone']) ? trim($_POST['customer-phone']) : '';
    $email = isset($_POST['customer-email-register']) ? trim($_POST['customer-email-register']) : '';
    $password = isset($_POST['customer-password-register']) ? $_POST['customer-password-register'] : '';
    $confirm_password = isset($_POST['customer-confirm-password-register']) ? $_POST['customer-confirm-password-register'] : '';
    $diachi = isset($_POST['customer-address']) ? trim($_POST['customer-address']) : '';
    
    // Kiểm tra dữ liệu đầu vào
    if (empty($tenkhachhang)) {
        $_SESSION['register_error'] = "Vui lòng nhập tên khách hàng!";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Email không hợp lệ!";
    } elseif (empty($password) || strlen($password) < 6) {
        $_SESSION['register_error'] = "Mật khẩu phải có ít nhất 6 ký tự!";
    } elseif ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Mật khẩu không khớp!";
    } else {
        // Thực hiện đăng ký
        $result = registerUser($tenkhachhang, $email, $password, $sdt, $diachi);
        
        if ($result['success']) {
            // Đăng ký thành công, tự động đăng nhập
            $loginResult = loginUser($email, $password);
            
            if ($loginResult['success']) {
                // Chuyển về trang chủ
                header("Location: ../public/index.php");
                exit();
            } else {
                // Nếu đăng nhập thất bại, chuyển đến trang đăng nhập
                $_SESSION['login_message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header("Location: ../views/login.php");
                exit();
            }
        } else {
            // Đăng ký thất bại
            $_SESSION['register_error'] = $result['message'];
        }
    }
    
    // Nếu có lỗi, chuyển hướng lại form đăng ký
    header("Location: ../views/register.php");
    exit();
}

// Chuyển hướng về trang đăng ký nếu truy cập trực tiếp
header("Location: ../views/register.php");
exit();
?>