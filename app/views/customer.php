<?php
session_start();
require_once '../model/account.php';
// Đảm bảo đường dẫn đúng
require_once '../controller/thongkeController.php';

$controller = new thongke($conn);
$startDate = "2024-01-01";
$endDate = "2025-12-31";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$data = $controller->getKhachHang($startDate, $endDate, "", $page);
$cus= $data['data'];
$currentPage = $data['current_page']; // trang hiện tại
$totalPages = $data['total_pages']; // tổng số trang


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
    <div class="input">
      <div class="btn-group ms-2">
            <input type="text" class="form-control" id="searchInput" placeholder="Tìm Kiếm Khách Hàng...."  style="width: 300px; margin-left: 10px;" >
            <button type="button" class="btn btn-primary ms-2" style="width:65px; border-radius:5px;" onclick="filter()">Tìm</button>
          
    </div>
    </div>
    </div>
<div style="display:none;" class="input-time">
<div class="d-flex align-items-center ms-3" >
  <label for="startdate" class="ms-3">Từ:</label>
  <input type="date" id="startdate" style="width:150px;" class="form-control ms-2">

  <label for="enddate" class="ms-3">Đến:</label>
  <input type="date" id="enddate" style="width:150px;" class="form-control ms-2">

  <button type="button" class="btn btn-primary ms-3" style="width:65px;" onclick="filter()">Lọc</button>
</div>
</div>


      </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table class="table table-bordered" id="customer-table">
          <thead>
            <tr>
                 <th style="width: 10px">ID</th>
              <th>Tên Khách Hàng</th>
              <th>Số Điện Thoại </th>
              <th>Email</th>
              <th>Tổng Tiền Mua Hàng</th>
              <th style="width: 94px;">Đơn Mua</th>
            </tr>
          </thead>
          <tbody id="customer-table-tbody">
             <?php foreach ($cus as $cust): ?>
           <tr class="align-middle">
    <td><?= $cust['makh']?></td>
    <td><?= $cust['tenkhachhang']?></td>
    <td><?= $cust['sdt']?></td>
    <td><?= $cust['email']?></td>
    <td><?= $cust['tongtienmuahang'] ?></td>
    <td>
        <button type="button" class="btn btn-primary" onclick="orderdetails(<?= $cust['makh'] ?>)">Xem</button>
    </td>
</tr>

                             <?php endforeach; ?>

            </tr>
             <?php if (empty($cus)): ?>
                    <tr>
                        <td colspan="11" class="text-center">Không có khách hàng nào.</td>
                    </tr>
                    <?php endif; ?>

          
          </tbody>
           
        </table>
      </div>
      <div class="card-footer" id="pagelink"> 
                            <?php if ($totalPages > 1): ?>
                              
                                <nav aria-label="Page navigation">
                                    <ul class="pagination float-end m-0">
                                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="customer.php?page=<?= $currentPage - 1 ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        <?php
                                        $range = 2;
                                        $start = max(1, $currentPage - $range);
                                        $end = min($totalPages, $currentPage + $range);

                                        if ($start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="customer.php?page=1">1</a></li>';
                                            if ($start > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                <a class="page-link" href="customer.php?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor;

                                        if ($end < $totalPages) {
                                            if ($end < $totalPages - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="customer.php?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="customer.php?page=<?= $currentPage + 1 ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                
                            <?php endif; ?>
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
  document.getElementById("statusButton1").addEventListener("change", function() {
    const selectedValue = this.value;
    if(selectedValue === "1"){
      document.querySelector(".input-time").style.display = "block";
      document.querySelector(".input").style.display = "none"; 

        
    }
    else{
      document.querySelector(".input-time").style.display = "none";
      document.querySelector(".input").style.display = "block";
    }
    
   
  });


function orderdetails(makh) {
    window.location.href = "detailcustomer.php?makh=" + makh;
}
function filter() {
    const startdate = document.getElementById("startdate").value;
    const enddate = document.getElementById("enddate").value;

    if (!startdate || !enddate) {
        alert("Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!");
        return;
    }

fetch(`../controller/gettopcustomer.php?startDate=${startdate}&endDate=${enddate}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Lỗi khi gọi server");
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            render(data);
        })
        .catch(error => {
            console.error("Lỗi:", error);
        });
}

function render(data) {
    const tbody = document.getElementById("customer-table-tbody");
    tbody.innerHTML = "";

    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">Không có khách hàng nào.</td>
            </tr>
        `;

        return;
    }

    data.forEach(item => {
        const row = `
        <tr class="align-middle">
            <td>${item.makh}</td>
            <td>${item.tenkhachhang}</td>
            <td>${item.sdt}</td>
            <td>${item.email}</td>
            <td>${item.tongtienmuahang}</td>
            <td>
                <button type="button" class="btn btn-primary" onclick="orderdetails(${item.makh})">Xem</button>
            </td>
        </tr>`;
        tbody.innerHTML += row;
    });
    document.querySelector("#pagelink").style.display = "none";
}
</script>

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