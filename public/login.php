<?php
session_start();
include "../config/database.php"; // Kết nối cơ sở dữ liệu

// Xử lý logic đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-btn'])) {
    $login_input = trim($_POST['customer-login']);
    $password_login = $_POST['customer-password-login'];

    // Kiểm tra định dạng đầu vào
    if (empty($login_input)) {
        $error_message = "Vui lòng nhập email hoặc số điện thoại!";
    } elseif (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        // Đầu vào là email
        $sql = "SELECT * FROM khachhang WHERE email = ?";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $login_input)) {
        $error_message = "Tài khoản không hợp lệ!";
    } else {
        // Đầu vào là số điện thoại
        $sql = "SELECT * FROM khachhang WHERE sdt = ?";
    }

    // Nếu không có thông báo lỗi, thực hiện truy vấn
    if (!isset($error_message)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password_login, $user['matkhau'])) {
                $_SESSION['user_id'] = $user['makh'];
                header("Location: ../views/dashboard.php");
                exit();
            } else {
                $error_message = "Mật khẩu không đúng!";
            }
        } else {
            $error_message = "Email hoặc số điện thoại không tồn tại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div id="account-content">
        <section id="login-registration-form">
            <div class="user-box">
                <a href="index.php" class="close-btn">×</a>
                <div id="login">
                    <div class="font-size-20">Đăng nhập</div>
                    <?php if (isset($error_message)): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    <form action="login.php" method="POST">
                        <div>
                            <label for="customer-login">Tài khoản</label>
                            <input type="text" id="customer-login" name="customer-login" placeholder="Email hoặc Số điện thoại" required>
                        </div>
                        <div>
                            <label for="customer-password-login">Mật khẩu</label>
                            <input type="password" id="customer-password-login" name="customer-password-login" placeholder="Mật khẩu" required>
                        </div>
                        <button type="submit" name="login-btn">Đăng nhập</button>
                    </form>
                    <div class="font-size-14 js-register">
                        <div></div>
                        <span>Không có tài khoản?</span>
                        <span><a href="register.php">Đăng ký</a></span>
                    </div>
                    <div class="font-size-14 margin-y-12">
                        <a href="index.php">← Trở về trang chủ</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>