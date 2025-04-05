<div class="acontainer">
        <div class="form-container ms-2">
            <div class="input-group mb-3 " style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Tên Sản Phẩm</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <label class="input-group-text" for="inputGroupSelect01">Thương Hiệu</label>
                <select class="form-select" id="inputGroupSelect01">
                    <option selected>Chọn</option>
                    <option value="1">Apple</option>
                    <option value="2">Samsung</option>
                    <option value="3">Xiaomi</option>
                </select>
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <label class="input-group-text" for="inputGroupFile01">Hình Ảnh</label>
                <input type="file" class="form-control" id="inputGroupFile01" onchange="previewImage(this)">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Chip Xử Lý</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Dung Lượng Pin</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Kích Thước Màn</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Hệ Điều Hành</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Camera Sau</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Camera Trước</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <div style="text-align: center; margin-top: 30px; margin-left: 20px; position:fixed; top:86%;left:45%;">
                             <input type="submit" class="btn btn-primary " name="" id="" value="LƯU" style="width: 70px; height: 35px; font-size: 17px;font-weight: bold;" onclick="thongbao()">
            </div>
        </div>
        
        <div class="image-preview-container">
            <div id="imagePreview"></div>
        </div>
    </div>
    <div class="bcontainer">

    </div>
</body>
<style>
.margin {
    margin-top: 20px;
    margin-bottom: 20px;
    margin-left: 30px;
}

.acontainer {
    position: absolute;
    width: 50%;
    height: 80%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 3px solid black;
    border-radius: 25px;
    display: flex;
    background-color: white;
}

.form-container {
    width: 70%;
}

.image-preview-container {
    width: 30%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 20px;
}

#imagePreview {
    width: 180px;
    height: 180px;
    border: 2px solid black;
    margin-top: 20px;
    margin-right: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

#imagePreview img {
    max-width: 100%;
    max-height: 100%;
   
}
.bcontainer{
     display:none; 
    }
</style>
<script>
    function thongbao() {
       
        $(".bcontainer").load("test2.php");
        $(".bcontainer").css("display", "block");
      $(".acontainer").css("display", "none");  
        $(".bcontainer").fadeIn();
    }
    
    function previewImage(input) {
        var preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>