<?php
session_start();
require_once '../config/database.php';
require_once '../model/account.php';

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Xử lý yêu cầu lấy thông tin người dùng
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'];
    
    // Truy vấn thông tin người dùng từ database
    $query = "SELECT makh, tenkhachhang, diachi, sdt, email, ngaythamgia FROM khachhang WHERE makh = ? AND trangthai = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Trả về dữ liệu dạng JSON
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'User not found']);
    }
    
    $stmt->close();
    exit;
}

// Xử lý yêu cầu cập nhật thông tin người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu đây là yêu cầu cập nhật thông tin cá nhân
    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $userId = $_SESSION['user_id'];
        $tenkhachhang = isset($_POST['tenkhachhang']) ? trim($_POST['tenkhachhang']) : null;
        $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null; 
        $diachi = isset($_POST['diachi']) ? trim($_POST['diachi']) : null;
        
        // Kiểm tra dữ liệu
        $errors = [];
        
        // Chỉ kiểm tra các trường được cung cấp
        if (isset($_POST['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ!";
        }
        
        if (isset($_POST['sdt']) && !preg_match('/^[0-9]{10,11}$/', $sdt)) {
            $errors[] = "Số điện thoại không hợp lệ!";
        }
        
        // Nếu không có lỗi, tiến hành cập nhật
        if (empty($errors)) {
            // Xây dựng câu truy vấn động dựa trên các trường được cung cấp
            $updateFields = [];
            $params = [];
            $types = "";
            
            if ($tenkhachhang !== null) {
                $updateFields[] = "tenkhachhang = ?";
                $params[] = $tenkhachhang;
                $types .= "s";
            }
            
            if ($sdt !== null) {
                $updateFields[] = "sdt = ?";
                $params[] = $sdt;
                $types .= "s";
            }
            
            if ($email !== null) {
                $updateFields[] = "email = ?";
                $params[] = $email;
                $types .= "s";
            }
            
            if ($diachi !== null) {
                $updateFields[] = "diachi = ?";
                $params[] = $diachi;
                $types .= "s";
            }
            
            // Thêm tham số cuối cùng là userId
            $params[] = $userId;
            $types .= "i";
            
            if (!empty($updateFields)) {
                $query = "UPDATE khachhang SET " . implode(", ", $updateFields) . " WHERE makh = ?";
                $stmt = $conn->prepare($query);
                
                // Liên kết các tham số động
                if ($stmt) {
                    $stmt->bind_param($types, ...$params);
                    $result = $stmt->execute();
                    
                    if ($result) {
                        // Cập nhật thành công
                        // Lấy thông tin đã cập nhật để trả về
                        $query = "SELECT makh, tenkhachhang, diachi, sdt, email, ngaythamgia FROM khachhang WHERE makh = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($row = $result->fetch_assoc()) {
                            // Cập nhật session
                            $_SESSION['user_name'] = $row['tenkhachhang'];
                            $_SESSION['user_email'] = $row['email'];
                            
                            header('Content-Type: application/json');
                            echo json_encode(['success' => true, 'data' => $row]);
                        } else {
                            header('Content-Type: application/json');
                            echo json_encode(['error' => 'Failed to retrieve updated data']);
                        }
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['error' => 'Failed to update user information']);
                    }
                    
                    $stmt->close();
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Database error: ' . $conn->error]);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No fields to update']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => implode(', ', $errors)]);
        }
        
        exit;
    }
    
    // Kiểm tra nếu đây là yêu cầu đổi mật khẩu
    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $userId = $_SESSION['user_id'];
        $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        
        // Kiểm tra dữ liệu
        if (empty($currentPassword)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Vui lòng nhập mật khẩu hiện tại']);
            exit;
        }
        
        if (empty($newPassword)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Vui lòng nhập mật khẩu mới']);
            exit;
        }
        
        if (strlen($newPassword) < 6) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Mật khẩu mới phải có ít nhất 6 ký tự']);
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Mật khẩu mới và xác nhận mật khẩu không khớp']);
            exit;
        }
        
        // Kiểm tra mật khẩu hiện tại
        $query = "SELECT matkhau FROM khachhang WHERE makh = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Kiểm tra mật khẩu
            if (!password_verify($currentPassword, $row['matkhau'])) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Mật khẩu hiện tại không đúng']);
                exit;
            }
            
            // Mã hóa mật khẩu mới
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Cập nhật mật khẩu
            $updateQuery = "UPDATE khachhang SET matkhau = ? WHERE makh = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedPassword, $userId);
            $updateResult = $updateStmt->execute();
            
            if ($updateResult) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Không thể cập nhật mật khẩu']);
            }
            
            $updateStmt->close();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Không tìm thấy thông tin người dùng']);
        }
        
        $stmt->close();
        exit;
    }
}

// Nếu không có hành động hợp lệ, trả về lỗi
header('Content-Type: application/json');
echo json_encode(['error' => 'Invalid request']);
exit;
?>