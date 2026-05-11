# Chức năng Quản lý Phim

## Mô tả chức năng

Trang quản lý phim cho phép quản trị viên xem danh sách phim, tìm kiếm theo tên và xem các thống kê cơ bản.

## Các công việc cần làm

1. Kết nối tới cơ sở dữ liệu.
2. Lấy danh sách phim từ bảng `phim`.
3. Cho phép tìm kiếm phim theo trường `ten_phim`.
4. Hiển thị số lượng phim hiện có.
5. Thống kê số phim đang chiếu và sắp chiếu.
6. Hiển thị danh sách phim dưới dạng bảng.
7. Thiết kế giao diện `admin/admin.css` để phù hợp với trang quản lý phim.

## Phân tích chi tiết

- File `admin/phim.php` thực hiện tìm kiếm bằng `LIKE` trên trường `ten_phim`.
- Hiển thị 3 thống kê: tổng phim, số phim đang chiếu, số phim sắp chiếu.
- Nội dung hiển thị được viết tương thích với cùng bộ giao diện admin.

## Tiến độ hoàn thành

- [x] Lấy toàn bộ phim.
- [x] Thêm chức năng tìm kiếm cơ bản.
- [x] Hiển thị số phim, đang chiếu và sắp chiếu.
- [ ] Thêm thao tác CRUD cho phim (thêm, sửa, xóa) nếu cần.
- [ ] Xác thực dữ liệu khi thêm hoặc sửa phim.
