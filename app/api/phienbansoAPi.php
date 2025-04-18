<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controller/product/detailController.php';
$controller = new detailController();

// Lấy method và tham số id nếu có
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'GET') {
    if ($id) {
        // GET chi tiết theo ID
        $result = $controller->getDetailByProductId($id);
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm với ID đã cho.'
            ]);
        }
    } else {
        // GET tất cả sản phẩm
        $result = $controller->index();
        echo json_encode([
            'status' => 'success',
            'data' => $result
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ.'
    ]);
}
?>