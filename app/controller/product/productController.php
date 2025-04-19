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
        public function create() {
            // Đường dẫn thư mục upload ảnh
            $thu_muc = __DIR__ . '/../../../public/assets/images/';
            $ten_file = '';
    
            // Xử lý file ảnh upload
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
                $filename = time() . '_' . basename($_FILES['hinhanh']['name']);
                $duong_dan = $thu_muc . $filename;
    
                // Kiểm tra và tạo thư mục nếu chưa tồn tại
                if (!file_exists($thu_muc)) {
                    print("Lỗi");
                }
    
                // Di chuyển file vào thư mục
                if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $duong_dan)) {
                    $ten_file = $filename;
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'Không thể upload ảnh'
                    ];
                }
            }
    
            // Gán giá trị từ request vào thuộc tính của sản phẩm
            $this->product->tensp = isset($_POST['tensp']) ? htmlspecialchars(strip_tags($_POST['tensp'])) : '';
            $this->product->hinhanh = $ten_file;
            $this->product->chipxuly = isset($_POST['chipxuly']) ? htmlspecialchars(strip_tags($_POST['chipxuly'])) : '';
            $this->product->dungluongpin = isset($_POST['dungluongpin']) ? htmlspecialchars(strip_tags($_POST['dungluongpin'])) : '';
            $this->product->kichthuocman = isset($_POST['kichthuocman']) ? htmlspecialchars(strip_tags($_POST['kichthuocman'])) : '';
            $this->product->hedieuhanh = isset($_POST['hedieuhanh']) ? htmlspecialchars(strip_tags($_POST['hedieuhanh'])) : '';
            $this->product->camerasau = isset($_POST['camerasau']) ? htmlspecialchars(strip_tags($_POST['camerasau'])) : '';
            $this->product->cameratruoc = isset($_POST['cameratruoc']) ? htmlspecialchars(strip_tags($_POST['cameratruoc'])) : '';
            $this->product->thoigianbaohanh = isset($_POST['thoigianbaohanh']) ? htmlspecialchars(strip_tags($_POST['thoigianbaohanh'])) : '';
            $this->product->thuonghieu = isset($_POST['thuonghieu']) ? htmlspecialchars(strip_tags($_POST['thuonghieu'])) : '';
            $this->product->trangthai = 1;

    
            // Gọi hàm create từ model Product
            if ($this->product->create()) {
                return [
                    'status' => 'success',
                    'message' => 'Tạo sản phẩm thành công'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Không thể tạo sản phẩm'
                ];
            }
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
