# Hướng dẫn sử dụng trang Chính sách

## Tổng quan

Hệ thống trang chính sách được tạo để hiển thị các chính sách của website rạp chiếu phim, bao gồm:

- Chính sách bảo mật
- Chính sách thanh toán
- Chính sách hoàn vé
- Điều khoản sử dụng

## Cài đặt

### 1. Import dữ liệu vào database

Chạy file SQL để thêm dữ liệu chính sách vào database:

```bash
mysql -u root -p demosqlWeb < insert_chinhsach.sql
```

Hoặc sử dụng phpMyAdmin:

1. Mở phpMyAdmin
2. Chọn database `demosqlWeb`
3. Vào tab "SQL"
4. Copy nội dung file `insert_chinhsach.sql` và chạy

### 2. Cấu trúc bảng chinhsach

```sql
CREATE TABLE chinhsach (
    id_chinhsach INT AUTO_INCREMENT PRIMARY KEY,
    tieu_de VARCHAR(255),
    loai VARCHAR(50),
    noi_dung LONGTEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Sử dụng

### Truy cập trang chính sách

Các link trong footer đã được cấu hình sẵn:

- **Chính sách bảo mật**: `Pages/trangchinhsach.php?page=bao-mat`
- **Chính sách thanh toán**: `Pages/trangchinhsach.php?page=thanh-toan`
- **Chính sách hoàn vé**: `Pages/trangchinhsach.php?page=giao-nhan`
- **Điều khoản sử dụng**: `Pages/trangchinhsach.php?page=dieu-khoan-chung`

### Cách hoạt động

1. Trang nhận parameter `page` từ URL
2. Map parameter với loại chính sách trong database:
   - `bao-mat` → `baomat`
   - `thanh-toan` → `thanhtoan`
   - `giao-nhan` → `hoanve`
   - `dieu-khoan-chung` → `dieukhoan`
3. Truy vấn database và hiển thị nội dung tương ứng

## Tính năng

### 1. Navigation tabs

- Hiển thị 4 tab chính sách
- Tab active được highlight màu đỏ
- Dễ dàng chuyển đổi giữa các chính sách

### 2. Responsive design

- Giao diện tương thích với header và footer hiện có
- Sử dụng backdrop-filter và gradient background
- Responsive trên mọi thiết bị

### 3. Bảo mật

- Sử dụng prepared statements để tránh SQL injection
- Escape HTML output với `htmlspecialchars()`
- Xử lý lỗi khi không tìm thấy dữ liệu

## Cập nhật nội dung

### Thêm chính sách mới

```sql
INSERT INTO chinhsach (tieu_de, loai, noi_dung)
VALUES ('Tiêu đề', 'loai_moi', 'Nội dung chính sách...');
```

### Cập nhật chính sách hiện có

```sql
UPDATE chinhsach
SET noi_dung = 'Nội dung mới...',
    ngay_cap_nhat = NOW()
WHERE loai = 'baomat';
```

## File liên quan

- `Pages/trangchinhsach.php` - Trang hiển thị chính sách
- `Module/footer.php` - Footer với links đến các trang chính sách
- `insert_chinhsach.sql` - Script insert dữ liệu
- `Group8.sql` - Schema database (bao gồm bảng chinhsach)
- `excel/chinhsach.csv` - Dữ liệu gốc

## Kiểm tra

Sau khi cài đặt, truy cập:

```
http://localhost/WebBooking/Pages/trangchinhsach.php?page=bao-mat
```

Hoặc click vào các link trong footer để kiểm tra.
