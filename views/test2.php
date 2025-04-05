<div class="econtainer ms-3">
    <div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
   <span class="input-group-text" id="basic-addon1">Mã Sản Phẩm</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1">
</div>
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
   <span class="input-group-text" id="basic-addon1">Rom</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1">
</div>
    
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
   <span class="input-group-text" id="basic-addon1">Rom</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1">
</div>
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
  <span class="input-group-text" id="basic-addon1">Màu Sắc</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1">
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


        <div style="text-align: center; margin-top: 30px; ">
            <input type="submit" class="btn btn-primary"name="" id="" value="LƯU" style="width: 70px; height: 35px; font-size: 17px;font-weight: bold;" onclick="thongbao()">
        </div>
    </div>
</div>
<style>


.econtainer
{
    position: absolute;
    width: 40%;
    height: 57%;
    top:50%;
    left:50%;
    transform: translate(-50%,-50%);
    border:3px solid black;

    border-radius: 25px;
    background-color: #f8f9fa;

}

</style>
<script>
    function thongbao() {
        alert("Đã Lưu!");
        window.location.href = "product.php";
    }
</script>