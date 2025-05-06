<?php
session_start();
// Đảm bảo đường dẫn đúng
require_once '../controller/user/userController.php';

$controller = new UserController();

// Xử lý các action POST trước khi lấy dữ liệu hiển thị
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $success = $controller->create($_POST);
        header("Location: user.php?status=" . ($success ? 'add_success' : 'add_error'));
        exit;
    } else if ($action === 'edit') {
        if (isset($_POST['makh'])) {
            $success = $controller->update($_POST['makh'], $_POST);
            header("Location: user.php?page=" . ($_GET['page'] ?? 1) . "&status=" . ($success ? 'edit_success' : 'edit_error')); // Giữ lại trang hiện tại
            exit;
        } 
    } else if ($action === 'toggle_status') { // Đổi tên action trong JS và form cho nhất quán
        if (isset($_POST['makh'])) {
            $success = $controller->updateStatus($_POST);
            exit; 
        } 
    }
}

$viewData = $controller->index(); 
$usersToDisplay = $viewData['users'];
$currentPage = $viewData['currentPage'];
$totalPages = $viewData['totalPages'];
session_write_close();
include("header1.php");
include("sidebar1.php");

?>
<style>
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .pagination .page-link {
        color: #0d6efd;
    }

    .pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>
<script>
    function openForm(button) {

        let row = button.closest('tr');

        let userID = row.cells[0].innerText;
        let userName = row.cells[1].innerText;
        let address = row.cells[2].innerText;
        let phoneNum = row.cells[3].innerText;
        let email = row.cells[4].innerText;
        let date = row.cells[6].innerText;

        let formattedDate = "";
        if (date) {
            let parts = date.split('/');
            if (parts.length === 3) {
                formattedDate = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
            }
        }

        document.getElementById("userID").value = userID;
        document.getElementById("username").value = userName;
        document.getElementById("address").value = address;
        document.getElementById("phoneNum").value = phoneNum;
        document.getElementById("email").value = email;
        document.getElementById("date").value = formattedDate;

        document.getElementById("formAction").value = "edit";

        document.getElementById("formTitle").textContent = "Sửa thông tin khách hàng";
        document.getElementById("acceptBtn").textContent = "Lưu";

        document.getElementById("userForm").style.display = "block";
        document.getElementById("overlay").style.display = "block";
    }
    function clearForm() {
        document.getElementById("userForm1").reset();
        document.getElementById("formAction").value = "";
    }
    function closeForm() {
        document.getElementById("userForm").style.display = "none";
        document.getElementById("overlay").style.display = "none";
        clearForm();
    }

    function openForm2(action) {
        if (action === "add") {
            clearForm();
            document.getElementById("formAction").value = "add";
            document.getElementById("formTitle").textContent = "Thêm khách hàng mới";
            document.getElementById("acceptBtn").textContent = "Xác nhận";

            document.getElementById("matKhauInput").style.display = "block";
            document.getElementById("userForm").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }
    }
    function toggleStatus(userId, btn) {
        btn.disabled = true;
        btn.innerHTML = 'Đang xử lý...';

        fetch('user.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=toggle_status&makh=' + userId
        })
        .then(response => {
            location.reload();
        })
    }

