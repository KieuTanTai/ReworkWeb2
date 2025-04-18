'use strict';

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý đăng nhập với modal (nếu có)
    // Xử lý đăng nhập với modal (nếu có)
const loginBtn = document.querySelectorAll('.js-login');
loginBtn.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '/Web2/app/views/login.php'; // Đường dẫn tuyệt đối
    });
});

// Xử lý đăng ký với modal (nếu có)
const registerBtn = document.querySelectorAll('.js-register');
registerBtn.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '/Web2/app/views/register.php'; // Đường dẫn tuyệt đối
    });
});

    // Xử lý đăng xuất
    const logoutBtn = document.querySelectorAll('.js-signout');
    logoutBtn.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                window.location.href = 'app/controllers/logout_controller.php';
            }
        });
    });

    // Hiển thị user-account dropdown khi click vào avatar/tên user
    const userAccount = document.getElementById('user-account');
    if (userAccount) {
        userAccount.addEventListener('click', function(e) {
            const navAccount = userAccount.querySelector('.nav-account');
            if (navAccount) {
                navAccount.classList.toggle('show');
                // Đóng dropdown khi click bên ngoài
                document.addEventListener('click', function closeDropdown(event) {
                    if (!userAccount.contains(event.target)) {
                        navAccount.classList.remove('show');
                        document.removeEventListener('click', closeDropdown);
                    }
                });
            }
        });
    }
    
    // Mở rộng hành vi nếu cần
    const navAccount = document.querySelector('.nav-account');
    if (navAccount) {
        // CSS để hiển thị dropdown khi hover
        navAccount.addEventListener('mouseenter', function() {
            this.classList.add('show');
        });
        
        navAccount.addEventListener('mouseleave', function() {
            this.classList.remove('show');
        });
    }
});