<?php
require_once __DIR__ . '/../../model/User.php';
require_once __DIR__ . '/../../config/database.php';

class UserController
{
    private $user;
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
        $this->user = new User($this->conn);
    }

    public function create($data)
    {
        $this->user->tenkhachhang = $data['tenkhachhang'];
        $this->user->diachi = $data['diachi'];
        $this->user->sdt = $data['sdt'];
        $this->user->email = $data['email'];
        $this->user->matkhau = $data['matkhau'];
        $this->user->ngaythamgia = $data['ngaythamgia'];

        return $this->user->create();
    }

    public function update($id, $data)
    {
        $this->user->makh = $id;
        $this->user->tenkhachhang = $data['tenkhachhang'];
        $this->user->diachi = $data['diachi'];
        $this->user->sdt = $data['sdt'];
        $this->user->email = $data['email'];
        $this->user->ngaythamgia = $data['ngaythamgia'];

        return $this->user->update();
    }

    public function index(): array
    {
        $itemsPerPage = 10; 
        $totalUsers = $this->user->getTotalUsersCount();

        $totalPages = ceil($totalUsers / $itemsPerPage);
        if ($totalPages < 1)
            $totalPages = 1;
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1)
            $currentPage = 1;
        elseif ($currentPage > $totalPages)
            $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $itemsPerPage;

        $result = $this->user->getUsersPaginated($itemsPerPage, $offset);

        $usersToDisplay = [];
        if ($result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $usersToDisplay[] = $row;
            }
            $result->free(); 
        } 

        return [
            'users' => $usersToDisplay,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];
    }

    public function updateStatus($data): bool
    {
        if (!isset($data['makh'])) {
            return false;
        }
        $makh = (int) $data['makh'];

        $stmt = $this->conn->prepare("SELECT trangthai FROM khachhang WHERE makh = ?");
        
        $stmt->bind_param("i", $makh);
        $stmt->execute();
        $result = $stmt->get_result();
        $current = $result->fetch_assoc();
        $stmt->close();

        if ($current) {
            $newStatus = ($current['trangthai'] == 1) ? 0 : 1; 
            return $this->user->updateUserStatusInternal($makh, $newStatus);
        } else {
            return false;
        }
    }
}
?>