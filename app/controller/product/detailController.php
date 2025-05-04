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
}
