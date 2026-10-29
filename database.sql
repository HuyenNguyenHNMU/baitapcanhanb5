CREATE DATABASE IF NOT EXISTS `studenthub_canhan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studenthub`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `dang_ky_hoc`;
DROP TABLE IF EXISTS `lop_hoc_phan`;
DROP TABLE IF EXISTS `hoc_phan`;
DROP TABLE IF EXISTS `hoc_ky`;
DROP TABLE IF EXISTS `nguoi_dung`;

-- 1. BẢNG NGƯỜI DÙNG
CREATE TABLE `nguoi_dung` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ten_dang_nhap` VARCHAR(50) NOT NULL,
    `mat_khau_hash` VARCHAR(255) NOT NULL,
    `ho_ten` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NULL,
    `so_dien_thoai` VARCHAR(20) NULL,
    `vai_tro` ENUM('sinh_vien', 'giang_vien', 'admin') NOT NULL DEFAULT 'sinh_vien',
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `uc_nguoi_dung_username` UNIQUE (`ten_dang_nhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG HỌC KỲ
CREATE TABLE `hoc_ky` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ten_hoc_ky` VARCHAR(100) NOT NULL,
    `ngay_bat_dau` DATE NOT NULL,
    `ngay_ket_thuc` DATE NOT NULL,
    `thoi_gian_mo_dk` DATETIME NOT NULL,
    `thoi_gian_dong_dk` DATETIME NOT NULL,
    `trang_thai_dang_ky` TINYINT(1) NOT NULL DEFAULT 1,
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG HỌC PHẦN
CREATE TABLE `hoc_phan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ma_hoc_phan` VARCHAR(20) NOT NULL,
    `ten_hoc_phan` VARCHAR(150) NOT NULL,
    `so_tin_chi` INT NOT NULL,
    `loai_hoc_phan` ENUM('dai_cuong', 'co_so_nganh', 'chuyen_nganh') NOT NULL DEFAULT 'co_so_nganh',
    `hoc_phan_tien_quyet_id` INT NULL,
    `mo_ta` TEXT NULL,
    `dieu_kien_tien_quyet` VARCHAR(255) NOT NULL DEFAULT 'Không có',
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `uc_hoc_phan_ma` UNIQUE (`ma_hoc_phan`),
    CONSTRAINT `chk_hoc_phan_tin_chi` CHECK (`so_tin_chi` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG LỚP HỌC PHẦN
CREATE TABLE `lop_hoc_phan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ma_lop` VARCHAR(50) NOT NULL,
    `hoc_phan_id` INT NOT NULL,
    `hoc_ky_id` INT NOT NULL,
    `giang_vien_id` INT NULL,
    `si_so_toi_da` INT NOT NULL DEFAULT 40,
    `trang_thai` ENUM('mo', 'khoa') NOT NULL DEFAULT 'mo',
    `thoi_gian_mo_rieng` DATETIME NULL,
    `thoi_gian_dong_rieng` DATETIME NULL,
    `lich_hoc` VARCHAR(100) NULL,
    `phong_hoc` VARCHAR(100) NULL,
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `uc_lop_ma_lop` UNIQUE (`ma_lop`),
    CONSTRAINT `fk_lop_hoc_phan` FOREIGN KEY (`hoc_phan_id`) REFERENCES `hoc_phan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_lop_hoc_ky` FOREIGN KEY (`hoc_ky_id`) REFERENCES `hoc_ky` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_lop_giang_vien` FOREIGN KEY (`giang_vien_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. BẢNG ĐĂNG KÝ HỌC
CREATE TABLE `dang_ky_hoc` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sinh_vien_id` INT NOT NULL,
    `lop_hoc_phan_id` INT NOT NULL,
    `trang_thai` ENUM('dang_ky', 'da_huy') NOT NULL DEFAULT 'dang_ky',
    `ngay_dang_ky` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ngay_huy` DATETIME NULL,
    CONSTRAINT `uc_sinh_vien_lop` UNIQUE (`sinh_vien_id`, `lop_hoc_phan_id`),
    CONSTRAINT `fk_dkh_sinh_vien` FOREIGN KEY (`sinh_vien_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_dkh_lop_hoc_phan` FOREIGN KEY (`lop_hoc_phan_id`) REFERENCES `lop_hoc_phan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DỮ LIỆU MẪU NGƯỜI DÙNG
INSERT INTO `nguoi_dung` (`id`, `ten_dang_nhap`, `mat_khau_hash`, `ho_ten`, `email`, `so_dien_thoai`, `vai_tro`, `ngay_tao`) VALUES
(1, 'admin', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Quản trị viên Hệ thống', 'admin@studenthub.edu.vn', '0901234567', 'admin', '2026-08-01 08:00:00'),
(2, 'GV001', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'ThS Trần Bình Trọng', 'trong.tb@studenthub.edu.vn', '0912345678', 'giang_vien', '2026-08-01 08:00:00'),
(3, 'GV002', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'TS Lê Phương Hoa', 'hoa.lp@studenthub.edu.vn', '0913456789', 'giang_vien', '2026-08-01 08:00:00'),
(4, 'GV003', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'PGS.TS Nguyễn Văn An', 'an.nv@studenthub.edu.vn', '0914567890', 'giang_vien', '2026-08-01 08:00:00'),
(5, 'GV004', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'ThS Hoàng Bích Ngọc', 'ngoc.hb@studenthub.edu.vn', '0915678901', 'giang_vien', '2026-08-01 08:00:00'),
(6, 'GV005', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'TS Vũ Đình Đạt', 'dat.vd@studenthub.edu.vn', '0916789012', 'giang_vien', '2026-08-01 08:00:00'),
(7,  'SV001', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Nguyễn Thu Trang', 'trang.nt@studenthub.edu.vn', '0934567890', 'sinh_vien', '2026-08-15 08:00:00'),
(8,  'SV002', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Vũ Quốc Bảo', 'bao.vq@studenthub.edu.vn', '0945678901', 'sinh_vien', '2026-08-15 08:00:00'),
(9,  'SV003', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Lê Hoàng Nam', 'nam.lh@studenthub.edu.vn', '0956789012', 'sinh_vien', '2026-08-15 08:00:00'),
(10, 'SV004', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Phạm Mai Phương', 'phuong.pm@studenthub.edu.vn', '0967890123', 'sinh_vien', '2026-08-15 08:00:00'),
(11, 'SV005', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Đỗ Minh Quân', 'quan.dm@studenthub.edu.vn', '0978901234', 'sinh_vien', '2026-08-15 08:00:00'),
(12, 'SV006', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Trần Thị Mai', 'mai.tt@studenthub.edu.vn', '0989012345', 'sinh_vien', '2026-08-15 08:00:00'),
(13, 'SV007', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Hoàng Đức Anh', 'anh.hd@studenthub.edu.vn', '0911223344', 'sinh_vien', '2026-08-15 08:00:00'),
(14, 'SV008', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Bùi Khánh Linh', 'linh.bk@studenthub.edu.vn', '0922334455', 'sinh_vien', '2026-08-15 08:00:00'),
(15, 'SV009', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Dương Tuấn Kiệt', 'kiet.dt@studenthub.edu.vn', '0933445566', 'sinh_vien', '2026-08-15 08:00:00'),
(16, 'SV010', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Ngô Phương Thảo', 'thao.np@studenthub.edu.vn', '0944556677', 'sinh_vien', '2026-08-15 08:00:00'),
(17, 'SV011', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Đinh Trọng Hiếu', 'hieu.dt@studenthub.edu.vn', '0955667788', 'sinh_vien', '2026-08-15 08:00:00'),
(18, 'SV012', '$2y$10$Knpw6PuRu8HeFmmuvzjzUOmK4IMoR03PcaOxqZ53TnuMRR0BxF2ri', 'Lê Thanh Hằng', 'hang.lt@studenthub.edu.vn', '0966778899', 'sinh_vien', '2026-08-15 08:00:00');

-- DỮ LIỆU MẪU HỌC KỲ
INSERT INTO `hoc_ky` (`id`, `ten_hoc_ky`, `ngay_bat_dau`, `ngay_ket_thuc`, `thoi_gian_mo_dk`, `thoi_gian_dong_dk`, `trang_thai_dang_ky`) VALUES
(1, 'Học kỳ 1 (2026 - 2027)', '2026-09-01', '2027-01-15', '2026-08-15 08:00:00', '2026-12-30 23:59:59', 1),
(2, 'Học kỳ 2 (2026 - 2027)', '2027-02-01', '2027-06-30', '2027-01-10 08:00:00', '2027-02-10 23:59:59', 0);

-- DỮ LIỆU MẪU HỌC PHẦN
INSERT INTO `hoc_phan` (`id`, `ma_hoc_phan`, `ten_hoc_phan`, `so_tin_chi`, `loai_hoc_phan`, `hoc_phan_tien_quyet_id`, `mo_ta`, `dieu_kien_tien_quyet`) VALUES
(1, 'MAT101', 'Toán Cao Cấp', 3, 'dai_cuong', NULL, 'Trang bị kiến thức nền tảng về phép tính vi tích phân, chuỗi số, ma trận định thức.', 'Không có'),
(2, 'MAT205', 'Giải Tích Số', 3, 'co_so_nganh', 1, 'Nghiên cứu các phương pháp giải gần đúng bài toán toán học.', 'Toán Cao Cấp (MAT101)'),
(3, 'WEB102', 'Lập Trình Web Cơ Bản', 3, 'co_so_nganh', NULL, 'Nhập môn thiết kế giao diện web với HTML5, CSS3, JavaScript.', 'Không có'),
(4, 'WEB201', 'Phát Triển Web Nâng Cao', 3, 'chuyen_nganh', 3, 'Xây dựng ứng dụng web hiện đại theo mô hình MVC sử dụng PHP 8.x, MySQL, PDO.', 'Lập Trình Web Cơ Bản (WEB102)'),
(5, 'DB101', 'Cơ Sở Dữ Liệu Quan Hệ', 3, 'co_so_nganh', NULL, 'Nắm vững mô hình quan hệ ERD, đại số quan hệ, ngôn ngữ truy vấn SQL.', 'Không có'),
(6, 'DSA102', 'Cấu Trúc Dữ Liệu & Giải Thuật', 4, 'co_so_nganh', NULL, 'Nghiên cứu các cấu trúc dữ liệu kinh điển và giải thuật.', 'Không có'),
(7, 'NET101', 'Mạng Máy Tính Căn Bản', 3, 'co_so_nganh', NULL, 'Khái niệm mô hình OSI 7 tầng, bộ giao thức TCP/IP.', 'Không có'),
(8, 'SE201', 'Công Nghệ Phần Mềm', 3, 'chuyen_nganh', 6, 'Quy trình phát triển phần mềm Agile/Scrum, phân tích thiết kế hệ thống.', 'Cấu Trúc Dữ Liệu & Giải Thuật (DSA102)'),
(9, 'AI301', 'Trí Tuệ Nhân Tạo & Học Máy', 3, 'chuyen_nganh', 6, 'Phương pháp tìm kiếm không gian trạng thái, học máy.', 'Cấu Trúc Dữ Liệu & Giải Thuật (DSA102)'),
(10, 'SEC201', 'An Toàn & Bảo Mật Thông Tin', 3, 'chuyen_nganh', 7, 'Các kỹ thuật tấn công và phòng thủ mạng, mật mã học.', 'Mạng Máy Tính Căn Bản (NET101)');

-- DỮ LIỆU MẪU 14 LỚP HỌC PHẦN (12 ĐANG MỞ, 2 ĐANG KHÓA)
INSERT INTO `lop_hoc_phan` (`id`, `ma_lop`, `hoc_phan_id`, `hoc_ky_id`, `giang_vien_id`, `si_so_toi_da`, `trang_thai`, `thoi_gian_mo_rieng`, `thoi_gian_dong_rieng`, `lich_hoc`, `phong_hoc`) VALUES
(1,  'LHP-WEB01', 4, 1, 2, 40, 'mo', NULL, NULL, 'Thứ 2 & Thứ 4 (Tiết 1 - 3, Sáng)', 'Lab 402 - Nhà C'),
(2,  'LHP-WEB02', 4, 1, 2, 35, 'mo', '2026-10-01 08:00:00', '2026-11-30 23:59:59', 'Thứ 3 & Thứ 5 (Tiết 7 - 9, Chiều)', 'Lab 403 - Nhà C'),
(3,  'LHP-DB01',  5, 1, 3, 5,  'mo', NULL, NULL, 'Thứ 3 & Thứ 5 (Tiết 1 - 3, Sáng)', 'Phòng 301 - Tòa A'),
(4,  'LHP-DB02',  5, 1, 3, 45, 'mo', NULL, NULL, 'Thứ 4 & Thứ 6 (Tiết 7 - 9, Chiều)', 'Phòng 302 - Tòa A'),
(5,  'LHP-DSA01', 6, 1, 4, 6,  'mo', NULL, NULL, 'Thứ 2 & Thứ 6 (Tiết 4 - 6, Sáng)', 'Hội trường B2'),
(6,  'LHP-DSA02', 6, 1, 4, 50, 'mo', NULL, NULL, 'Thứ 3 & Thứ 5 (Tiết 10 - 12, Chiều)', 'Hội trường B3'),
(7,  'LHP-MAT01', 1, 1, 5, 60, 'mo', NULL, NULL, 'Thứ 4 & Thứ 6 (Tiết 1 - 3, Sáng)', 'Phòng 201 - Tòa B'),
(8,  'LHP-NET01', 7, 1, 6, 40, 'mo', NULL, NULL, 'Thứ 7 (Tiết 1 - 5, Sáng)', 'Lab Mạng - Tòa CNTT'),
(9,  'LHP-SE01',  8, 1, 2, 40, 'mo', NULL, NULL, 'Thứ 2 & Thứ 4 (Tiết 7 - 9, Chiều)', 'Phòng 405 - Tòa C'),
(10, 'LHP-AI01',  9, 1, 4, 35, 'khoa', NULL, NULL, 'Thứ 5 & Thứ 7 (Tiết 7 - 9, Chiều)', 'Lab AI - Tòa CNTT'),
(11, 'LHP-SEC01', 10, 1, 6, 30, 'khoa', NULL, NULL, 'Thứ 3 & Thứ 5 (Tiết 4 - 6, Sáng)', 'Phòng 502 - Tòa A'),
(12, 'LHP-W102',  3, 1, 3, 45, 'mo', NULL, NULL, 'Thứ 2 & Thứ 6 (Tiết 7 - 9, Chiều)', 'Lab 301 - Nhà C'),
(13, 'LHP-M205',  2, 2, 5, 50, 'mo', NULL, NULL, 'Thứ 3 & Thứ 5 (Tiết 1 - 3, Sáng)', 'Phòng 204 - Tòa B'),
(14, 'LHP-SE02',  8, 2, 2, 40, 'mo', NULL, NULL, 'Thứ 4 & Thứ 6 (Tiết 7 - 9, Chiều)', 'Phòng 406 - Tòa C');

-- DỮ LIỆU MẪU ĐĂNG KÝ HỌC (33 LƯỢT ĐĂNG KÝ HỢP LỆ)
INSERT INTO `dang_ky_hoc` (`id`, `sinh_vien_id`, `lop_hoc_phan_id`, `trang_thai`, `ngay_dang_ky`, `ngay_huy`) VALUES
(1,  7,  1, 'dang_ky', '2026-10-15 08:30:15', NULL),
(2,  8,  1, 'dang_ky', '2026-10-15 08:32:00', NULL),
(3,  9,  1, 'dang_ky', '2026-10-15 08:35:40', NULL),
(4,  10, 1, 'dang_ky', '2026-10-15 08:40:12', NULL),
(5,  11, 1, 'dang_ky', '2026-10-15 08:45:00', NULL),
(6,  12, 1, 'dang_ky', '2026-10-15 09:00:22', NULL),
(7,  7,  3, 'dang_ky', '2026-10-15 08:31:00', NULL),
(8,  8,  3, 'dang_ky', '2026-10-15 08:33:10', NULL),
(9,  13, 3, 'dang_ky', '2026-10-15 08:36:55', NULL),
(10, 14, 3, 'dang_ky', '2026-10-15 08:42:18', NULL),
(11, 15, 3, 'dang_ky', '2026-10-15 08:50:30', NULL),
(12, 9,  5, 'dang_ky', '2026-10-15 08:37:12', NULL),
(13, 10, 5, 'dang_ky', '2026-10-15 08:41:45', NULL),
(14, 11, 5, 'dang_ky', '2026-10-15 08:46:00', NULL),
(15, 12, 5, 'dang_ky', '2026-10-15 09:02:10', NULL),
(16, 16, 5, 'dang_ky', '2026-10-15 09:15:30', NULL),
(17, 17, 5, 'dang_ky', '2026-10-15 09:20:00', NULL),
(18, 13, 7, 'dang_ky', '2026-10-15 08:38:00', NULL),
(19, 14, 7, 'dang_ky', '2026-10-15 08:43:20', NULL),
(20, 15, 7, 'dang_ky', '2026-10-15 08:51:10', NULL),
(21, 16, 7, 'dang_ky', '2026-10-15 09:16:45', NULL),
(22, 7,  8, 'dang_ky', '2026-10-15 08:34:00', NULL),
(23, 10, 8, 'dang_ky', '2026-10-15 08:42:00', NULL),
(24, 12, 8, 'dang_ky', '2026-10-15 09:03:50', NULL),
(25, 17, 8, 'dang_ky', '2026-10-15 09:22:15', NULL),
(26, 18, 8, 'dang_ky', '2026-10-15 09:30:00', NULL),
(27, 8,  9, 'dang_ky', '2026-10-15 08:35:00', NULL),
(28, 9,  9, 'dang_ky', '2026-10-15 08:39:10', NULL),
(29, 11, 9, 'dang_ky', '2026-10-15 08:48:30', NULL),
(30, 18, 9, 'dang_ky', '2026-10-15 09:32:40', NULL),
(31, 13, 12, 'dang_ky', '2026-10-15 08:40:00', NULL),
(32, 14, 12, 'dang_ky', '2026-10-15 08:44:50', NULL),
(33, 17, 12, 'dang_ky', '2026-10-15 09:25:00', NULL),
(34, 7,  4,  'da_huy', '2026-10-10 09:10:00', '2026-10-12 14:30:15'),
(35, 8,  7,  'da_huy', '2026-10-08 08:15:00', '2026-10-09 10:20:00'),
(36, 9,  8,  'da_huy', '2026-10-11 10:00:00', '2026-10-13 16:45:00'),
(37, 11, 12, 'da_huy', '2026-10-09 14:00:00', '2026-10-11 11:15:30');

SET FOREIGN_KEY_CHECKS = 1;