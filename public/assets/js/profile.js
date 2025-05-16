"use strict";
import * as Bridge from "./bridges.js";
import { hiddenException } from "./interfaces.js";
import { isLoggedIn, getCurrentUser } from "./auth.js";

/**
 * Điều hướng đến trang thông tin cá nhân
 */
async function handleProfileNavigation() {
    const profileButtons = Bridge.default().getProfileBtn();
    const navProfile = Bridge.default().getHeaderUserInfo();
    addEventForProfileButtons(profileButtons);
    addEventForProfileButtons([navProfile]);
}

function loadUserInfoFromSession() {
    // Đầu tiên thử lấy từ sessionStorage
    let user = getCurrentUser();
    
    if (!user || !user.makh) {
        // Nếu không có trong sessionStorage, lấy từ server
        fetch('../app/controller/user_profile_controller.php', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Lỗi khi lấy thông tin người dùng:', data.error);
                return;
            }
            
            displayUserInfo(data);
        })
        .catch(error => {
            console.error('Lỗi khi gọi API:', error);
        });
    } else {
        // Nếu có trong sessionStorage, hiển thị luôn
        displayUserInfo(user);
    }
}

function addEventForProfileButtons(buttons) {
    buttons.forEach((button) => {
        button.addEventListener("click", function(event) {
            event.preventDefault();
            
            // if (!isLoggedIn()) {
            //     alert("Vui lòng đăng nhập để xem thông tin cá nhân");
            //     window.location.href = "../app/views/login.php";
            //     return;
            // }
            
            hiddenException("profile-content");
            
            // Cập nhật URL
            const currentURL = window.location.origin + window.location.pathname;
            history.pushState({}, "", currentURL + "?type=profile");
            
            // Tải thông tin người dùng
            loadUserInfoFromSession();
            
            // Thiết lập các chức năng của profile
            setupTabNavigation();
            setupPasswordToggle();
            setupInfoForm();
            setupPasswordForm();
            
            // Cuộn về đầu trang
            window.scrollTo(0, 0);
        });
    });
}


/**
 * Hiển thị thông tin người dùng
 */
function displayUserInfo(user) {
    // Hiển thị trong tab thông tin
    const displayFullname = document.getElementById('display-fullname');
    const displayPhone = document.getElementById('display-phone');
    const displayEmail = document.getElementById('display-email');
    const displayAddress = document.getElementById('display-address');
    const displayJoinDate = document.getElementById('display-joindate');
    
    if (displayFullname) displayFullname.textContent = user.tenkhachhang || user.user_name || 'Chưa cập nhật';
    if (displayPhone) displayPhone.textContent = user.sdt || user.user_phone || 'Chưa cập nhật';
    if (displayEmail) displayEmail.textContent = user.email || user.user_email || 'Chưa cập nhật';
    if (displayAddress) displayAddress.textContent = user.diachi || 'Chưa cập nhật';
    if (displayJoinDate) displayJoinDate.textContent = user.ngaythamgia ? new Date(user.ngaythamgia).toLocaleDateString('vi-VN') : 'Chưa cập nhật';
    
    // Điền vào form chỉnh sửa
    const fullnameInput = document.getElementById('profile-fullname');
    const phoneInput = document.getElementById('profile-phone');
    const emailInput = document.getElementById('profile-email');
    const addressInput = document.getElementById('profile-address');
    
    if (fullnameInput) fullnameInput.value = user.tenkhachhang || user.user_name || '';
    if (phoneInput) phoneInput.value = user.sdt || user.user_phone || '';
    if (emailInput) emailInput.value = user.email || user.user_email || '';
    if (addressInput) addressInput.value = user.diachi || '';
}

/**
 * Thiết lập điều hướng tab
 */
