<?php
require_once '../controller/product/productController.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Khởi tạo controller và lấy thông tin sản phẩm
$controller = new ProductController();
$product = $controller->getOne($product_id);

// Kiểm tra xem sản phẩm có tồn tại không
if (!$product) {
    echo "Không tìm thấy sản phẩm!";
    exit;
}
?>

<div class="acontainer">
    <div class="form-container ms-2">
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="handleFormSubmit(event)" id="editForm">
            <input type="hidden" name="masp" value="<?= $product->masp ?>">
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Tên Sản Phẩm</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="tensp" value="<?= $product->tensp ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <label class="input-group-text" for="inputGroupSelect01">Thương Hiệu</label>
                <select class="form-select" id="inputGroupSelect01" name="thuonghieu">
                    <option>Chọn</option>
                    <option value="Apple" <?= ($product->thuonghieu == 'Apple') ? 'selected' : '' ?>>Apple</option>
                    <option value="Samsung" <?= ($product->thuonghieu == 'Samsung') ? 'selected' : '' ?>>Samsung</option>
                    <option value="Xiaomi" <?= ($product->thuonghieu == 'Xiaomi') ? 'selected' : '' ?>>Xiaomi</option>
                    <option value="Vivo" <?= ($product->thuonghieu == 'Vivo') ? 'selected' : '' ?>>Vivo</option>
                    <option value="Google" <?= ($product->thuonghieu == 'Google') ? 'selected' : '' ?>>Google</option>
                </select>
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <label class="input-group-text" for="inputGroupFile01">Hình Ảnh</label>
                <input type="file" class="form-control" id="inputGroupFile01" onchange="previewImage(this)" name="hinhanh">
                <input type="hidden" name="current_image" value="<?= $product->hinhanh ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Chip Xử Lý</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="chipxuly" value="<?= $product->chipxuly ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Dung Lượng Pin</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="dungluongpin" value="<?= $product->dungluongpin ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Kích Thước Màn</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="kichthuocman" value="<?= $product->kichthuocman ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Hệ Điều Hành</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="hedieuhanh" value="<?= $product->hedieuhanh ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Camera Sau</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="camerasau" value="<?= $product->camerasau ?>">
            </div>
            
            <div class="input-group mb-3" style="width:400px;margin-top:20px;margin-left:5px;">
                <span class="input-group-text" id="basic-addon1">Camera Trước</span>
                <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="cameratruoc" value="<?= $product->cameratruoc ?>">
            </div>
    
            <div style="text-align: center; margin-top: 30px; margin-left: 20px; position:fixed; top:86%;left:30%;">
                <input type="submit" class="btn btn-primary" value="CẬP NHẬT" style="width: 120px; height: 35px; font-size: 17px;font-weight: bold;">
            </div>
       
   
    </div>
    <div style="text-align: center; margin-top: 30px; margin-left: 100px; position:fixed; top:86%;left:40%;">
        <button class="btn btn-danger" style="width: 70px; height: 35px; font-size: 17px; font-weight: bold;"
    onclick="if(confirm('Bạn có chắc chắn muốn hủy không?')) window.location.href = 'product.php';">
    HỦY
</button>
        </div>
        
        
    <div class="image-preview-container">
        <div id="imagePreview">
            <?php if (!empty($product->hinhanh)): ?>
                <img src="<?= $product->hinhanh ?>" alt="Preview">
            <?php endif; ?>
        </div>
        </form>
    </div>
</div>

<script>
    function handleFormSubmit(event) {
        event.preventDefault();
        var form = document.getElementById('editForm');
        var formData = new FormData(form);
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../controller/product/updateProduct.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert("Cập nhật thành công!");
                    window.location.href = "product.php";
                } else {
                    alert("Lỗi: " + response.message);
                }
            } else {
                console.error('Error: ' + xhr.status);
                alert("Đã xảy ra lỗi khi cập nhật!");
            }
        };
        xhr.send(formData);
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
    overflow-y: auto;
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
</style>