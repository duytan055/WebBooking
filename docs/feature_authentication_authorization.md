# Chức năng Xác thực và Phân quyền

## Mô tả chức năng

Xây dựng hệ thống đăng nhập, đăng ký và phân quyền cho `admin`, `nhanvien`, và `nguoidung`.

## Công việc cần làm

1. Tạo xử lý đăng nhập thực tế cho `login.php`.
2. Tạo xử lý đăng ký thực tế cho `Sign-up.php`.
3. Mã hóa mật khẩu bằng `password_hash()` và kiểm tra bằng `password_verify()`.
4. Quản lý session để xác thực người dùng đã đăng nhập.
5. Phân quyền truy cập trang admin và trang bán vé.
6. Thêm kiểm tra role trước khi truy cập các trang nhạy cảm.
7. Lưu trữ login history hoặc session timeout nếu cần.

## Phân tích

- Hiện trạng: chỉ có giao diện form nhưng chưa có xử lý backend.
- Yêu cầu: cần đảm bảo xác thực an toàn và phân quyền rõ ràng.
- Bảng `adminn`, `nhanvien`, `nguoidung` chứa dữ liệu đăng nhập khác nhau.
- Cần tách quyền admin/nhân viên/khách hàng để mỗi nhóm chỉ làm được những thao tác phù hợp.

## Tiến độ hoàn thành

- [x] Thiết kế giao diện đăng nhập/đăng ký.
- [ ] Xây dựng xử lý đăng nhập với cơ sở dữ liệu.
- [ ] Xây dựng xử lý đăng ký và xác thực dữ liệu.
- [ ] Áp dụng mã hóa mật khẩu.
- [ ] Thêm phân quyền cho các trang admin và user.
