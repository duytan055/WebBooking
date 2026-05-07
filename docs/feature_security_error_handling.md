# Chức năng Bảo mật và Xử lý lỗi

## Mô tả chức năng

Tăng cường bảo mật và xử lý lỗi cho toàn bộ hệ thống, tránh SQL Injection, XSS và các lỗi runtime.

## Công việc cần làm

1. Chuyển các truy vấn SQL sang prepared statements hoặc PDO.
2. Kiểm tra và lọc dữ liệu đầu vào từ form.
3. Mã hóa mật khẩu và không lưu mật khẩu thô.
4. Thêm xử lý lỗi kết nối cơ sở dữ liệu.
5. Hiển thị thông báo thân thiện khi xảy ra lỗi.
6. Bảo vệ dữ liệu nhạy cảm như session và cookie.

## Phân tích

- Hệ thống hiện tại có nhiều truy vấn trực tiếp nối chuỗi SQL.
- Đây là lỗ hổng bảo mật nghiêm trọng nếu đầu vào không được kiểm tra.
- Xử lý lỗi tốt giúp người dùng hiểu vấn đề và admin dễ debug.

## Tiến độ hoàn thành

- [ ] Chuyển truy vấn sang prepared statements.
- [ ] Thêm xác thực dữ liệu đầu vào cho form.
- [ ] Mã hóa mật khẩu.
- [ ] Thêm xử lý lỗi kết nối DB và thông báo người dùng.
