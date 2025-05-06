<?php 

require_once '../controller/order/OrderController.php'; // Giả sử đường dẫn là vậy

$controller = new OrderController();

// Gọi phương thức showDetails để lấy dữ liệu
$viewData = $controller->showDetails();

// Giải nén dữ liệu
$order = $viewData['order'] ?? null; // Lấy thông tin đơn hàng (hoặc null nếu không tìm thấy)
$items = $viewData['items'] ?? [];   // Lấy danh sách sản phẩm (hoặc mảng rỗng)

include("header1.php"); 
include("sidebar1.php");
?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Chi Tiết Đơn Hàng</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><a href="order.php">Quản Lý Đơn Hàng</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><a href="#">Chi Tiết Đơn Hàng</a></li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
      <div class="card shadow mb-4">
        <div class="card-header py-2">
        <div class="row g-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Mã Phiên Bản Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Đơn Giá</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle;">
                    <?php foreach ($items as $item): ?>
                  <tr>
                    <td><?= htmlspecialchars($item['madonhang']) ?></td>
                    <td><?= htmlspecialchars($item['maphienbansp']) ?></td>
                    <td><?= htmlspecialchars($item['soluong']) ?></td>
                    <td><?= htmlspecialchars($item['dongia']) ?></td>
                  </tr>
                  <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
            </div>  
        </div>  
        <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->  
<?php
  include("footer1.php");
?>        