<?php
require_once 'productController.php';

// Thiết lập header để xử lý AJAX
header('Content-Type: application/json');

// Kiểm tra có phải là request POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductController();
    
    // Lấy ID sản phẩm từ POST request
    $product_id = isset($_POST['masp']) ? intval($_POST['masp']) : 0;
    $current_image = $_POST['current_image'];

    // Xử lý file hình ảnh nếu có
    $image_name = basename($current_image);
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $upload_dir = __DIR__ . '/../../../public/assets/images/';
        $filename = time() . '_' . basename($_FILES['hinhanh']['name']);
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $upload_path)) {
            // Xóa file cũ nếu không phải file mặc định và nếu file có tồn tại
            if ($image_name != '' && $image_name != 'default.jpg' && file_exists($upload_dir . $image_name)) {
                @unlink($upload_dir . $image_name);
            }
            $image_name = $filename;
        }
    } else {
        // Nếu không có file mới, giữ nguyên hình ảnh cũ
        $image_name = $current_image;
    }
    // Tạo mảng dữ liệu cập nhật
    $data = [
        'tensp' => isset($_POST['tensp']) ? $_POST['tensp'] : '',
        'hinhanh' => $image_name,
        'chipxuly' => isset($_POST['chipxuly']) ? $_POST['chipxuly'] : '',
        'dungluongpin' => isset($_POST['dungluongpin']) ? $_POST['dungluongpin'] : '',
        'kichthuocman' => isset($_POST['kichthuocman']) ? $_POST['kichthuocman'] : '',
        'hedieuhanh' => isset($_POST['hedieuhanh']) ? $_POST['hedieuhanh'] : '',
        'camerasau' => isset($_POST['camerasau']) ? $_POST['camerasau'] : '',
        'cameratruoc' => isset($_POST['cameratruoc']) ? $_POST['cameratruoc'] : '',
        'thoigianbaohanh' => isset($_POST['thoigianbaohanh']) ? $_POST['thoigianbaohanh'] : '',
        'thuonghieu' => isset($_POST['thuonghieu']) ? $_POST['thuonghieu'] : '',
        'trangthai' => isset($_POST['trangthai']) ? intval($_POST['trangthai']) : 1
    ];
    
    // Gọi hàm update và trả về kết quả
    if ($controller->update($product_id, $data)) {
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật sản phẩm thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật sản phẩm']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không được hỗ trợ']);
}
?>