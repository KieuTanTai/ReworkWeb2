<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php'; // Kết nối DB
require_once __DIR__ . '/../model/Ram.php';       // Model Ram

$controller = new Ram($conn); // Khởi tạo đối tượng Ram với kết nối DB

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $controller->getAllRam();

    $rams = [];
    while ($row = $result->fetch_assoc()) {
        $rams[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $rams
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ.'
    ]);
}
