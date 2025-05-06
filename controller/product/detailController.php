<?php
require_once __DIR__ . '/../../model/DetailProduct.php';
require_once __DIR__ . '/../../config/database.php';

class detailController
{
    private $detailProduct;
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
        $this->detailProduct = new DetailProduct($this->conn);
    }

    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $result = $this->detailProduct->getAllDetailProduct();
        $products = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return $products;
    }

    // Lấy chi tiết sản phẩm theo ID
    public function getDetailByProductId($id)
    {
        return $this->detailProduct->getDetailProductById($id);
    }

    // Lấy ID của phiên bản sản phẩm theo masp, mausac, ram, rom
    public function getVariantId(int $masp, int $mausac, int $ram, int $rom): ?int
    {
        return $this->detailProduct->getVariantId($masp, $mausac, $ram, $rom);
    }

    public function create() {
        

        // Giả sử bạn đang gửi dữ liệu qua POST
        $masp = $_POST['masp'] ?? null;
        $ram = $_POST['ram'] ?? null;
        $rom = $_POST['rom'] ?? null;
        $mausac = $_POST['mausac'] ?? null;
        $giaban = $_POST['giaban'] ?? null;
        $soluongton = $_POST['soluongton'] ?? null;
        $trangthai = $_POST['trangthai'] ?? null;

        // Kiểm tra dữ liệu đầu vào
        if (!$masp || !$ram || !$rom || !$mausac || !$giaban || !$soluongton || !isset($trangthai)) {
            http_response_code(400);
            echo json_encode(["message" => "Dữ liệu không hợp lệ."]);
            return;
        }

        // Gọi model
        $detailProduct = new DetailProduct($this->conn);
        $detailProduct->setData($masp, $ram, $rom, $mausac, $giaban, $soluongton, $trangthai);

        if ($detailProduct->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Tạo phiên bản sản phẩm thành công."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Không thể tạo phiên bản sản phẩm."]);
        }
    }
}
