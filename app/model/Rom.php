<?php 
class Rom {
    private $conn;
    private $table_name = "dungluongrom";
    private $madlrom;
    private $kichthuocrom;
    private $trangthai;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllRom(){
      $query = "Select * from " . $this->table_name;  
      $result = $this->conn->query($query);
      if (!$result) {
        die("Lỗi truy vấn: " . $this->conn->error);
    }
    
    return $result;
    }
}



?>