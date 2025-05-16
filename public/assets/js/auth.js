// auth.js - Quản lý xác thực người dùng

// Kiểm tra đăng nhập từ session PHP được chuyển sang sessionStorage
function isLoggedIn() {
    return sessionStorage.getItem('login') === 'true' && sessionStorage.getItem('loginAccount') !== null;
}

// Lấy thông tin người dùng hiện tại
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    try {
        return JSON.parse(sessionStorage.getItem('loginAccount'));
    } catch (e) {
        console.error('Error parsing user data:', e);
        return null;
    }
}

// Export các hàm
export { isLoggedIn, getCurrentUser };