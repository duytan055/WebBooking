# Chức năng Tìm kiếm, Lọc và Phân trang

## Mô tả chức năng

Cải thiện khả năng tìm kiếm, lọc kết quả và phân trang cho các danh sách phim, khách hàng, nhân viên và giao dịch vé.

## Công việc cần làm

1. Thêm lọc theo trạng thái phim (`Đang chiếu`, `Sắp chiếu`).
2. Thêm lọc theo loại phim, ngày chiếu, và trạng thái vé.
3. Thêm phân trang cho danh sách dài.
4. Cải thiện truy vấn bằng prepared statements để tránh SQL Injection.
5. Thêm UI bộ lọc rõ ràng và trạng thái tìm kiếm.
6. Cho phép tìm kiếm nâng cao với nhiều điều kiện.

## Phân tích

- Dữ liệu hiện tại tìm kiếm quá đơn giản và dễ gây chậm khi lớn.
- Phân trang giúp giảm tải trang và tăng tốc độ hiển thị.
- Prepared statements cải thiện bảo mật truy vấn.
- Bộ lọc giúp admin tìm đúng dữ liệu nhanh chóng.

## Tiến độ hoàn thành

- [ ] Thêm lọc trạng thái phim.
- [ ] Thêm lọc trạng thái vé và ngày.
- [ ] Thêm phân trang cho bảng.
- [ ] Chuyển tất cả truy vấn tìm kiếm sang prepared statements.
