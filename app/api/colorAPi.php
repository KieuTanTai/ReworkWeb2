<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php'; // Kết nối DB
require_once __DIR__ . '/../model/Color.php';      // Model Color

$controller = new Color($conn); // Khởi tạo đối tượng Color với kết nối DB

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $controller->getAllColor();
    
    $colors = [];
    while ($row = $result->fetch_assoc()) {
        $colors[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $colors
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ.'
    ]);
}
