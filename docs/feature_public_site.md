# Chức năng Trang công khai

## Mô tả chức năng

Trang công khai bao gồm trang chủ, hiển thị thanh điều hướng, slider phim, danh sách phim và khuyến mãi cùng trang liên hệ.

## Các công việc cần làm

1. Xây dựng header chung trong `Modun/header.php`.
2. Xây dựng footer chung trong `Modun/footer.php`.
3. Tạo slider phim tự động trong `Modun/SliderMovies.php`.
4. Hiển thị danh sách phim trong `Modun/movies.php`.
5. Hiển thị khuyến mãi và sự kiện trong `Modun/PromotionsAndEvents.php`.
6. Tạo trang chủ `Pages/trangChu.php` tích hợp header, slider, phim, khuyến mãi và footer.
7. Tạo trang danh sách phim hiện chiếu `Pages/NowMovies.php` và phim sắp chiếu `Pages/SoonMovies.php`.
8. Tạo trang liên hệ `Pages/trangLienHe.php` với form gửi thông tin.

## Phân tích chi tiết

- `header.php` chứa menu điều hướng, liên kết tới các trang chính và nút đăng nhập/đăng ký.
- `SliderMovies.php` lấy dữ liệu hình ảnh phim từ bảng `phim` và chuyển ảnh tự động.
- `movies.php` lấy toàn bộ phim từ bảng `phim` và hiển thị dạng slider tương tác.
- `PromotionsAndEvents.php` lấy khuyến mãi từ bảng `khuyenmai` và hiển thị ảnh event.
- `trangLienHe.php` là trang liên hệ với form gửi đến Google Apps Script.

## Tiến độ hoàn thành

- [x] Xây dựng header và footer chung.
- [x] Tạo slider tự động cho phim.
- [x] Hiển thị danh sách phim.
- [x] Hiển thị khuyến mãi và sự kiện.
- [x] Tạo trang chủ và trang phim.
- [x] Tạo trang liên hệ.
- [ ] Hoàn thiện responsive cho mobile.
- [ ] Thêm tính năng đặt vé trực tiếp từ trang công khai.
