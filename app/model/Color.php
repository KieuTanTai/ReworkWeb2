<?php 
    class Color {

        private $conn;
        private $table_name = "mausac";
        private $mamau;
        private $tenmau;
        private $trangthai;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getAllColor(){
          $query = "Select * from " . $this->table_name;  
          $result = $this->conn->query($query);
          if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error);
        }
        
        return $result;
        }
    }

?>