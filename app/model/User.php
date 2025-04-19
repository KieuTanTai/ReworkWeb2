<?php
    class User{
        private $conn;
        private $table_name = "khachhang";

        public $makhachhang;
        public $tenkhachhang;
        public $diachi;
        public $sdt;
        public $email;
        public $trangthai;
        public $ngaythamgia;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getAll(){
            $query = "SELECT * FROM ". $this->table_name;
            $result = $this->conn->query($query);

            if(!$result){
                die("Lỗi truy vấn: " . $this->conn->error);
            }

            return $result;
        }

        public function create(){
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                        tenkhachhang = ?,
                        diachi = ?,
                        sdt = ?,
                        email = ?,
                        trangthai = ?,
                        ngaythamgia = ?
                        ";
            
            $stmt = $this->conn->prepare($query);
            
            $this->tenkhachhang = htmlspecialchars(strip_tags($this->tenkhachhang));
            $this->diachi = htmlspecialchars(strip_tags($this->diachi));
            $this->sdt = htmlspecialchars(strip_tags($this->sdt));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->trangthai = htmlspecialchars(strip_tags($this->trangthai));
            $this->ngaythamgia = htmlspecialchars(strip_tags($this->ngaythamgia));

            $stmt->bind_param("ssssis",
                $this->tenkhachhang,
                $this->diachi,
                $this->sdt,
                $this->email,
                $this->trangthai,
                $this->ngaythamgia            
            );

            if($stmt->execute()){
                return true;
            }

            return false;
        }

        public function update(){
            $query = "UPDATE " . $this->table_name . "
                    SET
                        tenkhachhang = ?,
                        diachi = ?,
                        sdt = ?,
                        email = ?,
                        trangthai = ?,
                        ngaythamgia = ?
                    WHERE makhachhang = ?";
    
            $stmt = $this->conn->prepare($query);
            
            $this->tenkhachhang = htmlspecialchars(strip_tags($this->tenkhachhang));
            $this->diachi = htmlspecialchars(strip_tags($this->diachi));
            $this->sdt = htmlspecialchars(strip_tags($this->sdt));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->trangthai = htmlspecialchars(strip_tags($this->trangthai));
            $this->ngaythamgia = htmlspecialchars(strip_tags($this->ngaythamgia));
            
            $stmt->bind_param("ssssis",
                $this->tenkhachhang,
                $this->diachi,
                $this->sdt,
                $this->email,
                $this->trangthai,
                $this->ngaythamgia            
            );
    
            if($stmt->execute()){
                return true;
            }
    
            return false;    
        }
    }
?>