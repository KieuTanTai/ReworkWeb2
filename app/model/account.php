<?php
// File chứa các hàm xử lý xác thực (đăng nhập, đăng ký, đăng xuất)

// Hàm đăng ký tài khoản mới
function registerUser($tenkhachhang, $email, $password, $sdt = "", $diachi = "") {
    global $conn; // Sử dụng biến kết nối toàn cục
    
    // Kiểm tra xem email đã tồn tại chưa
    $checkEmailQuery = "SELECT * FROM khachhang WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        return [
            'success' => false,
            'message' => 'Email này đã được sử dụng.'
        ];
    }
    
    // Kiểm tra số điện thoại đã tồn tại chưa (nếu có cung cấp)
    if (!empty($sdt)) {
        $checkPhoneQuery = "SELECT * FROM khachhang WHERE sdt = ?";
        $stmt = mysqli_prepare($conn, $checkPhoneQuery);
        mysqli_stmt_bind_param($stmt, "s", $sdt);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            return [
                'success' => false,
                'message' => 'Số điện thoại này đã được sử dụng.'
            ];
        }
    }
    
    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Thêm người dùng mới vào cơ sở dữ liệu
    $insertQuery = "INSERT INTO khachhang (tenkhachhang, matkhau, email, sdt, diachi, trangthai) 
                   VALUES (?, ?, ?, ?, ?, 1)";
    
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "sssss", $tenkhachhang, $hashedPassword, $email, $sdt, $diachi);
    
    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($conn);
        return [
            'success' => true,
            'message' => 'Đăng ký thành công!',
            'user_id' => $user_id
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Đăng ký thất bại: ' . mysqli_error($conn)
        ];
    }
}

// Hàm đăng nhập
function loginUser($login_input, $password) {
    global $conn; // Sử dụng biến kết nối toàn cục
    
    // Xác định kiểu đầu vào (email hoặc số điện thoại)
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM khachhang WHERE email = ?";
    } elseif (preg_match('/^[0-9]{10,15}$/', $login_input)) {
        $sql = "SELECT * FROM khachhang WHERE sdt = ?";
    } else {
        return [
            'success' => false,
            'message' => 'Tài khoản không hợp lệ!'
        ];
    }
    
    // Thực hiện truy vấn
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return [
            'success' => false,
            'message' => 'Tài khoản không tồn tại!'
        ];
    }
    
    $user = mysqli_fetch_assoc($result);
    
    // Kiểm tra trạng thái khóa tài khoản
    if (isset($user['trangthai']) && $user['trangthai'] == 0) {
        return [
            'success' => false,
            'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!'
        ];
    } 
    
    // Kiểm tra mật khẩu
    if (password_verify($password, $user['matkhau'])) {
        // Đăng nhập thành công, lưu thông tin vào session
        $_SESSION['user_id'] = $user['makh'];
        $_SESSION['user_name'] = $user['tenkhachhang'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_phone'] = $user['sdt'];
        $_SESSION['is_logged_in'] = true;
        
        return [
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user' => [
                'makh' => $user['makh'],
                'tenkhachhang' => $user['tenkhachhang'],
                'email' => $user['email'],
                'sdt' => $user['sdt'],
                'diachi' => $user['diachi']
            ]
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Mật khẩu không chính xác!'
        ];
    }
}

// Hàm đăng xuất
function logoutUser() {
    // Xóa tất cả các biến session
    $_SESSION = array();
    
    // Nếu sử dụng session cookie, xóa cookie session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Hủy session
    session_destroy();
    
    return [
        'success' => true,
        'message' => 'Đăng xuất thành công!'
    ];
}

// Kiểm tra người dùng đã đăng nhập chưa
function isLoggedIn() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

// Lấy thông tin người dùng hiện tại
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'phone' => $_SESSION['user_phone'],
        ];
    }
    return null;
}
// Hàm đăng nhập cho nhân viên (đã tắt hash password để dễ dàng testing)
function loginStaff($login_input, $password) {
    global $conn;
    
    // Xác định kiểu đầu vào (email hoặc số điện thoại)
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM nhanvien WHERE email = ?";
    } else {
        $sql = "SELECT * FROM nhanvien WHERE sdt = ?";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return [
            'success' => false,
            'message' => 'Tài khoản không tồn tại!'
        ];
    }
    
    $staff = mysqli_fetch_assoc($result);
    
    // Kiểm tra trạng thái tài khoản
    if ($staff['trangthai'] == 0) {
        return [
            'success' => false,
            'message' => 'Tài khoản của bạn đã bị khóa!'
        ];
    }
    
    // So sánh mật khẩu trực tiếp (bỏ hash) - CHỈ SỬ DỤNG CHO TESTING
    if ($password === $staff['matkhau']) {  // So sánh trực tiếp thay vì dùng password_verify
        // Đăng nhập thành công, lưu thông tin vào session
        $_SESSION['user_id'] = $staff['manv'];
        $_SESSION['user_name'] = $staff['tennv'];
        $_SESSION['user_email'] = $staff['email'];
        $_SESSION['user_phone'] = $staff['sdt'];
        $_SESSION['user_role'] = $staff['vaitro']; // 1: Nhân viên, 2: Quản lý
        $_SESSION['is_logged_in'] = true;
        $_SESSION['is_staff'] = true;
        $_SESSION['is_admin'] = ($staff['vaitro'] == 2); // Admin nếu vai trò là 2
        
        return [
            'success' => true,
            'message' => 'Đăng nhập thành công!'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Mật khẩu không chính xác!'
        ];
    }
}
// Kiểm tra người dùng có phải là nhân viên không
function isStaff() {
    return isset($_SESSION['is_staff']) && $_SESSION['is_staff'] === true;
}

// Kiểm tra người dùng có phải là admin không
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}
?>