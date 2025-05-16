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

-- Insert data into mausac (Colors)
INSERT INTO mausac (tenmau) VALUES 
('Đen'), ('Trắng'), ('Xanh dương'), ('Xanh lá'), ('Đỏ'),
('Vàng'), ('Bạc'), ('Tím'), ('Hồng'), ('Cam'),
('Xám'), ('Vàng đồng'), ('Xanh ngọc'), ('Xanh tím than'), ('Đen mờ');

-- Insert data into dungluongram (RAM)
INSERT INTO dungluongram (kichthuocram) VALUES 
(2), (3), (4), (6), (8), (12), (16), (32), (64), (128);

-- Insert data into dungluongrom (ROM/Storage)
INSERT INTO dungluongrom (kichthuocrom) VALUES 
(16), (32), (64), (128), (256), (512), (1024), (2048);

-- Insert data into khachhang (Customers)
INSERT INTO khachhang (tenkhachhang, diachi, sdt, email, matkhau) VALUES
('Nguyễn Văn An', 'Hà Nội', '0901234567', 'an@example.com', MD5('password123')),
('Trần Thị Bình', 'Hồ Chí Minh', '0912345678', 'binh@example.com', MD5('password123')),
('Lê Văn Cường', 'Đà Nẵng', '0923456789', 'cuong@example.com', MD5('password123')),
('Phạm Thị Dung', 'Hải Phòng', '0934567890', 'dung@example.com', MD5('password123')),
('Hoàng Văn Em', 'Cần Thơ', '0945678901', 'em@example.com', MD5('password123')),
('Ngô Thị Phương', 'Huế', '0956789012', 'phuong@example.com', MD5('password123')),
('Đỗ Văn Giang', 'Nghệ An', '0967890123', 'giang@example.com', MD5('password123')),
('Vũ Thị Hoa', 'Thanh Hóa', '0978901234', 'hoa@example.com', MD5('password123')),
('Bùi Văn Inh', 'Quảng Ninh', '0989012345', 'inh@example.com', MD5('password123')),
('Mai Thị Kim', 'Khánh Hòa', '0990123456', 'kim@example.com', MD5('password123'));

-- Insert data into nhanvien (Employees)
INSERT INTO nhanvien (tennv, sdt, email, matkhau, vaitro) VALUES
('Nguyễn Quang Hải', '0801234567', 'hai@store.com', MD5('emp123'), 1),
('Trần Thu Hương', '0812345678', 'huong@store.com', MD5('emp123'), 1),
('Lê Minh Tuấn', '0823456789', 'tuan@store.com', MD5('emp123'), 1),
('Phạm Văn Đức', '0834567890', 'duc@store.com', MD5('emp123'), 2),
('Hoàng Thị Lan', '0845678901', 'lan@store.com', MD5('emp123'), 1);

-- Insert data into phuongthucthanhtoan (Payment Methods)
INSERT INTO phuongthucthanhtoan (tenpt) VALUES
('Tiền mặt'),
('Thẻ tín dụng/Ghi nợ'),
('Chuyển khoản ngân hàng'),
('Ví điện tử MoMo'),
('ZaloPay'),
('VNPay'),
('PayPal');

-- Insert data into vanchuyen (Shipping Methods)
INSERT INTO vanchuyen (tenvanchuyen, phi) VALUES
('Giao hàng tiêu chuẩn', 30000),
('Giao hàng nhanh', 50000),
('Giao hàng trong ngày', 100000),
('Nhận tại cửa hàng', 0);

-- Insert data into khohang (Warehouses)
INSERT INTO khohang (tenkho, diachi) VALUES
('Kho Hà Nội', 'Số 123 Đường Láng, Đống Đa, Hà Nội'),
('Kho Hồ Chí Minh', 'Số 456 Điện Biên Phủ, Quận 3, TP.HCM'),
('Kho Đà Nẵng', 'Số 789 Nguyễn Văn Linh, Hải Châu, Đà Nẵng');

-- Insert data into khuyenmai (Promotions)
INSERT INTO khuyenmai (tenkhuyenmai, phantramgiam, ngaybatdau, ngayketthuc) VALUES
('Khuyến mãi hè', 10, '2025-06-01 00:00:00', '2025-08-31 23:59:59'),
('Black Friday', 25, '2025-11-25 00:00:00', '2025-11-30 23:59:59'),
('Tết Nguyên Đán', 15, '2026-01-15 00:00:00', '2026-02-15 23:59:59'),
('Sinh nhật cửa hàng', 20, '2025-10-01 00:00:00', '2025-10-10 23:59:59'),
('Giáng sinh', 15, '2025-12-15 00:00:00', '2025-12-31 23:59:59');

