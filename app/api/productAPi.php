<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controller/product/productController.php';

// Kiểm tra phương thức HTTP
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Khởi tạo ProductController
        $productController = new ProductController();
        
        // Lấy danh sách sản phẩm
        $products = $productController->index();

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($products);
    } catch (Exception $e) {
        // Nếu có lỗi, trả về mã lỗi và thông báo
        http_response_code(500);
        echo json_encode(['error' => 'Không thể lấy dữ liệu sản phẩm']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Khởi tạo ProductController
        $productController = new ProductController();

        // Tạo sản phẩm mới
        $result = $productController->create();

        // Trả về kết quả dưới dạng JSON
        echo json_encode($result);
    } catch (Exception $e) {
        // Nếu có lỗi, trả về mã lỗi và thông báo
        http_response_code(500);
        echo json_encode(['error' => 'Không thể tạo sản phẩm']);
    }
} else {
    // Nếu không phải GET hoặc POST, trả về lỗi phương thức không hỗ trợ
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ'
    ]);
}
?>
