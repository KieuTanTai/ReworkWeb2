<?php
class DetailProduct {
    private $conn;
    private $table_name = "chitietsanpham";
    private $masp;
    private $ram;
    private $rom;
    private $mausac;
    private $giaban;
    private $soluongton;
    private $trangthai;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllDetailProduct(){
      $query = "Select * from" . $this->table_name;  
      $result = $this->conn->query($query);
      if (!$result) {
        die("Lỗi truy vấn: " . $this->conn->error);
    }
    
    return $result;
    }
}


?>