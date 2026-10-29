# BÀI TẬP CÁ NHÂN BUỔI 5: QUẢN LÝ DANH SÁCH LỚP HỌC PHẦN (QUẢN TRỊ VIÊN)

- **Họ và tên:** Nguyễn Thị Thu Huyền
- **Chức năng thực hiện:** Quản lý danh sách lớp học phần đào tạo (CRUD thêm/sửa/xóa, cầu dao mở/khóa lớp, lọc đa tiêu chí, xuất danh sách PDF).
- **Công nghệ:** PHP 8.x, MySQL, PDO Prepared Statements, bảo mật CSRF Token.


## 5 TEST CASE KIỂM THỬ NHẬP DỮ LIỆU ĐÚNG / SAI

| STT | Tình huống kiểm thử (Test Case) | Dữ liệu đầu vào (Input) | Kết quả mong đợi | Phân loại |
|---|---|---|---|---|
| 1 | Thêm mới lớp học phần hợp lệ | Mã lớp: `LHP-WEB03`, Môn: `WEB102`, Học kỳ 1, Sĩ số: `45`, Lịch: `Thứ 2 (Tiết 1-3)`, Phòng: `Lab 401` | Lưu vào CSDL thành công, hiển thị thông báo *"Tạo mới lớp học phần 'LHP-WEB03' thành công!"* | **Đúng (Valid)** |
| 2 | Nhập trùng Mã lớp đã tồn tại | Mã lớp: `LHP-SE02` (đã có trong hệ thống) | Hệ thống chặn lưu và báo lỗi: *"Mã lớp 'LHP-SE02' đã tồn tại trên hệ thống. Vui lòng chọn mã khác."* | **Sai (Invalid)** |
| 3 | Nhập Mã lớp sai định dạng ký tự | Mã lớp: `LHP@#123` (chứa ký tự đặc biệt) | Hệ thống chặn lưu và báo lỗi: *"Mã lớp phải có 2-30 ký tự gồm chữ cái in hoa, số hoặc gạch nối."* | **Sai (Invalid)** |
| 4 | Giảm sĩ số tối đa xuống thấp hơn số SV đã đăng ký | Sửa lớp `LHP-WEB01` (đang có 6 SV đăng ký), nhập Sĩ số tối đa: `4` | Hệ thống chặn cập nhật và báo lỗi: *"Sĩ số tối đa (4) không được nhỏ hơn số sinh viên hiện đang đăng ký (6 sinh viên)."* | **Sai (Invalid)** |
| 5 | Xóa lớp học phần đã có sinh viên đăng ký | Bấm nút **Xóa** ở lớp `LHP-W102` (đang có 3 SV đăng ký) | Hệ thống bảo vệ dữ liệu và báo lỗi: *"KHÔNG THỂ XÓA lớp 'LHP-W102' vì hiện đã có 3 sinh viên đăng ký trên hệ thống."* | **Sai (Invalid)** |