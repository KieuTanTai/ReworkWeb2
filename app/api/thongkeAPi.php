<?php
require_once '../config/database.php';
require_once '../controller/thongkeController.php';

header('Content-Type: application/json');

if (isset($_GET['start']) && isset($_GET['end'])) {
    $startDate = $_GET['start'];
    $endDate = $_GET['end'];

    $controller = new thongke($conn);

    if (isset($_GET['action']) && $_GET['action'] === 'getall') {
        $data = $controller->getKhachHang($startDate, $endDate);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        $data = $controller->getTopKhachHang($startDate, $endDate);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['error' => 'Thiếu tham số start hoặc end']);
}
