# Cấu trúc cơ sở dữ liệu

## Mô tả chung

Tài liệu này mô tả schema MySQL của dự án WebBooking dựa trên lệnh SQL `CREATE DATABASE` và `CREATE TABLE` bạn cung cấp.

## Các bảng chính

### 1. `adminn`

- Lưu thông tin quản trị viên.
- Các trường:
  - `id_admin` INT AUTO_INCREMENT PRIMARY KEY
  - `ten_dang_nhap` VARCHAR(100)
  - `mat_khau` VARCHAR(100)

### 2. `nhanvien`

- Lưu thông tin nhân viên xử lý giao dịch bán vé.
- Các trường:
  - `id_nhanvien` INT AUTO_INCREMENT PRIMARY KEY
  - `ten` VARCHAR(100)
  - `mat_khau` VARCHAR(100)
  - `sdt` VARCHAR(20)

### 3. `nguoidung`

- Lưu dữ liệu khách hàng/ người dùng đã đăng ký.
- Các trường:
  - `id_user` INT AUTO_INCREMENT PRIMARY KEY
  - `ten` VARCHAR(100)
  - `email` VARCHAR(100) UNIQUE
  - `cccd` VARCHAR(20)
  - `sdt` VARCHAR(20)
  - `ngay_sinh` DATETIME
  - `mat_khau` VARCHAR(100)

### 4. `dotuoi`

- Lưu mốc độ tuổi cho phim.
- Các trường:
  - `id_do_tuoi` INT AUTO_INCREMENT PRIMARY KEY
  - `do_tuoi` VARCHAR(10)
  - `mo_ta` VARCHAR(50)

### 5. `daodien`

- Lưu thông tin đạo diễn.
- Các trường:
  - `id_daodien` INT AUTO_INCREMENT PRIMARY KEY
  - `ten_dao_dien` VARCHAR(150)

### 6. `dienvien`

- Lưu thông tin diễn viên.
- Các trường:
  - `id_dienvien` INT AUTO_INCREMENT PRIMARY KEY
  - `ten_dien_vien` VARCHAR(150)

### 7. `phim`

- Lưu dữ liệu phim.
- Các trường:
  - `id_phim` INT AUTO_INCREMENT PRIMARY KEY
  - `ten_phim` VARCHAR(200)
  - `the_loai` VARCHAR(100)
  - `thoi_luong` INT
  - `ngay_khoi_chieu` DATE
  - `poster` VARCHAR(255)
  - `hinh_anh` VARCHAR(200)
  - `trailer_phim` VARCHAR(200)
  - `mo_ta` TEXT
  - `id_do_tuoi` INT
  - `trang_thai` VARCHAR(50)
- Khóa ngoại:
  - `FOREIGN KEY (id_do_tuoi) REFERENCES dotuoi(id_do_tuoi)`

### 8. `phim_dienvien`

- Bảng liên kết nhiều-nhiều giữa phim và diễn viên.
- Các trường:
  - `id_phim` INT
  - `id_dienvien` INT
- Khóa chính:
  - `PRIMARY KEY (id_phim, id_dienvien)`
- Khóa ngoại:
  - `FOREIGN KEY (id_phim) REFERENCES phim(id_phim)`
  - `FOREIGN KEY (id_dienvien) REFERENCES dienvien(id_dienvien)`

### 9. `phim_daodien`

- Bảng liên kết nhiều-nhiều giữa phim và đạo diễn.
- Các trường:
  - `id_phim` INT
  - `id_daodien` INT
- Khóa chính:
  - `PRIMARY KEY (id_phim, id_daodien)`
- Khóa ngoại:
  - `FOREIGN KEY (id_phim) REFERENCES phim(id_phim)`
  - `FOREIGN KEY (id_daodien) REFERENCES daodien(id_daodien)`

### 10. `phongchieu`

- Lưu thông tin phòng chiếu.
- Các trường:
  - `id_phong` INT AUTO_INCREMENT PRIMARY KEY
  - `ma_phong` VARCHAR(50)
  - `ten_phong` VARCHAR(50)
  - `loai_phong` VARCHAR(50)

### 11. `suatchieu`

- Lưu dữ liệu suất chiếu phim.
- Các trường:
  - `id_suat` INT AUTO_INCREMENT PRIMARY KEY
  - `id_phim` INT
  - `id_phong` INT
  - `thoi_gian` DATETIME
