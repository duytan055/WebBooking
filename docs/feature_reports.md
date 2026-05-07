# Chức năng Báo cáo

## Mô tả chức năng

Trang báo cáo cung cấp tổng quan hệ thống với các số liệu như doanh thu, tổng vé, tổng phim và tổng khách hàng cùng bảng xếp hạng phim.

## Các công việc cần làm

1. Kết nối tới cơ sở dữ liệu.
2. Tính tổng doanh thu từ bảng `datve`.
3. Tính tổng vé đã bán từ bảng `chitietve`.
4. Đếm tổng phim từ bảng `phim`.
5. Đếm tổng khách hàng từ bảng `nguoidung`.
6. Tạo báo cáo top phim theo doanh thu và số lượng vé.
7. Hiển thị giao diện báo cáo trong `admin/baocao.php`.

## Phân tích chi tiết

- File `admin/baocao.php` kết hợp nhiều truy vấn thống kê và hiển thị số liệu tổng hợp.
- Top phim được xác định bằng tổng doanh thu từ liên kết `datve`, `chitietve`, `suatchieu` và `phim`.
- Giao diện tương thích với layout admin chung.

## Tiến độ hoàn thành

- [x] Truy vấn tổng doanh thu.
- [x] Truy vấn tổng vé và tổng phim.
- [x] Truy vấn tổng khách hàng.
- [x] Truy vấn top phim và hiển thị trong bảng.
- [ ] Thêm biểu đồ trực quan cho báo cáo.
- [ ] Hỗ trợ lọc theo thời gian hoặc phim cụ thể.