-- Insert data into sanpham (Products) - At least 50 products
INSERT INTO sanpham (tensp, hinhanh, chipxuly, dungluongpin, kichthuocman, hedieuhanh, camerasau, cameratruoc, thoigianbaohanh, thuonghieu) VALUES
-- Iphone Products
('iPhone 15 Pro Max', 'iphone15promax.jpg', 'A17 Pro', 4422, 6.7, 'iOS 17', '48MP + 12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 15 Pro', 'iphone15pro.jpg', 'A17 Pro', 3274, 6.1, 'iOS 17', '48MP + 12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 15 Plus', 'iphone15plus.jpg', 'A16 Bionic', 4383, 6.7, 'iOS 17', '48MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 15', 'iphone15.jpg', 'A16 Bionic', 3349, 6.1, 'iOS 17', '48MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 14 Pro Max', 'iphone14promax.jpg', 'A16 Bionic', 4323, 6.7, 'iOS 16', '48MP + 12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 14 Pro', 'iphone14pro.jpg', 'A16 Bionic', 3200, 6.1, 'iOS 16', '48MP + 12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 14 Plus', 'iphone14plus.jpg', 'A15 Bionic', 4325, 6.7, 'iOS 16', '12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 14', 'iphone14.jpg', 'A15 Bionic', 3279, 6.1, 'iOS 16', '12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone 13', 'iphone13.jpg', 'A15 Bionic', 3240, 6.1, 'iOS 15', '12MP + 12MP', '12MP', 12, 'Iphone'),
('iPhone SE (2022)', 'iphonese2022.jpg', 'A15 Bionic', 2018, 4.7, 'iOS 15', '12MP', '7MP', 12, 'Iphone'),

-- Samsung Products
('Galaxy S25 Ultra', 'galaxys25ultra.jpg', 'Snapdragon 8 Gen 4', 5000, 6.9, 'Android 15', '200MP + 12MP + 10MP + 10MP', '40MP', 12, 'Samsung'),
('Galaxy S25+', 'galaxys25plus.jpg', 'Snapdragon 8 Gen 4', 4900, 6.7, 'Android 15', '50MP + 12MP + 10MP', '40MP', 12, 'Samsung'),
('Galaxy S25', 'galaxys25.jpg', 'Snapdragon 8 Gen 4', 4000, 6.2, 'Android 15', '50MP + 12MP + 10MP', '40MP', 12, 'Samsung'),
('Galaxy Z Fold 7', 'galaxyzfold7.jpg', 'Snapdragon 8 Gen 4', 4600, 7.6, 'Android 15', '50MP + 12MP + 10MP', '10MP + 4MP', 12, 'Samsung'),
('Galaxy Z Flip 7', 'galaxyzflip7.jpg', 'Snapdragon 8 Gen 4', 3700, 6.7, 'Android 15', '12MP + 12MP', '10MP', 12, 'Samsung'),
('Galaxy A55', 'galaxya55.jpg', 'Exynos 1480', 5000, 6.6, 'Android 14', '50MP + 12MP + 5MP', '32MP', 12, 'Samsung'),
('Galaxy A35', 'galaxya35.jpg', 'Exynos 1380', 5000, 6.6, 'Android 14', '50MP + 8MP + 5MP', '13MP', 12, 'Samsung'),
('Galaxy M55', 'galaxym55.jpg', 'Snapdragon 7 Gen 1', 5000, 6.7, 'Android 14', '50MP + 8MP + 2MP', '32MP', 12, 'Samsung'),
('Galaxy F55', 'galaxyf55.jpg', 'Snapdragon 7 Gen 1', 5000, 6.7, 'Android 14', '50MP + 8MP + 2MP', '32MP', 12, 'Samsung'),
('Galaxy S24 FE', 'galaxys24fe.jpg', 'Snapdragon 8 Gen 3', 4500, 6.4, 'Android 14', '50MP + 12MP + 8MP', '32MP', 12, 'Samsung'),

