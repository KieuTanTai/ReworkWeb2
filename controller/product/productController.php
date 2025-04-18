<?php
    require_once __DIR__ . '/../../model/Product.php';
    require_once __DIR__ . '/../../config/database.php';

    class ProductController {
        private $product;
        private $conn;

        public function __construct() {
            $this->conn = $GLOBALS['conn'];
            $this->product = new Product($this->conn);
        }

        // Hiển thị danh sách sản phẩm
        public function index() {
            $result = $this->product->getAll();
        
            $products = [];
        
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
            }
        
            return ($products); // Trả mảng sản phẩm cho view xử lý
        }
        
        public function indexJSON() {
            $result = $this->product->getAll();
        
            $products = [];
        
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
            }
        
            return  json_encode($products); // Trả mảng sản phẩm cho view xử lý
        }

        // Thêm sản phẩm mới
        public function create($data) {
            $this->product->tensp = $data['tensp'];
            $this->product->hinhanh = $data['hinhanh'];
            $this->product->chipxuly = $data['chipxuly'];
            $this->product->dungluongpin = $data['dungluongpin'];
            $this->product->kichthuocman = $data['kichthuocman'];
            $this->product->hedieuhanh = $data['hedieuhanh'];
            $this->product->camerasau = $data['camerasau'];
            $this->product->cameratruoc = $data['cameratruoc'];
            $this->product->thoigianbaohanh = $data['thoigianbaohanh'];
            $this->product->thuonghieu = $data['thuonghieu'];
            $this->product->trangthai = $data['trangthai'];

            return $this->product->create();
        }

        // Cập nhật sản phẩm
        public function update($id, $data) {
            $this->product->masp = $id;
            $this->product->tensp = $data['tensp'];
            $this->product->hinhanh = $data['hinhanh'];
            $this->product->chipxuly = $data['chipxuly'];
            $this->product->dungluongpin = $data['dungluongpin'];
            $this->product->kichthuocman = $data['kichthuocman'];
            $this->product->hedieuhanh = $data['hedieuhanh'];
            $this->product->camerasau = $data['camerasau'];
            $this->product->cameratruoc = $data['cameratruoc'];
            $this->product->thoigianbaohanh = $data['thoigianbaohanh'];
            $this->product->thuonghieu = $data['thuonghieu'];
            $this->product->trangthai = $data['trangthai'];

            return $this->product->update();
        }

        // Xóa sản phẩm
        public function delete($id) {
            $this->product->masp = $id;
            return $this->product->delete();
        }

        // Lấy thông tin một sản phẩm
        public function getOne($id) {
            $this->product->masp = $id;
            if ($this->product->getOne()) {
                return $this->product;
            }
            return null;
        }
    }
?>
