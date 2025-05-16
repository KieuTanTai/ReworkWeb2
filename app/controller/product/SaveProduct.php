<?php
require_once 'productController.php';

// Khởi tạo controller
$controller = new ProductController();

// Gọi hàm create và trả về kết quả dưới dạng JSON
$result = $controller->create();
echo json_encode($result);
?>