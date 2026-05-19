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
- `Pages/buyticket.php`: Trang đặt vé với UI chọn ghế, combo và thanh toán.

### Trang xác thực

- `LoginAndSign-up/login.php`: Giao diện đăng nhập và xử lý xác thực admin/user.
- `LoginAndSign-up/Sign-up.php`: Giao diện đăng ký và thêm người dùng vào bảng `nguoidung`.
- `LoginAndSign-up/logout.php`: Xử lý đăng xuất.

### Trang admin

- `admin/admin.php`: Dashboard tổng quan doanh thu, vé đã bán và khách hàng.
- `admin/phim.php`: Quản lý phim, tìm kiếm và thống kê phim đang chiếu/sắp chiếu.
- `admin/khachhang.php`: Quản lý khách hàng và tìm kiếm.
- `admin/nhanvien.php`: Quản lý nhân viên với tìm kiếm, thêm, sửa, xóa.
- `admin/baocao.php`: Báo cáo tổng hợp doanh thu, số vé và top phim.

### Module dùng chung

- `Module/header.php`: Header điều hướng chung.
- `Module/footer.php`: Footer chung.
- `Module/SliderMovies.php`: Slider ảnh phim trên trang chủ.
- `Module/movies.php`: Hiển thị danh sách phim dạng slider.
- `Module/PromotionsAndEvents.php`: Hiển thị khuyến mãi và sự kiện.

## Chức năng chính hiện có

### Public site

- Hiển thị phim hiện chiếu và sắp chiếu.
- Hiển thị khuyến mãi và sự kiện.
- Thông tin liên hệ và form gửi tin.
- Giao diện header/footer chung.
- Trang đặt vé có UI luồng ghế, combo và thanh toán.

### Authentication

- Có form đăng nhập, đăng ký và đăng xuất.
- Backend cơ bản có xác thực admin và user.
- Mật khẩu hiện tại lưu thẳng text, cần cải thiện mã hóa và bảo mật.

### Quản trị

- Dashboard hiển thị tổng vé, doanh thu và khách hàng.
- Quản lý phim với bảng, tìm kiếm và các liên kết CRUD.
- Quản lý khách hàng với tìm kiếm và xóa.
- Quản lý nhân viên với tìm kiếm, thêm, sửa và xóa.
- Báo cáo hệ thống và top phim.

## Cơ sở dữ liệu và cấu trúc

- Kết nối database qua `Connect/connecDB.php`.
- Bảng chính: `phim`, `suatchieu`, `datve`, `chitietve`, `ghe`, `nguoidung`, `nhanvien`, `khuyenmai`, `phim_dienvien`, `phim_daodien`, `phongchieu`, `dotuoi`, `daodien`, `dienvien`, `adminn`.
- Schema lưu trữ thông tin đặt vé, ghế, phim, suất chiếu và quan hệ người dùng.

## Tiến độ chung theo chức năng

| Chức năng                 | Tình trạng          | Ghi chú                                                                |
| ------------------------- | ------------------- | ---------------------------------------------------------------------- |
| Giao diện trang công khai | Hoàn thành cơ bản   | Trang chủ, phim, khuyến mãi, liên hệ, profile người dùng có giao diện. |
| Xác thực người dùng       | Hoàn thành cơ bản   | Form đăng nhập/đăng ký hoạt động, xác thực bằng user/admin đã có.      |
| Quản lý phim              | Hoàn thành hiển thị | Có bảng danh sách, tìm kiếm, liên kết thêm phim.                       |
| Quản lý khách hàng        | Hoàn thành hiển thị | Có bảng danh sách, tìm kiếm, xóa khách hàng.                           |
| Quản lý nhân viên         | Hoàn thành hiển thị | Có bảng danh sách, tìm kiếm, thêm/sửa/xóa nhân viên.                   |
| Báo cáo                   | Hoàn thành cơ bản   | Báo cáo tổng hợp đã có, vẫn cần biểu đồ và lọc theo thời gian.         |
| Đặt vé trực tuyến         | Tiền triển khai     | UI luồng đặt vé có sẵn, backend lưu vé cần kiểm tra hoàn thiện.        |
| Bảo mật                   | Cần cải thiện       | Sử dụng prepared statements ở nhiều nơi, cần mã hóa mật khẩu, CSRF.    |
| Triển khai                | Chưa hoàn thành     | Cần hướng dẫn cài đặt, tạo database và cấu hình môi trường.            |

## Đánh giá tổng thể

Dự án đã có nền tảng vững cho website đặt vé với nhiều màn hình front-end và admin.

- Phần admin cơ bản đã hoàn thành các màn hình quản lý và báo cáo.
- Phần công khai đã có hầu hết nội dung chính và luồng đặt vé frontend.
- Phần bảo mật và quá trình đặt vé backend cần tiếp tục hoàn thiện.

## Đề xuất bước tiếp theo

1. Hoàn thiện bảo mật đăng nhập/đăng ký và mã hóa mật khẩu.
2. Kiểm tra và hoàn thiện backend đặt vé `Pages/buyticket.php`.
3. Hoàn thiện CRUD admin còn thiếu và xác nhận các form thêm/sửa/xóa.
4. Thêm phần hướng dẫn triển khai cài đặt database và cấu hình XAMPP.
5. Nâng cấp báo cáo admin với biểu đồ và lọc theo thời gian.
