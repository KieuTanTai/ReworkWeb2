<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Order.php';

$controller = new Order($conn);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
     case 'GET':
          // -- Lấy đơn hàng theo ID: orderAPI.php?madonhang=123
          if (isset($_GET['madonhang'])) {
               $id = (int) $_GET['madonhang'];
               $order = $controller->getOrderById($id);
               if ($order) {
                    // đồng thời lấy chi tiết
                    $items = $controller->getOrderItems($id);
                    echo json_encode([
                         'status' => 'success',
                         'data' => [
                              'order' => $order,
                              'items' => $items
                         ]
                    ]);
               } else {
                    echo json_encode([
                         'status' => 'error',
                         'message' => 'Không tìm thấy đơn hàng.'
                    ]);
               }
               exit;
          }

          if (isset($_GET['makh'])) {
               $makh = (int) $_GET['makh'];
               $result = $controller->getByCustomer($makh);
               $orders = [];
               while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
               }
               echo json_encode([
                    'status' => 'success',
                    'data' => $orders
               ]);
               exit;
          }

          // -- Phân trang: ?limit=10&offset=0
          if (isset($_GET['limit'], $_GET['offset'])) {
               $limit = (int) $_GET['limit'];
               $offset = (int) $_GET['offset'];
               $result = $controller->getOrdersPaginated($limit, $offset);
               $total = $controller->getTotalOrdersCount();
               $data = [];
               while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
               }
               echo json_encode([
                    'status' => 'success',
                    'total' => $total,
                    'data' => $data
               ]);
          } else {
               // -- Lấy tất cả
               $result = $controller->getAll();
               $data = [];
               while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
               }
               echo json_encode([
                    'status' => 'success',
                    'data' => $data
               ]);
          }
          break;

     case 'POST':
          // Tạo đơn hàng mới
          $d = json_decode(file_get_contents('php://input'), true);
          if ($d) {
               $controller->makh = $d['makh'] ?? 0;
               $controller->thoigian = $d['thoigian'] ?? date('Y-m-d H:i:s');
               $controller->tongtien = $d['tongtien'] ?? 0;
               $controller->diachi = $d['diachi'] ?? '';
               // trạng thái mặc định = 1
               if ($controller->create()) {
                    echo json_encode(['status' => 'success', 'message' => 'Tạo đơn hàng thành công.']);
               } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Tạo đơn hàng thất bại.']);
               }
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
          }
          break;

     case 'PUT':
          // Cập nhật toàn bộ thông tin đơn (ngoại trừ items)
          $d = json_decode(file_get_contents('php://input'), true);
          if ($d && isset($d['madonhang'])) {
               $controller->madonhang = $d['madonhang'];
               $controller->makh = $d['makh'];
               $controller->thoigian = $d['thoigian'];
               $controller->tongtien = $d['tongtien'];
               $controller->diachi = $d['diachi'];
               $controller->trangthai = $d['trangthai'];
               if ($controller->update()) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật đơn hàng thành công.']);
               } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật đơn hàng thất bại.']);
               }
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Thiếu mã đơn hàng.']);
          }
          break;

     case 'PATCH':
          // Cập nhật trạng thái đơn
          $d = json_decode(file_get_contents('php://input'), true);
          if ($d && isset($d['madonhang'], $d['trangthai'])) {
               if ($controller->updateOrderStatus((int) $d['madonhang'], (int) $d['trangthai'])) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công.']);
               } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật trạng thái thất bại.']);
               }
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin trạng thái.']);
          }
          break;

     case 'DELETE':
          // Xóa đơn hàng
          $d = json_decode(file_get_contents('php://input'), true);
          if ($d && isset($d['madonhang'])) {
               $tbl = $controller->getTableName();
               $stmt = $conn->prepare("DELETE FROM {$tbl} WHERE madonhang = ?");
               $stmt->bind_param('i', $d['madonhang']);
               if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Xóa đơn hàng thành công.']);
               } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Xóa đơn hàng thất bại.']);
               }
               $stmt->close();
          } else {
               echo json_encode(['status' => 'error', 'message' => 'Thiếu mã đơn hàng để xóa.']);
          }
          break;

     default:
          echo json_encode(['status' => 'error', 'message' => 'Phương thức không hỗ trợ.']);
          break;
}
