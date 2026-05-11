# Chức năng Quản lý Nhân viên

## Mô tả chức năng

Trang quản lý nhân viên cho phép quản trị viên xem danh sách nhân viên và tìm kiếm theo tên.

## Các công việc cần làm

1. Kết nối tới cơ sở dữ liệu.
2. Lấy danh sách nhân viên từ bảng `nhanvien`.
3. Hỗ trợ tìm kiếm theo trường `ten`.
4. Hiển thị tổng số nhân viên.
5. Cung cấp liên kết đến trang thêm nhân viên (`themnhanvien.php`).
6. Hiển thị dữ liệu trong bảng.

## Phân tích chi tiết

- File `admin/nhanvien.php` tương tự `khachhang.php` nhưng truy vấn trên bảng `nhanvien`.
- Hiện tại chỉ có tìm kiếm và hiển thị tổng số nhân viên.
- Chưa có xử lý CRUD chi tiết trong file hiện tại.

## Tiến độ hoàn thành

- [x] Lấy và hiển thị danh sách nhân viên.
- [x] Thêm tìm kiếm theo tên.
- [x] Hiển thị tổng số nhân viên.
- [ ] Thêm chức năng sửa/xóa nhân viên.
- [ ] Cải thiện bảo mật và xác thực đầu vào tìm kiếm.
