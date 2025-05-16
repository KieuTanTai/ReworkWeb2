<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/DetailOrder.php';

$controller = new DetailOrder($conn);
$method     = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lấy 1 detail (madonhang + maphienbansp)
        if (isset($_GET['madonhang'], $_GET['maphienbansp'])) {
            $d = $controller->getOne((int)$_GET['madonhang'], (int)$_GET['maphienbansp']);
            if ($d) {
                echo json_encode(['status'=>'success','data'=>$d]);
            } else {
                echo json_encode(['status'=>'error','message'=>'Không tìm thấy chi tiết đơn.']);
            }
            exit;
        }
        // Lấy theo mã đơn
        if (isset($_GET['madonhang'])) {
            $res = $controller->getByOrder((int)$_GET['madonhang']);
            $rows = [];
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            echo json_encode(['status'=>'success','data'=>$rows]);
            exit;
        }
        // Lấy tất cả
        $res = $controller->getAll();
        $all = [];
        while ($r = $res->fetch_assoc()) $all[] = $r;
        echo json_encode(['status'=>'success','data'=>$all]);
        break;

    case 'POST':
        $d = json_decode(file_get_contents('php://input'), true);
        if ($d && isset($d['madonhang'], $d['maphienbansp'], $d['soluong'], $d['dongia'])) {
            $controller->madonhang     = (int)$d['madonhang'];
            $controller->maphienbansp  = (int)$d['maphienbansp'];
            $controller->soluong        = (int)$d['soluong'];
            $controller->dongia         = (int)$d['dongia'];

            if ($controller->create()) {
                echo json_encode(['status'=>'success','message'=>'Thêm chi tiết đơn thành công.']);
            } else {
                http_response_code(500);
                echo json_encode(['status'=>'error','message'=>'Thêm thất bại.']);
            }
        } else {
            echo json_encode(['status'=>'error','message'=>'Dữ liệu không hợp lệ.']);
        }
        break;

    case 'PUT':
        $d = json_decode(file_get_contents('php://input'), true);
        if ($d && isset($d['madonhang'], $d['maphienbansp'], $d['soluong'], $d['dongia'])) {
            $controller->madonhang     = (int)$d['madonhang'];
            $controller->maphienbansp  = (int)$d['maphienbansp'];
            $controller->soluong        = (int)$d['soluong'];
            $controller->dongia         = (int)$d['dongia'];

            if ($controller->update()) {
                echo json_encode(['status'=>'success','message'=>'Cập nhật thành công.']);
            } else {
                http_response_code(500);
                echo json_encode(['status'=>'error','message'=>'Cập nhật thất bại.']);
            }
        } else {
            echo json_encode(['status'=>'error','message'=>'Thiếu dữ liệu cần thiết.']);
        }
        break;

    case 'DELETE':
        $d = json_decode(file_get_contents('php://input'), true);
        if ($d && isset($d['madonhang'], $d['maphienbansp'])) {
            if ($controller->delete((int)$d['madonhang'], (int)$d['maphienbansp'])) {
                echo json_encode(['status'=>'success','message'=>'Xóa thành công.']);
            } else {
                http_response_code(500);
                echo json_encode(['status'=>'error','message'=>'Xóa thất bại.']);
            }
        } else {
            echo json_encode(['status'=>'error','message'=>'Thiếu khóa chính.']);
        }
        break;

    default:
        echo json_encode(['status'=>'error','message'=>'Phương thức không hỗ trợ.']);
        break;
}
