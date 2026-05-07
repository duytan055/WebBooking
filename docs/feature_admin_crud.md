# Chức năng CRUD Quản lý Admin

## Mô tả chức năng

Hoàn thiện các thao tác thêm, sửa, xóa và quản lý dữ liệu cho phim, khuyến mãi, phòng chiếu, ghế, nhân viên và khách hàng.

## Công việc cần làm

1. Thêm chức năng thêm/sửa/xóa phim trong khu vực admin.
2. Thêm chức năng quản lý `khuyenmai`.
3. Thêm chức năng quản lý `phongchieu` và `ghe`.
4. Thêm chức năng sửa/xóa `nhanvien`.
5. Thêm chức năng sửa/xóa `nguoidung`.
6. Hiển thị form chỉnh sửa với dữ liệu hiện tại và validate.
7. Thêm kiểm tra quyền admin trước khi thực hiện các thao tác này.

## Phân tích

- Hiện tại admin chỉ xem được dữ liệu mà chưa thể chỉnh sửa đầy đủ.
- CRUD giúp admin cập nhật hệ thống khi có phim mới, khuyến mãi mới hoặc thay đổi thông tin.
- Cần đảm bảo tính toàn vẹn dữ liệu: xóa phim không làm hỏng liên kết suất chiếu, vé, chi tiết vé.

## Tiến độ hoàn thành

- [ ] Thêm chức năng thêm phim.
- [ ] Thêm chức năng sửa phim.
- [ ] Thêm chức năng xóa phim.
- [ ] Thêm CRUD cho khuyến mãi và phòng chiếu.
- [ ] Thêm CRUD cho nhân viên và khách hàng.
