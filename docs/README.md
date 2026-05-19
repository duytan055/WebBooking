# WebBooking Project Documentation

Tài liệu này tổng hợp các chức năng chính của dự án WebBooking, phân tích từng phần, mô tả công việc cần làm và đánh dấu tiến độ hoàn thành.

## Cấu trúc tài liệu

### Tổng quan dự án

- `project_overview.md`: Tổng quan dự án, các trang chính, chức năng và tiến độ chung.
- `feature_next_steps.md`: Các chức năng tiếp theo và phân tích công việc.

### Trang công khai và trải nghiệm người dùng

- `feature_public_site.md`: Trang chủ, hiển thị phim và khuyến mãi.
- `feature_ticket_booking_flow.md`: Luồng đặt vé.
- `feature_responsive_ui.md`: Giao diện desktop và trải nghiệm người dùng trên màn hình lớn.
- `feature_authentication.md`: Đăng nhập và đăng ký người dùng.
- `feature_authentication_authorization.md`: Xác thực và phân quyền.

### Quản trị và báo cáo

- `feature_admin_dashboard.md`: Tổng quan và quản trị dữ liệu bán vé.
- `feature_movie_management.md`: Quản lý phim và trạng thái phim.
- `feature_customer_management.md`: Quản lý khách hàng.
- `feature_staff_management.md`: Quản lý nhân viên.
- `feature_admin_crud.md`: CRUD quản lý nội dung admin.
- `feature_reports.md`: Báo cáo doanh thu và thống kê.
- `feature_advanced_reporting.md`: Báo cáo nâng cao.

### Dữ liệu và bảo mật

- `feature_database.md`: Mô tả cấu trúc dữ liệu và liên kết bảng.
- `feature_database_relationships.md`: Mô tả quan hệ bảng và sơ đồ ER.
- `feature_search_filter_pagination.md`: Tìm kiếm, lọc và phân trang.
- `feature_security_error_handling.md`: Bảo mật và xử lý lỗi.
- `feature_deployment.md`: Triển khai và cài đặt.

## Tiến độ cập nhật (19/05/2026)

- Phần lớn giao diện trang công khai đã hiện diện: trang chủ, phim đang chiếu, phim sắp chiếu, khuyến mãi, liên hệ và phần profile người dùng.
- Đăng nhập và đăng ký đã có backend cơ bản cho user và admin, nhưng mật khẩu chưa được mã hóa và cần cải thiện bảo mật.
- Trang đặt vé `Pages/buyticket.php` tồn tại và có UI luồng chọn ghế / combo / thanh toán, nhưng cần xác nhận hoàn thiện backend xử lý đặt vé và lưu dữ liệu vé.
- Khu vực admin đã có dashboard, bảng thống kê và màn hình quản lý phim/khách hàng/nhân viên/ báo cáo.
- CRUD admin là một phần đã có, nhưng vẫn cần hoàn thiện thêm giao diện và chức năng thêm/sửa/xóa trên một số màn hình.

## Mục tiêu tài liệu

Tài liệu này được tổ chức để:

- Hiển thị tình trạng hiện tại của các phần chính.
- Ghi lại những việc đã hoàn thành và các điểm cần cải tiến.
- Hướng dẫn bước tiếp theo để hoàn thiện dự án.
