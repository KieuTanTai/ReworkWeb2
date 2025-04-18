<?php 
    include("header1.php"); 
    include("sidebar1.php");
?>
<script>
  // function selectStatus(element, value){
  //   document.getElementById("statusButton1").textContent = element.textContent;

  //   let items = document.querySelectorAll("#dropdownContent li");

  //   items.forEach(item => {
  //       if(parseInt(item.getAttribute("data-value")) < value){
  //         item.classList.add("disabled");
  //       }
  //   });
  // }

  // function fitlerByAddress(){
  //   let table = document.getElementById("dataTable");
  //   let rows = table.getElementsByTagName("tr");
  //   let input = document.getElementById("address").value.toLowerCase();

  //   for(let i = 1; i < rows.length; i++){
  //     let addressCell = rows[i].getElementsByTagName("td")[3];
  //     if(addressCell){
  //       let address = addressCell.textContent.toLowerCase();
  //       if(address.includes(input)){
  //         rows[i].style.display = "";
  //       }
  //       else{
  //         rows[i].style.display = "none";
  //       }
  //     }
  //   }
  // }

  // function filterByStatus(){
    
  //   let table = document.getElementById("dataTable");
  //   let rows = table.getElementsByTagName("tr");
  //   let selectedStatus = document.getElementById("statusButton1").value;

  //   for(let i = 1; i < rows.length; i++){
  //     let statusCell = rows[i].getElementsByTagName("td")[5];
  //     if(statusCell){
  //       let selectElement = statusCell.getElementsByTagName("select")[0];
  //       let status = selectElement.value;
  //       if(status === selectedStatus || selectedStatus === "0"){
  //         rows[i].style.display = "";
  //       }
  //       else{
  //         rows[i].style.display = "none";
  //       }
  //     }
  //   }
  // }

  // function filterByDate() {

  //   let table = document.getElementById("dataTable");
  //   let rows = table.getElementsByTagName("tr");

  //   let fromDate = document.getElementById("fromDate").value;
  //   let toDate = document.getElementById("toDate").value;

  //   let fromTimestamp = null;
  //   let toTimestamp = null;

  //   if(fromDate){
  //     fromTimestamp = new Date(fromDate).getTime();
  //   }
  //   if(toDate){
  //     toTimestamp = new Date(toDate).getTime();
  //   }

  //   for(let i = 1; i < rows.length; i++){
      
  //     let dateCell = rows[i].getElementsByTagName("td")[2];

  //     if(dateCell){
  //       let orderDate = new Date(dateCell.textContent.trim()).getTime();

  //       if(!fromTimestamp && !toTimestamp){
  //         rows[i].style.display = "";
  //         continue;
  //       }

  //       let isAfterFromDate = true;
  //       let isBeforeToDate = true;

  //       if(fromTimestamp !== null){
  //         isAfterFromDate = orderDate >= fromTimestamp;
  //       }
  //       if(toTimestamp !== null){
  //         isBeforeToDate = orderDate <= toTimestamp;
  //       }

  //       if(isAfterFromDate && isBeforeToDate){
  //         rows[i].style.display = "";
  //       }
  //       else{
  //         rows[i].style.display = "none";
  //       }
  //     }
  //   }
  // }

  function filterOrder(){
    let table = document.getElementById("dataTable");
    let rows = table.getElementsByTagName("tr");

    let address = document.getElementById("address").value.toLowerCase().trim();
    let status = document.getElementById("statusButton1").value;
    let fromDate = document.getElementById("fromDate").value;
    let toDate = document.getElementById("toDate").value;
  
    let fromTimestamp = null;
    let toTimestamp = null;

    if(fromDate){
      fromTimestamp = new Date(fromDate).getTime();
    }
    if(toDate){
      toTimestamp = new Date(toDate).getTime();
    }

    for(let i = 1; i < rows.length; i++){
      let addressCell = rows[i].getElementsByTagName("td")[3];
      let statusCell = rows[i].getElementsByTagName("td")[5];
      let dateCell = rows[i].getElementsByTagName("td")[5];

      let matchesAddress = true;
      let matchesStatus = true;
      let matchesDate = true;

      if(address && addressCell){
        let addressInput = addressCell.textContent.toLowerCase().trim();
        matchesAddress = addressInput.includes(address);
      }

      if(status !== "0" && statusCell){
        let selectElement = statusCell.getElementsByTagName("select")[0];
        let statusValue = "";
        if(selectElement){
          statusValue = selectElement.value;
        }
        matchesStatus = statusValue === status;
      }

      if(dateCell){
        let orderDate = new Date(dateCell.textContent.trim()).getTime();

        if(fromTimestamp !== null){
          matchesDate = orderDate >= fromTimestamp;
        }
        if(toTimestamp !== null){
          matchesDate = matchesDate && orderDate <= fromTimestamp;
        }
      }

      if(matchesAddress && matchesStatus && matchesDate){
        rows[i].style.display = "";
      }
      else{
        rows[i].style.display = "none";
      }
    }
  
  
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
              <div class="col-sm-6"><h3 class="mb-0">Quản Lý Đơn Hàng</h3></div>
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
            <!-- <div class="btn-group">
                <br> 
                <br>
                <br>
                <br>
                <button
                    type="button"
                    class="btn btn-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    id="statusButton"
                >
                    Tình Trạng Đơn Hàng
                </button>
                <ul class="dropdown-menu" onchange="findOrderStatus()" id="statusButton2">
                    <li class="dropdown-item" onclick="updateStatus(this)" value="">Tất Cả</li>
                    <li class="dropdown-item" onclick="updateStatus(this)" value="1">Chưa xác nhận</li>
                    <li class="dropdown-item" onclick="updateStatus(this)" value="2">Đã xác nhận</li>
                    <li class="dropdown-item" onclick="updateStatus(this)" value="3">Đã giao</li>
                    <li class="dropdown-item" onclick="updateStatus(this)" value="4">Huỷ đơn</li>
                </ul>  
                
            </div> --> 
                <div class="mb-3">
                <label for="statusButton1" class="form-label">Tình Trạng</label>
                <select name="" id="statusButton1" 
                        class="form-control"
                >
                  <option value="0">Tất cả</option>
                  <option value="1">Chưa xác nhận</option>
                  <option value="2">Đã xác nhận</option>
                  <option value="3">Đã giao</option>
                  <option value="4">Huỷ đơn</option>
                </select> 
                </div>
            </div>
            <br>   
              <div class="col-md-2"> 
                <div class="mb-3">
                        <label for="date" class="form-label">Từ Ngày</label>
                        <input
                          type="date"
                          class="form-control"
                          id="fromDate"
                        
                          aria-describedby="date"
                        />
                </div>       
              </div>
            <div class="col-md-2"> 
                <div class="mb-3">
                        <label for="date" class="form-label">Đến Ngày</label>
                        <input
                          type="date"
                          class="form-control"
                          id="toDate"
                          
                          aria-describedby="date"
                        />
                </div> 
            </div>
            <div class="col-md-2">
            <div class="mb-3">
                        <label for="address" class="form-label">Địa Chỉ</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Số nhà, Đường, Phường/Xã,..."
                          aria-label="address"
                          id="address"
                          
                        />
            </div>
            </div>
            <div class="btn-group">
            <div class="col-md-2">
            <div class="mb-3">
              <button class="btn btn-primary" onclick="filterOrder()">Lọc Đơn Hàng</button>
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
                    <tbody style="vertical-align: middle;">
                      <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>2011-01-25</td>
                            <td>TP.Hồ Chí Minh</td>
                            <td>500000</td>
                            <td>
                                    <!-- <button
                                        type="button"
                                        class="btn btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        id="statusButton1"
                                        onchange="findOrderStatus(this)"
                                    >
                                        Tình Trạng Đơn Hàng
                                    </button>
                                    <ul class="dropdown-menu" id="dropdownContent">
                                      <li class="dropdown-item" data-value="1" onclick="selectStatus(this, 1)">Chưa xác nhận</li>
                                      <li class="dropdown-item" data-value="2" onclick="selectStatus(this, 2)">Đã xác nhận</li>
                                      <li class="dropdown-item" data-value="3" onclick="selectStatus(this, 3)">Đã giao</li>
                                      <li class="dropdown-item" data-value="4" onclick="selectStatus(this, 4)">Huỷ đơn</li>
                                    </ul>  -->
                                    <select name="" id="selectStatus" class="form-control">
                                      <option value="1">Chưa xác nhận</option>
                                      <option value="2">Đã xác nhận</option>
                                      <option value="3">Đã giao</option>
                                      <option value="4">Huỷ đơn</option>
                                    </select>
                            </td>
                            <td>
                              <a href="order_detail.php">
                                <button class="btn btn-primary">Xem chi tiết</button>
                              </a>
                            </td>
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
        <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->  
<?php
  include("footer1.php");
?>        