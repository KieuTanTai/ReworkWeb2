<?php
class DetailProduct {
    private $conn;
    private $table_name = "phienbansanpham";
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

    public function setData($masp, $ram, $rom, $mausac, $giaban, $soluongton, $trangthai) {
        $this->masp = $masp;
        $this->ram = $ram;
        $this->rom = $rom;
        $this->mausac = $mausac;
        $this->giaban = $giaban;
        $this->soluongton = $soluongton;
        $this->trangthai = $trangthai;
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                    (masp, ram, rom, mausac, giaban, soluongton, trangthai)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }
    
        $stmt->bind_param(
            "iiisdii", // masp, ram, rom, mausac (string), giaban (double), soluongton, trangthai
            $this->masp,
            $this->ram,
            $this->rom,
            $this->mausac,
            $this->giaban,
            $this->soluongton,
            $this->trangthai
        );
    
        if ($stmt->execute()) {
            return true;
        }
    
        echo "Lỗi execute: " . $stmt->error;
        return false;
    }
    

    public function getAllDetailProduct(){
      $query = "Select * from " . $this->table_name;  
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

    public function getVariantId(int $masp, int $mausac, int $ram, int $rom): ?int
    {
        $query = "SELECT maphienbansp 
                  FROM {$this->table_name}
                  WHERE masp = ?
                    AND mausac = ?
                    AND ram = ?
                    AND rom = ?
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in getVariantId: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("iiii", $masp, $mausac, $ram, $rom);
        if (!$stmt->execute()) {
            error_log("Execute failed in getVariantId: " . $stmt->error);
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();

        $stmt->close();
        return $row ? (int)$row['maphienbansp'] : null;
    }
}


?>