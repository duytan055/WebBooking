# Chức năng Quản lý Khách hàng

## Mô tả chức năng

Trang quản lý khách hàng cho phép quản trị viên duyệt và tìm kiếm danh sách người dùng đăng ký.

## Các công việc cần làm

1. Kết nối tới cơ sở dữ liệu.
2. Lấy danh sách người dùng từ bảng `nguoidung`.
3. Hỗ trợ tìm kiếm theo tên người dùng (`ten`).
4. Hiển thị tổng số khách hàng.
5. Cung cấp nút thêm khách hàng mới (`themkhachhang.php`).
6. Hiển thị danh sách khách hàng trong bảng.

## Phân tích chi tiết

- File `admin/khachhang.php` lấy dữ liệu từ bảng `nguoidung` và lọc theo từ khóa tìm kiếm.
- Không có xử lý xóa hoặc sửa trong file hiện tại, chỉ có tính năng hiển thị và chuyển hướng sang trang thêm khách hàng.
- Giao diện và layout được kế thừa từ `admin/admin.css`.

## Tiến độ hoàn thành

- [x] Hiển thị danh sách khách hàng.
- [x] Thêm tìm kiếm theo tên.
- [x] Hiển thị tổng số khách hàng.
- [ ] Thêm chức năng sửa/xóa khách hàng.
- [ ] Cập nhật bảo mật truy vấn để tránh SQL Injection.
