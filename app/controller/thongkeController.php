<?php 
require_once __DIR__ . '/../config/database.php';
class thongke {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTopKhachHang($startDate, $endDate) {
        // 1. Lấy 5 khách hàng mua nhiều nhất
        $query = "
            SELECT 
                kh.makh,
                kh.tenkhachhang,
                kh.sdt,
                kh.email,
                COALESCE(SUM(CASE 
                    WHEN dh.thoigian BETWEEN ? AND ? AND dh.trangthai = 2 THEN dh.tongtien
                    ELSE 0 
                END), 0) AS tongtienmuahang
            FROM 
                khachhang kh
            LEFT JOIN 
                donhang dh ON kh.makh = dh.makh
            GROUP BY 
                kh.makh, kh.tenkhachhang, kh.sdt, kh.email
            ORDER BY 
                tongtienmuahang DESC
            LIMIT 5
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data = [];
    
        while ($row = $result->fetch_assoc()) {
            $makh = $row['makh'];
    
            // 2. Truy vấn các đơn hàng của khách này trong khoảng thời gian
            $queryOrders = "
                SELECT madonhang, thoigian, tongtien, trangthai 
                FROM donhang 
                WHERE makh = ? AND thoigian BETWEEN ? AND ? AND trangthai = 2
            ";
    
            $stmtOrders = $this->conn->prepare($queryOrders);
            $stmtOrders->bind_param("sss", $makh, $startDate, $endDate);
            $stmtOrders->execute();
            $resultOrders = $stmtOrders->get_result();
    
            $orders = [];
            while ($order = $resultOrders->fetch_assoc()) {
                // Add link_chitiet for each order
                $order['url'] = "http://localhost:8000/app/api/orderAPI.php?madonhang=" . $order['madonhang'];
                $orders[] = $order;
            }
    
            // Gắn danh sách đơn hàng vào khách hàng
            $row['donhang'] = $orders;
            $data[] = $row;
        }
    
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    


    public function getKhachHang($startDate, $endDate) {
        $query = "
            SELECT 
                kh.makh,
                kh.tenkhachhang,
                kh.sdt,
                kh.email,
                COALESCE(SUM(CASE 
                    WHEN dh.thoigian BETWEEN ? AND ? AND dh.trangthai = 2 THEN dh.tongtien
                    ELSE 0 
                END), 0) AS tongtienmuahang
            FROM 
                khachhang kh
            LEFT JOIN 
                donhang dh ON kh.makh = dh.makh
            GROUP BY 
                kh.makh, kh.tenkhachhang, kh.sdt, kh.email
            ORDER BY 
                tongtienmuahang DESC
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $makh = $row['makh'];
    
            // Fetch orders for this customer
            $queryOrders = "
                SELECT madonhang, thoigian, tongtien, trangthai 
                FROM donhang 
                WHERE makh = ? AND thoigian BETWEEN ? AND ? AND trangthai = 2
            ";
    
            $stmtOrders = $this->conn->prepare($queryOrders);
            $stmtOrders->bind_param("sss", $makh, $startDate, $endDate);
            $stmtOrders->execute();
            $resultOrders = $stmtOrders->get_result();
    
            $orders = [];
            while ($order = $resultOrders->fetch_assoc()) {
                // Add link_chitiet for each order
                $order['url'] = "http://localhost:8000/app/api/orderAPI.php?madonhang=" . $order['madonhang'];
                $orders[] = $order;
            }
    
            // Attach orders to customer data
            $row['donhang'] = $orders;
            $data[] = $row;
        }
    
        return $data;
    }

}
