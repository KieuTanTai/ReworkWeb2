<?php
session_start();

// Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: ../../public/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Mobile Phone Store</title>
    <link rel="shortcut icon" href="../../public/assets/images/icons/web_logo/main-logo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../../assets/fonts/fontawesome-6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/index.css" />
    <link rel="stylesheet" href="../../assets/css/responsive.css" />
    <link rel="stylesheet" href="../../public/assets/css/login.css">
</head>
<body>
    <div id="account-content">
        <section id="login-registration-form">
            <div class="user-box">
                <a href="../../public/index.php" class="close-btn">×</a>
                <div id="login">
                    <div class="font-size-20">Đăng nhập</div>
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="error-message">
                            <?php 
                                echo htmlspecialchars($_SESSION['login_error']); 
                                unset($_SESSION['login_error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['login_message'])): ?>
                        <div class="success-message">
                            <?php 
                                echo htmlspecialchars($_SESSION['login_message']); 
                                unset($_SESSION['login_message']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="../controller/login_controller.php" method="POST">
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
                        <a href="../../public/index.php">← Trở về trang chủ</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script>
        // Các validation client-side có thể thêm vào đây
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('#login form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const loginInput = document.getElementById('customer-login');
                    const passwordInput = document.getElementById('customer-password-login');
                    
                    if (!loginInput.value.trim()) {
                        e.preventDefault();
                        showError('Vui lòng nhập email hoặc số điện thoại!');
                        return;
                    }
                    
                    if (!passwordInput.value) {
                        e.preventDefault();
                        showError('Vui lòng nhập mật khẩu!');
                        return;
                    }
                });
            }
            
            function showError(message) {
                let errorElement = document.querySelector('.error-message');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'error-message';
                    const form = document.querySelector('form');
                    form.insertBefore(errorElement, form.firstChild);
                }
                
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
        });
    </script>
</body>
</html>