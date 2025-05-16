<?php
// get_top_customers.php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

// Lấy tham số từ URL
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

require_once '../controller/thongkeController.php';

$controller = new thongke($conn);
$custop = $controller->getTopKhachHang($startDate, $endDate);

// Trả về dữ liệu dạng JSON
echo $custop;

exit();
?>