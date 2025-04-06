<?php
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
</style>
<script>
  function openForm1(){
    document.getElementById("userForm").style.display = "block";
    //document.getElementById("overlay").style.display = "block";
    document.getElementById("userID").value = "";
    document.getElementById("userName").value = "";
    document.getElementById("address").value = "";
    document.getElementById("phoneNum").value = "";
    document.getElementById("email").value = "";
    document.getElementById("date").value = "";
  }
  function openForm(button){

    let row = button.parentElement.parentElement;

    let userID = row.cells[0].innerText;
    let userName = row.cells[1].innerText;
    let address = row.cells[2].innerText;
    let phoneNum = row.cells[3].innerText;
    let email = row.cells[4].innerText;
    let date = row.cells[6].innerText;
    
    document.getElementById("userID").value = userID;
    document.getElementById("userName").value = userName;
    document.getElementById("address").value = address;
    document.getElementById("phoneNum").value = phoneNum;
    document.getElementById("email").value = email;
    document.getElementById("date").value = date;

    document.getElementById("userForm").style.display = "block";
    document.getElementById("overlay").style.display = "block";
  }

  function closeForm(){
    document.getElementById("userForm").style.display = "none";
    document.getElementById("overlay").style.display = "none";
  }

  function openForm2(action){

    let submitButton = document.getElementById("acceptBtn");

    if (action === "add") {
          submitButton.textContent = "Xác nhận";
          openForm1();
          submitButton.onclick = userAdd;
    }      
  }
  function userEdit(){
  
    let userID = document.getElementById("userID").value;
    let userName = document.getElementById("userName").value;
    let address = document.getElementById("address").value;
    let phoneNum = document.getElementById("phoneNum").value;
    let email = document.getElementById("email").value;
    let date = document.getElementById("date").value;

    let table = document.querySelector("table tbody");
    for(let row of table.rows){
      if(row.cells[0].innerText === userID){
        row.cells[1].innerText = userName;
        row.cells[2].innerText = address;
        row.cells[3].innerText = phoneNum;
        row.cells[4].innerText = email;
        row.cells[6].innerText = date;
      }
    }

    alert("Cập nhật thành công");
    closeForm();
  }
  function userAdd(){

    let userID = document.getElementById("userID").value;
    let userName = document.getElementById("userName").value;
    let address = document.getElementById("address").value;
    let phoneNum = document.getElementById("phoneNum").value;
    let email = document.getElementById("email").value;
    let date = document.getElementById("date").value;

    let table = document.querySelector("table tbody");

    let newRow = table.insertRow();
    let userIDCell = newRow.insertCell(0);
    let userNameCell = newRow.insertCell(1);
    let addressCell = newRow.insertCell(2);
    let phoneNumCell = newRow.insertCell(3);
    let emailCell = newRow.insertCell(4);
    let statusCell = newRow.insertCell(5);
    let dateCell = newRow.insertCell(6);
    let actionCell = newRow.insertCell(7);

    userIDCell.innerText = userID;
    userNameCell.innerText = userName;
    addressCell.innerText = address;
    phoneNumCell.innerText = phoneNum;
    emailCell.innerText = email;
    statusCell.innerHTML = `<div class="custom-control custom-checkbox" style="text-align: center;">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" style="transform: scale(1.5);">
                              </div>`;
    dateCell.innerText = date;
    actionCell.innerHTML = `<button type="button" class="btn btn-primary mb-1" onclick="openForm(this)">Sửa</button>
                            <button type="button" class="btn btn-primary mb-1" id="buttonLock" onclick="lock()">Khoá / Mở</button>`
  
    alert("Cập nhật thành công");
    closeForm();
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
              <div class="col-sm-6"><h3 class="mb-0">Quản Lý Người Dùng</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><a href="#">Quản Lý Người Dùng</a></li>
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
          <div class="container-fluid"></div>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6> -->
            <br>
            <div id="overlay" class="overlay" onclick="closeForm()"></div>
            <button type="button" class="btn btn-primary mb-1" onclick="openForm2('add')">Thêm Người Dùng</button>
            <button type="button" class="btn btn-primary mb-1" onclick="openForm2('add')">Đăng Ký Người Dùng</button>
            <div class="col-md-6" style="display:none" id="userForm">
<div class="card card-primary card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title">Thêm Người Dùng</div></div>
                  <!--end::Header-->
                  <!--begin::Form-->
                
                  <form>
                    <!--begin::Body-->
                    <div class="card-body">
                      <div class="row g-1">
                      <div class="mb-3">
                        <label for="userID" class="form-label">Mã Người Dùng</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="001, 002,..."
                          aria-label="userID"
                          id="userID"
                        />
                      </div>
                      <div class="mb-3">
                        <label for="userName" class="form-label">Tên Người Dùng</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Tên Người Dùng"
                          aria-label="Username"
                          id="userName"
                        />
                      </div>
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
                      <div class="mb-3">
                        <label for="phoneNum" class="form-label">Số Điện Thoại</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Số Điện Thoại"
                          aria-label="PhoneNum"
                          id="phoneNum"
                        />
                      </div>
                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                          type="email"
                          class="form-control"
                          id="email"
                          placeholder="Email"
                          aria-describedby="emailHelp"
                        />
                      </div>
                      <div class="mb-3">
                        <label for="date" class="form-label">Ngày Tham Gia</label>
                        <input
                          type="date"
                          class="form-control"
                          id="date"
                          
                          aria-describedby="date"
                        />
                      </div>
                      </div>
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                      <!-- <button type="button" class="btn btn-primary" onclick="userEdit()" id="saveBtn">Lưu</button> -->
                      <button type="button" class="btn btn-primary" id="acceptBtn" onclick="userEdit()">Lưu</button>
                      <button type="button" class="btn btn-primary" onclick="closeForm(this)">Huỷ</button>
                      <button type="reset" class="btn btn-primary">Reset</button>
                    </div>
                    <!--end::Footer-->
                  </form>
                 
                  <!--end::Form-->
                </div>

                <!--end::Quick Example-->
</div>
        </div> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã Người Dùng</th>
                            <th>Họ Tên</th>
                            <th>Địa Chỉ</th>
                            <th>Số Điện Thoại</th>
                            <th>Email</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Tham Gia</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle;">
                    <tr>
                            <td>1</td>
                            <td>Donna Snider</td>
                            <td>Viet Nam</td>
                            <td>0901234567</td>
                            <td>abcd@gmail.com</td>
                            <td>
                              <div class="custom-control custom-checkbox" style="text-align: center;">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" style="transform: scale(1.5);">
                              </div>
                              <script>
                                function lock(){
                                  checkBox = document.getElementById("customCheck1");
                                  checkBox.checked = !checkBox.checked;
                                }
                              </script>
                            </td>
                            <td>2025-04-16</td>
                            <td>
                            <button type="button" class="btn btn-primary mb-1" onclick="openForm(this)">Sửa</button>
                            <button type="button" class="btn btn-primary mb-1" id="buttonLock" onclick="lock()">Khoá / Mở</button>
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