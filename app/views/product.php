<?php
include ("header1.php");
include ("sidebar1.php");
require_once '../controller/product/productController.php';

$controller = new ProductController();
$products = $controller->index(); // lấy danh sách sản phẩm
?>

<div class="app-content">
    <br>
    <div class="col-sm-6">
        <h3 class="mb-0 ms-3">Sản Phẩm</h3>
    </div>
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
                    <?php foreach ($products as $product): ?>
                    <tr class="align-middle">
                        <td><?= $product['masp'] ?></td>
                        <td><?= $product['tensp'] ?></td>
                        <td><img src="<?= $product['hinhanh'] ?>" width="80"></td>
                        <td><?= $product['chipxuly'] ?></td>
                        <td><?= $product['dungluongpin'] ?> mAh</td>
                        <td><?= $product['kichthuocman'] ?> inch</td>
                        <td><?= $product['hedieuhanh'] ?></td>
                        <td><?= $product['camerasau'] ?></td>
                        <td><?= $product['cameratruoc'] ?></td>
                        <td><?= $product['thuonghieu'] ?></td>
                        <td style="cursor:pointer;">
                        <a href="#" class="btn btn-primary btn-sm" onclick="editProduct(<?= $product['masp'] ?>); return false;">Sửa</a>
              
                        <a href="#"onclick="deleteProduct(<?= $product['masp'] ?>)" class="btn btn-danger btn-sm">Xóa</a> 

                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="11" class="text-center">Không có sản phẩm nào.</td>
                    </tr>
                    <?php endif; ?>
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
document.addEventListener('DOMContentLoaded', function() {
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
function editProduct(id) {
    $(".container").css("display", "block");
    $(".container").fadeIn();
    document.querySelector(".app-content").style.filter = "blur(5px)";
    $(".container").load("sua1.php?id=" + id);
}
function deleteProduct(productId) {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
        $.ajax({
            url: '../controller/product/delete.php',
            type: 'POST',
            data: { id: productId },
            success: function(response) {
                if (response.trim() === "success") {
                    alert("Xóa sản phẩm thành công.");
                    location.reload(); // hoặc xóa dòng khỏi bảng nếu muốn
                } else {
                    alert("Xóa sản phẩm thất bại.");
                }
            },
            error: function() {
                alert("Có lỗi xảy ra khi gửi yêu cầu.");
            }
        });
    }
}
</script>
<script src="https://kit.fontawesome.com/95a272230e.js" crossorigin="anonymous"></script>

<style>
.container {
    display: none;


}
</style>