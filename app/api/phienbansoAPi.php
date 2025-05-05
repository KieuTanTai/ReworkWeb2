<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controller/product/detailController.php';
$controller = new detailController();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $params = $_GET;

    // Nếu truyền đủ 4 tham số masp, mausac, ram, rom
    if (isset($params['masp'], $params['mausac'], $params['ram'], $params['rom'])) {
        $masp = (int)$params['masp'];
        $mausac = (int)$params['mausac'];
        $ram = (int)$params['ram'];
        $rom = (int)$params['rom'];

        $variantId = $controller->getVariantId($masp, $mausac, $ram, $rom);

        if ($variantId !== null) {
            echo json_encode([
                'status' => 'success',
                'data' => ['maphienbansp' => $variantId]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy biến thể với thông tin đã cung cấp.'
            ]);
        }
        exit;
    }

    // Nếu chỉ có id
    $id = isset($params['id']) ? (int)$params['id'] : null;
    if ($id) {
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
        // Không param nào → lấy tất cả
        $result = $controller->index();
        echo json_encode([
            'status' => 'success',
            'data' => $result
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller->create();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ.'
    ]);
}



?>
