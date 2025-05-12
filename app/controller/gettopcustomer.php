<?php
// get_top_customers.php
header('Content-Type: application/json');

// Lấy tham số từ URL
$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];

// Gọi hàm controller
$custop = $controller->getTopKhachHang($startDate, $endDate);

// Trả về dữ liệu dạng JSON
echo json_encode($custop);
?>