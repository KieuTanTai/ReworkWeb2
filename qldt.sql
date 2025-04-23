-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS `qlchdienthoai_simple`;
USE `qlchdienthoai_simple`;

-- Bảng khách hàng
CREATE TABLE IF NOT EXISTS `khachhang` (
  `makh` INT NOT NULL AUTO_INCREMENT,
  `tenkhachhang` VARCHAR(255) NOT NULL,
  `diachi` VARCHAR(255) NOT NULL,
  `sdt` VARCHAR(15) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `matkhau` VARCHAR(255) NOT NULL, -- Thêm cột mật khẩu
  `trangthai` INT NOT NULL DEFAULT 1,
  `ngaythamgia` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`makh`),
  UNIQUE KEY `sdt` (`sdt`),
  UNIQUE KEY `email` (`email`)
);

-- Bảng màu sắc
CREATE TABLE IF NOT EXISTS `mausac` (
  `mamau` INT NOT NULL AUTO_INCREMENT,
  `tenmau` VARCHAR(50) NOT NULL,
  `trangthai` TINYINT DEFAULT 1,
  PRIMARY KEY (`mamau`),
  UNIQUE KEY `tenmau` (`tenmau`)
);

-- Bảng dung lượng RAM
CREATE TABLE IF NOT EXISTS `dungluongram` (
  `madlram` INT NOT NULL AUTO_INCREMENT,
  `kichthuocram` INT NOT NULL CHECK (`kichthuocram` > 0),
  `trangthai` TINYINT DEFAULT 1,
  PRIMARY KEY (`madlram`),
  UNIQUE KEY `kichthuocram` (`kichthuocram`)
);

-- Bảng dung lượng ROM
CREATE TABLE IF NOT EXISTS `dungluongrom` (
  `madlrom` INT NOT NULL AUTO_INCREMENT,
  `kichthuocrom` INT NOT NULL CHECK (`kichthuocrom` > 0),
  `trangthai` TINYINT DEFAULT 1,
  PRIMARY KEY (`madlrom`),
  UNIQUE KEY `kichthuocrom` (`kichthuocrom`)
);

-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS `sanpham` (
  `masp` INT NOT NULL AUTO_INCREMENT,
  `tensp` VARCHAR(255) NOT NULL,
  `hinhanh` VARCHAR(255) DEFAULT NULL,
  `chipxuly` VARCHAR(255) DEFAULT NULL,
  `dungluongpin` INT DEFAULT NULL CHECK (`dungluongpin` > 0),
  `kichthuocman` DOUBLE DEFAULT NULL CHECK (`kichthuocman` > 0),
  `hedieuhanh` VARCHAR(255) DEFAULT NULL,
  `camerasau` VARCHAR(255) DEFAULT NULL,
  `cameratruoc` VARCHAR(255) DEFAULT NULL,
  `thoigianbaohanh` INT DEFAULT 12 CHECK (`thoigianbaohanh` >= 0),
  `thuonghieu` VARCHAR(255) NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`masp`)
);

-- Bảng phiên bản sản phẩm
CREATE TABLE IF NOT EXISTS `phienbansanpham` (
  `maphienbansp` INT NOT NULL AUTO_INCREMENT,
  `masp` INT NOT NULL,
  `rom` INT NOT NULL,
  `ram` INT NOT NULL,
  `mausac` INT NOT NULL,
  `giaban` BIGINT DEFAULT 0 CHECK (`giaban` >= 0),
  `soluongton` INT DEFAULT 0 CHECK (`soluongton` >= 0),
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`maphienbansp`),
  FOREIGN KEY (`masp`) REFERENCES `sanpham` (`masp`),
  FOREIGN KEY (`rom`) REFERENCES `dungluongrom` (`madlrom`),
  FOREIGN KEY (`ram`) REFERENCES `dungluongram` (`madlram`),
  FOREIGN KEY (`mausac`) REFERENCES `mausac` (`mamau`),
  UNIQUE KEY `unique_version` (`masp`, `rom`, `ram`, `mausac`)
);