-- Xiaomi Products
('Xiaomi 14 Ultra', 'xiaomi14ultra.jpg', 'Snapdragon 8 Gen 3', 5000, 6.73, 'Android 14', '50MP + 50MP + 50MP + 50MP', '32MP', 12, 'Xiaomi'),
('Xiaomi 14 Pro', 'xiaomi14pro.jpg', 'Snapdragon 8 Gen 3', 4800, 6.73, 'Android 14', '50MP + 50MP + 50MP', '32MP', 12, 'Xiaomi'),
('Xiaomi 14', 'xiaomi14.jpg', 'Snapdragon 8 Gen 3', 4600, 6.36, 'Android 14', '50MP + 50MP + 32MP', '32MP', 12, 'Xiaomi'),
('Redmi Note 13 Pro+ 5G', 'redminote13proplus.jpg', 'Dimensity 7200', 5000, 6.67, 'Android 13', '200MP + 8MP + 2MP', '16MP', 12, 'Xiaomi'),
('Redmi Note 13 Pro 5G', 'redminote13pro.jpg', 'Snapdragon 7s Gen 2', 5000, 6.67, 'Android 13', '200MP + 8MP + 2MP', '16MP', 12, 'Xiaomi'),
('Redmi Note 13', 'redminote13.jpg', 'Helio G99', 5000, 6.67, 'Android 13', '108MP + 8MP + 2MP', '16MP', 12, 'Xiaomi'),
('POCO X6 Pro', 'pocox6pro.jpg', 'Dimensity 8300', 5000, 6.67, 'Android 14', '64MP + 8MP + 2MP', '16MP', 12, 'Xiaomi'),
('POCO X6', 'pocox6.jpg', 'Snapdragon 7s Gen 2', 5100, 6.67, 'Android 13', '64MP + 8MP + 2MP', '16MP', 12, 'Xiaomi'),
('POCO F6', 'pocof6.jpg', 'Snapdragon 8 Gen 2', 5000, 6.67, 'Android 14', '50MP + 8MP', '16MP', 12, 'Xiaomi'),
('POCO M6', 'pocom6.jpg', 'Helio G85', 5000, 6.74, 'Android 13', '50MP + 2MP', '8MP', 12, 'Xiaomi'),

-- OPPO Products
('OPPO Find X7 Ultra', 'oppofindx7ultra.jpg', 'Snapdragon 8 Gen 3', 5000, 6.82, 'Android 14', '50MP + 50MP + 50MP + 50MP', '32MP', 12, 'OPPO'),
('OPPO Find X7', 'oppofindx7.jpg', 'Dimensity 9300', 4800, 6.78, 'Android 14', '50MP + 50MP + 50MP', '32MP', 12, 'OPPO'),
('OPPO Reno11 Pro', 'opporeno11pro.jpg', 'Dimensity 8200', 4700, 6.7, 'Android 14', '50MP + 8MP + 2MP', '32MP', 12, 'OPPO'),
('OPPO Reno11', 'opporeno11.jpg', 'Dimensity 7050', 4800, 6.7, 'Android 14', '50MP + 8MP + 2MP', '32MP', 12, 'OPPO'),
('OPPO A79', 'oppoa79.jpg', 'Dimensity 6300', 5000, 6.67, 'Android 14', '50MP + 2MP', '8MP', 12, 'OPPO'),

-- Vivo Products
('Vivo X100 Pro', 'vivox100pro.jpg', 'Dimensity 9300', 5400, 6.78, 'Android 14', '50MP + 50MP + 64MP', '32MP', 12, 'Vivo'),
('Vivo X100', 'vivox100.jpg', 'Dimensity 9300', 5000, 6.78, 'Android 14', '50MP + 50MP + 12MP', '32MP', 12, 'Vivo'),
('Vivo V30 Pro', 'vivov30pro.jpg', 'Snapdragon 8 Gen 2', 5000, 6.78, 'Android 14', '50MP + 50MP + 12MP', '50MP', 12, 'Vivo'),
('Vivo V30', 'vivov30.jpg', 'Snapdragon 7 Gen 3', 5000, 6.78, 'Android 14', '50MP + 8MP', '50MP', 12, 'Vivo'),
('Vivo Y200', 'vivoy200.jpg', 'Snapdragon 4 Gen 2', 4800, 6.67, 'Android 14', '64MP + 2MP', '16MP', 12, 'Vivo'),

-- Google Products
('Google Pixel 9 Pro XL', 'pixel9proxl.jpg', 'Tensor G4', 5000, 6.8, 'Android 15', '50MP + 48MP + 48MP', '42MP', 12, 'Google'),
('Google Pixel 9 Pro', 'pixel9pro.jpg', 'Tensor G4', 4700, 6.3, 'Android 15', '50MP + 48MP + 48MP', '42MP', 12, 'Google'),
('Google Pixel 9', 'pixel9.jpg', 'Tensor G4', 4500, 6.1, 'Android 15', '50MP + 12MP', '11MP', 12, 'Google'),
('Google Pixel 9 Fold', 'pixel9fold.jpg', 'Tensor G4', 4500, 7.6, 'Android 15', '48MP + 10.8MP + 10.8MP', '9.5MP + 8MP', 12, 'Google'),
('Google Pixel 8a', 'pixel8a.jpg', 'Tensor G3', 4500, 6.1, 'Android 14', '64MP + 13MP', '13MP', 12, 'Google'),

