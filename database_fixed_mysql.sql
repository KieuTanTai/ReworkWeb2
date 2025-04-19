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


-- Bảng thương hiệu
CREATE TABLE IF NOT EXISTS `thuonghieu` (
  `mathuonghieu` INT NOT NULL AUTO_INCREMENT,
  `tenthuonghieu` VARCHAR(255) NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`mathuonghieu`),
  UNIQUE KEY `tenthuonghieu` (`tenthuonghieu`)
);

-- Bảng hệ điều hành
CREATE TABLE IF NOT EXISTS `hedieuhanh` (
  `mahedieuhanh` INT NOT NULL AUTO_INCREMENT,
  `tenhedieuhanh` VARCHAR(255) NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`mahedieuhanh`),
  UNIQUE KEY `tenhedieuhanh` (`tenhedieuhanh`)
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
  `hedieuhanh` INT DEFAULT NULL,
  `camerasau` VARCHAR(255) DEFAULT NULL,
  `cameratruoc` VARCHAR(255) DEFAULT NULL,
  `thoigianbaohanh` INT DEFAULT 12 CHECK (`thoigianbaohanh` >= 0),
  `thuonghieu` INT NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`masp`),
  FOREIGN KEY (`thuonghieu`) REFERENCES `thuonghieu` (`mathuonghieu`),
  FOREIGN KEY (`hedieuhanh`) REFERENCES `hedieuhanh` (`mahedieuhanh`)
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

