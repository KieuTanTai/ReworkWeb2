<?php
class DetailOrder
{
    private $conn;
    private $table_name = "chitietdonhang";

    public $madonhang;
    public $maphienbansp;
    public $soluong;
    public $dongia;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /** Lấy tất cả chi tiết đơn hàng */
    public function getAll()
    {
        $query = "SELECT * FROM {$this->table_name}";
        return $this->conn->query($query);
    }

    /** Lấy tất cả chi tiết theo mã đơn hàng */
    public function getByOrder(int $madonhang)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE madonhang = ?";
        $stmt  = $this->conn->prepare($query);
        $stmt->bind_param("i", $madonhang);
        $stmt->execute();
        return $stmt->get_result();
    }

    /** Lấy 1 bản ghi theo composite key (madonhang + maphienbansp) */
    public function getOne(int $madonhang, int $maphienbansp)
    {
        $query = "SELECT * FROM {$this->table_name} 
                  WHERE madonhang = ? AND maphienbansp = ? LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bind_param("ii", $madonhang, $maphienbansp);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /** Tạo chi tiết đơn hàng mới */
    public function create()
    {
        $query = "INSERT INTO {$this->table_name}
                    (madonhang, maphienbansp, soluong, dongia)
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param(
            "iiii",
            $this->madonhang,
            $this->maphienbansp,
            $this->soluong,
            $this->dongia
        );

        return $stmt->execute();
    }

    /** Cập nhật chi tiết đơn hàng (số lượng, đơn giá) */
    public function update()
    {
        $query = "UPDATE {$this->table_name}
                  SET soluong = ?, dongia = ?
                  WHERE madonhang = ? AND maphienbansp = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param(
            "iiii",
            $this->soluong,
            $this->dongia,
            $this->madonhang,
            $this->maphienbansp
        );

        return $stmt->execute();
    }

    /** Xóa một chi tiết theo composite key */
    public function delete(int $madonhang, int $maphienbansp)
    {
        $query = "DELETE FROM {$this->table_name}
                  WHERE madonhang = ? AND maphienbansp = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $madonhang, $maphienbansp);
        return $stmt->execute();
    }
}