-- OnePlus Products
('OnePlus 12', 'oneplus12.jpg', 'Snapdragon 8 Gen 3', 5400, 6.82, 'Android 14', '50MP + 48MP + 64MP', '32MP', 12, 'OnePlus'),
('OnePlus 12R', 'oneplus12r.jpg', 'Snapdragon 8 Gen 2', 5500, 6.78, 'Android 14', '50MP + 8MP + 2MP', '16MP', 12, 'OnePlus'),
('OnePlus Nord 4', 'oneplusnord4.jpg', 'Snapdragon 7+ Gen 3', 5500, 6.74, 'Android 14', '50MP + 8MP', '16MP', 12, 'OnePlus'),
('OnePlus Nord CE 4', 'oneplusnordce4.jpg', 'Snapdragon 7 Gen 3', 5500, 6.7, 'Android 14', '50MP + 8MP', '16MP', 12, 'OnePlus'),
('OnePlus Nord CE 4 Lite', 'oneplusnordce4lite.jpg', 'Snapdragon 6 Gen 1', 5000, 6.67, 'Android 14', '50MP + 2MP', '16MP', 12, 'OnePlus'),

-- Realme Products
('Realme GT 6', 'realmegt6.jpg', 'Snapdragon 8s Gen 3', 5500, 6.78, 'Android 14', '50MP + 8MP + 2MP', '32MP', 12, 'Realme'),
('Realme GT Neo 6', 'realmegtnep6.jpg', 'Snapdragon 8 Gen 2', 5500, 6.74, 'Android 14', '50MP + 8MP', '16MP', 12, 'Realme'),
('Realme 12 Pro+', 'realme12proplus.jpg', 'Snapdragon 7s Gen 2', 5000, 6.7, 'Android 14', '50MP + 64MP + 8MP', '32MP', 12, 'Realme'),
('Realme 12 Pro', 'realme12pro.jpg', 'Snapdragon 6 Gen 1', 5000, 6.7, 'Android 14', '50MP + 32MP + 2MP', '16MP', 12, 'Realme'),
('Realme 12', 'realme12.jpg', 'Dimensity 6100+', 5000, 6.67, 'Android 14', '108MP + 2MP', '16MP', 12, 'Realme');

-- Insert data into phienbansanpham (Product Versions)
-- For iPhone 15 Pro Max
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(1, 6, 6, 1, 34990000, 50), -- iPhone 15 Pro Max 256GB 8GB RAM Đen
(1, 6, 6, 2, 34990000, 45), -- iPhone 15 Pro Max 256GB 8GB RAM Trắng
(1, 6, 6, 3, 34990000, 40), -- iPhone 15 Pro Max 256GB 8GB RAM Xanh dương
(1, 7, 6, 1, 39990000, 35), -- iPhone 15 Pro Max 512GB 8GB RAM Đen
(1, 7, 6, 2, 39990000, 30), -- iPhone 15 Pro Max 512GB 8GB RAM Trắng
(1, 7, 6, 3, 39990000, 25); -- iPhone 15 Pro Max 512GB 8GB RAM Xanh dương

-- For iPhone 15 Pro
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(2, 6, 6, 1, 29990000, 55), -- iPhone 15 Pro 256GB 8GB RAM Đen
(2, 6, 6, 2, 29990000, 50), -- iPhone 15 Pro 256GB 8GB RAM Trắng
(2, 6, 6, 3, 29990000, 45), -- iPhone 15 Pro 256GB 8GB RAM Xanh dương
(2, 7, 6, 1, 34990000, 40), -- iPhone 15 Pro 512GB 8GB RAM Đen
(2, 7, 6, 2, 34990000, 35), -- iPhone 15 Pro 512GB 8GB RAM Trắng
(2, 7, 6, 3, 34990000, 30); -- iPhone 15 Pro 512GB 8GB RAM Xanh dương

-- For iPhone 15 Plus
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(3, 5, 6, 1, 24990000, 60), -- iPhone 15 Plus 128GB 8GB RAM Đen
(3, 5, 6, 2, 24990000, 55), -- iPhone 15 Plus 128GB 8GB RAM Trắng
(3, 6, 6, 1, 27990000, 50), -- iPhone 15 Plus 256GB 8GB RAM Đen
(3, 6, 6, 2, 27990000, 45); -- iPhone 15 Plus 256GB 8GB RAM Trắng

