# Chức năng Admin Dashboard

## Mô tả chức năng

Admin Dashboard hiển thị số liệu tổng quan và lịch sử giao dịch vé. Đây là trang chính dành cho quản trị viên.

## Các công việc cần làm

1. Kết nối tới cơ sở dữ liệu (`Connect/connecDB.php`).
2. Truy vấn tổng số vé đã bán từ bảng `chitietve`.
3. Truy vấn doanh thu tổng từ bảng `datve`.
4. Truy vấn tổng số khách hàng từ bảng `nguoidung`.
5. Truy vấn lịch sử đặt vé chi tiết, bao gồm thông tin phim, suất chiếu, ghế, nhân viên xử lý, trạng thái và tổng tiền.
6. Hiển thị bảng giao dịch vé và các thẻ thống kê.
7. Cập nhật giao diện trong `admin/admin.css` để trình bày dashboard.

## Phân tích chi tiết

- Trang `admin/admin.php` thực hiện 4 truy vấn dữ liệu tổng quan.
- Dữ liệu lịch sử đặt vé lấy từ nhiều bảng liên quan: `datve`, `suatchieu`, `phim`, `chitietve`, `ghe`, `nhanvien`.
- Có phần điều kiện trạng thái vé: `Đã in vé` hoặc `Chờ thanh toán`.
- Giao diện sử dụng thẻ màu cho từng loại dữ liệu.

## Tiến độ hoàn thành

- [x] Kết nối database.
- [x] Truy vấn số vé, doanh thu, khách hàng.
- [x] Truy vấn lịch sử vé và hiển thị bảng.
- [ ] Thêm bộ lọc tìm kiếm nâng cao (hiện chỉ có input tìm kiếm nhưng chưa xử lý đầy đủ).
- [ ] Thêm phân trang nếu số lượng giao dịch lớn.