</script>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Quản Lý Khách Hàng</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản Lý Khách Hàng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <br>
                    <div id="overlay" class="overlay" onclick="closeForm()"></div>
                    <button type="button" class="btn btn-primary mb-1" onclick="openForm2('add')">Thêm Người
                        Dùng</button>

                    <div class="col-md-6" style="display:none;" id="userForm">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h5 class="card-title" id="formTitle">Thông Tin Khách Hàng</h5>
                            </div>
                            <form id="userForm1" method="POST"
                                action="user.php<?= isset($_GET['page']) ? '?page=' . $_GET['page'] : '' ?>">
                                <input type="hidden" id="formAction" name="action" value="">
                                <div class="card-body">
                                    <input type="text" class="form-control" placeholder="001, 002,..."
                                        aria-label="userID" id="userID" name="makh" style="display: none;" />
                                    <div class="mb-3">
                                        <label for="userName" class="form-label">Tên Khách Hàng</label>
                                        <input type="text" class="form-control" placeholder="Tên Khách Hàng"
                                            aria-label="Username" id="username" name="tenkhachhang" />
                                    </div>
                                    <div class="mb-3" style="display: none" id="matKhauInput">
                                        <label for="userName" class="form-label">Mật Khẩu</label>
                                        <input type="text" class="form-control" placeholder="Mật Khẩu"
                                            aria-label="Password" id="matkhau" name="matkhau" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Địa Chỉ</label>
                                        <input type="text" class="form-control"
                                            placeholder="Số nhà, Đường, Phường/Xã,..." aria-label="address" id="address"
                                            name="diachi" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="phoneNum" class="form-label">Số Điện Thoại</label>
                                        <input type="text" class="form-control" placeholder="Số Điện Thoại"
                                            aria-label="PhoneNum" id="phoneNum" name="sdt" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" placeholder="Email"
                                            aria-describedby="emailHelp" name="email" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Ngày Tham Gia (mm/dd/yyyy)</label>
                                        <input type="date" class="form-control" id="date" aria-describedby="date"
                                            name="ngaythamgia" value="<?= date('Y-m-d') ?>" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="acceptBtn">Lưu</button>
                                    <button type="button" class="btn btn-secondary" onclick="closeForm()">Huỷ</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                            <script>
                                document.getElementById('userForm1').addEventListener("submit", function (e) {
                                    let tenKH = document.getElementById('username').value;
                                    let matKhau = document.getElementById('matkhau').value;
                                    let diaChi = document.getElementById('address').value;
                                    let sdt = document.getElementById('phoneNum').value;
                                    let email = document.getElementById('email').value;

                                    let isValid = true;
                                    let errorMsg = "";

                                    if (tenKH === "") {
                                        isValid = false;
                                        errorMsg += "Không được để trống tên khách hàng\n";
                                    }

                                    if (diaChi === "") {
                                        isValid = false;
                                        errorMsg += "Không được để trống địa chỉ\n";
                                    }

                                    const sdtRegex = /^0[0-9]{9}$/;
                                    if (!sdtRegex.test(sdt)) {
                                        isValid = false;
                                        errorMsg += "Số điện thoại không hợp lệ\n";
                                    }

                                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    if (!emailRegex.test(email)) {
                                        isValid = false;
                                        errorMsg += "Địa chỉ email không hợp lệ\n";
                                    }

                                    if (!isValid) {
                                        e.preventDefault();
                                        alert(errorMsg);
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã KH</th>
                                    <th>Họ Tên</th>
                                    <th>Địa Chỉ</th>
                                    <th>SĐT</th>
                                    <th>Email</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Tham Gia</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="vertical-align: middle;">
                                <?php if (!empty($usersToDisplay)): ?>
                                    <?php foreach ($usersToDisplay as $u): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($u['makh']) ?></td>
                                            <td><?= htmlspecialchars($u['tenkhachhang']) ?></td>
                                            <td><?= htmlspecialchars($u['diachi']) ?></td>
                                            <td><?= htmlspecialchars($u['sdt']) ?></td>
                                            <td><?= htmlspecialchars($u['email']) ?></td>
                                            <td>
                                                <?php
                                                if ($u['trangthai'] == 1) {
                                                    echo '<span class="badge bg-success">Hoạt động</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">Đang khoá</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($u['ngaythamgia']))) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary mb-1"
                                                    onclick="openForm(this)">Sửa</button>
                                                <button type="button"
                                                    class="btn <?= ($u['trangthai'] == 1) ? 'btn-warning' : 'btn-success' ?> mb-1 buttonLock"
                                                    onclick="toggleStatus(<?= $u['makh'] ?>, this)">
                                                    <?= ($u['trangthai'] == 1) ? 'Khoá' : 'Mở' ?>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu khách hàng.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="card-footer"> 
                            <?php if ($totalPages > 1): ?>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination float-end m-0">
                                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="user.php?page=<?= $currentPage - 1 ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        <?php
                                        $range = 2;
                                        $start = max(1, $currentPage - $range);
                                        $end = min($totalPages, $currentPage + $range);

                                        if ($start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="user.php?page=1">1</a></li>';
                                            if ($start > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                <a class="page-link" href="user.php?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor;

                                        if ($end < $totalPages) {
                                            if ($end < $totalPages - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="user.php?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="user.php?page=<?= $currentPage + 1 ?>"
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
            </div>
        </div>
    </div>
</main>
<?php
include("footer1.php");
?>