<?php
include "../config/database.php"; // Kết nối cơ sở dữ liệu

// Xử lý logic đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register-btn'])) {
    $sdt = trim($_POST['customer-phone']);
    $email = trim($_POST['customer-email-register']);
    $password = $_POST['customer-password-register'];
    $confirm_password = $_POST['customer-confirm-password-register'];

    // Kiểm tra dữ liệu đầu vào
    if (empty($sdt)) {
        $error_message = "Vui lòng nhập số điện thoại!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $sdt)) {
        $error_message = "Số điện thoại không hợp lệ!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email không hợp lệ!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Mật khẩu không khớp!";
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kiểm tra email đã tồn tại chưa
        $check_sql = "SELECT * FROM khachhang WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email đã được sử dụng!";
        } else {
            // Kiểm tra số điện thoại đã tồn tại chưa
            $check_phone_sql = "SELECT * FROM khachhang WHERE sdt = ?";
            $stmt = $conn->prepare($check_phone_sql);
            $stmt->bind_param("s", $sdt);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Số điện thoại đã được sử dụng!";
            } else {
                // Lưu thông tin vào database
                $insert_sql = "INSERT INTO khachhang (sdt, email, matkhau) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("sss", $sdt, $email, $hashed_password);

                if ($stmt->execute()) {
                    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập
                    exit();
                } else {
                    $error_message = "Đã xảy ra lỗi! Vui lòng thử lại.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div id="account-content">
        <section id="login-registration-form">
            <div class="user-box">
                <div id="register">
                    <div class="font-size-20">Đăng ký</div>
                    <?php if (isset($error_message)): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    <form action="register.php" method="POST">
                        <div>
                            <label for="customer-phone">Số điện thoại</label>
                            <input type="text" id="customer-phone" name="customer-phone" placeholder="Số điện thoại" required>
                        </div>
                        <div>
                            <label for="customer-email-register">Email</label>
                            <input type="email" id="customer-email-register" name="customer-email-register" placeholder="Email" required>
                        </div>
                        <div>
                            <label for="customer-password-register">Mật khẩu</label>
                            <input type="password" id="customer-password-register" name="customer-password-register" placeholder="Tạo mật khẩu" required>
                        </div>
                        <div>
                            <label for="customer-confirm-password-register">Xác nhận mật khẩu</label>
                            <input type="password" id="customer-confirm-password-register" name="customer-confirm-password-register" placeholder="Xác nhận mật khẩu" required>
                        </div>
                        <button type="submit" name="register-btn">Đăng ký</button>
                    </form>
                    <div class="font-size-14 js-login">
                        <span>Đã có tài khoản?</span>
                        <span><a href="login.php">Đăng nhập</a></span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>