- Khóa ngoại:
  - `FOREIGN KEY (id_phim) REFERENCES phim(id_phim)`
  - `FOREIGN KEY (id_phong) REFERENCES phongchieu(id_phong)`

### 12. `ghe`

- Lưu dữ liệu ghế trong phòng chiếu.
- Các trường:
  - `id_ghe` INT AUTO_INCREMENT PRIMARY KEY
  - `ma_ghe` VARCHAR(10)
  - `id_phong` INT
  - `loai_ghe` VARCHAR(20)
- Khóa ngoại:
  - `FOREIGN KEY (id_phong) REFERENCES phongchieu(id_phong)`

### 13. `khuyenmai`

- Lưu dữ liệu chương trình khuyến mãi.
- Các trường:
  - `id_km` INT AUTO_INCREMENT PRIMARY KEY
  - `ma_km` VARCHAR(20) UNIQUE
  - `ten_khuyenmai` VARCHAR(100)
  - `anh_khuyen_mai` VARCHAR(200)
  - `giam_gia` INT
  - `ngay_bat_dau` DATETIME
  - `ngay_ket_thuc` DATETIME

### 14. `datve`

- Lưu thông tin đặt vé.
- Các trường:
  - `id_datve` INT AUTO_INCREMENT PRIMARY KEY
  - `id_nhanvien` INT
  - `id_user` INT
  - `id_suat` INT
  - `id_km` INT
  - `thoi_gian_dat` DATETIME DEFAULT CURRENT_TIMESTAMP
  - `tong_tien` DECIMAL(10,2)
  - `phuong_thuc_thanh_toan` VARCHAR(100)
  - `ma_giao_dich` VARCHAR(50)
  - `ten_nguoi_dat` VARCHAR(100)
  - `so_dien_thoai` VARCHAR(20)
  - `giam_gia` INT
  - `trang_thai` VARCHAR(100)
  - `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
- Ràng buộc:
  - `CHECK (tong_tien >= 0)`
- Khóa ngoại:
  - `FOREIGN KEY (id_user) REFERENCES nguoidung(id_user)`
  - `FOREIGN KEY (id_suat) REFERENCES suatchieu(id_suat)`
  - `FOREIGN KEY (id_km) REFERENCES khuyenmai(id_km)`
  - `FOREIGN KEY (id_nhanvien) REFERENCES nhanvien(id_nhanvien)`

### 15. `chitietve`

- Lưu chi tiết mỗi vé, bao gồm ghế và suất.
- Các trường:
  - `id_ve` INT AUTO_INCREMENT PRIMARY KEY
  - `id_datve` INT
  - `id_suat` INT
  - `id_ghe` INT
  - `gia_ve` DECIMAL(10,2)
  - `trang_thai` VARCHAR(100)
- Ràng buộc:
  - `UNIQUE (id_suat, id_ghe)`
- Khóa ngoại:
  - `FOREIGN KEY (id_datve) REFERENCES datve(id_datve)`
  - `FOREIGN KEY (id_suat) REFERENCES suatchieu(id_suat)`
  - `FOREIGN KEY (id_ghe) REFERENCES ghe(id_ghe)`

## Liên kết chính giữa các bảng

- `phim.id_do_tuoi` -> `dotuoi.id_do_tuoi`
- `phim_dienvien.id_phim` -> `phim.id_phim`
- `phim_dienvien.id_dienvien` -> `dienvien.id_dienvien`
- `phim_daodien.id_phim` -> `phim.id_phim`
- `phim_daodien.id_daodien` -> `daodien.id_daodien`
- `suatchieu.id_phim` -> `phim.id_phim`
- `suatchieu.id_phong` -> `phongchieu.id_phong`
- `ghe.id_phong` -> `phongchieu.id_phong`
- `datve.id_user` -> `nguoidung.id_user`
- `datve.id_suat` -> `suatchieu.id_suat`
- `datve.id_km` -> `khuyenmai.id_km`
- `datve.id_nhanvien` -> `nhanvien.id_nhanvien`
- `chitietve.id_datve` -> `datve.id_datve`
- `chitietve.id_suat` -> `suatchieu.id_suat`
- `chitietve.id_ghe` -> `ghe.id_ghe`

## Ghi chú

- Schema này phản ánh trực tiếp các bảng và ràng buộc được tạo ra bởi SQL bạn cung cấp.
- Nếu cần, có thể bổ sung sơ đồ ER hoặc file tạo DB đầy đủ để quản lý chi tiết dữ liệu.
