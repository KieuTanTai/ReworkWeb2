<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php'; // Kết nối DB
require_once __DIR__ . '/../model/Rom.php';       // Model Rom

$controller = new Rom($conn); // Khởi tạo đối tượng Rom với kết nối DB

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $controller->getAllRom();

    $roms = [];
    while ($row = $result->fetch_assoc()) {
        $roms[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $roms
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ.'
    ]);
}
