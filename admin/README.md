# Hệ Thống Quản Trị Admin - WebBooking

## Tổng Quan

Hệ thống quản trị admin hoàn chỉnh cho ứng dụng đặt vé xem phim với đầy đủ chức năng CRUD và báo cáo doanh thu trực quan.

## Các Chức Năng Đã Hoàn Thiện

### 1. Quản Lý Phim (phim.php)

- **Xem danh sách phim**: Hiển thị tất cả phim với poster, thông tin chi tiết
- **Tìm kiếm phim**: Tìm kiếm theo tên phim
- **Thống kê**: Tổng phim, phim đang chiếu, phim sắp chiếu

#### Thêm Phim (themphim.php)

- Nhập đầy đủ thông tin: tên phim, thể loại, thời lượng, ngày khởi chiếu
- Upload poster và hình ảnh
- Chọn độ tuổi phù hợp
- Nhập link trailer và mô tả phim
- Chọn trạng thái: Đang chiếu / Sắp chiếu

#### Sửa Phim (suaphim.php)

- Cập nhật thông tin phim
- Thay đổi poster/hình ảnh (tự động xóa file cũ)
- Hiển thị ảnh hiện tại
- Validation đầy đủ

#### Xóa Phim (xoaphim.php)

- Xóa phim và các dữ liệu liên quan:
  - Diễn viên của phim
  - Đạo diễn của phim
  - Suất chiếu
- Tự động xóa file ảnh
- Xác nhận trước khi xóa

### 2. Quản Lý Nhân Viên (nhanvien.php)

#### Thêm Nhân Viên (themnhanvien.php)

- Nhập tên, số điện thoại
- Tạo mật khẩu (tự động hash bằng password_hash)
- Form đơn giản, dễ sử dụng

#### Sửa Nhân Viên (suanhanvien.php)

- Cập nhật tên và số điện thoại
- Đổi mật khẩu (tùy chọn)
- Hiển thị thông tin hiện tại

#### Xóa Nhân Viên (xoanhanvien.php)

- Xóa nhân viên khỏi hệ thống
- Xác nhận trước khi xóa

### 3. Quản Lý Khách Hàng (khachhang.php)

#### Xóa Khách Hàng (xoakhachhang.php)

- Xóa khách hàng và dữ liệu liên quan:
  - Tất cả vé đã đặt
  - Chi tiết vé
- Đảm bảo tính toàn vẹn dữ liệu
- Xác nhận trước khi xóa

### 4. Báo Cáo Doanh Thu (baocao.php)

#### Thống Kê Tổng Quan

- **Tổng doanh thu**: Hiển thị tổng tiền từ tất cả vé bán
- **Tổng vé bán**: Số lượng vé đã bán
- **Tổng khách hàng**: Số lượng người dùng đăng ký
- **Tổng phim**: Số lượng phim trong hệ thống

#### Biểu Đồ Trực Quan (Chart.js)

1. **Biểu đồ cột - Top 5 Phim Doanh Thu Cao Nhất**
   - Hiển thị doanh thu của 5 phim bán chạy nhất
   - Màu sắc phân biệt rõ ràng
   - Tooltip hiển thị số tiền chi tiết
   - Định dạng tiền tệ Việt Nam

2. **Biểu đồ tròn - Phân Bố Số Vé Bán**
   - Hiển thị tỷ lệ phần trăm vé bán của từng phim
   - Legend bên phải
   - Tooltip hiển thị số vé và phần trăm
   - Màu sắc đa dạng

#### Bảng Chi Tiết

- Danh sách đầy đủ các phim theo doanh thu
- Hiển thị: Tên phim, Số vé bán, Doanh thu
- Sắp xếp theo doanh thu giảm dần

## Cấu Trúc File

