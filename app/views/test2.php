<?php
require_once '../controller/product/productController.php';
$sql= "SELECT * FROM dungluongram";
$dlram = mysqli_query($conn, $sql);
$sql1="SELECT * FROM dungluongrom";
$dlrom = mysqli_query($conn, $sql1);
$sql2="SELECT * FROM mausac";
$dlmau = mysqli_query($conn, $sql2);
?>
<div class="econtainer ms-3">
  <form action="" onsubmit="handleFormSubmit(event)" id="myForm2">
 
<div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:8px;">
               <label class="input-group-text" for="inputGroupSelect01">Ram</label>
      <select class="form-select" id="inputGroupSelect01" name="ram">
          <option selected>Chọn</option>
           <?php foreach ($dlram as $ram): ?>
        <option value="<?= $ram['madlram'] ?>"><?= $ram['kichthuocram'] ?></option>
           <?php endforeach; ?>
</select>
</div>
<div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:8px;">
         <label class="input-group-text" for="inputGroupSelect01">Rom</label>
      <select class="form-select" id="inputGroupSelect01" name="rom">
          <option selected>Chọn</option>
           <?php foreach ($dlrom as $rom): ?>
        <option value="<?= $rom['madlrom'] ?>"><?= $rom['kichthuocrom'] ?></option>
           <?php endforeach; ?>
</select>
</div>
<div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:8px;">
         <label class="input-group-text" for="inputGroupSelect01">Màu Sắc</label>
      <select class="form-select" id="inputGroupSelect01" name="mausac">
          <option selected>Chọn</option>
           <?php foreach ($dlmau as $mau): ?>
        <option value="<?= $mau['mamau'] ?>"><?= $mau['tenmau'] ?></option>
           <?php endforeach; ?>
</select>
</div>
<div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:8px;">
  <span class="input-group-text">Giá Bán</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
  <span class="input-group-text">$</span>
</div>
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
  <span class="input-group-text" id="basic-addon1">Số Lượng Tồn</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1">
</div>
<div class="input-group" style="width:95%;margin-top:20px;margin-left:8px; height: 200px;">
  <span class="input-group-text" >Mô Tả Sản Phẩm</span>
  <textarea class="form-control" aria-label="With textarea"></textarea>
</div>


<div style="text-align: center; margin-top: 30px; margin-left: 20px; position:fixed; top:86%;left:30%;">
<input type="submit" class="btn btn-primary"name="" id="" value="LƯU" style="width: 70px; height: 35px; font-size: 17px;font-weight: bold;" onclick="thongbao()">
        </div>
        <div style="text-align: center; margin-top: 30px; margin-left: 100px; position:fixed; top:86%;left:40%;">

        <button class="btn btn-danger" style="width: 70px; height: 35px; font-size: 17px; font-weight: bold;"
    onclick="if(confirm('Bạn có chắc chắn muốn hủy không?')) window.location.href = 'product.php';">
    HỦY
</button>
           </div>
    </div>
           </form>
</div>
<style>


.econtainer
{
    position: absolute;
    width: 40%;
    height: 80%;
    top:50%;
    left:50%;
    transform: translate(-50%,-50%);
    border:3px solid black;
  margin-top: 30px;
    border-radius: 25px;
    background-color: #f8f9fa;

}

</style>
<script>

function handleFormSubmit(event) {
        event.preventDefault(); 
        var form = document.getElementById('myForm2');
        var formDataa = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../controller/product/productController.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                thongbao();
            } else {
                console.error('Error: ' + xhr.status);
            }
        };
       
        xhr.send(formDataa);
       
    }
    function thongbao() {
        alert("Đã Lưu!");
        window.location.href = "product.php";
    }
</script>