-- For iPhone 15
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(4, 5, 6, 1, 21990000, 65), -- iPhone 15 128GB 8GB RAM Đen
(4, 5, 6, 2, 21990000, 60), -- iPhone 15 128GB 8GB RAM Trắng
(4, 6, 6, 1, 24990000, 55), -- iPhone 15 256GB 8GB RAM Đen
(4, 6, 6, 2, 24990000, 50); -- iPhone 15 256GB 8GB RAM Trắng

-- For older iPhones
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(5, 6, 6, 1, 27990000, 40), -- iPhone 14 Pro Max 256GB 8GB RAM Đen
(6, 5, 6, 1, 22990000, 45), -- iPhone 14 Pro 128GB 8GB RAM Đen
(7, 5, 6, 1, 19990000, 50), -- iPhone 14 Plus 128GB 8GB RAM Đen
(8, 5, 6, 1, 17990000, 55), -- iPhone 14 128GB 8GB RAM Đen
(9, 4, 4, 1, 14990000, 60), -- iPhone 13 64GB 4GB RAM Đen
(10, 3, 3, 1, 11990000, 65); -- iPhone SE (2022) 32GB 3GB RAM Đen

-- For Samsung Galaxy S25 series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(11, 6, 8, 1, 29990000, 40), -- Galaxy S25 Ultra 256GB 12GB RAM Đen
(11, 6, 8, 2, 29990000, 35), -- Galaxy S25 Ultra 256GB 12GB RAM Trắng
(11, 7, 8, 1, 32990000, 30), -- Galaxy S25 Ultra 512GB 12GB RAM Đen
(12, 6, 8, 1, 23990000, 45), -- Galaxy S25+ 256GB 12GB RAM Đen
(12, 6, 8, 2, 23990000, 40), -- Galaxy S25+ 256GB 12GB RAM Trắng
(13, 5, 6, 1, 19990000, 50), -- Galaxy S25 128GB 8GB RAM Đen
(13, 5, 6, 2, 19990000, 45); -- Galaxy S25 128GB 8GB RAM Trắng

-- For Samsung Fold and Flip series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(14, 6, 8, 1, 37990000, 25), -- Galaxy Z Fold 7 256GB 12GB RAM Đen
(14, 6, 8, 2, 37990000, 20), -- Galaxy Z Fold 7 256GB 12GB RAM Trắng
(15, 5, 6, 1, 22990000, 30), -- Galaxy Z Flip 7 128GB 8GB RAM Đen
(15, 5, 6, 8, 22990000, 25); -- Galaxy Z Flip 7 128GB 8GB RAM Tím

-- For Samsung A and M series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(16, 5, 6, 1, 10990000, 70), -- Galaxy A55 128GB 8GB RAM Đen
(17, 5, 6, 1, 8990000, 75), -- Galaxy A35 128GB 8GB RAM Đen
(18, 5, 6, 1, 7990000, 80), -- Galaxy M55 128GB 8GB RAM Đen
(19, 5, 6, 1, 7990000, 80), -- Galaxy F55 128GB 8GB RAM Đen
(20, 5, 6, 1, 13990000, 60); -- Galaxy S24 FE 128GB 8GB RAM Đen

-- For Xiaomi series (only one version per product to save space)
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(21, 6, 8, 1, 21990000, 40), -- Xiaomi 14 Ultra 256GB 12GB RAM Đen
(22, 6, 8, 1, 18990000, 45), -- Xiaomi 14 Pro 256GB 12GB RAM Đen
(23, 5, 6, 1, 14990000, 50), -- Xiaomi 14 128GB 8GB RAM Đen
(24, 5, 6, 1, 9990000, 60), -- Redmi Note 13 Pro+ 5G 128GB 8GB RAM Đen
(25, 5, 6, 1, 8990000, 65), -- Redmi Note 13 Pro 5G 128GB 8GB RAM Đen
(26, 4, 6, 1, 5990000, 70), -- Redmi Note 13 64GB 8GB RAM Đen
(27, 5, 6, 1, 8990000, 65), -- POCO X6 Pro 128GB 8GB RAM Đen
(28, 5, 6, 1, 7990000, 70), -- POCO X6 128GB 8GB RAM Đen
(29, 5, 6, 1, 10990000, 60), -- POCO F6 128GB 8GB RAM Đen
(30, 4, 4, 1, 3990000, 80); -- POCO M6 64GB 4GB RAM Đen

