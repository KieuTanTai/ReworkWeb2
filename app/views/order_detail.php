<?php 
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
                            <th>Hình Ảnh</th>
                            <th>Đơn Giá</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle;">
                      <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>2011-01-25</td>
                            <td>TP.Hồ Chí Minh</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-body">
                <nav aria-label="Page navigation example">
                      <ul class="pagination float-end m-0">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                      </ul>
                    </nav>
                </div>
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