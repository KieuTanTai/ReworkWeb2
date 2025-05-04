<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/User.php';

$controller = new User($conn);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
     case 'GET':
          // Lấy 1 user theo ID: userAPI.php?makh=123
          if (isset($_GET['makh'])) {
               $makh = (int) $_GET['makh'];
               $row = $controller->getById($makh);
               if ($row) {
                    echo json_encode(['status' => 'success', 'data' => $row]);
               } else {
                    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy user.']);
               }
               exit;
          }

          if (isset($_GET['limit']) && isset($_GET['offset'])) {
               $limit = (int) $_GET['limit'];
               $offset = (int) $_GET['offset'];
               $result = $controller->getUsersPaginated($limit, $offset);
          } else {
               $result = $controller->getAll();
          }

          $users = [];
          while ($row = $result->fetch_assoc()) {
               $users[] = $row;
          }

          echo json_encode([
               'status' => 'success',
               'data' => $users
          ]);
          break;

     case 'POST':
          $data = json_decode(file_get_contents("php://input"), true);

          if ($data) {
               $controller->tenkhachhang = $data['tenkhachhang'];
               $controller->diachi = $data['diachi'];
               $controller->sdt = $data['sdt'];
               $controller->email = $data['email'];
               $controller->matkhau = $data['matkhau'];
               $controller->ngaythamgia = $data['ngaythamgia'];

               if ($controller->create()) {
                    echo json_encode(['status' => 'success', 'message' => 'Thêm người dùng thành công.']);
               } else {
                    echo json_encode(['status' => 'error', 'message' => 'Không thể thêm người dùng.']);
               }
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
          }
          break;

     case 'PUT':
          $data = json_decode(file_get_contents("php://input"), true);
          if ($data && isset($data['makh'])) {
               $controller->makh = $data['makh'];
               $controller->tenkhachhang = $data['tenkhachhang'];
               $controller->diachi = $data['diachi'];
               $controller->sdt = $data['sdt'];
               $controller->email = $data['email'];
               $controller->ngaythamgia = $data['ngaythamgia'];

               if ($controller->update()) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật người dùng thành công.']);
               } else {
                    echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật người dùng.']);
               }
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ hoặc thiếu makh.']);
          }
          break;

     case 'PATCH':
          $data = json_decode(file_get_contents("php://input"), true);
          if ($data && isset($data['makh']) && isset($data['trangthai'])) {
               if ($controller->updateUserStatusInternal((int) $data['makh'], (int) $data['trangthai'])) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công.']);
               } else {
                    echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái.']);
               }
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cập nhật trạng thái.']);
          }
          break;

     default:
          echo json_encode([
               'status' => 'error',
               'message' => 'Phương thức không được hỗ trợ.'
          ]);
          break;
}
?>