-- For OPPO series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(31, 6, 8, 1, 22990000, 35), -- OPPO Find X7 Ultra 256GB 12GB RAM Đen
(32, 6, 8, 1, 19990000, 40), -- OPPO Find X7 256GB 12GB RAM Đen
(33, 5, 6, 1, 12990000, 55), -- OPPO Reno11 Pro 128GB 8GB RAM Đen
(34, 5, 6, 1, 9990000, 60), -- OPPO Reno11 128GB 8GB RAM Đen
(35, 4, 4, 1, 5990000, 70); -- OPPO A79 64GB 4GB RAM Đen

-- For Vivo series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(36, 6, 8, 1, 21990000, 35), -- Vivo X100 Pro 256GB 12GB RAM Đen
(37, 6, 8, 1, 18990000, 40), -- Vivo X100 256GB 12GB RAM Đen
(38, 5, 8, 1, 14990000, 45), -- Vivo V30 Pro 128GB 12GB RAM Đen
(39, 5, 6, 1, 11990000, 50); -- Vivo V30 128GB 

-- Continue VIVO series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(40, 4, 6, 1, 7990000, 65); -- Vivo Y200 64GB 8GB RAM Đen

-- For Google Pixel series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(41, 6, 8, 1, 25990000, 30), -- Google Pixel 9 Pro XL 256GB 12GB RAM Đen
(41, 6, 8, 2, 25990000, 25), -- Google Pixel 9 Pro XL 256GB 12GB RAM Trắng
(42, 6, 8, 1, 22990000, 35), -- Google Pixel 9 Pro 256GB 12GB RAM Đen
(42, 6, 8, 2, 22990000, 30), -- Google Pixel 9 Pro 256GB 12GB RAM Trắng
(43, 5, 6, 1, 18990000, 40), -- Google Pixel 9 128GB 8GB RAM Đen
(44, 6, 8, 1, 35990000, 20), -- Google Pixel 9 Fold 256GB 12GB RAM Đen
(45, 5, 6, 1, 12990000, 45); -- Google Pixel 8a 128GB 8GB RAM Đen

-- For OnePlus series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(46, 6, 8, 1, 17990000, 40), -- OnePlus 12 256GB 12GB RAM Đen
(46, 6, 8, 3, 17990000, 35), -- OnePlus 12 256GB 12GB RAM Xanh dương
(47, 5, 6, 1, 13990000, 45), -- OnePlus 12R 128GB 8GB RAM Đen
(48, 5, 6, 1, 9990000, 50), -- OnePlus Nord 4 128GB 8GB RAM Đen
(49, 5, 6, 1, 7990000, 55), -- OnePlus Nord CE 4 128GB 8GB RAM Đen
(50, 4, 4, 1, 5990000, 60); -- OnePlus Nord CE 4 Lite 64GB 4GB RAM Đen

-- For Realme series
INSERT INTO phienbansanpham (masp, rom, ram, mausac, giaban, soluongton) VALUES
(51, 5, 6, 1, 13990000, 45), -- Realme GT 6 128GB 8GB RAM Đen
(52, 5, 6, 1, 11990000, 50), -- Realme GT Neo 6 128GB 8GB RAM Đen
(53, 5, 6, 1, 9990000, 55), -- Realme 12 Pro+ 128GB 8GB RAM Đen
(54, 5, 6, 1, 7990000, 60), -- Realme 12 Pro 128GB 8GB RAM Đen
(55, 4, 4, 1, 5990000, 65); -- Realme 12 64GB 4GB RAM Đen

-- Insert into giohang (Shopping Cart)
INSERT INTO giohang (makh) VALUES
(1), (2), (3), (4), (5), (6), (7), (8), (9), (10);

-- Insert into chitietgiohang (Shopping Cart Details)
INSERT INTO chitietgiohang (magiohang, maphienbansp, soluong) VALUES
(1, 1, 1), -- Customer 1 adds iPhone 15 Pro Max 256GB to cart
(1, 15, 1), -- Customer 1 adds Galaxy Z Flip 7 to cart
(2, 5, 1), -- Customer 2 adds iPhone 15 Pro Max 512GB Trắng to cart
(3, 11, 1), -- Customer 3 adds Galaxy S25 Ultra 256GB to cart
(4, 21, 1), -- Customer 4 adds Xiaomi 14 Ultra to cart
(5, 31, 1), -- Customer 5 adds OPPO Find X7 Ultra to cart
(6, 41, 1), -- Customer 6 adds Google Pixel 9 Pro XL to cart
(7, 46, 1), -- Customer 7 adds OnePlus 12 to cart
(8, 51, 1), -- Customer 8 adds Realme GT 6 to cart
(9, 26, 1), -- Customer 9 adds Redmi Note 13 to cart
(10, 36, 1); -- Customer 10 adds Vivo X100 Pro to cart

