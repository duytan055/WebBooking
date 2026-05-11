# Chức năng Đăng nhập và Đăng ký

## Mô tả chức năng

Cung cấp giao diện đăng nhập và đăng ký cho người dùng, giúp họ truy cập vào hệ thống và tạo tài khoản.

## Các công việc cần làm

1. Tạo trang đăng nhập `LoginAndSign-up/login.php`.
2. Tạo trang đăng ký `LoginAndSign-up/Sign-up.php`.
3. Thiết kế giao diện form với CSS từ `LoginAndSign-up/style.css`.
4. Xử lý đăng nhập bằng tài khoản và mật khẩu.
5. Xử lý đăng ký với xác thực dữ liệu.
6. Quản lý phiên đăng nhập bằng `session`.
7. Thêm trang xử lý đăng xuất `admin/dangxuat.php`.

## Phân tích chi tiết

- `login.php` và `Sign-up.php` hiện chỉ chứa form HTML tĩnh.
- `dangxuat.php` dùng `session_unset()` và `session_destroy()` để đăng xuất.
- Hiện chưa có xử lý thực tế cho đăng nhập/đăng ký hoặc kết nối với bảng người dùng.

## Tiến độ hoàn thành

- [x] Xây dựng giao diện đăng nhập.
- [x] Xây dựng giao diện đăng ký.
- [x] Xây dựng trang đăng xuất.
- [ ] Thêm xử lý form đăng nhập/đăng ký với cơ sở dữ liệu.
- [ ] Thêm kiểm tra bảo mật, xác thực đầu vào và mã hóa mật khẩu.
- [ ] Điều hướng người dùng sau khi đăng nhập thành công.
