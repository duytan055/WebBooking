# Tổng quan dự án WebBooking

## Mục tiêu dự án

WebBooking là một hệ thống đặt vé xem phim trực tuyến, bao gồm:

- Trang công khai cho người dùng xem phim, khuyến mãi và liên hệ.
- Khu vực quản trị để quản lý phim, khách hàng, nhân viên, và báo cáo doanh thu.
- Cơ sở dữ liệu lưu trữ thông tin phim, suất chiếu, ghế, đặt vé, khuyến mãi và người dùng.

## Các trang chính

### Trang công khai

- `Pages/trangChu.php`: Trang chủ hiển thị slider phim, danh sách phim và khuyến mãi.
- `Pages/NowMovies.php`: Danh sách phim đang chiếu cùng thông tin đạo diễn và diễn viên.
- `Pages/SoonMovies.php`: Danh sách phim sắp chiếu.
- `Pages/trangLienHe.php`: Trang liên hệ với form gửi thông tin.

### Trang xác thực

- `LoginAndSign-up/login.php`: Giao diện đăng nhập.
- `LoginAndSign-up/Sign-up.php`: Giao diện đăng ký.
- `admin/dangxuat.php`: Xử lý đăng xuất.

### Trang admin

- `admin/admin.php`: Dashboard tổng quan doanh thu, vé đã bán và khách hàng.
- `admin/phim.php`: Quản lý phim và thống kê phim đang chiếu/sắp chiếu.
- `admin/khachhang.php`: Quản lý khách hàng.
- `admin/nhanvien.php`: Quản lý nhân viên.
- `admin/baocao.php`: Báo cáo doanh thu và top phim.

### Module dùng chung

- `Modun/header.php`: Header điều hướng chung.
- `Modun/footer.php`: Footer chung.
- `Modun/SliderMovies.php`: Slider ảnh phim trên trang chủ.
- `Modun/movies.php`: Hiển thị danh sách phim dạng slider.
- `Modun/PromotionsAndEvents.php`: Hiển thị khuyến mãi và sự kiện.

## Chức năng chính hiện có

### Public site

- Hiển thị phim hiện chiếu và sắp chiếu.
- Hiển thị khuyến mãi và sự kiện.
- Thông tin liên hệ và form gửi tin.
- Giao diện header/footer chung.

### Authentication

- Có form đăng nhập và đăng ký.
- Có trang đăng xuất.
- Hiện chưa có xử lý đăng nhập/đăng ký backend thực tế.

### Quản trị

- Dashboard hiển thị tổng vé, doanh thu, khách hàng và lịch sử giao dịch.
- Quản lý phim với tìm kiếm và thống kê.
- Quản lý khách hàng.
- Quản lý nhân viên.
- Báo cáo hệ thống và top phim.

## Cơ sở dữ liệu và cấu trúc

- Kết nối database qua `Connect/connecDB.php`.
- Bảng chính: `phim`, `suatchieu`, `datve`, `chitietve`, `ghe`, `nguoidung`, `nhanvien`, `khuyenmai`, `phim_dienvien`, `phim_daodien`, `phongchieu`, `dotuoi`, `daodien`, `dienvien`, `adminn`.
- Schema lưu trữ thông tin đặt vé, ghế, phim, suất chiếu và quan hệ người dùng.

## Tiến độ chung theo chức năng

| Chức năng                 | Tình trạng          | Ghi chú                                               |
| ------------------------- | ------------------- | ----------------------------------------------------- |
| Giao diện trang công khai | Hoàn thành cơ bản   | Trang chủ, phim, khuyến mãi, liên hệ có giao diện.    |
| Xác thực người dùng       | Chưa hoàn thành     | Chỉ có form, chưa có backend xử lý.                   |
| Dashboard admin           | Hoàn thành cơ bản   | Hiển thị số liệu tổng quan và lịch sử giao dịch.      |
| Quản lý phim              | Hoàn thành hiển thị | Chưa có CRUD thêm/sửa/xóa đầy đủ.                     |
| Quản lý khách hàng        | Hoàn thành hiển thị | Chưa có CRUD chi tiết.                                |
| Quản lý nhân viên         | Hoàn thành hiển thị | Chưa có CRUD chi tiết.                                |
| Báo cáo                   | Hoàn thành cơ bản   | Chưa có báo cáo theo thời gian hoặc biểu đồ nâng cao. |
| Đặt vé trực tuyến         | Chưa hoàn thành     | Chưa có page đặt vé cụ thể.                           |
| Bảo mật                   | Chưa hoàn thành     | Cần prepared statements và mã hóa mật khẩu.           |
| Triển khai                | Chưa có             | Cần hướng dẫn cài đặt và tạo database.                |

## Đánh giá tổng thể

Hiện tại dự án đã có nền tảng khá tốt cho một website đặt vé:

- Phần admin đã có dashboard và các màn hình quản lý cơ bản.
- Phần công khai đã có trang xem phim, khuyến mãi và liên hệ.

Tuy nhiên, còn cần hoàn thiện các chức năng backend, CRUD chi tiết, bảo mật, đặt vé thực tế và tài liệu triển khai.

## Đề xuất bước tiếp theo

1. Hoàn thiện xác thực và phân quyền.
2. Xây dựng luồng đặt vé trực tiếp.
3. Mở rộng CRUD quản trị.
4. Tối ưu bảo mật và xử lý lỗi.
5. Thêm hướng dẫn triển khai dự án.
