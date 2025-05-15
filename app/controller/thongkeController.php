<?php 
require_once __DIR__ . '/../config/database.php';
class thongke {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTopKhachHang($startDate, $endDate) {
        // 1. Lấy 5 khách hàng có đơn hàng mua nhiều nhất
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
            INNER JOIN 
                donhang dh ON kh.makh = dh.makh
            WHERE 
                dh.thoigian BETWEEN ? AND ? AND dh.trangthai = 2
            GROUP BY 
                kh.makh, kh.tenkhachhang, kh.sdt, kh.email
            ORDER BY 
                tongtienmuahang DESC
            LIMIT 5
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $startDate, $endDate, $startDate, $endDate);
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
    
    


    public function getKhachHang($startDate, $endDate, $keyword = '', $page = 1) {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $likeKeyword = '%' . strtolower($keyword) . '%';
    
        // 1. Truy vấn tổng số khách hàng
        $countQuery = "
            SELECT COUNT(DISTINCT kh.makh) AS total
            FROM khachhang kh
            INNER JOIN donhang dh ON kh.makh = dh.makh
            WHERE dh.thoigian BETWEEN ? AND ?
            AND dh.trangthai IN (2, 3)
            " . (!empty($keyword) ? "AND LOWER(kh.tenkhachhang) LIKE ?" : "")
        ;
    
        $stmtCount = $this->conn->prepare($countQuery);
        if (!empty($keyword)) {
            $stmtCount->bind_param("sss", $startDate, $endDate, $likeKeyword);
        } else {
            $stmtCount->bind_param("ss", $startDate, $endDate);
        }
        $stmtCount->execute();
        $resultCount = $stmtCount->get_result();
        $totalRow = $resultCount->fetch_assoc();
        $totalItems = $totalRow['total'];
        $totalPages = ceil($totalItems / $limit);
    
        // 2. Truy vấn danh sách khách hàng có mua đơn trạng thái 2 hoặc 3
        $query = "
            SELECT 
                kh.makh,
                kh.tenkhachhang,
                kh.sdt,
                kh.email,
                SUM(dh.tongtien) AS tongtienmuahang
            FROM khachhang kh
            INNER JOIN donhang dh ON kh.makh = dh.makh
            WHERE dh.thoigian BETWEEN ? AND ?
            AND dh.trangthai IN (2, 3)
            " . (!empty($keyword) ? "AND LOWER(kh.tenkhachhang) LIKE ?" : "") . "
            GROUP BY kh.makh, kh.tenkhachhang, kh.sdt, kh.email
            ORDER BY tongtienmuahang DESC
            LIMIT ? OFFSET ?
        ";
    
        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $stmt->bind_param("sssii", $startDate, $endDate, $likeKeyword, $limit, $offset);
        } else {
            $stmt->bind_param("ssii", $startDate, $endDate, $limit, $offset);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
    
        while ($row = $result->fetch_assoc()) {
            $makh = $row['makh'];
    
            // 3. Lấy danh sách đơn hàng trạng thái 2 hoặc 3 của khách
            $queryOrders = "
                SELECT madonhang, thoigian, tongtien, trangthai 
                FROM donhang 
                WHERE makh = ? AND thoigian BETWEEN ? AND ? AND trangthai IN (2, 3)
            ";
    
            $stmtOrders = $this->conn->prepare($queryOrders);
            $stmtOrders->bind_param("sss", $makh, $startDate, $endDate);
            $stmtOrders->execute();
            $resultOrders = $stmtOrders->get_result();
    
            $orders = [];
            while ($order = $resultOrders->fetch_assoc()) {
                $order['url'] = "http://localhost:8000/app/api/orderAPI.php?madonhang=" . $order['madonhang'];
                $orders[] = $order;
            }
    
            $row['donhang'] = $orders;
            $data[] = $row;
        }
    
        // 4. Trả về kết quả có phân trang
        return [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $limit,
            'total_items' => $totalItems,
            'data' => $data
        ];
    }
    
    

}