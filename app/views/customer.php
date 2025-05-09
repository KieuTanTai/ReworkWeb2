<?php
session_start();
require_once '../model/account.php';
// Đảm bảo đường dẫn đúng
require_once '../controller/user/userController.php';

$controller = new UserController();
$data = $controller->index();
$customer = $data['users'];
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
<div class="col-sm-6"><h3 class="mb-0 ms-3">Thống Kê Khách Hàng</h3></div>
<br>
  <div class="card mb-4 ">
      <div class="card-header">
      
     <div class="d-flex align-items-center">
     <div class="btn-group">
     <select name="" id="statusButton1" class="form-control">
            <option value="0">Tất cả Khách Hàng</option>
            <option value="1">Top 5 Khách Hàng có doanh thu cao nhất</option>
    </select> 
  </ul>
</div>
     <label for="" class="ms-3">Từ:</label>
      <input type="date"  id="startdate" style="width:150px;"class="form-control ms-3"> 
      <label for="" class="ms-3">Đến:</label>
      <input type="date"  id="enddate" style="width:150px;"class="form-control ms-3"> 
      <button type="button" class="btn btn-primary ms-3 "  style="width:65x;" onclick="filter()">Lọc</button>


      </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
  

            <tr>
                
              <th style="width: 10px">ID</th>
              <th>Tên Khách Hàng</th>
              <th>Tổng Mua</th>
              <th>Đơn Hàng</th>
              <th style="width: 94px;">Label</th>
            </tr>
          </thead>
          <tbody>
             <?php foreach ($customer as $u): ?>
            <tr class="align-middle">
              <td><?= htmlspecialchars($u['makh'])?></td>
              <td><?= htmlspecialchars($u['tenkhachhang'])?></td>
              <td></td>
              <td></td>
             
        
              <td style=" cursor:pointer;">
              <button type="button" class="btn btn-primary " onclick="orderdetails()">Chi Tiết</button>
            </tr>
         
            </tr>
<?php endforeach; ?>
          
          </tbody>
           
        </table>
      </div>
      <div class="card-footer clearfix" id="pagelink">
                    <ul class="pagination pagination-sm m-0 float-end">
                      <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
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
function orderdetails() {
    window.location.href = "order.php";
}
function filter(){
   const tmp= document.getElementById("statusButton1").value;
   if(tmp==="1"){
    document.querySelector("#pagelink").style.display="none";
   }
   else{
    document.querySelector("#pagelink").style.display="block";
   }
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