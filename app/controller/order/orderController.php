<?php
require_once __DIR__ . '/../../model/Order.php';
require_once __DIR__ . '/../../config/database.php';

class OrderController
{
    private $order;
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
        $this->order = new Order($this->conn);
    }

    public function create($data)
    {
        $this->order->makh = $data['makh'];
        $this->order->thoigian = $data['thoigian'];
        $this->order->tongtien = $data['tongtien'];
        $this->order->diachi = $data['diachi'];
        $this->order->trangthai = $data['trangthai'];

        return $this->order->create();
    }

    public function update($id, $data)
    {
        $this->order->madonhang = $id;
        $this->order->makh = $data['makh'];
        $this->order->thoigian = $data['thoigian'];
        $this->order->tongtien = $data['tongtien'];
        $this->order->diachi = $data['diachi'];
        $this->order->trangthai = $data['trangthai'];

        return $this->order->update();
    }

    public function index(): array
    {
        $itemsPerPage = 10; 
        $totalOrders = $this->order->getTotalOrdersCount();

        $totalPages = ceil($totalOrders / $itemsPerPage);
        if ($totalPages < 1)
            $totalPages = 1;
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1)
            $currentPage = 1;
        elseif ($currentPage > $totalPages)
            $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $itemsPerPage;

        $result = $this->order->getOrdersPaginated($itemsPerPage, $offset);

        $ordersToDisplay = [];
        if ($result instanceof mysqli_result) { 
            while ($row = $result->fetch_assoc()) {
                $ordersToDisplay[] = $row;
            }
            $result->free(); 
        } elseif ($result === false) {
            error_log("Lỗi khi lấy dữ liệu người dùng phân trang từ Controller.");
        }
        return [
            'orders' => $ordersToDisplay,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];
    }

}
?>