function setupTabNavigation() {
    const menuItems = document.querySelectorAll('.profile-menu-item');
    const profileTabs = document.querySelectorAll('.profile-tab');
    
    menuItems.forEach(item => {
        if (item.classList.contains('js-signout')) return; // Bỏ qua nút đăng xuất
        
        item.addEventListener('click', () => {
            // Cập nhật trạng thái active cho menu
            menuItems.forEach(mi => mi.classList.remove('active'));
            item.classList.add('active');
            
            // Hiển thị tab tương ứng
            const tabId = item.getAttribute('data-tab');
            profileTabs.forEach(tab => tab.classList.remove('active'));
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
}

/**
 * Thiết lập toggle hiển thị mật khẩu
 */
function setupPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const passwordField = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

/**
 * Thiết lập form cập nhật thông tin
 */
function setupInfoForm() {
    const infoForm = document.getElementById('profile-info-form');
    
    if (!infoForm) return;
    
    infoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Lấy dữ liệu
        const tenkhachhang = document.getElementById('profile-fullname').value;
        const sdt = document.getElementById('profile-phone').value;
        const email = document.getElementById('profile-email').value;
        const diachi = document.getElementById('profile-address').value;
        
        // Kiểm tra dữ liệu
        if (sdt && !/^[0-9]{10,11}$/.test(sdt)) {
            alert('Số điện thoại không hợp lệ!');
            return;
        }
        
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Email không hợp lệ!');
            return;
        }
        
        // Tạo form data
        const formData = new FormData();
        formData.append('action', 'update_profile');
        formData.append('tenkhachhang', tenkhachhang);
        formData.append('sdt', sdt);
        formData.append('email', email);
        formData.append('diachi', diachi);
        
        // Gửi request
        fetch('../app/controller/user_profile_controller.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cập nhật thông tin thành công!');
                
                // Cập nhật dữ liệu hiển thị
                displayUserInfo(data.data);
                
                // Cập nhật session storage nếu cần
                try {
                    const currentUser = JSON.parse(sessionStorage.getItem('loginAccount')) || {};
                    currentUser.tenkhachhang = data.data.tenkhachhang;
                    currentUser.user_name = data.data.tenkhachhang;
                    currentUser.sdt = data.data.sdt;
                    currentUser.user_phone = data.data.sdt;
                    currentUser.email = data.data.email;
                    currentUser.user_email = data.data.email;
                    currentUser.diachi = data.data.diachi;
                    
                    sessionStorage.setItem('loginAccount', JSON.stringify(currentUser));
                } catch (error) {
                    console.error('Lỗi khi cập nhật session storage:', error);
                }
                
                // Chuyển về tab thông tin
                document.querySelector('.profile-menu-item[data-tab="user-info"]').click();
            } else {
                alert('Cập nhật thông tin thất bại: ' + (data.error || 'Đã xảy ra lỗi'));
            }
        })
        .catch(error => {
            console.error('Lỗi khi gọi API:', error);
            alert('Đã xảy ra lỗi khi cập nhật thông tin. Vui lòng thử lại sau.');
        });
    });
}

/**
 * Thiết lập form đổi mật khẩu
 */
function setupPasswordForm() {
    const passwordForm = document.getElementById('change-password-form');
    
    if (!passwordForm) return;
    
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Lấy dữ liệu
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        
        // Kiểm tra dữ liệu
        if (!currentPassword) {
            alert('Vui lòng nhập mật khẩu hiện tại!');
            return;
        }
        
        if (!newPassword) {
            alert('Vui lòng nhập mật khẩu mới!');
            return;
        }
        
        if (newPassword.length < 6) {
            alert('Mật khẩu mới phải có ít nhất 6 ký tự!');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
            return;
        }
        
        // Tạo form data
        const formData = new FormData();
        formData.append('action', 'change_password');
        formData.append('current_password', currentPassword);
        formData.append('new_password', newPassword);
        formData.append('confirm_password', confirmPassword);
        
        // Gửi request
        fetch('../app/controller/user_profile_controller.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đổi mật khẩu thành công!');
                passwordForm.reset();
                
                // Chuyển về tab thông tin
                document.querySelector('.profile-menu-item[data-tab="user-info"]').click();
            } else {
                alert('Đổi mật khẩu thất bại: ' + (data.error || 'Đã xảy ra lỗi'));
            }
        })
        .catch(error => {
            console.error('Lỗi khi gọi API:', error);
            alert('Đã xảy ra lỗi khi đổi mật khẩu. Vui lòng thử lại sau.');
        });
    });
}

/**
 * Kiểm tra URL khi tải trang
 */
function checkProfileUrlState() {
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    
    if (type === 'profile') {
        handleProfileNavigation();
    }
}

// Khởi tạo khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    checkProfileUrlState();
    handleProfileNavigation();
});

export {
    handleProfileNavigation,
    loadUserInfoFromSession,
    displayUserInfo,
    setupTabNavigation,
    setupPasswordToggle,
    setupInfoForm,
    setupPasswordForm
};