-- Insert into donhang (Orders)
INSERT INTO donhang (makh, thoigian, tongtien, diachi, trangthai) VALUES
(1, '2025-01-15 10:30:00', 57980000, 'Hà Nội', 2), -- Customer 1 completed order
(2, '2025-01-20 14:45:00', 39990000, 'Hồ Chí Minh', 2), -- Customer 2 completed order
(3, '2025-02-05 09:15:00', 29990000, 'Đà Nẵng', 2), -- Customer 3 completed order
(4, '2025-02-10 16:20:00', 21990000, 'Hải Phòng', 2), -- Customer 4 completed order
(5, '2025-03-01 11:00:00', 22990000, 'Cần Thơ', 1), -- Customer 5 order pending
(6, '2025-03-15 15:30:00', 25990000, 'Huế', 1), -- Customer 6 order pending
(7, '2025-04-01 10:45:00', 17990000, 'Nghệ An', 3), -- Customer 7 canceled order
(8, '2025-04-10 09:30:00', 13990000, 'Thanh Hóa', 1), -- Customer 8 order pending
(9, '2025-04-15 14:15:00', 5990000, 'Quảng Ninh', 1), -- Customer 9 order pending
(10, '2025-04-20 16:45:00', 21990000, 'Khánh Hòa', 1); -- Customer 10 order pending

-- Insert into chitietdonhang (Order Details)
INSERT INTO chitietdonhang (madonhang, maphienbansp, soluong, dongia) VALUES
(1, 1, 1, 34990000), -- iPhone 15 Pro Max 256GB Đen
(1, 15, 1, 22990000), -- Galaxy Z Flip 7
(2, 5, 1, 39990000), -- iPhone 15 Pro Max 512GB Trắng
(3, 11, 1, 29990000), -- Galaxy S25 Ultra 256GB Đen
(4, 21, 1, 21990000), -- Xiaomi 14 Ultra
(5, 31, 1, 22990000), -- OPPO Find X7 Ultra
(6, 41, 1, 25990000), -- Google Pixel 9 Pro XL Đen
(7, 46, 1, 17990000), -- OnePlus 12 Đen
(8, 51, 1, 13990000), -- Realme GT 6
(9, 26, 1, 5990000), -- Redmi Note 13
(10, 36, 1, 21990000); -- Vivo X100 Pro

-- Insert into apdungkhuyenmai (Apply Promotions to Products)
INSERT INTO apdungkhuyenmai (makhuyenmai, maphienbansp) VALUES
(1, 1), -- Summer promotion for iPhone 15 Pro Max
(1, 2),
(1, 3),
(1, 11), -- Summer promotion for Galaxy S25 Ultra
(1, 12),
(1, 13),
(2, 21), -- Black Friday for Xiaomi 14 Ultra
(2, 22),
(2, 23),
(3, 31), -- Tet promotion for OPPO Find X7 Ultra
(3, 32),
(3, 33),
(4, 41), -- Store birthday for Google Pixel 9 Pro XL
(4, 42),
(4, 43),
(5, 46), -- Christmas promotion for OnePlus 12
(5, 47),
(5, 48);

-- Insert into lichsuthanhtoan (Payment History)
INSERT INTO lichsuthanhtoan (madonhang, mapt, thoigian, sotien, trangthai) VALUES
(1, 1, '2025-01-15 10:35:00', 57980000, 1), -- Cash payment for Customer 1's order
(2, 2, '2025-01-20 14:50:00', 39990000, 1), -- Credit card payment for Customer 2's order
(3, 3, '2025-02-05 09:20:00', 29990000, 1), -- Bank transfer for Customer 3's order
(4, 4, '2025-02-10 16:25:00', 21990000, 1), -- MoMo payment for Customer 4's order
(7, 6, '2025-04-01 10:50:00', 17990000, 0); -- Failed VNPay payment for Customer 7's order (which was later canceled)

-- Insert into chitietvanchuyen (Shipping Details)
INSERT INTO chitietvanchuyen (madonhang, mavanchuyen, thoigian, trangthai) VALUES
(1, 1, '2025-01-15 11:00:00', 2), -- Standard shipping for Customer 1's order (delivered)
(2, 2, '2025-01-20 15:00:00', 2), -- Express shipping for Customer 2's order (delivered)
(3, 3, '2025-02-05 10:00:00', 2), -- Same-day shipping for Customer 3's order (delivered)
(4, 4, '2025-02-10 16:30:00', 2), -- Store pickup for Customer 4's order (delivered)
(5, 1, '2025-03-01 11:30:00', 1), -- Standard shipping for Customer 5's order (in transit)
(6, 2, '2025-03-15 16:00:00', 1), -- Express shipping for Customer 6's order (in transit)
(8, 3, '2025-04-10 10:00:00', 1), -- Same-day shipping for Customer 8's order (in transit)
(9, 1, '2025-04-15 14:30:00', 1), -- Standard shipping for Customer 9's order (in transit)
(10, 2, '2025-04-20 17:00:00', 1); -- Express shipping for Customer 10's order (in transit)

