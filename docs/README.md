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

## Tiến độ cập nhật (02/06/2026)

- Giao diện công khai cơ bản đã có: trang chủ, phim đang chiếu, phim sắp chiếu, khuyến mãi, liên hệ và profile người dùng.
- Đăng nhập và đăng ký hoạt động với backend cơ bản cho user và admin. Mật khẩu hiện đang so sánh thẳng text và cần nâng cấp bảo mật.
- Trang đặt vé `Pages/buyticket.php` đã có luồng UI chọn ghế, chọn suất chiếu và thanh toán, nhưng cần kiểm tra hoàn thiện phần lưu vé sang database.
- Dashboard admin cùng các màn hình quản lý phim, khách hàng, nhân viên và báo cáo đã tồn tại.
- Chức năng CRUD admin cơ bản đã hiện diện, nhưng vẫn cần hoàn thiện hành vi thêm, sửa và xóa trong một số trang.

## Mục tiêu tài liệu

Tài liệu này được tổ chức để:

- Hiển thị tình trạng hiện tại của các phần chính.
- Ghi lại những việc đã hoàn thành và các điểm cần cải tiến.
- Hướng dẫn bước tiếp theo để hoàn thiện dự án.
