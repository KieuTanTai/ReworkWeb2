<?php
session_start();
include ("header1.php");
include ("sidebar1.php");
require_once '../controller/product/productController.php';

$controller = new ProductController();
$data = $controller->indexlimit(); // lấy danh sách sản phẩm
$products = $data['products']; // danh sách sản phẩm
$currentPage = $data['currentPage']; // trang hiện tại
$totalPages = $data['totalPages']; // tổng số trang

$products2 = $controller ->index(); // danh sách sản phẩm

session_write_close();

?>

<div class="app-content">
    <br>
    <div class="col-sm-6">
        <h3 class="mb-0 ms-3">Sản Phẩm</h3>
    </div>
    <br>
    <div class="card mb-4 ">
        <div class="card-header">
            <div class="button-group">
            <button type="button" class="btn btn-primary" onclick="addproduct()">Thêm Sản Phẩm</button> 
            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm sản phẩm..."  style="width: 300px; margin-left: 10px;" >
            <button type="button" class="btn btn-primary" onclick="searchProduct()" style="margin-left: 10px;">Tìm Kiếm</button>
            <button type="button" class="btn btn-success" onclick="document.getElementById('searchInput').value=''; resetSearchProduct()" style="margin-left: 10px;">Làm Mới</button>
            </div>
      

        </div>
        <div>
            
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
                        <td><img src="../../public/assets/images/<?php echo $product['hinhanh']; ?>" style="width: 50px; height: 50px;" /></td>                        <td><?= $product['chipxuly'] ?></td>
                        <td><?= $product['dungluongpin'] ?> mAh</td>
                        <td><?= $product['kichthuocman'] ?> inch</td>
                        <td><?= $product['hedieuhanh'] ?></td>
                        <td><?= $product['camerasau'] ?></td>
                        <td><?= $product['cameratruoc'] ?></td>
                        <td><?= $product['thuonghieu'] ?></td>
                        <td style="cursor:pointer;">
                        <div class="button-group">
                       <button href="#" class="btn btn-primary btn-sm" onclick="editProduct(<?= $product['masp'] ?>); return false;">Sửa</button><button onclick="deleteProduct(<?= $product['masp'] ?>)" class="btn btn-danger btn-sm">Xóa</button> <button class="btn btn-success" style="width:136px;" onclick="dtproduct(<?= $product['masp'] ?>)">Thêm Phiên Bản</button>
                        </div>
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
            <div id="customPagination" class="mt-3"></div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer"> 
                            <?php if ($totalPages > 1): ?>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination float-end m-0">
                                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="product.php?page=<?= $currentPage - 1 ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        <?php
                                        $range = 2;
                                        $start = max(1, $currentPage - $range);
                                        $end = min($totalPages, $currentPage + $range);

                                        if ($start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>';
                                            if ($start > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                <a class="page-link" href="product.php?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor;

                                        if ($end < $totalPages) {
                                            if ($end < $totalPages - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="product.php?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="product.php?page=<?= $currentPage + 1 ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        </div>
    </div>
</div>

<div class="container">


</div>


<script src="../../public/assets/js/adminlte.js"></script>
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
    let filteredProducts = [];
let currentSearchPage = 1;
const searchItemsPerPage = 5;

        const allProducts = <?= json_encode($products2) ?>;

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
function dtproduct(id){
    $(".container").css("display", "block");
    $(".container").fadeIn();
    document.querySelector(".app-content").style.filter = "blur(5px)";
    $(".container").load("test2.php?id=" + id);
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
function searchProduct() {
    const input = document.getElementById("searchInput").value.trim().toUpperCase();
    const cardFooter = document.querySelector(".card-footer");

    cardFooter.style.display = "none"; // Ẩn phân trang gốc

    filteredProducts = allProducts.filter(p =>
        Object.values(p).some(val =>
            String(val).toUpperCase().includes(input)
        )
    );

    currentSearchPage = 1;
    renderSearchResults();
}


function renderSearchResults() {
    const tbody = document.querySelector("table tbody");
    tbody.innerHTML = "";

    if (filteredProducts.length === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center">Không tìm thấy sản phẩm.</td></tr>`;
        document.getElementById("customPagination").innerHTML = "";
        return;
    }

    const start = (currentSearchPage - 1) * searchItemsPerPage;
    const end = start + searchItemsPerPage;
    const currentPageItems = filteredProducts.slice(start, end);

    currentPageItems.forEach(p => {
        const row = `
        <tr class="align-middle">
            <td>${p.masp}</td>
            <td>${p.tensp}</td>
            <td><img src="../../public/assets/images/${p.hinhanh}" style="width: 50px; height: 50px;" /></td>
            <td>${p.chipxuly}</td>
            <td>${p.dungluongpin} mAh</td>
            <td>${p.kichthuocman} inch</td>
            <td>${p.hedieuhanh}</td>
            <td>${p.camerasau}</td>
            <td>${p.cameratruoc}</td>
            <td>${p.thuonghieu}</td>
            <td>
                <div class="button-group">
                    <button class="btn btn-primary btn-sm" onclick="editProduct(${p.masp})">Sửa</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.masp})">Xóa</button>
                    <button class="btn btn-success" style="width:136px;" onclick="dtproduct(${p.masp})">Thêm Phiên Bản</button>
                </div>
            </td>
        </tr>`;
        tbody.innerHTML += row;
    });

    renderSearchPagination();
}
function renderSearchPagination() {
    const totalPages = Math.ceil(filteredProducts.length / searchItemsPerPage);
    let paginationHTML = `<ul class="pagination float-end m-0">`;

    if (currentSearchPage > 1) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="gotoSearchPage(${currentSearchPage - 1})">&laquo;</a></li>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `<li class="page-item ${i === currentSearchPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="gotoSearchPage(${i})">${i}</a></li>`;
    }

    if (currentSearchPage < totalPages) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="gotoSearchPage(${currentSearchPage + 1})">&raquo;</a></li>`;
    }

    paginationHTML += `</ul>`;

    document.getElementById("customPagination").innerHTML = paginationHTML;
}
function gotoSearchPage(page) {
    currentSearchPage = page;
    renderSearchResults();
}


function renderFullTable() {
    const tbody = document.querySelector("table tbody");
    tbody.innerHTML = "";
    allProducts.forEach(p => {
        const row = `
        <tr class="align-middle">
            <td>${p.masp}</td>
            <td>${p.tensp}</td>
            <td><img src="../../public/assets/images/${p.hinhanh}" style="width: 50px; height: 50px;" /></td>
            <td>${p.chipxuly}</td>
            <td>${p.dungluongpin} mAh</td>
            <td>${p.kichthuocman} inch</td>
            <td>${p.hedieuhanh}</td>
            <td>${p.camerasau}</td>
            <td>${p.cameratruoc}</td>
            <td>${p.thuonghieu}</td>
            <td>
                <div class="button-group">
                    <button class="btn btn-primary btn-sm" onclick="editProduct(${p.masp})">Sửa</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.masp})">Xóa</button>
                    <button class="btn btn-success" style="width:136px;" onclick="dtproduct(${p.masp})">Thêm Phiên Bản</button>
                </div>
            </td>
        </tr>`;
        tbody.innerHTML += row;
    });
}


function resetSearchProduct() {
   window.location.reload();
    const cardFooter = document.querySelector(".card-footer");
    cardFooter.style.display = "block"; // Hiện lại phân trang gốc
    document.getElementById("customPagination").innerHTML = ""; // Xóa phân trang tìm kiếm
    renderFullTable(); // Hiện lại bảng đầy đủ
    document.getElementById("searchInput").value = ""; // Xóa giá trị tìm kiếm
}

</script>
<script src="https://kit.fontawesome.com/95a272230e.js" crossorigin="anonymous"></script>

<style>
.container {
    display: none;
}
.button-group {
  display: flex;
  gap: 5px; 

}
</style>