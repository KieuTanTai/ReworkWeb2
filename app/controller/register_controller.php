<?php
// Đảm bảo session được khởi tạo ở đầu file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa ROOT_PATH, giả sử file này nằm trong /app/controllers/
if (!defined('ROOT_PATH')) {
    // __DIR__ là /app/controllers, đi lên 2 cấp (../../) sẽ ra your_project_root
    define('ROOT_PATH', realpath(__DIR__ . '/../../'));
}

// Include các file cần thiết
// Đường dẫn này giả định file database.php và account.php nằm đúng vị trí như cấu trúc trên
require_once ROOT_PATH . '/app/config/database.php';
require_once ROOT_PATH . '/app/model/account.php'; // File này chứa hàm registerUser() và isLoggedIn()

// Kiểm tra nếu người dùng đã đăng nhập thì chuyển hướng đi
if (isLoggedIn()) { // Hàm isLoggedIn() phải được định nghĩa trong account.php (hoặc file được include)
    header("Location: /index.php"); // Hoặc trang dashboard/profile của người dùng
    exit();
}

// Xử lý khi form được submit (phương thức POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form, dùng trim để loại bỏ khoảng trắng thừa
    $tenkhachhang = isset($_POST['customer-full-name']) ? trim($_POST['customer-full-name']) : '';
    $sdt = isset($_POST['customer-phone']) ? trim($_POST['customer-phone']) : '';
    $email = isset($_POST['customer-email-register']) ? trim($_POST['customer-email-register']) : '';
    $password = isset($_POST['customer-password-register']) ? $_POST['customer-password-register'] : ''; // Không trim mật khẩu
    $confirm_password = isset($_POST['customer-confirm-password-register']) ? $_POST['customer-confirm-password-register'] : '';
    $diachi = isset($_POST['customer-address']) ? trim($_POST['customer-address']) : '';

    // Lưu dữ liệu người dùng đã nhập vào session để điền lại form nếu có lỗi
    // Không lưu lại mật khẩu vì lý do bảo mật và UX (người dùng nên nhập lại)
    $_SESSION['register_form_data'] = [
        'customer-full-name' => $tenkhachhang,
        'customer-phone' => $sdt,
        'customer-email-register' => $email,
        'customer-address' => $diachi
    ];

    // Biến lưu trữ thông báo lỗi
    $error_message = null;

    // Bắt đầu kiểm tra dữ liệu đầu vào
    if (empty($tenkhachhang)) {
        $error_message = "Vui lòng nhập họ và tên!";
    } elseif (empty($sdt)) {
        $error_message = "Vui lòng nhập số điện thoại!";
    } elseif (!preg_match('/^(0\d{9,10})$/', $sdt)) { // Kiểm tra định dạng SĐT Việt Nam (10-11 số, bắt đầu bằng 0)
        $error_message = "Số điện thoại không hợp lệ! (Phải bắt đầu bằng 0, và có 10 hoặc 11 chữ số)";
    } elseif (empty($email)) {
        $error_message = "Vui lòng nhập địa chỉ email!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Địa chỉ email không hợp lệ!";
    } elseif (empty($password)) {
        $error_message = "Vui lòng nhập mật khẩu!";
    } elseif (strlen($password) < 6) {
        $error_message = "Mật khẩu phải có ít nhất 6 ký tự!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Mật khẩu xác nhận không khớp!";
    }
    // Không cần kiểm tra địa chỉ có trống không nếu nó không bắt buộc

    // Nếu có lỗi từ các kiểm tra trên
    if ($error_message) {
        $_SESSION['register_error'] = $error_message;
        header("Location: ../views/register.php"); // Quay lại trang đăng ký
        exit();
    } else {
        // Nếu không có lỗi validation cơ bản, tiến hành gọi hàm đăng ký từ model
        // Hàm registerUser() được giả định là đã định nghĩa trong account.php
        // và trả về một mảng ['success' => true/false, 'message' => 'thông báo']
        $result = registerUser($tenkhachhang, $email, $password, $sdt, $diachi);

        if ($result['success']) {
            // Đăng ký thành công
            unset($_SESSION['register_form_data']); // Xóa dữ liệu form đã lưu vì không cần nữa
            unset($_SESSION['register_error']);     // Xóa thông báo lỗi cũ nếu có
            $_SESSION['login_message'] = "Đăng ký tài khoản thành công! Vui lòng đăng nhập."; // Thông báo cho trang đăng nhập

            // Chuyển hướng đến trang đăng nhập (đảm bảo đường dẫn đúng)
            // Nếu login_controller.php nằm cùng cấp controllers/
            header("Location: login_controller.php");
            // Hoặc nếu bạn muốn đến thẳng view: header("Location: ../views/login.php");
            exit();
        } else {
            // Đăng ký thất bại do lỗi từ model (ví dụ: email hoặc SĐT đã tồn tại trong DB)
            $_SESSION['register_error'] = $result['message']; // Lấy thông báo lỗi từ kết quả trả về của hàm registerUser
            header("Location: ../views/register.php"); // Quay lại trang đăng ký
            exit();
        }
    }
} else {
    // Nếu truy cập trực tiếp file controller này bằng phương thức GET (không phải POST từ form)
    // thì đơn giản là chuyển hướng về trang đăng ký.
    // Cũng nên xóa session dữ liệu form và lỗi cũ nếu người dùng vào trang mới.
    if (isset($_SESSION['register_form_data'])) {
        unset($_SESSION['register_form_data']);
    }
    if (isset($_SESSION['register_error'])) {
        unset($_SESSION['register_error']);
    }
    header("Location: ../views/register.php");
    exit();
}
?>