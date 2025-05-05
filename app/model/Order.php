<?php
class Order {
    private $conn;
    private $table_name = "donhang";
    private $ctdonhang_table_name = "chitietdonhang";
    private $pbsp_table_name = "phienbansanpham";

    public $madonhang;
    public $makh;
    public $thoigian;
    public $tongtien;
    public $diachi;
    public $trangthai;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAll(){
        $query = "SELECT * FROM ".$this->table_name;
        $result = $this->conn->query($query);

        if($result){
            die("Lỗi truy vấn: ".$this->conn->error);
        }

        return $result;
    }

    public function create(){

        $query = "INSERT INTO " . $this->table_name . "
                    SET
                        makh = ?,
                        thoigian = ?,
                        tongtien = ?,
                        diachi = ?, 
                        trangthai = ?
                        ";
        $stmt = $this->conn->prepare($query);
        
        $this->makh = htmlspecialchars(strip_tags($this->makh));
        $this->thoigian = htmlspecialchars(strip_tags($this->thoigian));
        $this->tongtien = htmlspecialchars(strip_tags($this->tongtien));
        $this->diachi = htmlspecialchars(strip_tags($this->diachi));
        $this->trangthai = 1;

        $stmt->bind_param(
            "isisi",
            $this->makh,
            $this->thoigian,
            $this->tongtien,
            $this->diachi,
            $this->trangthai
        );

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    public function update(){
        
        $query = "UPDATE " . $this->table_name . "
                    SET
                        makh = ?,
                        thoigian = ?,
                        tongtien = ?,
                        diachi = ?,
                        trangthai = ?
                    WHERE madonhang = ?";
        $stmt = $this->conn->prepare($query);

        $this->madonhang = htmlspecialchars(strip_tags($this->madonhang));
        $this->makh = htmlspecialchars(strip_tags($this->makh));
        $this->thoigian = htmlspecialchars(strip_tags($this->thoigian));
        $this->tongtien = htmlspecialchars(strip_tags($this->tongtien));
        $this->diachi = htmlspecialchars(strip_tags($this->diachi));
        $this->trangthai = htmlspecialchars(strip_tags($this->trangthai));

        $stmt->bind_param(
            "isisii",
            $this->makh,
            $this->thoigian,
            $this->tongtien,
            $this->diachi,
            $this->trangthai,
            $this->madonhang
        );

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getTotalOrdersCount(): int
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $result = $this->conn->query($query);
        if ($result) {
            $row = $result->fetch_assoc();
            $result->free(); 
            return (int) $row['total'];
        }
        error_log("Lỗi truy vấn đếm đơn hàng: " . $this->conn->error);
        return 0; 
    }

    public function getOrdersPaginated(int $limit, int $offset)
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY madonhang DESC LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);

        $bindResult = $stmt->bind_param("ii", $offset, $limit);

        $executeResult = $stmt->execute();
        $result = $stmt->get_result();

        return $result; 
    }

    public function updateOrderStatus(int $orderID, int $newStatus){
        $query = "UPDATE " . $this->table_name . " SET trangthai = ? WHERE madonhang = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $newStatus, $orderID);

        if($stmt->execute()){
            $stmt->close();
            return true;
        }

        return false;
    }

    public function getOrderById(int $orderId): ?array
    {
        $query = "SELECT dh.*
                  FROM " . $this->table_name . " dh
                  LEFT JOIN " . $this->ctdonhang_table_name . " ct ON dh.madonhang = ct.madonhang
                  WHERE dh.madonhang = ?
                  LIMIT 1"; 

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $orderId);

        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        $orderData = $result->fetch_assoc(); 

        $stmt->close();
        $result->free();

        return $orderData ?: null; 
    }

    public function getOrderItems(int $orderId): array
    {
        $query = "SELECT ct.*
                  FROM " . $this->ctdonhang_table_name . " ct
                  LEFT JOIN " . $this->pbsp_table_name . " sp ON ct.maphienbansp = sp.maphienbansp
                  WHERE ct.madonhang = ?";

        $stmt = $this->conn->prepare($query);
         if (!$stmt) {
            error_log("Prepare failed for getOrderItems: " . $this->conn->error);
            return []; 
        }

        $stmt->bind_param("i", $orderId);

        if (!$stmt->execute()) {
             error_log("Execute failed for getOrderItems: " . $stmt->error);
             $stmt->close();
             return [];
        }

        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        $stmt->close();
        $result->free();

        return $items; 
    }
}
?>