<?php
session_start();

// Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Mobile Phone Store</title>
    <link rel="shortcut icon" href="../../assets/images/icons/web_logo/main-logo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../../assets/fonts/fontawesome-6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/index.css" />
    <link rel="stylesheet" href="../../assets/css/responsive.css" />
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div id="account-content">
        <section id="login-registration-form">
            <div class="user-box">
                <a href="../../index.php" class="close-btn">×</a>
                <div id="register">
                    <div class="font-size-20">Đăng ký</div>
                    <?php if (isset($_SESSION['register_error'])): ?>
                        <div class="error-message">
                            <?php 
                                echo htmlspecialchars($_SESSION['register_error']); 
                                unset($_SESSION['register_error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <form action="../controller/register_controller.php" method="POST">
                        <!-- <div>
                            <label for="customer-first-name">Họ</label>
                            <input type="text" id="customer-first-name" name="customer-first-name" placeholder="Họ" required>
                        </div> -->
                        <div>
                            <label for="customer-full-name">Tên</label>
                            <input type="text" id="customer-full-name" name="customer-full-name" placeholder="Tên" required>
                        </div>
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
                    <div class="font-size-14 margin-y-12">
                        <a href="index.php">← Trở về trang chủ</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script>
        // Các validation client-side có thể thêm vào đây
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.querySelector('#register form');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    // const firstNameInput = document.getElementById('customer-first-name');
                    const fullNameInput = document.getElementById('customer-full-name');
                    const phoneInput = document.getElementById('customer-phone');
                    const emailInput = document.getElementById('customer-email-register');
                    const passwordInput = document.getElementById('customer-password-register');
                    const confirmPasswordInput = document.getElementById('customer-confirm-password-register');
                    
                    let hasError = false;
                    
                    // if (!firstNameInput.value.trim()) {
                    //     e.preventDefault();
                    //     showError('Vui lòng nhập họ!');
                    //     hasError = true;
                    // }
                    
                    if (!fullNameInput.value.trim()) {
                        e.preventDefault();
                        showError('Vui lòng nhập tên!');
                        hasError = true;
                    }
                    
                    if (!phoneInput.value.trim()) {
                        e.preventDefault();
                        showError('Vui lòng nhập số điện thoại!');
                        hasError = true;
                    }
                    
                    if (!/^[0-9]{10,15}$/.test(phoneInput.value.trim())) {
                        e.preventDefault();
                        showError('Số điện thoại không hợp lệ!');
                        hasError = true;
                    }
                    
                    if (!emailInput.value.trim() || !isValidEmail(emailInput.value.trim())) {
                        e.preventDefault();
                        showError('Email không hợp lệ!');
                        hasError = true;
                    }
                    
                    if (!passwordInput.value || passwordInput.value.length < 6) {
                        e.preventDefault();
                        showError('Mật khẩu phải có ít nhất 6 ký tự!');
                        hasError = true;
                    }
                    
                    if (passwordInput.value !== confirmPasswordInput.value) {
                        e.preventDefault();
                        showError('Mật khẩu không khớp!');
                        hasError = true;
                    }
                    
                    if (hasError) {
                        return false;
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
            
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
</body>
</html>