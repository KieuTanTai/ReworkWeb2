<?php 

class Ram {
    private $conn;
    private $table_name = "dungluongram";
    private $madlram;
    private $kichthuocram;
    private $trangthai;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllRam(){
      $query = "Select * from " . $this->table_name;  
      $result = $this->conn->query($query);
      if (!$result) {
        die("Lỗi truy vấn: " . $this->conn->error);
    }
    
    return $result;
    }
}



?>