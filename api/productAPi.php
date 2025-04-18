<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controller/product/productController.php';

try {
    $productController = new ProductController();
    $products = $productController->index();
    echo json_encode($products);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Không thể lấy dữ liệu sản phẩm']);
}
?>