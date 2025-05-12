<?php
session_start();
// Đảm bảo đường dẫn đúng
require_once '../model/Order.php';

$makh = $_GET['makh'] ?? null;
if ($makh) {
    // Lấy dữ liệu đơn hàng của khách hàng từ DB
    $orderModel = new Order($conn);
    $orders = $orderModel -> getByCustomer($makh);
    // Xử lý và hiển thị
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    $_SESSION['login_error'] = "Vui lòng đăng nhập để tiếp tục!";
    header("Location: login.php");
    exit();
}

// Kiểm tra quyền admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Nếu không phải admin, chuyển hướng về trang admin
    header("Location: admin.php");
    exit();
}
session_write_close();
include ("header1.php");
include ("sidebar1.php");
?>
<div class="app-content">
<br>
<div class="col-sm-6"><h3 class="mb-0 ms-3">Đơn Hàng</h3></div>
<br>
  <div class="card mb-4 ">
      <div class="card-header">
      
     <div class="d-flex align-items-center">
     <div class="btn-group">
 
</div>
    


      </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
  

            <tr>
                
              <th style="width: 10px">ID</th>
              <th>Thời Gian</th>
              <th>Tổng Tiền Mua Hàng</th>
              <th>Chi Tiết</th>
            </tr>
          </thead>
          <tbody>
             <?php foreach ($orders as $o): ?>
            <tr class="align-middle">
              <td><?= $o['madonhang']?></td>
              <td><?= $o['thoigian']?></td>
              <td><?= $o['tongtien']?></td>
            <td style=" cursor:pointer;">
<button type="button" class="btn btn-primary " onclick="orderdetails(<?= $o['madonhang'] ?>)">Chi Tiết</button>
            </tr>
             
        
            </tr>
         
            </tr>
<?php endforeach; ?>
          
          </tbody>
           
        </table>
      </div>
     
                </div>
      <!-- /.card-body -->
    </div>
    
  
  
<script src="../../public/assets/js/adminlte.js"></script>
<script src="https://unpkg.com/popper.js@1/dist/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.5.0/styles/overlayscrollbars.min.css" />
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.5.0/browser/overlayscrollbars.browser.es.min.js"></script>

<script>
const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const Default = {
scrollbarTheme: 'os-theme-light',
scrollbarAutoHide: 'leave',
scrollbarClickScroll: true,
};
document.addEventListener('DOMContentLoaded', function () {
const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
OverlayScrollbarsGlobal?.OverlayScrollbars(sidebarWrapper, {
scrollbars: {
  theme: Default.scrollbarTheme,
  autoHide: Default.scrollbarAutoHide,
  clickScroll: Default.scrollbarClickScroll,
},
});
}
});
</script>

<script>
function orderdetails(id) {
    window.location.href = "order_detail.php?id=" + id;
}


</script>
<script src="https://kit.fontawesome.com/95a272230e.js" crossorigin="anonymous"></script>

<style>
.container
{
display:none;
width:50%;
height :50%;
top:50%;
left: 50%;
transform: translate(-50%, -50%);
background: white;

}


</style>