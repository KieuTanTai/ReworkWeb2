
<div class="econtainer ms-3">
<form method="POST" onsubmit="handleFormSubmit(event)" id="myForm">
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
   <span class="input-group-text" id="basic-addon1">Rom</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1" name="rom">
</div>
    
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
   <span class="input-group-text" id="basic-addon1">Ram</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1" name="ram">
</div>
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
  <span class="input-group-text" id="basic-addon1">Màu Sắc</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1" name="color">
</div>
<div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:8px;">
  <span class="input-group-text">Giá Bán</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="price">
  <span class="input-group-text">$</span>
</div>
<div class="input-group mb-3"style="width:400px;margin-top:20px;margin-left:8px;">
  <span class="input-group-text" id="basic-addon1">Số Lượng Tồn</span>
  <input type="text" class="form-control"aria-label="Username" aria-describedby="basic-addon1" name="sl">
</div>
<div class="input-group" style="width:98%;margin-top:20px;margin-left:8px; height: 200px;">
  <span class="input-group-text" >Mô Tả Sản Phẩm</span>
  <textarea class="form-control" aria-label="With textarea" name="mota"></textarea>
</div>

        <div style="text-align: center; margin-top: 15px; justify-content: center;">
            <input type="submit" class="btn btn-primary"name="" id="" value="LƯU" style="width: 70px; height: 35px; font-size: 17px;font-weight: bold;" onclick="thongbao()">
        </div>
    </div>
</div>
</form>
<style>


.econtainer
{
    position: absolute;
    width: 40%;
    height: 77%;
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
    event.preventDefault(); // Prevent the default form submission behavior
    const form = document.getElementById("myForm");
    const formData = new FormData(form); 
    const xhr = new XMLHttpRequest();
    // Change the URL to your PHP file
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response from the server if needed
            console.log(xhr.responseText); // Optional: log the response for debugging
        }
    }
    xhr.open("POST", "test.php", true);
    xmlHttp.send(formData); 
  }
    function thongbao() {
        alert("Đã Lưu!");
        window.location.reload(); // Reload the page after submission
       
    }
</script>