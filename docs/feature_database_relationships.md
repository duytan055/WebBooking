# Mối quan hệ bảng trong Database WebBooking

## Mục tiêu

Tài liệu này mô tả các quan hệ chính giữa các bảng trong schema `demosqlWeb`, giúp hình dung luồng dữ liệu và kết nối giữa các thực thể.

## Sơ đồ quan hệ bảng (ER)

- `adminn`
  - Không liên kết trực tiếp với bảng khác trong schema hiện tại.

- `nguoidung`
  - `id_user` được tham chiếu bởi `datve.id_user`.

- `nhanvien`
  - `id_nhanvien` được tham chiếu bởi `datve.id_nhanvien`.

- `dotuoi`
  - `id_do_tuoi` được tham chiếu bởi `phim.id_do_tuoi`.

- `phim`
  - `id_phim` được tham chiếu bởi `suatchieu.id_phim`.
  - `id_phim` được tham chiếu bởi `phim_dienvien.id_phim`.
  - `id_phim` được tham chiếu bởi `phim_daodien.id_phim`.

- `dienvien`
  - `id_dienvien` được tham chiếu bởi `phim_dienvien.id_dienvien`.

- `daodien`
  - `id_daodien` được tham chiếu bởi `phim_daodien.id_daodien`.

- `phongchieu`
  - `id_phong` được tham chiếu bởi `suatchieu.id_phong`.
  - `id_phong` được tham chiếu bởi `ghe.id_phong`.

- `suatchieu`
  - `id_suat` được tham chiếu bởi `datve.id_suat`.
  - `id_suat` được tham chiếu bởi `chitietve.id_suat`.

- `ghe`
  - `id_ghe` được tham chiếu bởi `chitietve.id_ghe`.

- `khuyenmai`
  - `id_km` được tham chiếu bởi `datve.id_km`.

- `datve`
  - `id_datve` được tham chiếu bởi `chitietve.id_datve`.

- `chitietve`
  - `id_suat` được tham chiếu bởi `suatchieu.id_suat`.
  - `id_ghe` được tham chiếu bởi `ghe.id_ghe`.

## Các nhóm quan hệ chính

### 1. Quan hệ phim và suất chiếu

- `phim` (1) -> `suatchieu` (N)
- Mỗi phim có thể có nhiều suất chiếu.
- Mỗi suất chiếu chỉ thuộc về một phim.

### 2. Quan hệ suất chiếu, phòng chiếu và ghế

- `phongchieu` (1) -> `suatchieu` (N)
- `phongchieu` (1) -> `ghe` (N)
- `suatchieu` và `ghe` được liên kết gián tiếp qua `chitietve`.

### 3. Quan hệ đặt vé và chi tiết vé

- `datve` (1) -> `chitietve` (N)
- Mỗi vé đặt có thể chứa nhiều chi tiết vé, nhưng mỗi ghế trong một suất chiếu chỉ xuất hiện một lần do ràng buộc `UNIQUE (id_suat, id_ghe)`.

### 4. Quan hệ người dùng và nhân viên

- `nguoidung` (1) -> `datve` (N)
- `nhanvien` (1) -> `datve` (N)
- Mỗi đặt vé gán cho một khách hàng và một nhân viên xử lý.

### 5. Quan hệ phim với diễn viên và đạo diễn

- `phim` (1) -> `phim_dienvien` (N) -> `dienvien` (1)
- `phim` (1) -> `phim_daodien` (N) -> `daodien` (1)

## Gợi ý trực quan hóa

### ER diagram đơn giản

```
phim --< suatchieu >-- phongchieu
     \              \
      \              >-- ghe
       \
        >-- phim_dienvien -- dienvien
        >-- phim_daodien -- daodien

nguoidung --< datve >-- nhanvien
               |
               >-- khuyenmai
               |
               >-- chitietve >-- ghe
               |
               >-- suatchieu
```

> Lưu ý: đây là sơ đồ chữ giản lược, giúp hình dung các quan hệ chính.

## Kết luận

Schema `demosqlWeb` có cấu trúc rõ ràng cho hệ thống bán vé:

- Phim, suất chiếu, phòng chiếu và ghế tạo thành mô-đun lịch chiếu.
- Đặt vé và chi tiết vé liên kết với người dùng, nhân viên và khuyến mãi.
- Bảng liên kết nhiều-nhiều cho phim với diễn viên và đạo diễn mở rộng được dữ liệu nội dung phim.
