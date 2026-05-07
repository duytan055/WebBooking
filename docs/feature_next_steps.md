# Các chức năng cần làm tiếp theo cho WebBooking

## Tổng quan

Tài liệu này liệt kê các chức năng tiếp theo cần triển khai cho WebBooking và cung cấp tiến độ, phân tích và liên kết đến tài liệu chi tiết cho từng chức năng.

## 1. Xác thực và phân quyền

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_authentication_authorization.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Xây dựng xử lý đăng nhập với cơ sở dữ liệu.
- [ ] Xây dựng xử lý đăng ký và xác thực dữ liệu.
- [ ] Áp dụng mã hóa mật khẩu.
- [ ] Thêm phân quyền page admin / nhân viên.

### Phân tích

Đây là chức năng cơ bản nhất để bảo vệ hệ thống. Nếu không có phân quyền, admin và nhân viên có thể bị truy cập không kiểm soát.

## 2. Luồng đặt vé trực tiếp

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_ticket_booking_flow.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Tạo trang chi tiết phim và suất chiếu.
- [ ] Tạo giao diện chọn ghế.
- [ ] Lưu đơn đặt vé và chi tiết ghế.
- [ ] Áp dụng khuyến mãi khi thanh toán.
- [ ] Hiển thị lịch sử đặt vé cho người dùng.

### Phân tích

Đây là chức năng lõi của website đặt vé. Luồng đặt vé cần đảm bảo ghế không bị đặt trùng và tính tổng tiền chính xác.

## 3. CRUD quản lý nội dung admin

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_admin_crud.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Thêm chức năng thêm/sửa/xóa phim.
- [ ] Thêm CRUD cho khuyến mãi, phòng chiếu và ghế.
- [ ] Thêm CRUD cho nhân viên và khách hàng.
- [ ] Bảo vệ truy cập CRUD bằng phân quyền.

### Phân tích

CRUD là điều kiện cần để admin quản lý nội dung động của hệ thống. Không có CRUD, admin chỉ có thể xem dữ liệu mà không thể cập nhật.

## 4. Tìm kiếm, lọc và phân trang

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_search_filter_pagination.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Thêm lọc trạng thái phim và trạng thái vé.
- [ ] Thêm phân trang cho bảng dữ liệu.
- [ ] Chuyển truy vấn tìm kiếm sang prepared statements.

### Phân tích

Khi dữ liệu lớn, phân trang và lọc giúp duy trì hiệu suất trang và cải thiện trải nghiệm người dùng.

## 5. Báo cáo nâng cao

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_advanced_reporting.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Thêm bộ lọc theo thời gian.
- [ ] Thêm biểu đồ doanh thu và số vé.
- [ ] Báo cáo theo phim, suất chiếu và khuyến mãi.

### Phân tích

Báo cáo nâng cao cho admin cái nhìn sâu hơn về doanh thu và hiệu suất kinh doanh.

## 6. Responsive UI

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_responsive_ui.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Kiểm tra responsive trên mobile/tablet.
- [ ] Thêm breakpoint và CSS tương thích.
- [ ] Cập nhật layout form và bảng.

### Phân tích

Responsive là bắt buộc để website hoạt động tốt trên điện thoại và tablet.

## 7. Bảo mật và xử lý lỗi

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_security_error_handling.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Chuyển truy vấn sang prepared statements.
- [ ] Thêm xác thực dữ liệu đầu vào.
- [ ] Xử lý lỗi DB và hiển thị thông báo phù hợp.

### Phân tích

Không chỉ bảo vệ dữ liệu cá nhân, bảo mật còn giúp tránh các lỗ hổng như SQL Injection và XSS.

## 8. Triển khai và cài đặt

- Trạng thái: Chưa thực hiện.
- Tài liệu chi tiết: `feature_deployment.md`

### Công việc cần làm

- [x] Tạo tài liệu chức năng.
- [ ] Viết hướng dẫn cài XAMPP/Apache/MySQL.
- [ ] Hướng dẫn cấu hình `connecDB.php`.
- [ ] Ghi rõ cách tạo database và chạy trang.

### Phân tích

Hướng dẫn triển khai giúp người khác dễ thiết lập và chạy dự án trên môi trường local.

Đây là phần mở rộng cần thiết cho giai đoạn phát triển tiếp theo của dự án.