-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS `donhang` (
  `madonhang` INT NOT NULL AUTO_INCREMENT,
  `makh` INT NOT NULL,
  `thoigian` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `tongtien` BIGINT DEFAULT 0 CHECK (`tongtien` >= 0),
  `diachi` VARCHAR(255) DEFAULT NULL,
  `trangthai` INT DEFAULT 1, -- 1: Chưa giao, 2: Đã giao, 3: Đã hủy
  PRIMARY KEY (`madonhang`),
  FOREIGN KEY (`makh`) REFERENCES `khachhang` (`makh`)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS `chitietdonhang` (
  `madonhang` INT NOT NULL,
  `maphienbansp` INT NOT NULL,
  `soluong` INT DEFAULT 1 CHECK (`soluong` > 0),
  `dongia` BIGINT DEFAULT 0 CHECK (`dongia` >= 0),
  PRIMARY KEY (`madonhang`, `maphienbansp`),
  FOREIGN KEY (`madonhang`) REFERENCES `donhang` (`madonhang`),
  FOREIGN KEY (`maphienbansp`) REFERENCES `phienbansanpham` (`maphienbansp`)
);

-- Bảng giỏ hàng
CREATE TABLE IF NOT EXISTS `giohang` (
  `magiohang` INT NOT NULL AUTO_INCREMENT,
  `makh` INT NOT NULL,
  `thoigiantao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`magiohang`),
  FOREIGN KEY (`makh`) REFERENCES `khachhang` (`makh`),
  UNIQUE KEY `unique_giohang_kh` (`makh`)
);

-- Bảng chi tiết giỏ hàng
CREATE TABLE IF NOT EXISTS `chitietgiohang` (
  `magiohang` INT NOT NULL,
  `maphienbansp` INT NOT NULL,
  `soluong` INT DEFAULT 1 CHECK (`soluong` > 0),
  PRIMARY KEY (`magiohang`, `maphienbansp`),
  FOREIGN KEY (`magiohang`) REFERENCES `giohang` (`magiohang`),
  FOREIGN KEY (`maphienbansp`) REFERENCES `phienbansanpham` (`maphienbansp`)
);

-- Bảng khuyến mãi
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `makhuyenmai` INT NOT NULL AUTO_INCREMENT,
  `tenkhuyenmai` VARCHAR(255) NOT NULL,
  `phantramgiam` INT DEFAULT 0 CHECK (`phantramgiam` BETWEEN 0 AND 100),
  `ngaybatdau` DATETIME NOT NULL,
  `ngayketthuc` DATETIME NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`makhuyenmai`),
  CHECK (`ngayketthuc` > `ngaybatdau`)
);

-- Bảng áp dụng khuyến mãi cho sản phẩm
CREATE TABLE IF NOT EXISTS `apdungkhuyenmai` (
  `makhuyenmai` INT NOT NULL,
  `maphienbansp` INT NOT NULL,
  PRIMARY KEY (`makhuyenmai`, `maphienbansp`),
  FOREIGN KEY (`makhuyenmai`) REFERENCES `khuyenmai` (`makhuyenmai`),
  FOREIGN KEY (`maphienbansp`) REFERENCES `phienbansanpham` (`maphienbansp`)
);

-- Bảng đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS `danhgia` (
  `madanhgia` INT NOT NULL AUTO_INCREMENT,
  `makh` INT NOT NULL,
  `maphienbansp` INT NOT NULL,
  `diem` INT NOT NULL CHECK (`diem` BETWEEN 1 AND 5),
  `nhanxet` TEXT DEFAULT NULL,
  `thoigian` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`madanhgia`),
  FOREIGN KEY (`makh`) REFERENCES `khachhang` (`makh`),
  FOREIGN KEY (`maphienbansp`) REFERENCES `phienbansanpham` (`maphienbansp`),
  UNIQUE KEY `unique_danhgia` (`makh`, `maphienbansp`)
);

-- Bảng phương thức thanh toán
CREATE TABLE IF NOT EXISTS `phuongthucthanhtoan` (
  `mapt` INT NOT NULL AUTO_INCREMENT,
  `tenpt` VARCHAR(255) NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`mapt`),
  UNIQUE KEY `tenpt` (`tenpt`)
);

-- Bảng lịch sử thanh toán
CREATE TABLE IF NOT EXISTS `lichsuthanhtoan` (
  `malichsu` INT NOT NULL AUTO_INCREMENT,
  `madonhang` INT NOT NULL,
  `mapt` INT NOT NULL,
  `thoigian` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `sotien` BIGINT NOT NULL CHECK (`sotien` >= 0),
  `trangthai` INT DEFAULT 1, -- 1: Thành công, 0: Thất bại
  PRIMARY KEY (`malichsu`),
  FOREIGN KEY (`madonhang`) REFERENCES `donhang` (`madonhang`),
  FOREIGN KEY (`mapt`) REFERENCES `phuongthucthanhtoan` (`mapt`)
);

-- Bảng vận chuyển
CREATE TABLE IF NOT EXISTS `vanchuyen` (
  `mavanchuyen` INT NOT NULL AUTO_INCREMENT,
  `tenvanchuyen` VARCHAR(255) NOT NULL,
  `phi` INT DEFAULT 0 CHECK (`phi` >= 0),
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`mavanchuyen`),
  UNIQUE KEY `tenvanchuyen` (`tenvanchuyen`)
);

-- Bảng chi tiết vận chuyển cho đơn hàng
CREATE TABLE IF NOT EXISTS `chitietvanchuyen` (
  `madonhang` INT NOT NULL,
  `mavanchuyen` INT NOT NULL,
  `thoigian` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `trangthai` INT DEFAULT 1, -- 1: Đang giao, 2: Đã giao, 3: Hủy
  PRIMARY KEY (`madonhang`),
  FOREIGN KEY (`madonhang`) REFERENCES `donhang` (`madonhang`),
  FOREIGN KEY (`mavanchuyen`) REFERENCES `vanchuyen` (`mavanchuyen`)
);

-- Bảng kho hàng
CREATE TABLE IF NOT EXISTS `khohang` (
  `makho` INT NOT NULL AUTO_INCREMENT,
  `tenkho` VARCHAR(255) NOT NULL,
  `diachi` VARCHAR(255) NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`makho`),
  UNIQUE KEY `tenkho` (`tenkho`)
);

-- Bảng tồn kho
CREATE TABLE IF NOT EXISTS `tonkho` (
  `makho` INT NOT NULL,
  `maphienbansp` INT NOT NULL,
  `soluong` INT DEFAULT 0 CHECK (`soluong` >= 0),
  PRIMARY KEY (`makho`, `maphienbansp`),
  FOREIGN KEY (`makho`) REFERENCES `khohang` (`makho`),
  FOREIGN KEY (`maphienbansp`) REFERENCES `phienbansanpham` (`maphienbansp`)
);

-- Bảng nhân viên
CREATE TABLE IF NOT EXISTS `nhanvien` (
  `manv` INT NOT NULL AUTO_INCREMENT,
  `tennv` VARCHAR(255) NOT NULL,
  `sdt` VARCHAR(15) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `matkhau` VARCHAR(255) NOT NULL,
  `vaitro` INT DEFAULT 1, -- 1: Nhân viên, 2: Quản lý
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`manv`),
  UNIQUE KEY `sdt` (`sdt`),
  UNIQUE KEY `email` (`email`)
);

-- Chèn dữ liệu mẫu
INSERT IGNORE INTO `mausac` (`tenmau`) VALUES 
('Xanh'), ('Đen'), ('Trắng'), ('Vàng'), ('Đỏ'), ('Hồng'), ('Tím'), ('Bạc'), ('Xám'), ('Xanh dương'), ('Cam');

INSERT IGNORE INTO `dungluongram` (`kichthuocram`) VALUES 
(4), (6), (8), (12), (16), (2);

INSERT IGNORE INTO `dungluongrom` (`kichthuocrom`) VALUES 
(64), (128), (256), (32), (512), (1024);

INSERT IGNORE INTO `sanpham` (`tensp`, `hinhanh`, `chipxuly`, `dungluongpin`, `kichthuocman`, `hedieuhanh`, `camerasau`, `cameratruoc`, `thuonghieu`) VALUES
('iPhone 13', 'iphone13.jpg', 'A15 Bionic', 3240, 6.1, 'IOS', '12MP + 12MP', '12MP', 'Iphone'),
('Samsung Galaxy A53', 'samsungA53.jpg', 'Exynos 1280', 5000, 6.5, 'Android', '64MP + 12MP', '32MP', 'Samsung'),
('Vivo Y22s', 'vivoy22s.jpg', 'Snapdragon 680', 5000, 6.55, 'Android', '50MP + 2MP', '8MP', 'Vivo');

INSERT IGNORE INTO `phienbansanpham` (`masp`, `rom`, `ram`, `mausac`, `giaban`, `soluongton`) VALUES
(1, 2, 2, 1, 20000000, 10), -- iPhone 13, 128GB, 6GB, Xanh
(2, 1, 1, 2, 7000000, 15),  -- Samsung A53, 64GB, 4GB, Đen
(3, 3, 3, 3, 5500000, 20);  -- Vivo Y22s, 256GB, 8GB, Trắng

INSERT IGNORE INTO `khachhang` (`tenkhachhang`, `diachi`, `sdt`, `email`, `matkhau`) VALUES
('Nguyễn Văn A', '123 Hà Nội', '0912345678', 'nguyenvana@gmail.com', '$2y$10$examplehashA'),
('Trần Thị B', '456 TP.HCM', '0987654321', 'tranthib@gmail.com', '$2y$10$examplehashB'),
('Lê Hoàng C', '789 Đà Nẵng', '0933344556', 'lehoangc@gmail.com', '$2y$10$examplehashC'),
('Phạm Quỳnh D', '321 Hải Phòng', '0901234567', 'phamquynhd@gmail.com', '$2y$10$examplehashD'),
('Võ Anh E', '654 Cần Thơ', '0945678910', 'voanhe@gmail.com', '$2y$10$examplehashE'),
('Đỗ Thị F', '987 Nha Trang', '0965432198', 'dothif@gmail.com', '$2y$10$examplehashF'),
('Ngô Minh G', '111 Huế', '0978456123', 'ngominhg@gmail.com', '$2y$10$examplehashG'),
('Trịnh Văn H', '222 Vũng Tàu', '0923956789', 'trinhvanh@gmail.com', '$2y$10$examplehashH'),
('Huỳnh Thị I', '333 Quảng Ninh', '0932123456', 'huynhthii@gmail.com', '$2y$10$examplehashI'),
('Bùi Đức J', '444 Long An', '0956781234', 'buiducj@gmail.com', '$2y$10$examplehashJ'),
('Nguyễn Thảo K', '555 Bình Dương', '0911223344', 'nguyenthaok@gmail.com', '$2y$10$examplehashK'),
('Trần Vũ L', '666 Biên Hòa', '0977553311', 'tranvul@gmail.com', '$2y$10$examplehashL');

INSERT IGNORE INTO `donhang` (`makh`, `tongtien`, `diachi`, `trangthai`) VALUES
(1, 20000000, '123 Hà Nội', 1), -- Đơn hàng chưa giao
(2, 7000000, '456 TP.HCM', 2);  -- Đơn hàng đã giao

INSERT IGNORE INTO `chitietdonhang` (`madonhang`, `maphienbansp`, `soluong`, `dongia`) VALUES
(1, 1, 1, 20000000), -- Đơn 1: 1 iPhone 13
(2, 2, 1, 7000000);  -- Đơn 2: 1 Samsung A53

INSERT IGNORE INTO `giohang` (`makh`) VALUES
(1), -- Giỏ hàng của Nguyễn Văn A
(2); -- Giỏ hàng của Trần Thị B

INSERT IGNORE INTO `chitietgiohang` (`magiohang`, `maphienbansp`, `soluong`) VALUES
(1, 1, 2), -- 2 iPhone 13 trong giỏ của Nguyễn Văn A
(2, 3, 1); -- 1 Vivo Y22s trong giỏ của Trần Thị B

INSERT IGNORE INTO `khuyenmai` (`tenkhuyenmai`, `phantramgiam`, `ngaybatdau`, `ngayketthuc`) VALUES
('Khuyến mãi mùa hè', 10, '2025-06-01 00:00:00', '2025-06-30 23:59:59'),
('Black Friday', 20, '2025-11-25 00:00:00', '2025-11-30 23:59:59');

INSERT IGNORE INTO `apdungkhuyenmai` (`makhuyenmai`, `maphienbansp`) VALUES
(1, 2), -- Samsung A53 giảm 10%
(2, 1); -- iPhone 13 giảm 20%

INSERT IGNORE INTO `danhgia` (`makh`, `maphienbansp`, `diem`, `nhanxet`) VALUES
(1, 1, 5, 'Sản phẩm tuyệt vời, đáng tiền!'),
(2, 2, 4, 'Camera đẹp, pin lâu.');

INSERT IGNORE INTO `phuongthucthanhtoan` (`tenpt`) VALUES
('Thanh toán khi nhận hàng'),
('Chuyển khoản ngân hàng'),
('Thẻ tín dụng');

INSERT IGNORE INTO `lichsuthanhtoan` (`madonhang`, `mapt`, `sotien`, `trangthai`) VALUES
(1, 1, 20000000, 1), -- Đơn 1 thanh toán COD thành công
(2, 2, 7000000, 1);  -- Đơn 2 thanh toán chuyển khoản thành công

INSERT IGNORE INTO `vanchuyen` (`tenvanchuyen`, `phi`) VALUES
('Giao hàng tiết kiệm', 20000),
('Giao hàng nhanh', 50000),
('Giao hàng hỏa tốc', 100000);

INSERT IGNORE INTO `chitietvanchuyen` (`madonhang`, `mavanchuyen`, `trangthai`) VALUES
(1, 1, 1), -- Đơn 1 đang giao tiết kiệm
(2, 2, 2); -- Đơn 2 đã giao nhanh

INSERT IGNORE INTO `khohang` (`tenkho`, `diachi`) VALUES
('Kho Hà Nội', '789 Hà Nội'),
('Kho TP.HCM', '101 TP.HCM');

INSERT IGNORE INTO `tonkho` (`makho`, `maphienbansp`, `soluong`) VALUES
(1, 1, 5), -- 5 iPhone 13 tại kho Hà Nội
(2, 2, 10), -- 10 Samsung A53 tại kho TP.HCM
(2, 3, 15); -- 15 Vivo Y22s tại kho TP.HCM

INSERT IGNORE INTO `nhanvien` (`tennv`, `sdt`, `email`, `matkhau`, `vaitro`) VALUES
('Lê Văn C', '0901234567', 'levanc@gmail.com', 'hashed_password_123', 1),
('Phạm Thị D', '0909876543', 'phamthid@gmail.com', 'hashed_password_456', 2);

-- Thêm sản phẩm mới
INSERT INTO `sanpham` (`tensp`, `hinhanh`, `chipxuly`, `dungluongpin`, `kichthuocman`, `hedieuhanh`, `camerasau`, `cameratruoc`, `thuonghieu`) 
VALUES
('Redmi Note 12', 'redmi_note12.jpg', 'Snapdragon 685', 5000, 6.67, 'Android', '50MP + 8MP + 2MP', '13MP', 'Redmi'),
('Google Pixel 7', 'google_pixel7.jpg', 'Google Tensor G2', 4355, 6.3, 'Android', '50MP + 12MP', '10.8MP', 'Google'),
('Oppo Reno 8T', 'oppo_reno8t.jpg', 'Dimensity 920', 4800, 6.7, 'Android', '108MP', '32MP', 'Oppo');

-- Thêm sản phẩm
INSERT INTO `sanpham` (`tensp`, `hinhanh`, `chipxuly`, `dungluongpin`, `kichthuocman`, `hedieuhanh`, `camerasau`, `cameratruoc`, `thuonghieu`) VALUES
('iPhone 14 Pro Max', 'iphone14promax.jpg', 'A16 Bionic', 4323, 6.7, 'iOS', '48MP + 12MP + 12MP', '12MP', 'Iphone'),
('Samsung Galaxy S23 Ultra', 'samsungs23ultra.jpg', 'Snapdragon 8 Gen 2', 5000, 6.8, 'Android', '200MP + 10MP + 10MP + 12MP', '12MP', 'Samsung'),
('Xiaomi 13 Pro', 'xiaomi13pro.jpg', 'Snapdragon 8 Gen 2', 4820, 6.73, 'Android', '50MP + 50MP + 50MP', '32MP', 'Xiaomi'),
('OnePlus 11', 'oneplus11.jpg', 'Snapdragon 8 Gen 2', 5000, 6.7, 'Android', '50MP + 48MP + 32MP', '16MP', 'OnePlus'),
('Oppo Find X6 Pro', 'oppofindx6pro.jpg', 'Snapdragon 8 Gen 2', 5000, 6.82, 'Android', '50MP + 50MP + 50MP', '32MP', 'Oppo'),
('Realme GT Neo 5', 'realmegt5.jpg', 'Snapdragon 8+ Gen 1', 5000, 6.74, 'Android', '50MP + 8MP + 2MP', '16MP', 'Realme'),
('Nothing Phone 2', 'nothingphone2.jpg', 'Snapdragon 8+ Gen 1', 4700, 6.7, 'Android', '50MP + 50MP', '32MP', 'Nothing'),
('Vivo X90 Pro', 'vivox90pro.jpg', 'Dimensity 9200', 4870, 6.78, 'Android', '50MP + 50MP + 12MP', '32MP', 'Vivo'),
('Google Pixel 7a', 'googlepixel7a.jpg', 'Google Tensor G2', 4385, 6.1, 'Android', '64MP + 13MP', '13MP', 'Google'),
('Huawei P60 Pro', 'huaweip60pro.jpg', 'Snapdragon 8+ Gen 1', 4815, 6.67, 'Android', '48MP + 48MP + 13MP', '13MP', 'Huawei'),
('iPhone 15', 'iphone15.jpg', 'A17 Pro', 3877, 6.1, 'iOS', '48MP + 12MP', '12MP', 'Iphone');

-- Thêm phiên bản sản phẩm
INSERT INTO `phienbansanpham` (`masp`, `rom`, `ram`, `mausac`, `giaban`, `soluongton`) VALUES
-- iPhone 14 Pro Max
(4, 3, 3, 1, 35000000, 15), -- 256GB, 8GB, Đen
(4, 5, 2, 3, 38000000, 10), -- 512GB, 8GB, Trắng
(4, 6, 1, 5, 42000000, 5),  -- 1TB, 8GB, Hồng

-- Samsung Galaxy S23 Ultra
(5, 3, 3, 1, 28000000, 12), -- 256GB, 8GB, Xanh
(5, 5, 4, 2, 32000000, 8),  -- 512GB, 12GB, Đen
(5, 6, 4, 6, 36000000, 4),  -- 1TB, 12GB, Tím

-- Xiaomi 13 Pro
(6, 3, 3, 2, 22000000, 20), -- 256GB, 8GB, Đen
(6, 5, 4, 3, 25000000, 15), -- 512GB, 12GB, Trắng

-- OnePlus 11
(7, 3, 3, 2, 18000000, 25), -- 256GB, 8GB, Đen
(7, 5, 4, 7, 21000000, 15), -- 512GB, 12GB, Xanh dương

-- Oppo Find X6 Pro
(8, 3, 4, 2, 24000000, 10), -- 256GB, 12GB, Đen
(8, 5, 4, 3, 27000000, 8),  -- 512GB, 12GB, Trắng

-- Realme GT Neo 5
(9, 3, 3, 2, 15000000, 30), -- 256GB, 8GB, Đen
(9, 3, 4, 1, 16500000, 20), -- 256GB, 12GB, Xanh
(9, 5, 4, 6, 18000000, 15), -- 512GB, 12GB, Tím

-- Nothing Phone 2
(10, 3, 3, 3, 16000000, 18), -- 256GB, 8GB, Trắng
(10, 5, 4, 8, 19000000, 12), -- 512GB, 12GB, Xám

-- Vivo X90 Pro
(11, 3, 4, 2, 23000000, 15), -- 256GB, 12GB, Đen
(11, 5, 4, 5, 26000000, 10), -- 512GB, 12GB, Hồng

-- Google Pixel 7a
(12, 2, 2, 2, 12000000, 25), -- 128GB, 6GB, Đen
(12, 2, 2, 1, 12000000, 20), -- 128GB, 6GB, Xanh
(12, 2, 2, 3, 12000000, 18), -- 128GB, 6GB, Trắng

-- Huawei P60 Pro
(13, 3, 3, 2, 21000000, 12), -- 256GB, 8GB, Đen
(13, 5, 3, 3, 24000000, 8),  -- 512GB, 8GB, Trắng

-- iPhone 15
(14, 2, 2, 2, 25000000, 30), -- 128GB, 6GB, Đen
(14, 2, 2, 3, 25000000, 25), -- 128GB, 6GB, Trắng
(14, 2, 2, 5, 25000000, 20), -- 128GB, 6GB, Hồng
(14, 3, 2, 2, 28000000, 20), -- 256GB, 6GB, Đen
(14, 3, 2, 3, 28000000, 15), -- 256GB, 6GB, Trắng
(14, 5, 2, 2, 32000000, 10), -- 512GB, 6GB, Đen

-- Redmi Note 12
(1, 2, 1, 1, 4500000, 40), -- 128GB, 4GB, Xanh
(1, 2, 2, 2, 5000000, 35), -- 128GB, 6GB, Đen
(1, 3, 2, 3, 5500000, 30), -- 256GB, 6GB, Trắng

-- Google Pixel 7
(2, 2, 2, 2, 15000000, 22), -- 128GB, 6GB, Đen
(2, 3, 3, 3, 17000000, 18), -- 256GB, 8GB, Trắng
(2, 3, 3, 8, 17000000, 15), -- 256GB, 8GB, Xám

-- Oppo Reno 8T
(3, 2, 2, 1, 8500000, 28), -- 128GB, 6GB, Xanh
(3, 2, 2, 2, 8500000, 25), -- 128GB, 6GB, Đen
(3, 2, 2, 6, 8500000, 20), -- 128GB, 6GB, Tím
(3 , 3, 3, 2, 9500000, 18)  -- 256GB, 8GB, Đen
;

-- Thêm dữ liệu tồn kho
INSERT INTO `tonkho` (`makho`, `maphienbansp`, `soluong`) VALUES
-- Kho Hà Nội
(1, 4, 8),  -- iPhone 14 Pro Max, 256GB, 8GB, Đen
(1, 5, 6),  -- iPhone 14 Pro Max, 512GB, 8GB, Trắng
(1, 6, 3),  -- iPhone 14 Pro Max, 1TB, 8GB, Hồng
(1, 7, 7),  -- Samsung Galaxy S23 Ultra, 256GB, 8GB, Xanh
(1, 8, 5),  -- Samsung Galaxy S23 Ultra, 512GB, 12GB, Đen
(1, 9, 2),  -- Samsung Galaxy S23 Ultra, 1TB, 12GB, Tím
(1, 10, 10), -- Xiaomi 13 Pro, 256GB, 8GB, Đen
(1, 11, 8),  -- Xiaomi 13 Pro, 512GB, 12GB, Trắng
(1, 12, 12), -- OnePlus 11, 256GB, 8GB, Đen
(1, 13, 8),  -- OnePlus 11, 512GB, 12GB, Xanh dương
(1, 14, 5),  -- Oppo Find X6 Pro, 256GB, 12GB, Đen
(1, 15, 4),  -- Oppo Find X6 Pro, 512GB, 12GB, Trắng

-- Kho TP.HCM
(2, 4, 7),  -- iPhone 14 Pro Max, 256GB, 8GB, Đen
(2, 5, 4),  -- iPhone 14 Pro Max, 512GB, 8GB, Trắng
(2, 6, 2),  -- iPhone 14 Pro Max, 1TB, 8GB, Hồng
(2, 7, 5),  -- Samsung Galaxy S23 Ultra, 256GB, 8GB, Xanh
(2, 8, 3),  -- Samsung Galaxy S23 Ultra, 512GB, 12GB, Đen
(2, 9, 2),  -- Samsung Galaxy S23 Ultra, 1TB, 12GB, Tím
(2, 16, 15), -- Realme GT Neo 5, 256GB, 8GB, Đen
(2, 17, 10), -- Realme GT Neo 5, 256GB, 12GB, Xanh
(2, 18, 7),  -- Realme GT Neo 5, 512GB, 12GB, Tím
(2, 19, 9),  -- Nothing Phone 2, 256GB, 8GB, Trắng
(2, 20, 6),  -- Nothing Phone 2, 512GB, 12GB, Xám
(2, 21, 7),  -- Vivo X90 Pro, 256GB, 12GB, Đen
(2, 22, 5),  -- Vivo X90 Pro, 512GB, 12GB, Hồng
(2, 23, 12), -- Google Pixel 7a, 128GB, 6GB, Đen
(2, 24, 10), -- Google Pixel 7a, 128GB, 6GB, Xanh
(2, 25, 9),  -- Google Pixel 7a, a, 128GB, 6GB, Trắng
(2, 26, 6),  -- Huawei P60 Pro, 256GB, 8GB, Đen
(2, 27, 4),  -- Huawei P60 Pro, 512GB, 8GB, Trắng
(2, 28, 15), -- iPhone 15, 128GB, 6GB, Đen
(2, 29, 12), -- iPhone 15, 128GB, 6GB, Trắng
(2, 30, 10), -- iPhone 15, 128GB, 6GB, Hồng
(2, 31, 10), -- iPhone 15, 256GB, 6GB, Đen
(2, 32, 8),  -- iPhone 15, 256GB, 6GB, Trắng
(2, 33, 5)   -- iPhone 15, 512GB, 6GB, Đen
;

-- Thêm 50 sản phẩm mới
INSERT INTO `sanpham` (`tensp`, `hinhanh`, `chipxuly`, `dungluongpin`, `kichthuocman`, `hedieuhanh`, `camerasau`, `cameratruoc`, `thuonghieu`) VALUES
('iPhone 14', 'iphone14.jpg', 'A15 Bionic', 3279, 6.1, 'iOS', '12MP + 12MP', '12MP', 'Iphone'),
('iPhone 14 Plus', 'iphone14plus.jpg', 'A15 Bionic', 4323, 6.7, 'iOS', '12MP + 12MP', '12MP', 'Iphone'),
('iPhone 15 Plus', 'iphone15plus.jpg', 'A16 Bionic', 4383, 6.7, 'iOS', '48MP + 12MP', '12MP', 'Iphone'), 
('iPhone 15 Pro', 'iphone15pro.jpg', 'A17 Pro', 3650, 6.1, 'iOS', '48MP + 12MP + 12MP', '12MP', 'Iphone'),
('iPhone 15 Pro Max', 'iphone15promax.jpg', 'A17 Pro', 4441, 6.7, 'iOS', '48MP + 12MP + 12MP', '12MP', 'Iphone'),

('Samsung Galaxy S23', 'samsungs23.jpg', 'Snapdragon 8 Gen 2', 3900, 6.1, 'Android', '50MP + 12MP + 10MP', '12MP', 'Samsung'),
('Samsung Galaxy S23+', 'samsungs23plus.jpg', 'Snapdragon 8 Gen 2', 4700, 6.6, 'Android', '50MP + 12MP + 10MP', '12MP', 'Samsung'),
('Samsung Galaxy Z Fold 5', 'samsungzfold5.jpg', 'Snapdragon 8 Gen 2', 4400, 7.6, 'Android', '50MP + 12MP + 10MP', '4MP + 10MP', 'Samsung'),
('Samsung Galaxy Z Flip 5', 'samsungzflip5.jpg', 'Snapdragon 8 Gen 2', 3700, 6.7, 'Android', '12MP + 12MP', '10MP', 'Samsung'),
('Samsung Galaxy A54', 'samsunga54.jpg', 'Exynos 1380', 5000, 6.4, 'Android', '50MP + 12MP + 5MP', '32MP', 'Samsung'),

('Xiaomi 14', 'xiaomi14.jpg', 'Snapdragon 8 Gen 3', 4610, 6.36, 'Android', '50MP + 50MP + 12MP', '32MP', 'Xiaomi'),
('Xiaomi 14 Pro', 'xiaomi14pro.jpg', 'Snapdragon 8 Gen 3', 4880, 6.73, 'Android', '50MP + 50MP + 50MP', '32MP', 'Xiaomi'),
('Xiaomi Redmi Note 13 Pro', 'redminote13pro.jpg', 'Dimensity 7200 Ultra', 5000, 6.67, 'Android', '200MP + 8MP + 2MP', '16MP', 'Xiaomi'),
('Xiaomi Redmi Note 13', 'redminote13.jpg', 'Snapdragon 685', 5000, 6.67, 'Android', '108MP + 8MP + 2MP', '16MP', 'Xiaomi'),
('Xiaomi POCO F5', 'pocof5.jpg', 'Snapdragon 7+ Gen 2', 5000, 6.67, 'Android', '64MP + 8MP + 2MP', '16MP', 'Xiaomi'),

('Google Pixel 8', 'pixel8.jpg', 'Google Tensor G3', 4575, 6.2, 'Android', '50MP + 12MP', '10.5MP', 'Google'),
('Google Pixel 8 Pro', 'pixel8pro.jpg', 'Google Tensor G3', 5050, 6.7, 'Android', '50MP + 48MP + 48MP', '10.5MP', 'Google'),
('Google Pixel Fold', 'pixelfold.jpg', 'Google Tensor G2', 4821, 7.6, 'Android', '48MP + 10.8MP + 10.8MP', '8MP + 9.5MP', 'Google'),
('Google Pixel 7 Pro', 'pixel7pro.jpg', 'Google Tensor G2', 5000, 6.7, 'Android', '50MP + 48MP + 12MP', '10.8MP', 'Google'),
('Google Pixel 6a', 'pixel6a.jpg', 'Google Tensor', 4410, 6.1, 'Android', '12.2MP + 12MP', '8MP', 'Google'),

('OnePlus 12', 'oneplus12.jpg', 'Snapdragon 8 Gen 3', 5400, 6.82, 'Android', '50MP + 48MP + 64MP', '32MP', 'OnePlus'),
('OnePlus Nord 3', 'oneplusnord3.jpg', 'Dimensity 9000', 5000, 6.74, 'Android', '50MP + 8MP + 2MP', '16MP', 'OnePlus'),
('OnePlus Nord CE 3', 'oneplusnordce3.jpg', 'Snapdragon 782G', 5000, 6.7, 'Android', '50MP + 8MP + 2MP', '16MP', 'OnePlus'),
('OnePlus 11R', 'oneplus11r.jpg', 'Snapdragon 8+ Gen 1', 5000, 6.7, 'Android', '50MP + 8MP + 2MP', '16MP', 'OnePlus'),
('OnePlus Open', 'oneplusopen.jpg', 'Snapdragon 8 Gen 2', 4805, 7.82, 'Android', '48MP + 64MP + 48MP', '20MP + 32MP', 'OnePlus'),

('Oppo Find N3', 'oppofindN3.jpg', 'Snapdragon 8 Gen 2', 4805, 7.82, 'Android', '48MP + 64MP + 48MP', '20MP + 32MP', 'Oppo'),
('Oppo Find N3 Flip', 'oppofindN3flip.jpg', 'Dimensity 9200', 4300, 6.8, 'Android', '50MP + 8MP', '32MP', 'Oppo'),
('Oppo Reno 10', 'opporeno10.jpg', 'Snapdragon 778G', 5000, 6.7, 'Android', '64MP + 8MP + 2MP', '32MP', 'Oppo'),
('Oppo Reno 10 Pro', 'opporeno10pro.jpg', 'Snapdragon 778G+', 4600, 6.7, 'Android', '50MP + 32MP + 8MP', '32MP', 'Oppo'),
('Oppo A78', 'oppoa78.jpg', 'Dimensity 700', 5000, 6.56, 'Android', '50MP + 2MP', '8MP', 'Oppo'),

('Vivo X100', 'vivox100.jpg', 'Dimensity 9300', 5000, 6.78, 'Android', '50MP + 50MP + 50MP', '32MP', 'Vivo'),
('Vivo X100 Pro', 'vivox100pro.jpg', 'Dimensity 9300', 5400, 6.78, 'Android', '50MP + 50MP + 50MP', '32MP', 'Vivo'),
('Vivo V30', 'vivov30.jpg', 'Snapdragon 7 Gen 3', 5000, 6.78, 'Android', '50MP + 50MP', '50MP', 'Vivo'),
('Vivo V30 Pro', 'vivov30pro.jpg', 'Dimensity 8200', 5000, 6.78, 'Android', '50MP + 50MP + 12MP', '50MP', 'Vivo'),
('Vivo Y78+', 'vivoy78plus.jpg', 'Dimensity 6200', 5000, 6.78, 'Android', '50MP + 2MP', '8MP', 'Vivo'),

('Realme GT 5', 'realmegt5.jpg', 'Snapdragon 8 Gen 2', 5240, 6.74, 'Android', '50MP + 8MP', '16MP', 'Realme'),
('Realme GT 5 Pro', 'realmegt5pro.jpg', 'Snapdragon 8 Gen 3', 5400, 6.78, 'Android', '50MP + 8MP + 50MP', '32MP', 'Realme'),
('Realme GT Neo 5 SE', 'realmegt5se.jpg', 'Snapdragon 7+ Gen 2', 5500, 6.74, 'Android', '50MP + 8MP + 2MP', '16MP', 'Realme'),
('Realme 11 Pro', 'realme11pro.jpg', 'Dimensity 7050', 5000, 6.7, 'Android', '100MP + 2MP', '16MP', 'Realme'),
('Realme 11 Pro+', 'realme11proplus.jpg', 'Dimensity 7050+', 5000, 6.7, 'Android', '200MP + 8MP + 2MP', '32MP', 'Realme'),

('Huawei Mate 60 Pro', 'huaweimate60pro.jpg', 'Kirin 9000S', 5000, 6.82, 'HarmonyOS', '50MP + 48MP + 12MP', '13MP', 'Huawei'),
('Huawei P60 Art', 'huaweip60art.jpg', 'Snapdragon 8+ Gen 1', 4815, 6.67, 'HarmonyOS', '48MP + 48MP + 13MP', '13MP', 'Huawei'),
('Huawei Nova 12 Pro', 'huaweinova12pro.jpg', 'Kirin 9000S', 4500, 6.78, 'HarmonyOS', '50MP + 8MP', '60MP + 8MP', 'Huawei'),
('Huawei Mate X5', 'huaweimatex5.jpg', 'Kirin 9000S', 5060, 7.85, 'HarmonyOS', '50MP + 13MP + 12MP', '8MP', 'Huawei'),
('Huawei Pocket S', 'huaweipockets.jpg', 'Snapdragon 778G', 4000, 6.9, 'HarmonyOS', '40MP + 13MP', '10.7MP', 'Huawei'),

('Nothing Phone 2a', 'nothingphone2a.jpg', 'Dimensity 7200 Pro', 4700, 6.7, 'Android', '50MP + 50MP', '32MP', 'Nothing'),
('Motorola Edge 40 Pro', 'motorolaedge40pro.jpg', 'Snapdragon 8 Gen 2', 4600, 6.67, 'Android', '50MP + 50MP + 12MP', '60MP', 'Motorola'),
('Motorola Razr 40 Ultra', 'motorolarazr40ultra.jpg', 'Snapdragon 8+ Gen 1', 3800, 6.9, 'Android', '12MP + 13MP', '32MP', 'Motorola'),
('Sony Xperia 1 V', 'sonyxperia1v.jpg', 'Snapdragon 8 Gen 2', 5000, 6.5, 'Android', '48MP + 12MP + 12MP', '12MP', 'Sony'),
('Asus ROG Phone 7', 'asusrogphone7.jpg', 'Snapdragon 8 Gen 2', 6000, 6.78, 'Android', '50MP + 13MP + 5MP', '32MP', 'Asus');

-- Thêm màu sắc đa dạng hơn
INSERT INTO `mausac` (`tenmau`) VALUES 
('Tím lavender'), ('Xanh ngọc'), ('Vàng hổ phách'), ('Đồng'), ('Titan'), 
('Xanh đậm'), ('Xám than'), ('Bạc ánh kim'), ('Nâu đồng'), ('Đỏ rượu');

-- Thêm nhiều phiên bản sản phẩm cho các sản phẩm mới
INSERT INTO `phienbansanpham` (`masp`, `rom`, `ram`, `mausac`, `giaban`, `soluongton`) VALUES
-- iPhone 14
(15, 2, 2, 2, 21000000, 25), -- 128GB, 6GB, Đen
(15, 2, 2, 3, 21000000, 20), -- 128GB, 6GB, Trắng
(15, 3, 2, 2, 24000000, 18), -- 256GB, 6GB, Đen
(15, 3, 2, 3, 24000000, 15), -- 256GB, 6GB, Trắng
(15, 5, 2, 2, 27000000, 10), -- 512GB, 6GB, Đen

-- iPhone 14 Plus
(16, 2, 2, 2, 23500000, 20), -- 128GB, 6GB, Đen
(16, 2, 2, 3, 23500000, 18), -- 128GB, 6GB, Trắng
(16, 2, 2, 5, 23500000, 15), -- 128GB, 6GB, Hồng
(16, 3, 2, 2, 26500000, 15), -- 256GB, 6GB, Đen
(16, 5, 2, 2, 29500000, 8),  -- 512GB, 6GB, Đen

-- iPhone 15 Plus
(17, 2, 2, 2, 27000000, 22), -- 128GB, 6GB, Đen
(17, 2, 2, 3, 27000000, 18), -- 128GB, 6GB, Trắng
(17, 3, 2, 2, 30000000, 15), -- 256GB, 6GB, Đen
(17, 5, 2, 2, 33000000, 10), -- 512GB, 6GB, Đen

-- Samsung Galaxy S23
(20, 2, 2, 2, 18000000, 25), -- 128GB, 6GB, Đen
(20, 2, 2, 3, 18000000, 20), -- 128GB, 6GB, Trắng
(20, 3, 3, 2, 21000000, 15), -- 256GB, 8GB, Đen
(20, 3, 3, 4, 21000000, 12), -- 256GB, 8GB, Vàng

-- Samsung Galaxy Z Fold 5
(22, 3, 4, 2, 38000000, 10), -- 256GB, 12GB, Đen
(22, 3, 4, 3, 38000000, 8),  -- 256GB, 12GB, Trắng
(22, 5, 4, 2, 41000000, 6),  -- 512GB, 12GB, Đen
(22, 6, 4, 2, 45000000, 4),  -- 1TB, 12GB, Đen

-- Xiaomi 14
(25, 3, 4, 3, 19000000, 18), -- 256GB, 12GB, Trắng
(25, 5, 4, 2, 22000000, 12), -- 512GB, 12GB, Đen
(25, 5, 4, 3, 22000000, 10), -- 512GB, 12GB, Trắng

-- Xiaomi Redmi Note 13 Pro
(27, 2, 2, 2, 7500000, 30), -- 128GB, 6GB, Đen
(27, 2, 2, 3, 7500000, 28), -- 128GB, 6GB, Trắng
(27, 2, 3, 2, 8000000, 25), -- 128GB, 8GB, Đen
(27, 3, 3, 2, 8500000, 22), -- 256GB, 8GB, Đen

-- Google Pixel 8
(30, 2, 3, 2, 19500000, 15), -- 128GB, 8GB, Đen
(30, 2, 3, 3, 19500000, 12), -- 128GB, 8GB, Trắng
(30, 3, 3, 2, 22500000, 10), -- 256GB, 8GB, Đen

-- Google Pixel 8 Pro
(31, 3, 4, 2, 27500000, 12), -- 256GB, 12GB, Đen
(31, 3, 4, 8, 27500000, 10), -- 256GB, 12GB, Xám
(31, 5, 4, 2, 30500000, 8),  -- 512GB, 12GB, Đen
(31, 6, 4, 8, 33500000, 5),  -- 1TB, 12GB, Xám

-- OnePlus 12
(35, 3, 4, 2, 23500000, 15), -- 256GB, 12GB, Đen
(35, 3, 4, 11, 23500000, 12), -- 256GB, 12GB, Xanh ngọc
(35, 5, 5, 2, 26500000, 10), -- 512GB, 16GB, Đen
(35, 5, 5, 11, 26500000, 8), -- 512GB, 16GB, Xanh ngọc

-- OnePlus Nord 3
(36, 2, 3, 2, 11500000, 22), -- 128GB, 8GB, Đen
(36, 3, 4, 2, 13500000, 18), -- 256GB, 12GB, Đen
(36, 3, 4, 11, 13500000, 15), -- 256GB, 12GB, Xanh ngọc

-- Oppo Find N3
(40, 3, 4, 2, 41000000, 8),  -- 256GB, 12GB, Đen
(40, 5, 4, 3, 44000000, 6),  -- 512GB, 12GB, Trắng
(40, 5, 5, 9, 44000000, 5),  -- 512GB, 16GB, Nâu đồng

-- Oppo Reno 10
(42, 2, 3, 2, 10500000, 25), -- 128GB, 8GB, Đen
(42, 2, 3, 3, 10500000, 22), -- 128GB, 8GB, Trắng
(42, 3, 3, 2, 11500000, 18), -- 256GB, 8GB, Đen

-- Vivo X100
(45, 3, 4, 2, 20500000, 15), -- 256GB, 12GB, Đen
(45, 3, 4, 7, 20500000, 12), -- 256GB, 12GB, Xanh dương
(45, 5, 4, 2, 23500000, 10), -- 512GB, 12GB, Đen

-- Vivo V30
(47, 2, 3, 2, 11000000, 20), -- 128GB, 8GB, Đen
(47, 2, 3, 3, 11000000, 18), -- 128GB, 8GB, Trắng
(47, 3, 3, 2, 12000000, 15), -- 256GB, 8GB, Đen

-- Realme GT 5
(50, 3, 3, 2, 14500000, 18), -- 256GB, 8GB, Đen
(50, 3, 4, 3, 15500000, 15), -- 256GB, 12GB, Trắng
(50, 5, 4, 2, 17500000, 10), -- 512GB, 12GB, Đen

-- Huawei Mate 60 Pro
(55, 3, 4, 2, 25500000, 12), -- 256GB, 12GB, Đen
(55, 3, 4, 3, 25500000, 10), -- 256GB, 12GB, Trắng
(55, 5, 4, 2, 28500000, 8),  -- 512GB, 12GB, Đen

-- Nothing Phone 2a
(60, 2, 3, 3, 9500000, 25), -- 128GB, 8GB, Trắng
(60, 2, 3, 2, 9500000, 22), -- 128GB, 8GB, Đen
(60, 3, 3, 3, 10500000, 18), -- 256GB, 8GB, Trắng

-- Motorola Edge 40 Pro
(61, 3, 4, 2, 19500000, 15), -- 256GB, 12GB, Đen
(61, 3, 4, 7, 19500000, 12), -- 256GB, 12GB, Xanh dương
(61, 5, 4, 2, 22500000, 8),  -- 512GB, 12GB, Đen

-- Sony Xperia 1 V
(63, 3, 4, 2, 28500000, 10), -- 256GB, 12GB, Đen
(63, 3, 4, 8, 28500000, 8),  -- 256GB, 12GB, Xám
(63, 5, 4, 2, 31500000, 6);  -- 512GB, 12GB, Đen

-- Thêm dữ liệu tồn kho cho các phiên bản sản phẩm mới
INSERT INTO `tonkho` (`makho`, `maphienbansp`, `soluong`) VALUES
-- Kho Hà Nội
(1, 34, 15), -- iPhone 14, 128GB, 6GB, Đen
(1, 35, 12), -- iPhone 14, 128GB, 6GB, Trắng
(1, 36, 10), -- iPhone 14, 256GB, 6GB, Đen
(1, 39, 12), -- iPhone 14 Plus, 128GB, 6GB, Đen
(1, 40, 10), -- iPhone 14 Plus, 128GB, 6GB, Trắng
(1, 43, 11), -- iPhone 15 Plus, 128GB, 6GB, Đen
(1, 44, 9),  -- iPhone 15 Plus, 128GB, 6GB, Trắng
(1, 47, 12), -- Samsung Galaxy S23, 128GB, 6GB, Đen
(1, 48, 10), -- Samsung Galaxy S23, 128GB, 6GB, Trắng
(1, 51, 5),  -- Samsung Galaxy Z Fold 5, 256GB, 12GB, Đen
(1, 52, 4),  -- Samsung Galaxy Z Fold 5, 256GB, 12GB, Trắng
(1, 55, 9),  -- Xiaomi 14, 256GB, 12GB, Trắng
(1, 56, 6),  -- Xiaomi 14, 512GB, 12GB, Đen
(1, 59, 15), -- Xiaomi Redmi Note 13 Pro, 128GB, 6GB, Đen
(1, 63, 8),  -- Google Pixel 8, 128GB, 8GB, Đen
(1, 67, 6),  -- Google Pixel 8 Pro, 256GB, 12GB, Đen
(1, 71, 7),  -- OnePlus 12, 256GB, 12GB, Đen
(1, 74, 11), -- OnePlus Nord 3, 128GB, 8GB, Đen
(1, 78, 4),  -- Oppo Find N3, 256GB, 12GB, Đen
(1, 81, 12), -- Oppo Reno 10, 128GB, 8GB, Đen,

-- Kho TP.HCM
(2, 37, 8),  -- iPhone 14, 256GB, 6GB, Trắng
(2, 38, 5),  -- iPhone 14, 512GB, 6GB, Đen
(2, 41, 8),  -- iPhone 14 Plus, 128GB, 6GB, Hồng
(2, 42, 7),  -- iPhone 14 Plus, 256GB, 6GB, Đen
(2, 45, 7),  -- iPhone 15 Plus, 256GB, 6GB, Đen
(2, 46, 5),  -- iPhone 15 Plus, 512GB, 6GB, Đen
(2, 49, 8),  -- Samsung Galaxy S23, 256GB, 8GB, Đen
(2, 50, 6),  -- Samsung Galaxy S23, 256GB, 8GB, Vàng
(2, 53, 3),  -- Samsung Galaxy Z Fold 5, 512GB, 12GB, Đen
(2, 54, 2),  -- Samsung Galaxy Z Fold 5, 1TB, 12GB, Đen
(2, 57, 5),  -- Xiaomi 14, 512GB, 12GB, Trắng
(2, 58, 14), -- Xiaomi Redmi Note 13 Pro, 128GB, 6GB, Đen
(2, 60, 13), -- Xiaomi Redmi Note 13 Pro, 128GB, 6GB, Trắng
(2, 61, 11), -- Xiaomi Redmi Note 13 Pro, 128GB, 8GB, Đen
(2, 62, 9),  -- Xiaomi Redmi Note 13 Pro, 256GB, 8GB, Đen
(2, 64, 7),  -- Google Pixel 8, 128GB, 8GB, Trắng
(2, 65, 5),  -- Google Pixel 8, 256GB, 8GB, Đen
(2, 68, 5),  -- Google Pixel 8 Pro, 256GB, 12GB, Xám
(2, 69, 4),  -- Google Pixel 8 Pro, 512GB, 12GB, Đen
(2, 70, 3),  -- Google Pixel 8 Pro, 1TB, 12GB, Xám
(2, 72, 6),  -- OnePlus 12, 256GB, 12GB, Xanh ngọc
(2, 73, 5),  -- OnePlus 12, 512GB, 16GB, Đen
(2, 75, 9),  -- OnePlus Nord 3, 256GB, 12GB, Đen
(2, 76, 7),  -- OnePlus Nord 3, 256GB, 12GB, Xanh ngọc
(2, 79, 3),  -- Oppo Find N3, 512GB, 12GB, Trắng
(2, 80, 2),  -- Oppo Find N3, 512GB, 16GB, Nâu đồng
(2, 82, 11), -- Oppo Reno 10, 128GB, 8GB, Trắng
(2, 83, 9),  -- Oppo Reno 10, 256GB, 8GB, Đen
(2, 84, 8),  -- Vivo X100, 256GB, 12GB, Đen
(2, 85, 6),  -- Vivo X100, 256GB, 12GB, Xanh dương
(2, 86, 5);  -- Vivo X100, 512GB, 12GB, Đen

-- Thêm khuyến mãi mới
INSERT INTO `khuyenmai` (`tenkhuyenmai`, `phantramgiam`, `ngaybatdau`, `ngayketthuc`) VALUES
('Giảm giá mùa xuân', 15, '2025-03-01 00:00:00', '2025-03-31 23:59:59'),
('Khuyến mãi sinh nhật', 25, '2025-08-01 00:00:00', '2025-08-15 23:59:59'),
('Quà tặng học sinh', 10, '2025-09-01 00:00:00', '2025-09-30 23:59:59'),
('Khuyến mãi tháng 10', 12, '2025-10-01 00:00:00', '2025-10-31 23:59:59'),
('Giáng sinh vui vẻ', 20, '2025-12-15 00:00:00', '2025-12-31 23:59:59');

-- Áp dụng khuyến mãi cho các sản phẩm
INSERT INTO `apdungkhuyenmai` (`makhuyenmai`, `maphienbansp`) VALUES
(3, 34), -- iPhone 14, Giảm giá mùa xuân
(3, 39), -- iPhone 14 Plus, Giảm giá mùa xuân
(3, 43), -- iPhone 15 Plus, Giảm giá mùa xuân
(4, 47), -- Samsung Galaxy S23, Khuyến mãi sinh nhật
(4, 51), -- Samsung Galaxy Z Fold 5, Khuyến mãi sinh nhật
(4, 55), -- Xiaomi 14, Khuyến mãi sinh nhật
(5, 59), -- Xiaomi Redmi Note 13 Pro, Quà tặng học sinh
(5, 63), -- Google Pixel 8, Quà tặng học sinh
(5, 67), -- Google Pixel 8 Pro, Quà tặng học sinh
(6, 71), -- OnePlus 12, Khuyến mãi tháng 10
(6, 74), -- OnePlus Nord 3, Khuyến mãi tháng 10
(6, 78), -- Oppo Find N3, Khuyến mãi tháng 10
(7, 81), -- Oppo Reno 10, Giáng sinh vui vẻ
(7, 84), -- Vivo X100, Giáng sinh vui vẻ
(7, 87); -- Vivo V30, Giáng sinh vui vẻ

-- Thêm đánh giá cho các sản phẩm
INSERT INTO `khachhang` (`tenkhachhang`, `diachi`, `sdt`, `email`) VALUES
('Lê Thị C', '789 Đà Nẵng', '0923456789', 'lethic@gmail.com'),
('Phạm Văn D', '101 Cần Thơ', '0934567890', 'phamvand@gmail.com'),
('Hoàng Thị E', '202 Huế', '0945678901', 'hoangthie@gmail.com'),
('Ngô Văn F', '303 Nha Trang', '0956789012', 'ngovang@gmail.com');

INSERT INTO `danhgia` (`makh`, `maphienbansp`, `diem`, `nhanxet`) VALUES
(3, 34, 5, 'iPhone 14 có hiệu năng rất tốt, pin trâu hơn thế hệ trước!'),
(3, 47, 4, 'Galaxy S23 nhỏ gọn, camera chụp đẹp, chỉ tiếc pin hơi yếu.'),
(4, 51, 5, 'Galaxy Z Fold 5 là chiếc điện thoại gập hoàn hảo, màn hình đẹp, hiệu năng cao.'),
(4, 55, 4, 'Xiaomi 14 có cấu hình mạnh, chụp hình đẹp, giá tốt so với tính năng.'),
(5, 59, 3, 'Redmi Note 13 Pro camera 200MP ấn tượng, nhưng phần mềm còn một số lỗi vặt.'),
(5, 63, 5, 'Google Pixel 8 có camera xuất sắc và trải nghiệm Android thuần khiết tuyệt vời.'),
(1, 67, 5, 'Pixel 8 Pro là chiếc điện thoại Android tốt nhất hiện nay, camera đỉnh cao!'),
(1, 71, 4, 'OnePlus 12 nhanh, mượt, sạc siêu tốc, camera đã cải tiến nhiều.'),
(2, 78, 5, 'Oppo Find N3 có thiết kế gập đẹp, camera Hasselblad chụp ảnh rất chi tiết.'),
(2, 84, 4, 'Vivo X100 có camera chụp chân dung đẹp nhất phân khúc.');

-- Thêm kho hàng mới
INSERT INTO `khohang` (`tenkho`, `diachi`) VALUES
('Kho Đà Nẵng', '555 Đà Nẵng'),
('Kho Cần Thơ', '777 Cần Thơ');

-- Thêm tồn kho cho kho mới
INSERT INTO `tonkho` (`makho`, `maphienbansp`, `soluong`) VALUES
-- Kho Đà Nẵng
(3, 34, 8),  -- iPhone 14, 128GB, 6GB, Đen
(3, 35, 7),  -- iPhone 14, 128GB, 6GB, Trắng
(3, 43, 6),  -- iPhone 15 Plus, 128GB, 6GB, Đen
(3, 47, 9),  -- Samsung Galaxy S23, 128GB, 6GB, Đen
(3, 59, 12), -- Xiaomi Redmi Note 13 Pro, 128GB, 6GB, Đen
(3, 63, 7),  -- Google Pixel 8, 128GB, 8GB, Đen
(3, 71, 5),  -- OnePlus 12, 256GB, 12GB, Đen
(3, 81, 8),  -- Oppo Reno 10, 128GB, 8GB, Đen

-- Kho Cần Thơ
(4, 39, 6),  -- iPhone 14 Plus, 128GB, 6GB, Đen
(4, 51, 3),  -- Samsung Galaxy Z Fold 5, 256GB, 12GB, Đen
(4, 55, 5),  -- Xiaomi 14, 256GB, 12GB, Trắng
(4, 67, 4),  -- Google Pixel 8 Pro, 256GB, 12GB, Đen
(4, 74, 7),  -- OnePlus Nord 3, 128GB, 8GB, Đen
(4, 78, 2),  -- Oppo Find N3, 256GB, 12GB, Đen
(4, 84, 5);  -- Vivo X100, 256GB, 12GB, Đen

-- Thêm đơn hàng mới
INSERT INTO `donhang` (`makh`, `tongtien`, `diachi`, `trangthai`) VALUES
(3, 21000000, '789 Đà Nẵng', 1), -- Đơn hàng chưa giao
(4, 25500000, '101 Cần Thơ', 2), -- Đơn hàng đã giao
(5, 19500000, '202 Huế', 1);     -- Đơn hàng chưa giao

-- Thêm chi tiết đơn hàng
INSERT INTO `chitietdonhang` (`madonhang`, `maphienbansp`, `soluong`, `dongia`) VALUES
(3, 34, 1, 21000000), -- Đơn 3: 1 iPhone 14, 128GB
(4, 55, 1, 25500000), -- Đơn 4: 1 Xiaomi 14, 256GB
(5, 63, 1, 19500000); -- Đơn 5: 1 Google Pixel 8, 128GB

-- Thêm chi tiết vận chuyển
INSERT INTO `chitietvanchuyen` (`madonhang`, `mavanchuyen`, `trangthai`) VALUES
(3, 1, 1), -- Đơn 3 đang giao tiết kiệm
(4, 2, 2), -- Đơn 4 đã giao nhanh
(5, 3, 1); -- Đơn 5 đang giao hỏa tốc

-- Thêm lịch sử thanh toán
INSERT INTO `lichsuthanhtoan` (`madonhang`, `mapt`, `sotien`, `trangthai`) VALUES
(3, 1, 21000000, 1), -- Đơn 3 thanh toán COD thành công
(4, 2, 25500000, 1), -- Đơn 4 thanh toán chuyển khoản thành công
(5, 3, 19500000, 1); -- Đơn 5 thanh toán thẻ tín dụng thành công

-- Thêm giỏ hàng cho khách hàng mới
INSERT INTO `giohang` (`makh`) VALUES
(3), -- Giỏ hàng của Lê Thị C
(4); -- Giỏ hàng của Phạm Văn D

-- Thêm chi tiết giỏ hàng
INSERT INTO `chitietgiohang` (`magiohang`, `maphienbansp`, `soluong`) VALUES
(3, 67, 1), -- 1 Google Pixel 8 Pro trong giỏ của Lê Thị C
(3, 71, 1), -- 1 OnePlus 12 trong giỏ của Lê Thị C
(4, 78, 1); -- 1 Oppo Find N3 trong giỏ của Phạm Văn D