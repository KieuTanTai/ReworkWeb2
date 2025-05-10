<?php
session_start();
require_once '../controller/order/orderController.php';

// Kiểm tra session hiện tại (chuyển display:block để hiện thông tin session)
echo "<pre style='display:none'>";
print_r($_SESSION);
echo "</pre>";

$controller = new OrderController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($action === 'update_status') {
    $success = $controller->updateStatus($_POST);
    header("Location: order.php?status=" . ($success ? 'add_success' : 'add_error'));
    exit;
  }
}
$orders2 = $controller->index1();
$viewData = $controller->index();
$ordersToDisplay = $viewData['orders'];
$currentPage = $viewData['currentPage'];
$totalPages = $viewData['totalPages'];
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page == 'user.php' || $current_page == 'customer.php') {
  if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin.php");
    exit();
  }
}
// Giữ nguyên session khi chuyển trang
session_write_close();
include("header1.php");
include("sidebar1.php");
?>
<script>
  let filteredOrders = [];
  let currentSearchPage = 1;
  const searchItemsPerPage = 10;
  const allOrders = <?= json_encode($orders2) ?>;

  function getStatusOptions(currentStatus) {
    const statuses = [
      { value: 1, label: "Chưa xác nhận" },
      { value: 2, label: "Đã xác nhận" },
      { value: 3, label: "Đã giao" },
      { value: 4, label: "Đã huỷ" }
    ];

    return statuses.map(status => {
      const isSelected = Number(currentStatus) === status.value ? "selected" : "";
      let isDisabled = "";

      if (status.value < currentStatus) {
        isDisabled = "disabled";
      }

      return `<option value="${status.value}" ${isSelected} ${isDisabled}>${status.label}</option>`;
    }).join("");
  }

  function renderSearchResults() {
    const tbody = document.getElementById('orderBody');
    tbody.innerHTML = "";

    const start = (currentSearchPage - 1) * searchItemsPerPage;
    const end = start + searchItemsPerPage;
    const currentPageItems = filteredOrders.slice(start, end);

    currentPageItems.forEach(order => {
      const row = `
        <tr class="align-middle">
            <td>${order.madonhang}</td>
            <td>${order.makh}</td>
            <td>${order.thoigian}</td>
            <td>${order.diachi}</td>
            <td>${order.tongtien}</td>
            <td>
                      <form method="POST"
                        action="order.php<?= isset($_GET['page']) ? '?page=' . (int) $_GET['page'] : '' ?>">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="madonhang" value="${order.madonhang}">

                        <select name="new_status" class="form-select"
                          onchange="this.form.submit();" ?>>
                          ${getStatusOptions(order.trangthai)}
                        </select>
                      </form>
            </td>
            <td>
                      <a href="order_detail.php?id=${order.madonhang}">
                        <button class="btn btn-primary">Xem chi tiết</button>
                      </a>
                    </td>
        </tr>`;
      tbody.innerHTML += row;
    });

    renderSearchPagination();
  }
  function filterOrder() {
    const cardFooter = document.querySelector(".card-footer");
    cardFooter.style.display = "none";

    let table = document.getElementById("dataTable");
    let rows = table.getElementsByTagName("tr");

    let address = document.getElementById("address").value.toLowerCase().trim();
    let status = document.getElementById("statusButton1").value;
    let fromDate = document.getElementById("fromDate").value;
    let toDate = document.getElementById("toDate").value;

    let fromTimestamp = null;
    let toTimestamp = null;

    if (fromDate) {
      let tempFromDate = new Date(fromDate);
      tempFromDate.setHours(0, 0, 0, 0); 
      fromTimestamp = tempFromDate.getTime();
    }
    if (toDate) {
      let tempToDate = new Date(toDate);
      tempToDate.setHours(23, 59, 59, 999);
      toTimestamp = tempToDate.getTime();
    }

    filteredOrders = allOrders.filter(order => {
      let matchesAddress = true;
      let matchesStatus = true;
      let matchesDate = true;

      if (address) {
        let addressInput = order.diachi.toLowerCase().trim();
        matchesAddress = addressInput.includes(address);
      }

      if (status !== "0") {
        matchesStatus = String(order.trangthai) === status;
      }

      let orderDate = new Date(order.thoigian.trim()).getTime();

      if (fromTimestamp !== null) {
        matchesDate = orderDate >= fromTimestamp;
      }
      if (toTimestamp !== null) {
        matchesDate = matchesDate && orderDate <= toTimestamp;
      }

      return matchesAddress && matchesStatus && matchesDate;
    });

    currentSearchPage = 1;
    renderSearchResults();
  }

  function renderSearchPagination() {
    const totalPages = Math.ceil(filteredOrders.length / searchItemsPerPage);
    let paginationHTML = `<ul class="pagination float-end m-0">`;

    if (currentSearchPage > 1) {
      paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToSearchPage(${currentSearchPage - 1})">&laquo;</a></li>`;
    }

    for (let i = 1; i <= totalPages; i++) {
      paginationHTML += `<li class="page-item ${i === currentSearchPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="goToSearchPage(${i})">${i}</a></li>`;
    }

    if (currentSearchPage < totalPages) {
      paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToSearchPage(${currentSearchPage + 1})">&raquo;</a></li>`;
    }

    paginationHTML += `</ul>`;

    document.getElementById("customPagination").innerHTML = paginationHTML;
  }
  function goToSearchPage(page) {
    currentSearchPage = page;
    renderSearchResults();
  }

  function renderFullTable() {
    const tbody = document.getElementById('orderBody');
    tbody.innerHTML = "";

    allOrders.forEach(order => {
      const row = `
        <tr class="align-middle">
            <td>${order.madonhang}</td>
            <td>${order.makh}</td>
            <td>${order.thoigian}</td>
            <td>${order.diachi}</td>
            <td>${order.tongtien}</td>
            <td>
                      <form method="POST"
                        action="order.php<?= isset($_GET['page']) ? '?page=' . (int) $_GET['page'] : '' ?>">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="madonhang" value="${order.madonhang}">

                        <select name="new_status" class="form-select"
                          onchange="this.form.submit();" ?>>
                          ${getStatusOptions(order.trangthai)}
                        </select>
                      </form>
            </td>
            <td>
                      <a href="order_detail.php?id=${order.madonhang}">
                        <button class="btn btn-primary">Xem chi tiết</button>
                      </a>
                    </td>
        </tr>`;
      tbody.innerHTML += row;
    });
  }

  function refresh() {
    window.location.reload();
    const cardFooter = document.querySelector(".card-footer");
    cardFooter.style.display = "block"; 
    document.getElementById("customPagination").innerHTML = "";
    renderFullTable();
    document.getElementById("statusButton1").value = 0;
    document.getElementById("fromDate").value = "";
    document.getElementById("toDate").value = "";
    document.getElementById("address").value = "";
  }
