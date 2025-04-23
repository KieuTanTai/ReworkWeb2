<div class="acontainer">
        <div class="form-container ms-2">
<form action="" method="POST" enctype="multipart/form-data" onsubmit="handleFormSubmit(event)" id="myForm">
            <div class="input-group mb-3 " style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Tên Sản Phẩm</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="tensp">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <label class="input-group-text" for="inputGroupSelect01">Thương Hiệu</label>
                <select class="form-select" id="inputGroupSelect01" name="thuonghieu">
                    <option selected>Chọn</option>
                    <option value="Apple">Apple</option>
                    <option value="Samsung">Samsung</option>
                    <option value="Xiaomi">Xiaomi</option>
                    <option value="Vivo">Vivo</option>
                    <option value="Google">Google</option>
                </select>
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <label class="input-group-text" for="inputGroupFile01">Hình Ảnh</label>
                <input type="file" class="form-control" id="inputGroupFile01" onchange="previewImage(this)" name="hinhanh">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Chip Xử Lý</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="chipxuly">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Dung Lượng Pin</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="dungluongpin">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Kích Thước Màn</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="kichthuocman">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Hệ Điều Hành</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="hedieuhanh">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Camera Sau</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="camerasau">
            </div>
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Camera Trước</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="cameratruoc">
            </div>

            <div style="text-align: center; margin-top: 30px; margin-left: 20px; position:fixed; top:86%;left:45%;">
                             <input type="submit" class="btn btn-primary " name="" id="" value="LƯU" style="width: 70px; height: 35px; font-size: 17px;font-weight: bold;" >
            </div>
        </div>
        
        <div class="image-preview-container">
            <div id="imagePreview"></div>
        </div>
    </div>
    </form>
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
    top: 53%;
    left: 53%;
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
    function handleFormSubmit(event) {
        event.preventDefault(); 
        var form = document.getElementById('myForm');
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../controller/product/SaveProduct.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                // thongbao();
                alert("Đã Lưu!");
                window.location.href = "product.php";
            } else {
                console.error('Error: ' + xhr.status);
            }
        };
       
        xhr.send(formData);
       
    }
    // function thongbao() {
       
    //     $(".bcontainer").load("test2.php");
    //     $(".bcontainer").css("display", "block");
    //   $(".acontainer").css("display", "none");  
    //     $(".bcontainer").fadeIn();
    // }
    
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