-- Bảng kho hàng (bổ sung mới)
CREATE TABLE IF NOT EXISTS `khohang` (
  `makho` INT NOT NULL AUTO_INCREMENT,
  `tenkho` VARCHAR(255) NOT NULL,
  `diachi` VARCHAR(255) NOT NULL,
  `trangthai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`makho`),
  UNIQUE KEY `tenkho` (`tenkho`)
);

-- Bảng tồn kho (bổ sung mới)
CREATE TABLE IF NOT EXISTS `tonkho` (
  `makho` INT NOT NULL,
  `maphienbansp` INT NOT NULL,
  `soluong` INT DEFAULT 0 CHECK (`soluong` >= 0),
  PRIMARY KEY (`makho`, `maphienbansp`),
  FOREIGN KEY (`makho`) REFERENCES `khohang` (`makho`),
  FOREIGN KEY (`maphienbansp`) REFERENCES `phienbansanpham` (`maphienbansp`)
);

-- Bảng nhân viên (bổ sung mới)
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
INSERT IGNORE INTO `thuonghieu` (`tenthuonghieu`) VALUES 
('Iphone'), ('Samsung'), ('Vivo'), ('Xiaomi'), ('Oppo');

INSERT IGNORE INTO `hedieuhanh` (`tenhedieuhanh`) VALUES 
('Android'), ('IOS');

INSERT IGNORE INTO `mausac` (`tenmau`) VALUES 
('Xanh'), ('Đen'), ('Trắng'), ('Vàng');

INSERT IGNORE INTO `dungluongram` (`kichthuocram`) VALUES 
(4), (6), (8);

INSERT IGNORE INTO `dungluongrom` (`kichthuocrom`) VALUES 
(64), (128), (256);

INSERT IGNORE INTO `sanpham` (`tensp`, `hinhanh`, `chipxuly`, `dungluongpin`, `kichthuocman`, `hedieuhanh`, `camerasau`, `cameratruoc`, `thuonghieu`) VALUES
('iPhone 13', 'iphone13.jpg', 'A15 Bionic', 3240, 6.1, 2, '12MP + 12MP', '12MP', 1),
('Samsung Galaxy A53', 'samsunggalaxya53.jpg', 'Exynos 1280', 5000, 6.5, 1, '64MP + 12MP', '32MP', 2),
('Vivo Y22s', 'vivoy22s.jpg', 'Snapdragon 680', 5000, 6.55, 1, '50MP + 2MP', '8MP', 3),
('OPPO Reno8 Pro', 'opporeno8pro.jpg', 'MediaTek Dimensity 1300', 4500, 6.43, 1, '50MP + 8MP + 2MP', '32MP', 4),
('Xiaomi 12', 'xiaomi12.jpg', 'Snapdragon 8 Gen 1', 4500, 6.28, 1, '50MP + 13MP + 5MP', '32MP', 5),
('iPhone 14 Pro', 'iphone14pro.jpg', 'A16 Bionic', 3200, 6.1, 2, '48MP + 12MP + 12MP', '12MP', 1),
('Samsung Galaxy S22', 'samsunggalaxys22.jpg', 'Snapdragon 8 Gen 1', 3700, 6.1, 1, '50MP + 12MP + 10MP', '10MP', 2),
('Vivo V25 Plus', 'vivov25plus.jpg', 'MediaTek Dimensity 900', 4500, 6.44, 1, '64MP + 8MP + 2MP', '50MP', 3),
('OPPO A96', 'oppoa96.jpg', 'Snapdragon 680', 5000, 6.59, 1, '50MP + 2MP', '16MP', 4),
('Xiaomi Redmi Note 11 Max', 'xiaomiredminote11max.jpg', 'Snapdragon 680', 5000, 6.43, 1, '50MP + 8MP + 2MP + 2MP', '13MP', 5),
('iPhone 13 Pro', 'iphone13pro.jpg', 'A15 Bionic', 3240, 6.1, 2, '12MP + 12MP', '12MP', 1),
('Samsung Galaxy A53 Max', 'samsunggalaxya53max.jpg', 'Exynos 1280', 5000, 6.5, 1, '64MP + 12MP', '32MP', 2),
('Vivo Y22s Plus', 'vivoy22splus.jpg', 'Snapdragon 680', 5000, 6.55, 1, '50MP + 2MP', '8MP', 3),
('OPPO Reno8 Plus', 'opporeno8plus.jpg', 'MediaTek Dimensity 1300', 4500, 6.43, 1, '50MP + 8MP + 2MP', '32MP', 4),
('Xiaomi 12 Pro', 'xiaomi12pro.jpg', 'Snapdragon 8 Gen 1', 4500, 6.28, 1, '50MP + 13MP + 5MP', '32MP', 5),
('iPhone 14 Max', 'iphone14max.jpg', 'A16 Bionic', 3200, 6.1, 2, '48MP + 12MP + 12MP', '12MP', 1),
('Samsung Galaxy S22 Plus', 'samsunggalaxys22plus.jpg', 'Snapdragon 8 Gen 1', 3700, 6.1, 1, '50MP + 12MP + 10MP', '10MP', 2),
('Vivo V25 Pro', 'vivov25pro.jpg', 'MediaTek Dimensity 900', 4500, 6.44, 1, '64MP + 8MP + 2MP', '50MP', 3),
('OPPO A96 Max', 'oppoa96max.jpg', 'Snapdragon 680', 5000, 6.59, 1, '50MP + 2MP', '16MP', 4),
('Xiaomi Redmi Note 11 Pro', 'xiaomiredminote11pro.jpg', 'Snapdragon 680', 5000, 6.43, 1, '50MP + 8MP + 2MP + 2MP', '13MP', 5),
('iPhone 13 Max', 'iphone13max.jpg', 'A15 Bionic', 3240, 6.1, 2, '12MP + 12MP', '12MP', 1),
('Samsung Galaxy A53 Pro', 'samsunggalaxya53pro.jpg', 'Exynos 1280', 5000, 6.5, 1, '64MP + 12MP', '32MP', 2),
('Vivo Y22s Max', 'vivoy22smax.jpg', 'Snapdragon 680', 5000, 6.55, 1, '50MP + 2MP', '8MP', 3),
('OPPO Reno8 Max', 'opporeno8max.jpg', 'MediaTek Dimensity 1300', 4500, 6.43, 1, '50MP + 8MP + 2MP', '32MP', 4),
('Xiaomi 12 Max', 'xiaomi12max.jpg', 'Snapdragon 8 Gen 1', 4500, 6.28, 1, '50MP + 13MP + 5MP', '32MP', 5),
('iPhone 14', 'iphone14.jpg', 'A16 Bionic', 3200, 6.1, 2, '48MP + 12MP + 12MP', '12MP', 1),
('Samsung Galaxy S22 Ultra', 'samsunggalaxys22ultra.jpg', 'Snapdragon 8 Gen 1', 3700, 6.1, 1, '50MP + 12MP + 10MP', '10MP', 2),
('Vivo V25 Max', 'vivov25max.jpg', 'MediaTek Dimensity 900', 4500, 6.44, 1, '64MP + 8MP + 2MP', '50MP', 3),
('OPPO A96 Pro', 'oppoa96pro.jpg', 'Snapdragon 680', 5000, 6.59, 1, '50MP + 2MP', '16MP', 4),
('Xiaomi Redmi Note 11 Ultra', 'xiaomiredminote11ultra.jpg', 'Snapdragon 680', 5000, 6.43, 1, '50MP + 8MP + 2MP + 2MP', '13MP', 5);


INSERT IGNORE INTO `phienbansanpham` (`masp`, `rom`, `ram`, `mausac`, `giaban`, `soluongton`) VALUES
(1, 1, 1, 3, 9583790, 30),
(2, 3, 2, 2, 14794859, 29),
(3, 2, 2, 2, 20814634, 6),
(4, 2, 3, 2, 9158500, 25),
(5, 3, 2, 3, 23019635, 30),
(6, 1, 3, 2, 23482762, 30),
(7, 3, 2, 1, 4997737, 29),
(8, 1, 1, 4, 24064300, 28),
(9, 3, 3, 1, 11977566, 14),
(10, 1, 2, 4, 18944260, 6),
(11, 2, 1, 4, 10979063, 10),
(12, 1, 1, 4, 5357071, 9),
(13, 2, 3, 3, 4543725, 26),
(14, 1, 3, 1, 13911139, 13),
(15, 2, 2, 2, 18028661, 21),
(16, 2, 1, 4, 13321133, 25),
(17, 2, 1, 1, 14092000, 14),
(18, 3, 2, 2, 8419227, 27),
(19, 1, 3, 4, 21884802, 18),
(20, 3, 2, 4, 11311232, 27),
(21, 2, 2, 3, 18327976, 17),
(22, 2, 3, 2, 13822125, 24),
(23, 2, 3, 4, 22115677, 10),
(24, 2, 3, 1, 19307470, 22),
(25, 3, 2, 2, 18444491, 5),
(26, 3, 1, 3, 19414398, 10),
(27, 2, 3, 2, 23290473, 23),
(28, 2, 2, 3, 18699395, 6),
(29, 1, 2, 3, 15400241, 27),
(30, 1, 2, 4, 10493642, 9);


INSERT IGNORE INTO `khachhang` (`tenkhachhang`, `diachi`, `sdt`, `email`, `matkhau`) VALUES
('Nguyễn Văn A', '123 Hà Nội', '0912345678', 'nguyenvana@gmail.com', '$2y$10$examplehashA'),
('Trần Thị B', '456 TP.HCM', '0987654321', 'tranthib@gmail.com', '$2y$10$examplehashB'),
('Lê Hoàng C', '789 Đà Nẵng', '0933344556', 'lehoangc@gmail.com', '$2y$10$examplehashC'),
('Phạm Quỳnh D', '321 Hải Phòng', '0901234567', 'phamquynhd@gmail.com', '$2y$10$examplehashD'),
('Võ Anh E', '654 Cần Thơ', '0945678910', 'voanhe@gmail.com', '$2y$10$examplehashE'),
('Đỗ Thị F', '987 Nha Trang', '0965432198', 'dothif@gmail.com', '$2y$10$examplehashF'),
('Ngô Minh G', '111 Huế', '0978456123', 'ngominhg@gmail.com', '$2y$10$examplehashG'),
('Trịnh Văn H', '222 Vũng Tàu', '0923456789', 'trinhvanh@gmail.com', '$2y$10$examplehashH'),
('Huỳnh Thị I', '333 Quảng Ninh', '0932123456', 'huynhthii@gmail.com', '$2y$10$examplehashI'),
('Bùi Đức J', '444 Long An', '0956781234', 'buiducj@gmail.com', '$2y$10$examplehashJ'),
('Nguyễn Thảo K', '555 Bình Dương', '0911223344', 'nguyenthaok@gmail.com', '$2y$10$examplehashK'),
('Trần Vũ L', '666 Biên Hòa', '0977553311', 'tranvul@gmail.com', '$2y$10$examplehashL');


INSERT IGNORE INTO `donhang` (`makh`, `tongtien`, `trangthai`) VALUES
(1, 20000000, 1), -- Đơn hàng chưa giao
(2, 7000000, 2);  -- Đơn hàng đã giao

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

-- Thêm cột diachi vào bảng donhang
ALTER TABLE `donhang`
ADD COLUMN `diachi` VARCHAR(255) DEFAULT NULL AFTER `tongtien`;

-- 1. Xóa các ràng buộc khóa ngoại trong bảng sanpham
ALTER TABLE `sanpham`
DROP FOREIGN KEY `sanpham_ibfk_1`, -- Ràng buộc với `thuonghieu`
DROP FOREIGN KEY `sanpham_ibfk_2`; -- Ràng buộc với `hedieuhanh`

-- 2. Chỉnh sửa bảng sanpham
ALTER TABLE `sanpham`
MODIFY COLUMN `hedieuhanh` VARCHAR(255) DEFAULT NULL, -- Chuyển hệ điều hành thành chuỗi
MODIFY COLUMN `thuonghieu` VARCHAR(255) NOT NULL;     -- Chuyển thương hiệu thành chuỗi

-- 3. Xóa bảng hệ điều hành và thương hiệu
DROP TABLE IF EXISTS `hedieuhanh`;
DROP TABLE IF EXISTS `thuonghieu`;

-- Cập nhật dữ liệu thương hiệu và hệ điều hành
UPDATE `sanpham`
SET `thuonghieu` = 'Iphone', `hedieuhanh` = 'IOS'
WHERE `tensp` = 'iPhone 13';

UPDATE `sanpham`
SET `thuonghieu` = 'Samsung', `hedieuhanh` = 'Android'
WHERE `tensp` = 'Samsung Galaxy A53';

UPDATE `sanpham`
SET `thuonghieu` = 'Vivo', `hedieuhanh` = 'Android'
WHERE `tensp` = 'Vivo Y22s';


--
-- Cập nhật thương hiệu cho sản phẩm mới hoặc hiện tại
-- Nếu thêm sản phẩm mới với các thương hiệu này:
INSERT INTO `sanpham` (`tensp`, `hinhanh`, `chipxuly`, `dungluongpin`, `kichthuocman`, `hedieuhanh`, `camerasau`, `cameratruoc`, `thuonghieu`) 
VALUES
('Redmi Note 12', 'redmi_note12.jpg', 'Snapdragon 685', 5000, 6.67, 'Android', '50MP + 8MP + 2MP', '13MP', 'Redmi'),
('Google Pixel 7', 'google_pixel7.jpg', 'Google Tensor G2', 4355, 6.3, 'Android', '50MP + 12MP', '10.8MP', 'Google'),
('Oppo Reno 8T', 'oppo_reno8t.jpg', 'Dimensity 920', 4800, 6.7, 'Android', '108MP', '32MP', 'Oppo');
