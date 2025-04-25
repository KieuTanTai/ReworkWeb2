<?php
    class Product {
        private $conn;
        private $table_name = "sanpham";
        
        // Các thuộc tính của sản phẩm
        public $masp;
        public $tensp;
        public $hinhanh;
        public $chipxuly;
        public $dungluongpin;
        public $kichthuocman;
        public $hedieuhanh;
        public $camerasau;
        public $cameratruoc;
        public $thoigianbaohanh;
        public $thuonghieu;
        public $trangthai;

        // Constructor
        public function __construct($db) {
            $this->conn = $db;
        }

        // Phương thức lấy tất cả sản phẩm
        public function getAll() {
            $query = "SELECT * FROM " . $this->table_name . " WHERE trangthai = 1 ORDER BY masp ASC";
            $result = $this->conn->query($query);
            
            if (!$result) {
                die("Lỗi truy vấn: " . $this->conn->error);
            }
            
            return $result;
        }

        // Phương thức tạo sản phẩm mới
        public function create() {
            $query = "INSERT INTO " . $this->table_name . " 
                      (tensp, hinhanh, chipxuly, dungluongpin, kichthuocman, hedieuhanh, camerasau, cameratruoc, thoigianbaohanh, thuonghieu, trangthai) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Chuẩn bị truy vấn với MySQLi
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                file_put_contents(__DIR__ . '/sql_error.log', "Prepare failed: " . $this->conn->error);
                return false;
            }
        
            // Làm sạch dữ liệu
            $tensp = htmlspecialchars(strip_tags($this->tensp));
            $hinhanh = htmlspecialchars(strip_tags($this->hinhanh));
            $chipxuly = htmlspecialchars(strip_tags($this->chipxuly));
            $dungluongpin = htmlspecialchars(strip_tags($this->dungluongpin));
            $kichthuocman = htmlspecialchars(strip_tags($this->kichthuocman));
            $hedieuhanh = htmlspecialchars(strip_tags($this->hedieuhanh));
            $camerasau = htmlspecialchars(strip_tags($this->camerasau));
            $cameratruoc = htmlspecialchars(strip_tags($this->cameratruoc));
            $thoigianbaohanh = htmlspecialchars(strip_tags($this->thoigianbaohanh));
            $thuonghieu = htmlspecialchars(strip_tags($this->thuonghieu));
            $trangthai = htmlspecialchars(strip_tags($this->trangthai));
        
            // Bind giá trị với MySQLi
            $stmt->bind_param(
                'sssssssssss',
                $tensp,
                $hinhanh,
                $chipxuly,
                $dungluongpin,
                $kichthuocman,
                $hedieuhanh,
                $camerasau,
                $cameratruoc,
                $thoigianbaohanh,
                $thuonghieu,
                $trangthai
            );
        
            // Thực thi truy vấn
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                // Ghi log lỗi
                file_put_contents(__DIR__ . '/sql_error.log', "Execute failed: " . $stmt->error);
                $stmt->close();
                return false;
            }
        }
        

        // Phương thức cập nhật sản phẩm
        public function update() {
            $query = "UPDATE " . $this->table_name . "
                    SET
                        tensp = ?,
                        hinhanh = ?,
                        chipxuly = ?,
                        dungluongpin = ?,
                        kichthuocman = ?,
                        hedieuhanh = ?,
                        camerasau = ?,
                        cameratruoc = ?,
                        thoigianbaohanh = ?,
                        thuonghieu = ?,
                        trangthai = ?
                    WHERE masp = ?";

            $stmt = $this->conn->prepare($query);

            // Làm sạch dữ liệu
            $this->masp = htmlspecialchars(strip_tags($this->masp));
            $this->tensp = htmlspecialchars(strip_tags($this->tensp));
            $this->hinhanh = htmlspecialchars(strip_tags($this->hinhanh));
            $this->chipxuly = htmlspecialchars(strip_tags($this->chipxuly));
            $this->dungluongpin = htmlspecialchars(strip_tags($this->dungluongpin));
            $this->kichthuocman = htmlspecialchars(strip_tags($this->kichthuocman));
            $this->hedieuhanh = htmlspecialchars(strip_tags($this->hedieuhanh));
            $this->camerasau = htmlspecialchars(strip_tags($this->camerasau));
            $this->cameratruoc = htmlspecialchars(strip_tags($this->cameratruoc));
            $this->thoigianbaohanh = htmlspecialchars(strip_tags($this->thoigianbaohanh));
            $this->thuonghieu = htmlspecialchars(strip_tags($this->thuonghieu));
            $this->trangthai = htmlspecialchars(strip_tags($this->trangthai));

            // Bind các giá trị
            $stmt->bind_param("ssssssssssii", 
                $this->tensp,
                $this->hinhanh,
                $this->chipxuly,
                $this->dungluongpin,
                $this->kichthuocman,
                $this->hedieuhanh,
                $this->camerasau,
                $this->cameratruoc,
                $this->thoigianbaohanh,
                $this->thuonghieu,
                $this->trangthai,
                $this->masp
            );

            if($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Phương thức xóa sản phẩm
        public function delete() {
            $query = "UPDATE " . $this->table_name . " SET trangthai = 0 WHERE masp = ?";
            $stmt = $this->conn->prepare($query);
        
            $this->masp = htmlspecialchars(strip_tags($this->masp));
            $stmt->bind_param("i", $this->masp);
        
            return $stmt->execute();
        }
        

        // Phương thức lấy một sản phẩm theo ID
        public function getOne() {
            $query = "SELECT * FROM " . $this->table_name . " WHERE masp = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            
            $this->masp = htmlspecialchars(strip_tags($this->masp));
            $stmt->bind_param("i", $this->masp);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if($row) {
                $this->tensp = $row['tensp'];
                $this->hinhanh = $row['hinhanh'];
                $this->chipxuly = $row['chipxuly'];
                $this->dungluongpin = $row['dungluongpin'];
                $this->kichthuocman = $row['kichthuocman'];
                $this->hedieuhanh = $row['hedieuhanh'];
                $this->camerasau = $row['camerasau'];
                $this->cameratruoc = $row['cameratruoc'];
                $this->thoigianbaohanh = $row['thoigianbaohanh'];
                $this->thuonghieu = $row['thuonghieu'];
                $this->trangthai = $row['trangthai'];
                return true;
            }
            return false;
        }
    }
?>

    

   


