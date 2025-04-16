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
            $ten_file = '..../public/assets/images/';
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
                $thu_muc = '';
                $ten_file = time() . '_' . basename($_FILES['hinhanh']['name']); // thêm timestamp tránh trùng
                $duong_dan = $thu_muc . $ten_file;
    
                if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $duong_dan)) {
                    // Upload thành công
                } else {
                    // Upload thất bại
                    $ten_file = ''; // hoặc set thông báo lỗi
                }
            }
    
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $this->product->tensp = $data['tensp'];
            $this->product->hinhanh = $ten_file;
            $this->product->chipxuly = $data['chipxuly'];
            $this->product->dungluongpin = $data['dungluongpin'];
            $this->product->kichthuocman = $data['kichthuocman'];
            $this->product->hedieuhanh = $data['hedieuhanh'];
            $this->product->camerasau = $data['camerasau'];
            $this->product->cameratruoc = $data['cameratruoc'];
            $this->product->thuonghieu = $data['thuonghieu'];
      
            }
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
