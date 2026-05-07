# Chức năng Luồng Đặt vé

## Mô tả chức năng

Xây dựng trải nghiệm chọn phim, suất chiếu, ghế và thanh toán đặt vé trực tiếp trên website.

## Công việc cần làm

1. Tạo trang chi tiết phim với thông tin, trailer, và suất chiếu.
2. Hiển thị danh sách ghế theo phòng chiếu và trạng thái đã đặt/chưa đặt.
3. Cho phép người dùng chọn ghế, áp dụng khuyến mãi và xem tổng tiền.
4. Lưu đơn đặt vé vào `datve` và chi tiết ghế vào `chitietve`.
5. Cập nhật trạng thái ghế để tránh đặt trùng.
6. Tích hợp phương thức thanh toán cơ bản hoặc mô phỏng.
7. Hiển thị lịch sử đặt vé cho người dùng.

## Phân tích

- Đây là chức năng lõi của trang web đặt vé.
- Luồng cần đảm bảo dữ liệu ghế chính xác và tránh trùng chỗ.
- Sử dụng các bảng `phim`, `suatchieu`, `ghe`, `datve`, `chitietve`, `khuyenmai`.
- Cần xử lý hợp lệ khi khách hàng chọn nhiều ghế và tính đúng `tong_tien`.

## Tiến độ hoàn thành

- [ ] Tạo trang chi tiết phim và suất chiếu.
- [ ] Tạo giao diện chọn ghế.
- [ ] Lưu đơn đặt vé và chi tiết ghế.
- [ ] Áp dụng khuyến mãi khi thanh toán.
- [ ] Hiển thị lịch sử đặt vé cho người dùng.