</script>
<!--begin::App Main-->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Quản Lý Đơn Hàng</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Quản Lý Đơn Hàng</a></li>
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
        <div class="card-header py-0">
          <!-- <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6> -->
          <br>
          <!--begin::Row-->
          <div class="card-body">
            <div class="row g-3">
              <!--begin::Col-->
              <div class="col-md-2">
                <div class="mb-3">
                  <label for="statusButton1" class="form-label">Tình Trạng</label>
                  <select name="" id="statusButton1" class="form-control">
                    <option value="0">Tất cả</option>
                    <option value="1">Chưa xác nhận</option>
                    <option value="2">Đã xác nhận</option>
                    <option value="3">Đã giao</option>
                    <option value="4">Đã huỷ</option>
                  </select>
                </div>
              </div>
              <br>
              <div class="col-md-2">
                <div class="mb-3">
                  <label for="date" class="form-label">Từ Ngày</label>
                  <input type="date" class="form-control" id="fromDate" aria-describedby="date" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="mb-3">
                  <label for="date" class="form-label">Đến Ngày</label>
                  <input type="date" class="form-control" id="toDate" aria-describedby="date" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="mb-3">
                  <label for="address" class="form-label">Địa Chỉ</label>
                  <input type="text" class="form-control" placeholder="Số nhà, Đường, Phường/Xã,..."
                    aria-label="address" id="address" />
                </div>
              </div>
              <div class="btn-group">
                <div class="col-md-2">
                  <div class="mb-3">
                    <button class="btn btn-primary" onclick="filterOrder()">Lọc Đơn Hàng</button>
                    <button class="btn btn-primary" onclick="refresh()">Làm Mới</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Mã Đơn Hàng</th>
                  <th>Mã Khách Hàng</th>
                  <th>Thời Gian</th>
                  <th>Địa Chỉ</th>
                  <th>Tổng Tiền</th>
                  <th>Trạng Thái</th>
                  <th>Hành Động</th>
                </tr>
              </thead>
              <tbody style="vertical-align: middle;" id="orderBody">
                <?php foreach ($ordersToDisplay as $order): ?>
                  <tr>
                    <td><?= htmlspecialchars($order['madonhang']) ?></td>
                    <td><?= htmlspecialchars($order['makh']) ?></td>
                    <td><?= htmlspecialchars($order['thoigian']) ?></td>
                    <td><?= htmlspecialchars($order['diachi']) ?></td>
                    <td><?= htmlspecialchars($order['tongtien']) ?></td>
                    <td>
                      <form method="POST"
                        action="order.php<?= isset($_GET['page']) ? '?page=' . (int) $_GET['page'] : '' ?>">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="madonhang" value="<?= htmlspecialchars($order['madonhang']) ?>">

                        <select name="new_status" class="form-select" onchange="this.form.submit();" ?>>
                          <option value="1" <?= $order['trangthai'] == 1 ? 'selected disabled' : ($order['trangthai'] > 1 ? 'disabled' : '') ?>>
                            Chưa xác nhận
                          </option>
                          <option value="2" <?= $order['trangthai'] == 2 ? 'selected disabled' : ($order['trangthai'] > 2 ? 'disabled' : '') ?>>
                            Đã xác nhận
                          </option>
                          <option value="3" <?= $order['trangthai'] == 3 ? 'selected disabled' : ($order['trangthai'] > 3 ? 'disabled' : '') ?>>
                            Đã giao
                          </option>
                          <option value="4" <?= $order['trangthai'] == 4 ? 'selected disabled' : '' ?>>
                            Đã huỷ
                          </option>
                        </select>
                      </form>
                    </td>
                    <td>
                      <a href="order_detail.php?id=<?= htmlspecialchars($order['madonhang']) ?>">
                        <button class="btn btn-primary">Xem chi tiết</button>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div id="customPagination" class="mt-3"></div>
            <div class="card-footer">
              <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation">
                  <ul class="pagination float-end m-0">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                      <a class="page-link" href="order.php?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>

                    <?php
                    $range = 2;
                    $start = max(1, $currentPage - $range);
                    $end = min($totalPages, $currentPage + $range);

                    if ($start > 1) {
                      echo '<li class="page-item">
                                            <a class="page-link" href="order.php?page=1">1</a>
                                          </li>';
                      if ($start > 2) {
                        echo '<li class="page-item disabled">
                                                <span class="page-link">...</span>
                                              </li>';
                      }
                    }

                    for ($i = $start; $i <= $end; $i++): ?>
                      <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="order.php?page=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor;

                    if ($end < $totalPages) {
                      if ($end < $totalPages - 1) {
                        echo '<li class="page-item disabled">
                                                <span class="page-link">...</span>
                                              </li>';
                      }
                      echo '<li class="page-item">
                                            <a class="page-link" href="order.php?page=' . $totalPages . '">' . $totalPages . '</a>
                                          </li>';
                    }
                    ?>
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                      <a class="page-link" href="order.php?page=<?= $currentPage + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              <?php endif; ?>
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