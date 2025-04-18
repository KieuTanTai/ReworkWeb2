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
    public function getDetailProductById($masp){
        $query = "SELECT * FROM " . $this->table_name . " WHERE masp = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $masp);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}


?>