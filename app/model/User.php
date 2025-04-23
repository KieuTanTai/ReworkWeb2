<?php
class User
{
    private $conn;
    private $table_name = "khachhang";

    public $makh;
    public $tenkhachhang;
    public $diachi;
    public $sdt;
    public $email;
    public $matkhau;
    public $trangthai;
    public $ngaythamgia;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);

        if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error);
        }

        return $result;
    }
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                    SET
                        tenkhachhang = ?,
                        diachi = ?,
                        sdt = ?,
                        email = ?,
                        matkhau = ?,
                        trangthai = ?,
                        ngaythamgia = ?
                        ";

        $stmt = $this->conn->prepare($query);

        $this->tenkhachhang = htmlspecialchars(strip_tags($this->tenkhachhang));
        $this->diachi = htmlspecialchars(strip_tags($this->diachi));
        $this->sdt = htmlspecialchars(strip_tags($this->sdt));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->matkhau = password_hash(strip_tags($this->matkhau), PASSWORD_DEFAULT);
        $this->trangthai = 1;
        $this->ngaythamgia = htmlspecialchars(strip_tags($this->ngaythamgia));

        $stmt->bind_param(
            "sssssis",
            $this->tenkhachhang,
            $this->diachi,
            $this->sdt,
            $this->email,
            $this->matkhau,
            $this->trangthai,
            $this->ngaythamgia
        );

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        tenkhachhang = ?,
                        diachi = ?,
                        sdt = ?,
                        email = ?,
                        ngaythamgia = ?
                    WHERE makh = ?";

        $stmt = $this->conn->prepare($query);

        $this->makh = htmlspecialchars(strip_tags($this->makh));
        $this->tenkhachhang = htmlspecialchars(strip_tags($this->tenkhachhang));
        $this->diachi = htmlspecialchars(strip_tags($this->diachi));
        $this->sdt = htmlspecialchars(strip_tags($this->sdt));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->ngaythamgia = htmlspecialchars(strip_tags($this->ngaythamgia));

        $stmt->bind_param(
            "sssssi",
            $this->tenkhachhang,
            $this->diachi,
            $this->sdt,
            $this->email,
            $this->ngaythamgia,
            $this->makh
        );

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getTotalUsersCount(): int
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $result = $this->conn->query($query);
        if ($result) {
            $row = $result->fetch_assoc();
            $result->free();
            return (int) $row['total'];
        }
        
        return 0;
    }

    public function getUsersPaginated(int $limit, int $offset)
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY makh ASC LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);

        $bindResult = $stmt->bind_param("ii", $offset, $limit);

        $executeResult = $stmt->execute();

        $result = $stmt->get_result();

        return $result;
    }

    public function updateUserStatusInternal(int $makh, int $newStatus): bool
    {
        $query = "UPDATE " . $this->table_name . " SET trangthai = ? WHERE makh = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $newStatus, $makh);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
?>