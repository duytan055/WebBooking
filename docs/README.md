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
<img width="1891" height="910" alt="Ảnh chụp màn hình 2026-08-31 231833" src="https://github.com/user-attachments/assets/1443cf9f-eee5-4d97-ace9-7a2107a3347c" />
<img width="1868" height="877" alt="image7" src="https://github.com/user-attachments/assets/596b917b-608a-407f-8864-a3bcf80351eb" />
<img width="1551" height="887" alt="image6" src="https://github.com/user-attachments/assets/85826907-6496-40fd-9d0a-b6a34bf0c795" />
<img width="1517" height="870" alt="image5" src="https://github.com/user-attachments/assets/c8ca8244-1e4b-49e1-b992-a282798b7ebe" />
<img width="1862" height="892" alt="image4" src="https://github.com/user-attachments/assets/5ff8c174-6f3c-4b73-874b-151886859890" />
<img width="1907" height="885" alt="image3" src="https://github.com/user-attachments/assets/d63114bd-bc27-4af3-ab5d-fa724042575e" />
<img width="1861" height="881" alt="image2" src="https://github.com/user-attachments/assets/eb968dd9-93fb-4879-bf07-1cbcf54a4243" />
<img width="1857" height="893" alt="image1" src="https://github.com/user-attachments/assets/7e41dd6c-a573-41b2-b3e2-c91462e672f8" />
<img width="1836" height="891" alt="image15" src="https://github.com/user-attachments/assets/70ae7183-dd8d-4cb4-9ad3-fa5b39e23651" />
<img width="1873" height="892" alt="image14" src="https://github.com/user-attachments/assets/6d417485-daa8-4f3b-bb7a-7c92c3e1897b" />
<img width="1861" height="897" alt="image13" src="https://github.com/user-attachments/assets/a857feb2-c55d-4c68-837d-c72195165287" />
<img width="1861" height="882" alt="image12" src="https://github.com/user-attachments/assets/276144df-e3d8-4ead-8ef0-0c37ecf9e350" />
<img width="1853" height="873" alt="image11" src="https://github.com/user-attachments/assets/d65caac0-7fe7-48ce-bd16-d2443c743a96" />
<img width="1773" height="886" alt="image10" src="https://github.com/user-attachments/assets/2b62714f-1b14-4644-a1e4-fb84c9df70c9" />
<img width="1858" height="902" alt="image9" src="https://github.com/user-attachments/assets/a78bb62c-9ed3-49da-8eca-4f275aec8ee2" />
<img width="1856" height="892" alt="image8" src="https://github.com/user-attachments/assets/36160dec-2ea0-40cf-874c-3217f334ef2c" />

