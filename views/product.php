<?php
include ("header1.php");
include ("sidebar1.php");
?>

        <div class="app-content">
            <br>
        <div class="col-sm-6"><h3 class="mb-0 ms-3">Sản Phẩm</h3></div>
        <br>
              <div class="card mb-4 ">
                  <div class="card-header">
                  <button type="button" class="btn btn-primary" onclick="addproduct()">Thêm Sản Phẩm</button>
                
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">ID</th>
                          <th>Tên Sản Phẩm</th>
                          <th>Hình Ảnh</th>
                          <th>Chip Xử Lý</th>
                          <th>Dung Lượng Pin</th>
                          <th>Kích Thước Màn</th>
                          <th>Hệ Điều Hành</th>
                          <th>Camera Sau</th>
                          <th>Camera Trước</th>
                          <th>Thương Hiệu</th>
                          <th style="width: 122px">Label</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="align-middle">
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>
                          <button type="button" class="btn btn-primary" >Sửa</button>
                          <button type="button" class="btn btn-danger" >Xóa</button>
</td>
                        </tr>
                        <tr class="align-middle">
                          <td></td>
                          <td></td>
                          <td>
                            
                          </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td style=" cursor:pointer;">
                          <button type="button" class="btn btn-primary" >Sửa</button>
                          <button type="button" class="btn btn-danger" >Xóa</button>
                        </tr>
                        <tr class="align-middle">
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td style=" cursor:pointer;">
                          <button type="button" class="btn btn-primary" >Sửa</button>
                          <button type="button" class="btn btn-danger" >Xóa</button>
                        </tr>
                        <tr class="align-middle">
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td style=" cursor:pointer;">
                          <button type="button" class="btn btn-primary" >Sửa</button>
                          <button type="button" class="btn btn-danger" >Xóa</button>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-end">
                      <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                  </div>
                </div>
</div>

                <div class="container">
                 

                </div>
              

    <script src="../../dist/js/adminlte.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    


 
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
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
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
        function addproduct() {
          $(".container").css("display", "block");
          $(".container").fadeIn();
      document.querySelector(".app-content").style.filter = "blur(5px)";
          $(".container").load("test.php");

        }
       
    </script>
    <script src="https://kit.fontawesome.com/95a272230e.js" crossorigin="anonymous"></script>

    <style>
      .container
      {
        display:none;
        
     
      }
  

    </style>
                