-- Insert into tonkho (Inventory)
-- For warehouse in Hanoi
INSERT INTO tonkho (makho, maphienbansp, soluong) VALUES
(1, 1, 20), -- iPhone 15 Pro Max 256GB Đen in Hanoi warehouse
(1, 2, 15), -- iPhone 15 Pro Max 256GB Trắng in Hanoi warehouse
(1, 3, 15), -- iPhone 15 Pro Max 256GB Xanh dương in Hanoi warehouse
(1, 11, 15), -- Galaxy S25 Ultra 256GB Đen in Hanoi warehouse
(1, 21, 15), -- Xiaomi 14 Ultra in Hanoi warehouse
(1, 31, 12), -- OPPO Find X7 Ultra in Hanoi warehouse
(1, 41, 10), -- Google Pixel 9 Pro XL in Hanoi warehouse
(1, 46, 15), -- OnePlus 12 in Hanoi warehouse
(1, 51, 15); -- Realme GT 6 in Hanoi warehouse

-- For warehouse in HCM
INSERT INTO tonkho (makho, maphienbansp, soluong) VALUES
(2, 1, 20), -- iPhone 15 Pro Max 256GB Đen in HCM warehouse
(2, 2, 20), -- iPhone 15 Pro Max 256GB Trắng in HCM warehouse
(2, 3, 15), -- iPhone 15 Pro Max 256GB Xanh dương in HCM warehouse
(2, 11, 15), -- Galaxy S25 Ultra 256GB Đen in HCM warehouse
(2, 21, 15), -- Xiaomi 14 Ultra in HCM warehouse
(2, 31, 13), -- OPPO Find X7 Ultra in HCM warehouse
(2, 41, 10), -- Google Pixel 9 Pro XL in HCM warehouse
(2, 46, 15), -- OnePlus 12 in HCM warehouse
(2, 51, 20); -- Realme GT 6 in HCM warehouse

-- For warehouse in Da Nang
INSERT INTO tonkho (makho, maphienbansp, soluong) VALUES
(3, 1, 10), -- iPhone 15 Pro Max 256GB Đen in Da Nang warehouse
(3, 2, 10), -- iPhone 15 Pro Max 256GB Trắng in Da Nang warehouse
(3, 3, 10), -- iPhone 15 Pro Max 256GB Xanh dương in Da Nang warehouse
(3, 11, 10), -- Galaxy S25 Ultra 256GB Đen in Da Nang warehouse
(3, 21, 10), -- Xiaomi 14 Ultra in Da Nang warehouse
(3, 31, 10), -- OPPO Find X7 Ultra in Da Nang warehouse
(3, 41, 10), -- Google Pixel 9 Pro XL in Da Nang warehouse
(3, 46, 10), -- OnePlus 12 in Da Nang warehouse
(3, 51, 10); -- Realme GT 6 in Da Nang warehouse

-- Insert into danhgia (Reviews)
INSERT INTO danhgia (makh, maphienbansp, diem, nhanxet, thoigian) VALUES
(1, 1, 5, 'Sản phẩm tuyệt vời, chụp ảnh rất đẹp và pin trâu.', '2025-01-20 09:30:00'), -- Customer 1 reviews iPhone 15 Pro Max
(1, 15, 4, 'Điện thoại gập rất tiện lợi, nhưng hơi dễ xước.', '2025-01-20 09:45:00'), -- Customer 1 reviews Galaxy Z Flip 7
(2, 5, 5, 'Hiệu năng tuyệt vời, màn hình rất sắc nét.', '2025-01-25 14:20:00'), -- Customer 2 reviews iPhone 15 Pro Max 512GB
(3, 11, 4, 'Camera chụp đêm rất tốt, nhưng hơi nóng khi sử dụng lâu.', '2025-02-10 16:15:00'), -- Customer 3 reviews Galaxy S25 Ultra
(4, 21, 5, 'Điện thoại chụp ảnh tuyệt vời, pin trâu.', '2025-02-15 11:30:00'); -- Customer 4 reviews Xiaomi 14 Ultra