```
admin/
├── admin.css              # CSS chung cho admin
├── admin.php              # Trang chủ admin
├── sidebar.php            # Menu sidebar
├── baocao.php            # Báo cáo với biểu đồ
├── phim.php              # Danh sách phim
├── themphim.php          # Thêm phim mới
├── suaphim.php           # Sửa phim
├── xoaphim.php           # Xóa phim
├── nhanvien.php          # Danh sách nhân viên
├── themnhanvien.php      # Thêm nhân viên
├── suanhanvien.php       # Sửa nhân viên
├── xoanhanvien.php       # Xóa nhân viên
├── khachhang.php         # Danh sách khách hàng
├── xoakhachhang.php      # Xóa khách hàng
├── img/                  # Thư mục lưu ảnh phim
└── README.md             # File này
```

## Công Nghệ Sử Dụng

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Charts**: Chart.js 4.x
- **Icons**: Font Awesome 6.7.2
- **Security**:
  - mysqli_real_escape_string() để chống SQL Injection
  - password_hash() cho mật khẩu
  - htmlspecialchars() để chống XSS

## Tính Năng Bảo Mật

1. **SQL Injection Prevention**: Sử dụng mysqli_real_escape_string()
2. **Password Hashing**: Mật khẩu được hash bằng PASSWORD_DEFAULT
3. **XSS Prevention**: Sử dụng htmlspecialchars() khi hiển thị dữ liệu
4. **File Upload Security**: Kiểm tra extension, tạo tên file unique
5. **Confirmation Dialogs**: Xác nhận trước khi xóa dữ liệu quan trọng

## Hướng Dẫn Sử Dụng

### Thêm Phim Mới

1. Truy cập "Quản lý phim"
2. Click nút "+ Thêm phim"
3. Điền đầy đủ thông tin
4. Upload poster và hình ảnh (tùy chọn)
5. Click "Lưu phim"

### Sửa Phim

1. Trong danh sách phim, click nút "Sửa"
2. Cập nhật thông tin cần thiết
3. Upload ảnh mới nếu muốn thay đổi
4. Click "Cập nhật"

### Xóa Phim

1. Trong danh sách phim, click nút "Xóa"
2. Xác nhận trong hộp thoại
3. Phim và dữ liệu liên quan sẽ bị xóa

### Quản Lý Nhân Viên

- Tương tự như quản lý phim
- Mật khẩu được mã hóa tự động
- Có thể đổi mật khẩu khi sửa (để trống nếu không đổi)

### Xem Báo Cáo

1. Truy cập "Báo cáo"
2. Xem thống kê tổng quan ở phần card
3. Xem biểu đồ trực quan
4. Xem bảng chi tiết bên dưới

## Yêu Cầu Hệ Thống

- PHP 7.4 trở lên
- MySQL 5.7 trở lên / MariaDB 10.2 trở lên
- Apache/Nginx web server
- Extension PHP: mysqli, gd (cho xử lý ảnh)
- Quyền ghi vào thư mục admin/img/

## Lưu Ý

1. **Thư mục img**: Đảm bảo thư mục `admin/img/` có quyền ghi (chmod 755 hoặc 777)
2. **Upload Size**: Kiểm tra `upload_max_filesize` và `post_max_size` trong php.ini
3. **Database**: Đảm bảo các bảng có đúng cấu trúc và foreign key
4. **Backup**: Nên backup database trước khi xóa dữ liệu quan trọng

## Tính Năng Nổi Bật

✅ Giao diện thân thiện, dễ sử dụng
✅ Responsive design
✅ Biểu đồ trực quan với Chart.js
✅ Upload và quản lý ảnh tự động
✅ Xóa cascade đảm bảo tính toàn vẹn dữ liệu
✅ Validation đầy đủ
✅ Thông báo rõ ràng cho người dùng
✅ Bảo mật tốt

## Hỗ Trợ

Nếu gặp vấn đề, kiểm tra:

1. Kết nối database trong `Connect/connecDB.php`
2. Quyền thư mục `admin/img/`
3. PHP error log
4. Browser console (F12) cho lỗi JavaScript

---

**Phát triển bởi**: WebBooking Team
**Ngày cập nhật**: 19/05/2026
**Phiên bản**: 1.0.0
