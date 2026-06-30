# Tài Liệu Chi Tiết Dự Án WebBooking

## Hệ Thống Đặt Vé Xem Phim Trực Tuyến

---

# MỤC LỤC

1. [Tổng Quan Dự Án](#1-tổng-quan-dự-án)
2. [Cấu Trúc Thư Mục](#2-cấu-trúc-thư-mục)
3. [Cơ Sở Dữ Liệu (MySQL)](#3-cơ-sở-dữ-liệu-mysql)
4. [Chức Năng Xác Thực & Người Dùng](#4-chức-năng-xác-thực--người-dùng)
5. [Giao Diện Người Dùng (Frontend)](#5-giao-diện-người-dùng-frontend)
6. [Module Đặt Vé - Luồng Chính](#6-module-đặt-vé---luồng-chính)
7. [Chức Năng Thanh Toán](#7-chức-năng-thanh-toán)
8. [Module Quản Trị Admin](#8-module-quản-trị-admin)
9. [API & Tích Hợp Bên Ngoài](#9-api--tích-hợp-bên-ngoài)
10. [Các Chức Năng Khác](#10-các-chức-năng-khác)
11. [Kiến Trúc Hệ Thống & Luồng Xử Lý](#11-kiến-trúc-hệ-thống--luồng-xử-lý)
12. [Hướng Dẫn Triển Khai & Cài Đặt](#12-hướng-dẫn-triển-khai--cài-đặt)

---

## 1. Tổng Quan Dự Án

### 1.1 Giới thiệu

**WebBooking** là một hệ thống đặt vé xem phim trực tuyến được phát triển bằng ngôn ngữ **PHP** thuần (không sử dụng framework) kết hợp với **JavaScript**, **HTML/CSS** và cơ sở dữ liệu **MySQL**. Dự án được xây dựng theo mô hình **Client-Server** truyền thống, chạy trên nền tảng **XAMPP** (Apache + MySQL + PHP).

Hệ thống cung cấp hai giao diện chính:

- **Giao diện người dùng (Public Site)**: Cho phép khách hàng xem phim đang chiếu, sắp chiếu, đặt vé, chọn ghế, mua combo và thanh toán.
- **Giao diện quản trị (Admin Panel)**: Cho phép quản trị viên quản lý phim, suất chiếu, nhân viên, khách hàng, xem báo cáo thống kê.

### 1.2 Công nghệ sử dụng

| Công nghệ             | Mục đích                                          |
| --------------------- | ------------------------------------------------- |
| **PHP 8.x**           | Ngôn ngữ lập trình back-end xử lý logic nghiệp vụ |
| **MySQL**             | Hệ quản trị cơ sở dữ liệu lưu trữ dữ liệu         |
| **JavaScript (ES6+)** | Xử lý tương tác front-end, AJAX, DOM manipulation |
| **HTML5/CSS3**        | Xây dựng giao diện người dùng                     |
| **XAMPP**             | Môi trường máy chủ local (Apache + MySQL + PHP)   |
| **Google OAuth 2.0**  | Đăng nhập bằng tài khoản Google                   |
| **VNPAY**             | Cổng thanh toán điện tử                           |
| **QR Code API**       | Tạo mã QR cho thanh toán chuyển khoản             |
| **PHPMailer**         | Gửi email xác nhận vé                             |

### 1.3 Mục tiêu

- Cung cấp nền tảng đặt vé xem phim trực tuyến dễ sử dụng
- Hỗ trợ quy trình đặt vé 3 bước: **Chọn ghế → Combo → Thanh toán**
- Tích hợp nhiều phương thức thanh toán: VNPAY, Chuyển khoản ngân hàng (QR)
- Cho phép quản trị viên quản lý toàn bộ hệ thống qua giao diện admin
- Gửi email xác nhận vé tự động sau khi thanh toán thành công

---

## 2. Cấu Trúc Thư Mục

Dưới đây là cấu trúc thư mục đầy đủ của dự án kèm giải thích chi tiết từng thành phần:

```
WebBooking/
│
├── .gitignore                         # File cấu hình Git ignore
├── composer.json                      # File khai báo dependencies PHP (Composer)
├── composer.lock                      # File lock version của Composer
├── Group8.sql                         # File dump cơ sở dữ liệu MySQL
│
├── API/                               # Thư mục chứa API tích hợp bên ngoài
│   ├── config-google.php              # Cấu hình Google OAuth Client
│   └── login-google.php              # Xử lý callback đăng nhập Google
│
├── admin/                             # Module quản trị (Admin Panel)
│   ├── admin.css                      # Stylesheet cho trang admin
│   ├── admin.php                      # Dashboard chính - thống kê & lịch sử giao dịch
│   ├── baocao.php                     # Báo cáo doanh thu chi tiết
│   ├── khachhang.php                  # Quản lý danh sách khách hàng
│   ├── nhanvien.php                   # Quản lý danh sách nhân viên
│   ├── phim.php                       # Quản lý danh sách phim
│   ├── sidebar.php                    # Sidebar navigation cho admin
│   ├── suanhanvien.php                # Sửa thông tin nhân viên
│   ├── suaphim.php                    # Sửa thông tin phim
│   ├── suasuatchieu.php               # Sửa suất chiếu
│   ├── suatchieu.php                  # Quản lý suất chiếu
│   ├── themnhanvien.php               # Thêm nhân viên mới
│   ├── themphim.php                   # Thêm phim mới
│   ├── themsuatchieu.php              # Thêm suất chiếu mới
│   ├── xoakhachhang.php              # Xóa khách hàng
│   ├── xoanhanvien.php                # Xóa nhân viên
│   ├── xoaphim.php                    # Xóa phim
│   ├── xoasuatchieu.php               # Xóa suất chiếu
│   ├── README.md                      # Tài liệu hướng dẫn admin
│   └── img/                           # Hình ảnh sử dụng trong admin
│
├── Anh_Khuyen_Mai/                   # Thư mục chứa ảnh khuyến mãi
│   ├── image1.jpg đến image7.jpg      # 7 ảnh khuyến mãi
│
├── config/                            # Thư mục cấu hình
│   └── vnpay.php                      # Cấu hình cổng thanh toán VNPAY
│
├── Connect/                           # Thư mục kết nối & xử lý
│   ├── connecDB.php                   # Kết nối cơ sở dữ liệu MySQL
│   └── sendMail.php                   # Hàm gửi email xác nhận (PHPMailer)
│
├── docs/                              # Tài liệu dự án (nhiều file .md)
│   ├── README.md                      # Mục lục tài liệu
│   ├── project_overview.md            # Tổng quan dự án
│   ├── full_project_documentation.md  # Tài liệu toàn diện (file này)
│   ├── feature_admin_crud.md          # CRUD admin
│   ├── feature_admin_dashboard.md     # Dashboard admin
│   ├── feature_advanced_reporting.md  # Báo cáo nâng cao
│   ├── feature_authentication.md      # Xác thực
│   ├── feature_authentication_authorization.md  # Phân quyền
│   ├── feature_customer_management.md # Quản lý khách hàng
│   ├── feature_database.md            # Cơ sở dữ liệu
│   ├── feature_database_relationships.md       # Quan hệ DB
│   ├── feature_deployment.md          # Triển khai
│   ├── feature_movie_management.md    # Quản lý phim
│   ├── feature_next_steps.md          # Các bước tiếp theo
│   ├── feature_public_site.md         # Trang public
│   ├── feature_reports.md             # Báo cáo
│   ├── feature_responsive_ui.md       # Responsive UI
│   ├── feature_search_filter_pagination.md    # Tìm kiếm & phân trang
│   ├── feature_security_error_handling.md     # Bảo mật & lỗi
│   ├── feature_staff_management.md    # Quản lý nhân viên
│   ├── feature_ticket_booking_flow.md # Luồng đặt vé
│   └── progress_update_2026-06-02.md  # Cập nhật tiến độ
│
├── excel/                             # File Excel/CSV mẫu cho import dữ liệu
│   ├── admin.csv đến user.csv         # 16 file CSV cho từng bảng
│
├── LoginAndSign-up/                   # Module đăng nhập & đăng ký
│   ├── login.php                      # Trang đăng nhập
│   ├── Sign-up.php                    # Trang đăng ký tài khoản
│   ├── logout.php                     # Xử lý đăng xuất
│   ├── forgot_password.php            # Quên mật khẩu
│   ├── verify_otp_reset.php           # Xác thực OTP đặt lại mật khẩu
│   ├── style.css                      # Stylesheet riêng
│   └── image1.webp                    # Hình nền
│
├── Logo/                              # Thư mục logo
│   └── logo1.png                      # Logo của hệ thống
│
├── Module/                            # Các module tái sử dụng
│   ├── footer.php                     # Footer chung
│   ├── header.php                     # Header chung (navigation bar)
│   ├── movies.php                     # Hiển thị danh sách phim
│   ├── PromotionsAndEvents.php        # Module khuyến mãi & sự kiện
│   ├── sendMail.php                   # (Liên kết đến Connect/sendMail.php)
│   ├── SliderMovies.php               # Slider phim (carousel)
│   └── TrailerModal.php              # Modal xem trailer phim
│
├── Pages/                             # Các trang chính của hệ thống
│   ├── trangChu.php                   # Trang chủ
│   ├── NowMovies.php                  # Phim đang chiếu
│   ├── SoonMovies.php                 # Phim sắp chiếu
│   ├── SeeMoreMovies.php              # Xem thêm phim
│   ├── TopPhim.php                    # Top phim / Bảng xếp hạng
│   ├── KhuyenMai.php                  # Danh sách khuyến mãi
│   ├── ChiTietKhuyenMai.php           # Chi tiết khuyến mãi
│   ├── trangchinhsach.php             # Trang chính sách & điều khoản
│   ├── trangLienHe.php                # Trang liên hệ
│   ├── profileUser.php               # Thông tin & hồ sơ người dùng
│   ├── buyticket.php                  # Trang đặt vé (chọn ghế, combo, thanh toán)
│   ├── buyticket.js                   # JavaScript xử lý đặt vé
│   ├── payment_success.php            # Trang thông báo thanh toán thành công
│   └── ticket_success.php             # Trang thông báo đặt vé thành công
│
├── payment/                           # Module thanh toán
│   ├── vnpay_payment.php              # Tạo yêu cầu thanh toán VNPAY
│   └── vnpay_return.php              # Xử lý kết quả từ VNPAY
│
├── Picture_Bank/                      # Hình ảnh ngân hàng & QR
│   ├── MBBank.webp                    # Logo MB Bank
│   ├── Techcombank.webp               # Logo Techcombank
│   ├── Vietcombank.webp               # Logo Vietcombank
│   ├── VNPAY.webp                     # Logo VNPAY
│   └── QR_Banking.webp               # Mẫu QR Banking
│
└── poster/                            # Poster phim (51 ảnh JPEG)
    ├── 1780661260_6a22bc0c10c82.jpg   # Poster phim 1
    ├── anh-hung.jpg                    # Poster "Anh Hùng"
    ├── ... (tổng cộng 51 file poster)
    └── yeunuthichhanghieu.jpg         # Poster "Yêu Nụ Thích Hàng Hiệu"
```

### 2.1 Giải thích chi tiết từng thư mục

#### `API/` - Tích hợp API bên ngoài

- **config-google.php**: Khởi tạo Google Client cho OAuth 2.0. Định nghĩa Client ID, Client Secret, Redirect URI. Tạo service `Google_Client` để gọi API xác thực.
- **login-google.php**: Xử lý callback sau khi người dùng đăng nhập Google thành công. Lấy thông tin user từ Google (tên, email, Google ID), kiểm tra xem email đã tồn tại trong DB chưa. Nếu chưa, tạo tài khoản mới. Nếu có, đăng nhập và tạo session.

#### `Connect/` - Kết nối & Dịch vụ hệ thống

- **connecDB.php**:
  - Kết nối đến MySQL bằng `mysqli` (MySQL Improved Extension)
  - Cấu hình: host = `localhost`, user = `root`, password = `''` (trống), database = `group8`
  - Thiết lập charset UTF-8 để hỗ trợ tiếng Việt: `$conn->set_charset("utf8")`
  - Biến `$conn` được include trong tất cả các file PHP để tái sử dụng
- **sendMail.php**:
  - Sử dụng thư viện **PHPMailer** (cài qua Composer)
  - Hàm `sendTicketEmail($id_datve, $conn)`:
    - Nhận ID đặt vé và kết nối DB
    - Truy vấn thông tin vé (phim, suất chiếu, ghế, tổng tiền, mã giao dịch)
    - Tạo nội dung email HTML với đầy đủ thông tin vé
    - Gửi email đến địa chỉ email của người dùng
    - Nội dung email bao gồm: tên phim, suất chiếu, danh sách ghế, combo, tổng tiền, mã giao dịch, QR code

#### `Module/` - Các Module Tái Sử Dụng (Components)

Các file trong thư mục này đóng vai trò như **components** trong kiến trúc component-based. Chúng được include vào nhiều trang khác nhau để tái sử dụng giao diện.

- **header.php**:
  - Navigation bar chính của toàn bộ hệ thống
  - Bao gồm: logo, menu (Trang chủ, Phim, Khuyến mãi, Liên hệ), thanh tìm kiếm
  - Kiểm tra session để hiển thị tên người dùng hoặc nút Đăng nhập/Đăng ký
  - Dropdown menu cho người dùng đã đăng nhập (Hồ sơ, Đăng xuất)
- **footer.php**:
  - Footer chung với thông tin bản quyền, liên kết nhanh
  - Bao gồm các cột: Về chúng tôi, Liên kết nhanh, Hỗ trợ khách hàng, Theo dõi
- **movies.php**:
  - Component hiển thị danh sách phim dạng card (grid)
  - Mỗi card phim gồm: poster, tên phim, nút "Mua Vé", nút "Trailer"
  - Nhận dữ liệu phim từ biến `$movies` được truyền từ trang chủ
- **SliderMovies.php**:
  - Slider/carousel hiển thị các phim nổi bật
  - Sử dụng JavaScript để tạo hiệu ứng chuyển slide
  - Mỗi slide hiển thị poster phim, tên phim, mô tả ngắn
  - Tích hợp nút "Mua Vé" và "Xem Trailer"
- **PromotionsAndEvents.php**:
  - Hiển thị các chương trình khuyến mãi và sự kiện đặc biệt
  - Layout dạng lưới với ảnh khuyến mãi và mô tả
- **TrailerModal.php**:
  - Modal/popup để xem trailer phim
  - Tích hợp YouTube iframe để phát trailer
  - Mở khi người dùng click "Xem Trailer" trên card phim

#### `Pages/` - Các Trang Chức Năng

- **trangChu.php** (Trang chủ):
  - Trang đầu tiên người dùng thấy khi truy cập
  - Hiển thị: Slider phim nổi bật, danh sách phim đang chiếu (giới hạn), danh sách phim sắp chiếu (giới hạn), khuyến mãi, tin tức
  - Sử dụng `include` để gọi các Module: `SliderMovies.php`, `movies.php`, `PromotionsAndEvents.php`
  - Truy vấn SQL lấy phim từ CSDL, phân loại đang chiếu/sắp chiếu dựa trên ngày khởi chiếu

- **NowMovies.php** (Phim đang chiếu):
  - Hiển thị tất cả phim có ngày khởi chiếu <= ngày hiện tại
  - Mỗi phim hiển thị poster, tên, thời lượng, thể loại, nút "Mua Vé"
  - Click "Mua Vé" chuyển đến `buyticket.php?id=X` (X = ID phim)

- **SoonMovies.php** (Phim sắp chiếu):
  - Hiển thị tất cả phim có ngày khởi chiếu > ngày hiện tại
  - Giao diện tương tự NowMovies nhưng nút "Mua Vé" có thể bị vô hiệu hóa

- **TopPhim.php** (Bảng xếp hạng):
  - Hiển thị top phim dựa trên lượt đặt vé hoặc đánh giá

- **KhuyenMai.php** & **ChiTietKhuyenMai.php**:
  - Danh sách khuyến mãi đang có
  - Chi tiết từng chương trình khuyến mãi

- **profileUser.php**:
  - Thông tin cá nhân người dùng
  - Lịch sử đặt vé (các vé đã đặt, trạng thái thanh toán)
  - Cho phép chỉnh sửa thông tin cá nhân

- **trangchinhsach.php**: Trang hiển thị chính sách, điều khoản sử dụng
- **trangLienHe.php**: Trang liên hệ với thông tin địa chỉ, email, số điện thoại

---

## 3. Cơ Sở Dữ Liệu (MySQL)

### 3.1 Tổng quan

Cơ sở dữ liệu `group8` được thiết kế với 13 bảng chính, lưu trữ toàn bộ dữ liệu của hệ thống đặt vé xem phim.

### 3.2 Danh sách các bảng

#### `adminn` - Bảng quản trị viên

| Cột             | Kiểu dữ liệu             | Mô tả                                 |
| --------------- | ------------------------ | ------------------------------------- |
| `id_admin`      | INT (PK, AUTO_INCREMENT) | ID quản trị viên                      |
| `ten_dang_nhap` | VARCHAR(50)              | Tên đăng nhập                         |
| `mat_khau`      | VARCHAR(255)             | Mật khẩu (plain text - cần cải thiện) |
| `ten`           | VARCHAR(100)             | Họ tên                                |
| `email`         | VARCHAR(100)             | Email                                 |
| `sdt`           | VARCHAR(15)              | Số điện thoại                         |

#### `nguoidung` - Bảng người dùng (khách hàng)

| Cột         | Kiểu dữ liệu             | Mô tả                                 |
| ----------- | ------------------------ | ------------------------------------- |
| `id_user`   | INT (PK, AUTO_INCREMENT) | ID người dùng                         |
| `ten`       | VARCHAR(50)              | Tên đăng nhập                         |
| `mat_khau`  | VARCHAR(255)             | Mật khẩu                              |
| `email`     | VARCHAR(100)             | Email                                 |
| `sdt`       | VARCHAR(15)              | Số điện thoại                         |
| `google_id` | VARCHAR(255)             | Google ID (nếu đăng nhập bằng Google) |
| `ngay_tao`  | DATETIME                 | Ngày tạo tài khoản                    |

#### `phim` - Bảng phim

| Cột               | Kiểu dữ liệu             | Mô tả                |
| ----------------- | ------------------------ | -------------------- |
| `id_phim`         | INT (PK, AUTO_INCREMENT) | ID phim              |
| `ten_phim`        | VARCHAR(200)             | Tên phim             |
| `thoi_luong`      | INT                      | Thời lượng (phút)    |
| `the_loai`        | VARCHAR(100)             | Thể loại             |
| `ngay_khoi_chieu` | DATE                     | Ngày khởi chiếu      |
| `mo_ta`           | TEXT                     | Mô tả nội dung       |
| `dao_dien`        | VARCHAR(100)             | Đạo diễn             |
| `dien_vien`       | TEXT                     | Dàn diễn viên        |
| `ngon_ngu`        | VARCHAR(50)              | Ngôn ngữ             |
| `danh_gia`        | DECIMAL(2,1)             | Đánh giá (VD: 8.5)   |
| `poster`          | VARCHAR(255)             | Đường dẫn poster     |
| `trailer`         | VARCHAR(255)             | Link YouTube trailer |
| `quoc_gia`        | VARCHAR(100)             | Quốc gia sản xuất    |

#### `phongchieu` - Bảng phòng chiếu

| Cột            | Kiểu dữ liệu             | Mô tả                              |
| -------------- | ------------------------ | ---------------------------------- |
| `id_phong`     | INT (PK, AUTO_INCREMENT) | ID phòng chiếu                     |
| `ten_phong`    | VARCHAR(50)              | Tên phòng (VD: Phòng 1, Phòng VIP) |
| `so_luong_ghe` | INT                      | Số lượng ghế                       |

#### `ghe` - Bảng ghế

| Cột        | Kiểu dữ liệu                   | Mô tả                               |
| ---------- | ------------------------------ | ----------------------------------- |
| `id_ghe`   | INT (PK, AUTO_INCREMENT)       | ID ghế                              |
| `id_phong` | INT (FK → phongchieu.id_phong) | ID phòng chiếu                      |
| `ma_ghe`   | VARCHAR(10)                    | Mã ghế (VD: A01, B12)               |
| `loai_ghe` | VARCHAR(20)                    | Loại ghế: `normal`, `vip`, `couple` |

**Quan hệ**: Một phòng chiếu có nhiều ghế (1:N). Ghế thuộc về một phòng duy nhất.

#### `suatchieu` - Bảng suất chiếu

| Cột          | Kiểu dữ liệu                   | Mô tả          |
| ------------ | ------------------------------ | -------------- |
| `id_suat`    | INT (PK, AUTO_INCREMENT)       | ID suất chiếu  |
| `id_phim`    | INT (FK → phim.id_phim)        | ID phim        |
| `id_phong`   | INT (FK → phongchieu.id_phong) | ID phòng chiếu |
| `date_chieu` | DATE                           | Ngày chiếu     |
| `thoi_gian`  | TIME                           | Giờ chiếu      |

**Quan hệ**: Một phim có nhiều suất chiếu (1:N). Một phòng có nhiều suất chiếu (1:N).

#### `datve` - Bảng đặt vé

| Cột                      | Kiểu dữ liệu                                 | Mô tả                              |
| ------------------------ | -------------------------------------------- | ---------------------------------- |
| `id_datve`               | INT (PK, AUTO_INCREMENT)                     | ID đơn đặt vé                      |
| `id_user`                | INT (FK → nguoidung.id_user)                 | ID người dùng                      |
| `id_suat`                | INT (FK → suatchieu.id_suat)                 | ID suất chiếu                      |
| `thoi_gian_dat`          | DATETIME                                     | Thời gian đặt                      |
| `tong_tien`              | INT                                          | Tổng tiền thanh toán               |
| `phuong_thuc_thanh_toan` | VARCHAR(50)                                  | Phương thức: VNPAY, QR_BANKING     |
| `ma_giao_dich`           | VARCHAR(100)                                 | Mã giao dịch (VD: DH1688899999123) |
| `ten_nguoi_dat`          | VARCHAR(100)                                 | Tên người đặt                      |
| `so_dien_thoai`          | VARCHAR(15)                                  | Số điện thoại                      |
| `giam_gia`               | INT                                          | Số tiền giảm giá                   |
| `trang_thai`             | ENUM('PENDING','PAID','EXPIRED','CANCELLED') | Trạng thái thanh toán              |
| `created_at`             | DATETIME                                     | Thời gian tạo                      |

**Quan hệ**: Một người dùng có nhiều đơn đặt vé (1:N). Một suất chiếu có nhiều đơn đặt vé (1:N).

#### `chitietve` - Bảng chi tiết vé

| Cột          | Kiểu dữ liệu                 | Mô tả         |
| ------------ | ---------------------------- | ------------- |
| `id_chitiet` | INT (PK, AUTO_INCREMENT)     | ID chi tiết   |
| `id_datve`   | INT (FK → datve.id_datve)    | ID đơn đặt vé |
| `id_suat`    | INT (FK → suatchieu.id_suat) | ID suất chiếu |
| `id_ghe`     | INT (FK → ghe.id_ghe)        | ID ghế        |

**Quan hệ**: Một đơn đặt vé có nhiều chi tiết vé (1:N). Mỗi chi tiết ứng với một ghế trong suất chiếu.

#### `ghe_tam_giu` - Bảng giữ ghế tạm thời

| Cột          | Kiểu dữ liệu                 | Mô tả                            |
| ------------ | ---------------------------- | -------------------------------- |
| `id_hold`    | INT (PK, AUTO_INCREMENT)     | ID bản ghi giữ                   |
| `id_suat`    | INT (FK → suatchieu.id_suat) | ID suất chiếu                    |
| `id_ghe`     | INT (FK → ghe.id_ghe)        | ID ghế                           |
| `id_user`    | INT (FK → nguoidung.id_user) | ID người dùng giữ                |
| `expires_at` | DATETIME                     | Thời hạn giữ (hiện tại + 4 phút) |

**Quan hệ**: Mỗi bản ghi giữ ghế tương ứng với một user, một suất chiếu và một ghế.

**UNIQUE KEY**: `(id_suat, id_ghe)` - Đảm bảo mỗi ghế trong suất chiếu chỉ được giữ bởi một người.

#### `khuyenmai` - Bảng khuyến mãi

| Cột             | Kiểu dữ liệu             | Mô tả                               |
| --------------- | ------------------------ | ----------------------------------- |
| `id_km`         | INT (PK, AUTO_INCREMENT) | ID khuyến mãi                       |
| `tieu_de`       | VARCHAR(200)             | Tiêu đề khuyến mãi                  |
| `noi_dung`      | TEXT                     | Nội dung chi tiết                   |
| `hinh_anh`      | VARCHAR(255)             | Đường dẫn hình ảnh                  |
| `ngay_bat_dau`  | DATE                     | Ngày bắt đầu                        |
| `ngay_ket_thuc` | DATE                     | Ngày kết thúc                       |
| `trang_thai`    | TINYINT(1)               | Trạng thái (1: Active, 0: Inactive) |

#### `nhanvien` - Bảng nhân viên

| Cột       | Kiểu dữ liệu             | Mô tả         |
| --------- | ------------------------ | ------------- |
| `id_nv`   | INT (PK, AUTO_INCREMENT) | ID nhân viên  |
| `ten_nv`  | VARCHAR(100)             | Tên nhân viên |
| `email`   | VARCHAR(100)             | Email         |
| `sdt`     | VARCHAR(15)              | Số điện thoại |
| `chuc_vu` | VARCHAR(100)             | Chức vụ       |

#### `daodien` & `dienvien` - Bảng đạo diễn & diễn viên

- Lưu thông tin đạo diễn và diễn viên
- Liên kết với phim qua các bảng trung gian `phim_daodien` và `phim_dienvien` (quan hệ N:N)

### 3.3 Mối quan hệ giữa các bảng (Sơ đồ)

```
nguoidung (1) ----< (N) datve (1) ----< (N) chitietve (N) >---- (1) ghe
                                |                                    |
                                | (N)                                | (1)
                                v                                    v
                          suatchieu (1)                         phongchieu (1)
                                |                                    |
                                | (N)                                | (1)
                                v                                    v
                            phim (1)                             (phongchieu)
```

- **nguoidung → datve**: 1:N (Một người dùng có nhiều đơn đặt vé)
- **datve → chitietve**: 1:N (Một đơn có nhiều vé chi tiết)
- **chitietve → ghe**: N:1 (Nhiều chi tiết vé ứng với một ghế)
- **ghe → phongchieu**: N:1 (Nhiều ghế thuộc một phòng)
- **datve → suatchieu**: N:1 (Nhiều đơn cho một suất chiếu)
- **suatchieu → phim**: N:1 (Nhiều suất chiếu cho một phim)
- **suatchieu → phongchieu**: N:1 (Nhiều suất chiếu trong một phòng)

---

## 4. Chức Năng Xác Thực & Người Dùng

### 4.1 Đăng nhập (`LoginAndSign-up/login.php`)

**Mô tả**: Trang đăng nhập hỗ trợ hai hình thức: đăng nhập bằng tài khoản mật khẩu và đăng nhập bằng Google.

**Các thành phần chính**:

1. **Xử lý đăng nhập local** (dòng 9-58):
   - Nhận dữ liệu từ form: `$_POST['username']`, `$_POST['password']`
   - Kiểm tra tính hợp lệ (không để trống)
   - **Bước 1**: Kiểm tra trong bảng `adminn` - nếu khớp, tạo session với role = 'admin' và chuyển hướng đến `admin/admin.php`
   - **Bước 2**: Kiểm tra trong bảng `nguoidung` - nếu khớp, tạo session với role = 'user' và chuyển hướng đến `Pages/trangChu.php`
   - **Bước 3**: Nếu không khớp, hiển thị thông báo lỗi

2. **Đăng nhập Google** (dòng 4, 159):
   - Sử dụng Google OAuth 2.0 Client từ `API/config-google.php`
   - Hiển thị nút đăng nhập Google với icon Google
   - Khi click, chuyển hướng đến URL xác thực Google

3. **Session**:
   - Cấu trúc session: `$_SESSION['user']` chứa:
     - `id`: ID người dùng/admin
     - `name`: Tên đăng nhập
     - `email`: Email
     - `role`: 'admin' hoặc 'user'
     - `type`: 'local' hoặc 'google'

4. **Giao diện**:
   - Form đăng nhập với các trường: Username, Password
   - Checkbox "Remember me" (chỉ giao diện)
   - Link "Forget password" đến `forgot_password.php`
   - Link "Create account" đến `Sign-up.php`
   - Nút đăng nhập Google
   - Notification tự động ẩn sau 4 giây

### 4.2 Đăng ký (`LoginAndSign-up/Sign-up.php`)

**Mô tả**: Cho phép người dùng tạo tài khoản mới.

**Chức năng**:

- Form đăng ký với các trường: Tên, Email, Số điện thoại, Mật khẩu, Xác nhận mật khẩu
- Kiểm tra tính hợp lệ của dữ liệu đầu vào
- Kiểm tra email đã tồn tại trong hệ thống chưa
- Mã hóa mật khẩu (có thể sử dụng `password_hash()`)
- Tạo tài khoản mới trong bảng `nguoidung`
- Chuyển hướng đến trang đăng nhập sau khi đăng ký thành công

### 4.3 Quên mật khẩu (`LoginAndSign-up/forgot_password.php`)

**Mô tả**: Cho phép người dùng đặt lại mật khẩu khi quên.

**Chức năng**:

- Nhập email để yêu cầu đặt lại mật khẩu
- Kiểm tra email có tồn tại trong hệ thống
- Gửi mã OTP qua email để xác thực
- Chuyển hướng đến trang xác thực OTP

### 4.4 Xác thực OTP (`LoginAndSign-up/verify_otp_reset.php`)

**Mô tả**: Xác thực mã OTP và cho phép đặt mật khẩu mới.

**Chức năng**:

- Nhập mã OTP được gửi qua email
- Kiểm tra mã OTP có hợp lệ và chưa hết hạn
- Nếu OTP hợp lệ, hiển thị form đặt mật khẩu mới
- Cập nhật mật khẩu mới vào cơ sở dữ liệu

### 4.5 Đăng xuất (`LoginAndSign-up/logout.php`)

**Mô tả**: Xử lý đăng xuất người dùng.

**Chức năng**:

- Hủy toàn bộ session: `session_destroy()`
- Xóa tất cả biến session
- Chuyển hướng về trang đăng nhập

### 4.6 Đăng nhập Google (`API/login-google.php` & `API/config-google.php`)

**`config-google.php`**:

```php
$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri('http://localhost/WebBooking/API/login-google.php');
$client->addScope('email');
$client->addScope('profile');
```

**`login-google.php`**:

1. Nhận mã xác thực từ Google callback
2. Đổi mã lấy access token
3. Lấy thông tin user từ Google API (tên, email, ID)
4. Kiểm tra email trong bảng `nguoidung`:
   - Nếu tồn tại: cập nhật `google_id` nếu chưa có
   - Nếu chưa: tạo tài khoản mới với thông tin từ Google
5. Tạo session và chuyển hướng đến trang chủ

---

## 5. Giao Diện Người Dùng (Frontend)

### 5.1 Header (`Module/header.php`)

**Mô tả**: Thanh điều hướng chính xuất hiện trên tất cả các trang.

**Cấu trúc giao diện**:

- **Logo**: Hiển thị logo hệ thống, click để về trang chủ
- **Menu chính**:
  - "Trang Chủ" → `trangChu.php`
  - "Phim" → Dropdown với "Phim Đang Chiếu" (`NowMovies.php`) và "Phim Sắp Chiếu" (`SoonMovies.php`)
  - "Khuyến Mãi" → `KhuyenMai.php`
  - "Liên Hệ" → `trangLienHe.php`
- **Thanh tìm kiếm**: Form tìm kiếm phim theo tên
- **User Menu**:
  - Nếu chưa đăng nhập: Hiển thị nút "Đăng nhập" và "Đăng ký"
  - Nếu đã đăng nhập: Hiển thị tên người dùng với dropdown (Hồ sơ, Đăng xuất)

**Xử lý PHP**:

- Kiểm tra `$_SESSION['user']` để xác định trạng thái đăng nhập
- Include các file CSS/JS cần thiết
- Responsive design với hamburger menu trên mobile

### 5.2 Footer (`Module/footer.php`)

**Mô tả**: Footer hiển thị ở cuối mỗi trang.

**Cấu trúc**:

- **Cột 1 - Về chúng tôi**: Giới thiệu ngắn về hệ thống
- **Cột 2 - Liên kết nhanh**: Các trang chính
- **Cột 3 - Hỗ trợ**: Thông tin liên hệ, email hỗ trợ
- **Cột 4 - Mạng xã hội**: Icon Facebook, Instagram, YouTube
- **Dòng cuối**: Bản quyền © 2026

### 5.3 Trang chủ (`Pages/trangChu.php`)

**Mô tả**: Trang đầu tiên người dùng nhìn thấy.

**Cấu trúc**:

1. **Slider** (Module/SliderMovies.php):
   - Hiển thị 5-7 phim nổi bật dạng carousel
   - Tự động chuyển slide sau mỗi 5 giây
   - Mỗi slide có poster, tên phim, nút "Mua Vé" và "Xem Trailer"
   - Điều khiển bằng nút prev/next hoặc chấm tròn

2. **Phim đang chiếu**:
   - Hiển thị tối đa 8 phim đang chiếu gần nhất
   - Layout dạng grid (4 cột trên desktop)
   - Mỗi card phim: poster, tên, thời lượng, nút "Mua Vé"
   - Nút "Xem Thêm" để xem tất cả phim đang chiếu

3. **Phim sắp chiếu**:
   - Tương tự phim đang chiếu nhưng hiển thị phim sắp khởi chiếu

4. **Khuyến mãi & Sự kiện** (Module/PromotionsAndEvents.php):
   - Hiển thị các chương trình khuyến mãi đang hoạt động
   - Layout dạng lưới với ảnh và mô tả

**Xử lý dữ liệu**:

- Truy vấn phim từ bảng `phim` với điều kiện ngày khởi chiếu
- Phân trang hoặc giới hạn số lượng hiển thị

### 5.4 Danh sách phim (`Pages/NowMovies.php` & `Pages/SoonMovies.php`)

**Mô tả**: Hiển thị toàn bộ danh sách phim theo loại.

**Chức năng**:

- `NowMovies.php`: Lấy phim có `ngay_khoi_chieu <= CURDATE()`
- `SoonMovies.php`: Lấy phim có `ngay_khoi_chieu > CURDATE()`
- Hiển thị dạng lưới card phim
- Mỗi card gồm: poster, tên phim, thể loại, thời lượng, đánh giá, nút "Mua Vé"
- Tích hợp tìm kiếm và lọc theo thể loại

### 5.5 Hồ sơ người dùng (`Pages/profileUser.php`)

**Mô tả**: Trang quản lý thông tin cá nhân và lịch sử đặt vé.

**Chức năng**:

- Hiển thị thông tin cá nhân: tên, email, số điện thoại
- Cho phép chỉnh sửa thông tin
- Hiển thị lịch sử đặt vé:
  - Danh sách các đơn đã đặt
  - Chi tiết: phim, suất chiếu, ghế, tổng tiền, trạng thái
  - Phân loại: đã thanh toán (PAID), chờ thanh toán (PENDING), đã hủy (EXPIRED)
- Nút hủy đơn đối với đơn đang chờ thanh toán

---

## 6. Module Đặt Vé - Luồng Chính

### 6.1 Tổng quan luồng đặt vé

Đây là module phức tạp nhất của hệ thống, bao gồm 3 bước trên 1 trang duy nhất (`buyticket.php`):

```
Bước 1: Chọn ghế → Bước 2: Chọn Combo → Bước 3: Thanh toán
```

### 6.2 File xử lý: `Pages/buyticket.php`

**Mô tả**: File PHP xử lý toàn bộ logic đặt vé và hiển thị giao diện.

#### Phần 1: Xử lý AJAX (dòng 13-190)

File xử lý 6 action khác nhau thông qua `$_POST['action']`:

1. **`hold`** - Giữ ghế tạm thời (dòng 22-46):
   - Kiểm tra ghế đã được bán chưa (tồn tại trong `chitietve`)
   - Nếu chưa bán, thêm bản ghi vào `ghe_tam_giu` với thời hạn 4 phút
   - Nếu đã có bản ghi, cập nhật (ON DUPLICATE KEY UPDATE)

2. **`release`** - Giải phóng ghế (dòng 48-56):
   - Xóa bản ghi trong `ghe_tam_giu` tương ứng với user và ghế
   - Được gọi khi người dùng bỏ chọn ghế

3. **`refresh_hold`** - Gia hạn giữ ghế (dòng 58-67):
   - Cập nhật `expires_at` thêm 4 phút
   - Được gọi khi người dùng chuyển bước (từ chọn ghế sang combo, từ combo sang thanh toán)

4. **`cancel_booking`** - Hủy đơn hàng (dòng 69-82):
   - Xóa tất cả bản ghi giữ ghế của user cho suất chiếu này
   - Cập nhật trạng thái đơn PENDING thành EXPIRED nếu có

5. **`checkout`** - Tạo đơn hàng (dòng 84-169):
   - **Bước 1**: Lấy danh sách ghế đang giữ của user (dòng 90-109)
   - **Bước 2**: Tính tổng tiền dựa trên loại ghế:
     - `normal`: 50,000đ
     - `vip`: 80,000đ
     - `couple`: 100,000đ
   - **Bước 3**: Tính tổng = tiền ghế + tiền combo - giảm giá (dòng 119-120)
   - **Bước 4**: Lấy thông tin người dùng (tên, số điện thoại) (dòng 122-132)
   - **Bước 5**: Tạo mã giao dịch: `DH` + timestamp + 3 số ngẫu nhiên (dòng 133)
   - **Bước 6**: Chèn bản ghi vào bảng `datve` với trạng thái PENDING (dòng 134-148)
   - **Bước 7**: Chèn chi tiết vé vào `chitietve` cho từng ghế (dòng 153-157)
   - **Bước 8**: Xóa bản ghi giữ ghế (dòng 160-163)
   - **Bước 9**: Tự động EXPIRED các đơn PENDING quá 15 phút (dòng 161)
   - **Bước 10**: Trả về `id_datve` cho JavaScript xử lý tiếp (dòng 168)

6. **`confirm_payment`** - Xác nhận thanh toán QR (dòng 171-188):
   - Cập nhật trạng thái đơn từ PENDING → PAID
   - Gọi hàm `sendTicketEmail()` để gửi email xác nhận

#### Phần 2: Load dữ liệu ban đầu (dòng 192-308)

1. **Lấy thông tin phim** (dòng 199-211):
   - Query phim dựa trên `$_GET['id']`
   - Lấy `ten_phim` và `thoi_luong`

2. **Lấy suất chiếu hôm nay** (dòng 214-233):
   - Query `suatchieu` với `date_chieu = CURDATE()`
   - Sắp xếp theo giờ chiếu
   - Chọn suất chiếu đầu tiên làm mặc định

3. **Lấy danh sách ghế của phòng** (dòng 237-261):
   - Xác định phòng chiếu dựa trên suất chiếu đã chọn
   - Lấy tất cả ghế thuộc phòng đó, sắp xếp theo mã ghế

4. **Lấy ghế đã đặt** (dòng 264-286):
   - Xóa các bản ghi giữ ghế đã hết hạn
   - Lấy ghế đã được đặt (từ `chitietve` JOIN `datve` với trạng thái PAID hoặc PENDING)

5. **Lấy ghế đang giữ** (dòng 289-308):
   - Phân biệt ghế do user hiện tại giữ (`myHoldingSeats`) và do người khác giữ (`otherHoldingSeats`)

#### Phần 3: Giao diện HTML (dòng 310-1294)

Giao diện chia làm 3 trang (page) được ẩn/hiện bằng CSS class `active`:

- **`#seatPage`**: Chọn ghế - hiển thị sơ đồ ghế, màn hình, chú thích
- **`#comboPage`**: Chọn combo bắp nước
- **`#paymentPage`**: Thanh toán - hiển thị thông tin đơn hàng, chọn phương thức thanh toán

**Các thành phần giao diện chính**:

- **Screen**: Màn hình chiếu dạng cong (CSS arc) với hiệu ứng phát sáng
- **Seat Map**: Sơ đồ ghế dạng lưới 10 cột, mỗi ghế là một ô vuông
- **Legend**: Chú thích màu sắc cho các loại ghế
- **Summary Card**: Thông tin tóm tắt đơn hàng
- **Combo Cards**: Hiển thị các combo dạng card với hình ảnh
- **Payment Methods**: Lựa chọn phương thức thanh toán (VNPAY, QR Banking)
- **Timer**: Đếm ngược thời gian giữ ghế (4 phút)

### 6.3 File JavaScript: `Pages/buyticket.js`

**Mô tả**: File JavaScript xử lý tất cả tương tác người dùng trong quá trình đặt vé.

#### Các biến toàn cục (dòng 1-29)

```javascript
let selectedSeats = []; // Mảng lưu mã ghế (VD: ["A01", "A02"])
let selectedSeatIds = []; // Mảng lưu ID ghế (số nguyên)
let seatPrices = {}; // Object lưu giá từng ghế
let comboTotal = 0; // Tổng tiền combo
let comboName = ""; // Tên combo đã chọn
let discount = 0; // Số tiền giảm giá
let selectedPaymentMethod = null; // Phương thức thanh toán
let seatMoney = 0; // Tổng tiền ghế
```

#### Hàm `createSeatMap()` (dòng 39-115)

**Mô tả**: Tạo sơ đồ ghế động từ dữ liệu server gửi xuống.

**Quy trình**:

1. Xóa sơ đồ cũ
2. Nhóm ghế theo hàng (ký tự đầu của mã ghế, VD: A01 → hàng A)
3. Sắp xếp các hàng theo thứ tự alphabet
4. Với mỗi hàng, sắp xếp ghế theo số thứ tự
5. Tạo element DIV cho mỗi ghế với:
   - CSS class tương ứng loại ghế (normal, vip, couple)
   - CSS class cho trạng thái (booked, holding, selected)
   - Gán giá ghế vào object `seatPrices`
   - Thêm event listener click (nếu ghế không bị chiếm)
6. Thêm vào DOM

#### Hàm `selectSeat()` (dòng 137-175)

**Mô tả**: Xử lý khi người dùng click chọn/bỏ chọn ghế.

**Quy trình**:

1. Kiểm tra ghế không phải booked hoặc holding
2. Xác định action: `hold` (giữ) hoặc `release` (thả)
3. Gửi AJAX request đến `buyticket.php` với action tương ứng
4. Nếu thành công:
   - Chọn: Thêm class "selected", thêm vào mảng `selectedSeats` và `selectedSeatIds`
   - Bỏ chọn: Remove class "selected", xóa khỏi mảng
5. Cập nhật UI và Legend
6. Nếu thất bại (ghế đã bán), hiển thị thông báo và reload trang

#### Hàm `updateUI()` (dòng 177-183)

**Mô tả**: Cập nhật giao diện hiển thị ghế đã chọn và tổng tiền.

- Cập nhật danh sách ghế đã chọn
- Tính tổng tiền ghế: duyệt mảng `selectedSeats`, cộng giá từ `seatPrices`
- Hiển thị tổng tiền

#### Hàm `refreshHoldOnServer()` (dòng 186-204)

**Mô tả**: Gia hạn thời gian giữ ghế trên server.

- Gửi AJAX request với action `refresh_hold`
- Nếu thất bại (hết hạn), thông báo và reload trang
- Được gọi khi chuyển bước (Seat → Combo → Payment)

#### Hàm `goCombo()` (dòng 206-218)

**Mô tả**: Chuyển từ bước chọn ghế sang bước chọn combo.

- Kiểm tra đã chọn ghế chưa
- Gia hạn giữ ghế
- Ẩn trang ghế, hiện trang combo

#### Hàm `addCombo()` (dòng 220-224)

**Mô tả**: Thêm combo vào đơn hàng.

- Cộng dồn giá combo vào `comboTotal`
- Cập nhật hiển thị

#### Hàm `goPayment()` (dòng 226-256)

**Mô tả**: Chuyển từ bước chọn combo sang bước thanh toán.

- Gia hạn giữ ghế
- Ẩn trang combo, hiện trang thanh toán
- Hiển thị thông tin đơn hàng:
  - Số lượng ghế, danh sách ghế
  - Tiền ghế, tiền combo, giảm giá
  - Thông tin phim, phòng, suất chiếu

#### Hàm `applyDiscount()` (dòng 263-270)

**Mô tả**: Áp dụng mã giảm giá.

- Mã "G8Cenima" giảm 20,000đ
- Cập nhật hiển thị giảm giá và tổng tiền

#### Hàm `checkout()` (dòng 273-313)

**Mô tả**: Xử lý thanh toán - gửi dữ liệu đơn hàng lên server.

**Quy trình**:

1. Kiểm tra đã chọn ghế
2. Tạo FormData với các tham số: action, suat_id, combo_money, discount, payment_method
3. Gửi AJAX request đến `buyticket.php`
4. Xử lý kết quả:
   - VNPAY: Chuyển hướng đến `payment/vnpay_payment.php?id_datve=X`
   - QR_BANKING: Gọi `showQRCode()` để hiển thị mã QR
   - Khác: Chuyển đến trang chủ

#### Hàm `showQRCode()` (dòng 315-338)

**Mô tả**: Hiển thị mã QR cho thanh toán chuyển khoản.

- Hiển thị div QR code
- Điền nội dung chuyển khoản (mã đơn hàng)
- Tạo mã QR từ API: `https://api.qrserver.com/v1/create-qr-code/`
- Đổi nút thanh toán thành "TÔI ĐÃ THANH TOÁN"
- Gán sự kiện `confirmPayment()` cho nút

#### Hàm `confirmPayment()` (dòng 340-361)

**Mô tả**: Xác nhận đã chuyển khoản thành công.

- Gửi AJAX với action `confirm_payment`
- Server cập nhật trạng thái PAID và gửi email
- Chuyển hướng đến trang `ticket_success.php`

#### Hàm `goBack()` (dòng 363-371)

**Mô tả**: Quay lại bước trước.

- Từ Payment → Combo
- Từ Combo → Seat

#### Timer (dòng 373-385)

```javascript
let timeLeft = 240; // 4 phút
const timerInterval = setInterval(() => {
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;
  timerElement.textContent = `${minutes}:${seconds}`;
  if (timeLeft <= 0) {
    clearInterval(timerInterval);
    alert("Hết thời gian giữ ghế");
    window.location.reload();
  }
  timeLeft -= 1;
}, 1000);
```

- Đếm ngược từ 240 giây (4 phút)
- Hiển thị định dạng MM:SS
- Khi hết giờ, reload trang để giải phóng ghế

#### Xử lý phương thức thanh toán (dòng 422-483)

- Click vào method để chọn (active)
- Nếu chọn QR_BANKING: hiển thị danh sách ngân hàng
- Nếu chọn VNPAY: ẩn QR details
- Click lần nữa để bỏ chọn
- Nút thanh toán: kiểm tra đã chọn phương thức chưa, nếu QR thì kiểm tra đã chọn ngân hàng

---

## 7. Chức Năng Thanh Toán

### 7.1 Tổng quan

Hệ thống hỗ trợ 2 phương thức thanh toán:

1. **VNPAY**: Cổng thanh toán điện tử qua VNPAY
2. **QR Banking**: Chuyển khoản ngân hàng qua mã QR

### 7.2 Cấu hình VNPAY (`config/vnpay.php`)

```php
define('VNPAY_TMN_CODE', 'YOUR_TMN_CODE');
define('VNPAY_HASH_SECRET', 'YOUR_HASH_SECRET');
define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
define('VNPAY_RETURN_URL', 'http://localhost/WebBooking/payment/vnpay_return.php');
```

### 7.3 Tạo thanh toán VNPAY (`payment/vnpay_payment.php`)

**Mô tả**: Tạo yêu cầu thanh toán và chuyển hướng đến cổng VNPAY.

**Quy trình**:

1. Nhận `id_datve` từ query string
2. Truy vấn thông tin đơn hàng từ bảng `datve`
3. Xây dựng các tham số thanh toán:
   - `vnp_TmnCode`: Mã website tại VNPAY
   - `vnp_Amount`: Số tiền (nhân 100 - VNPAY yêu cầu)
   - `vnp_Command`: `pay`
   - `vnp_CreateDate`: Ngày giờ tạo
   - `vnp_CurrCode`: `VND`
   - `vnp_IpAddr`: IP người dùng
   - `vnp_Locale`: `vn`
   - `vnp_OrderInfo`: Mô tả đơn hàng
   - `vnp_OrderType`: Loại hàng hóa
   - `vnp_ReturnUrl`: URL nhận kết quả
   - `vnp_TxnRef`: Mã tham chiếu (mã giao dịch)
4. Tạo chuỗi hash để xác thực
5. Chuyển hướng đến URL thanh toán VNPAY

### 7.4 Xử lý kết quả VNPAY (`payment/vnpay_return.php`)

**Mô tả**: Nhận và xử lý kết quả thanh toán từ VNPAY.

**Quy trình**:

1. Nhận các tham số trả về từ VNPAY (`vnp_ResponseCode`, `vnp_TransactionStatus`, `vnp_TxnRef`, `vnp_SecureHash`)
2. Kiểm tra chữ ký hash để xác thực dữ liệu
3. Nếu thanh toán thành công (`vnp_ResponseCode = '00'`):
   - Cập nhật trạng thái `datve` thành PAID
   - Gửi email xác nhận vé
   - Chuyển hướng đến `payment_success.php`
4. Nếu thất bại:
   - Cập nhật trạng thái thành EXPIRED
   - Chuyển hướng đến trang thông báo lỗi

### 7.5 Thanh toán QR Banking

**Mô tả**: Cho phép người dùng chuyển khoản qua QR code.

**Hỗ trợ các ngân hàng**:

- MB Bank
- Techcombank
- Vietcombank
- VNPAY

**Quy trình**:

1. Người dùng chọn QR Banking và chọn ngân hàng
2. Hệ thống tạo mã QR chứa thông tin chuyển khoản
3. Người dùng quét mã và chuyển tiền
4. Người dùng click "TÔI ĐÃ THANH TOÁN"
5. Hệ thống xác nhận và gửi email vé

---

## 8. Module Quản Trị Admin

### 8.1 Dashboard (`admin/admin.php`)

**Mô tả**: Trang tổng quan hiển thị thống kê chính.

**Các thống kê**:

- **Tổng vé đã bán**: Đếm số bản ghi trong `chitietve` với trạng thái PAID
- **Doanh thu**: Tổng `tong_tien` từ các đơn PAID
- **Tổng khách hàng**: Số bản ghi trong bảng `nguoidung`

**Lịch sử giao dịch**:

- Hiển thị bảng giao dịch với phân trang (20 giao dịch/trang)
- Mỗi dòng: Mã đơn, Tên khách hàng, Tên phim, Suất chiếu, Danh sách ghế, Tổng tiền, Trạng thái, Thời gian đặt
- Tìm kiếm theo: ID đơn, tên khách hàng, tên phim
- Phân trang: Điều hướng trang đầu/cuối, số trang

**Xử lý phân trang**:

```php
$limit = 20;  // Số bản ghi mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);
$offset = ($page - 1) * $limit;
```

### 8.2 Sidebar (`admin/sidebar.php`)

**Mô tả**: Thanh điều hướng bên trái cho toàn bộ trang admin.

**Các mục menu**:

- **Dashboard** → `admin.php`
- **Quản lý Phim** → Danh sách phim (`phim.php`)
- **Quản lý Suất chiếu** → `suatchieu.php`
- **Quản lý Nhân viên** → `nhanvien.php`
- **Quản lý Khách hàng** → `khachhang.php`
- **Báo cáo** → `baocao.php`

### 8.3 Quản lý Phim (`admin/phim.php`, `themphim.php`, `suaphim.php`, `xoaphim.php`)

#### Danh sách phim (`phim.php`)

**Mô tả**: Hiển thị danh sách tất cả phim với các thao tác CRUD.

**Chức năng**:

- Bảng danh sách: ID, Tên phim, Thời lượng, Thể loại, Ngày khởi chiếu, Poster, Đánh giá
- Nút "Thêm phim" → `themphim.php`
- Nút "Sửa" trên mỗi dòng → `suaphim.php?id=X`
- Nút "Xóa" trên mỗi dòng → `xoaphim.php?id=X`
- Tìm kiếm phim theo tên

#### Thêm phim (`themphim.php`)

**Form nhập liệu**:

- Tên phim, Thời lượng (phút), Thể loại, Ngày khởi chiếu
- Mô tả, Đạo diễn, Diễn viên, Ngôn ngữ
- Đánh giá, Poster (file upload), Trailer (URL YouTube)
- Quốc gia

**Xử lý**:

- Validate dữ liệu đầu vào
- Upload file poster vào thư mục `poster/`
- INSERT vào bảng `phim`
- Thông báo thành công và chuyển về danh sách

#### Sửa phim (`suaphim.php`)

- Load dữ liệu phim từ ID
- Form tương tự thêm phim với dữ liệu đã điền sẵn
- UPDATE bảng `phim`
- Hỗ trợ thay đổi poster

#### Xóa phim (`xoaphim.php`)

- Xóa phim theo ID
- Kiểm tra ràng buộc khóa ngoại trước khi xóa
- Xóa file poster nếu có

### 8.4 Quản lý Suất chiếu (`admin/suatchieu.php`, `themsuatchieu.php`, `suasuatchieu.php`, `xoasuatchieu.php`)

#### Danh sách suất chiếu (`suatchieu.php`)

**Mô tả**: Quản lý lịch chiếu phim.

**Chức năng**:

- Bảng: ID, Phim, Phòng chiếu, Ngày chiếu, Giờ chiếu
- Thêm, Sửa, Xóa suất chiếu
- Lọc theo phim, ngày

#### Thêm suất chiếu (`themsuatchieu.php`)

**Form**: Chọn phim (dropdown), Chọn phòng, Ngày chiếu, Giờ chiếu
**Xử lý**: INSERT vào bảng `suatchieu`

### 8.5 Quản lý Nhân viên (`admin/nhanvien.php`, `themnhanvien.php`, `suanhanvien.php`, `xoanhanvien.php`)

#### Danh sách nhân viên (`nhanvien.php`)

**Bảng**: ID, Tên, Email, Số điện thoại, Chức vụ
**CRUD**: Thêm, Sửa, Xóa

### 8.6 Quản lý Khách hàng (`admin/khachhang.php`, `xoakhachhang.php`)

#### Danh sách khách hàng (`khachhang.php`)

**Bảng**: ID, Tên, Email, Số điện thoại, Ngày tạo
**Chức năng**: Xem danh sách, Xóa khách hàng

### 8.7 Báo cáo (`admin/baocao.php`)

**Mô tả**: Báo cáo doanh thu chi tiết.

**Chức năng**:

- Thống kê doanh thu theo ngày, tháng, năm
- Biểu đồ trực quan (có thể sử dụng Chart.js)
- Lọc theo khoảng thời gian
- Xuất báo cáo (CSV/Excel)

### 8.8 Giao diện Admin CSS (`admin/admin.css`)

**Mô tả**: Stylesheet cho toàn bộ giao diện admin.

**Các thành phần**:

- Layout: Sidebar + Main content
- Dashboard cards (blue, green, orange) với icon
- Bảng dữ liệu (table) responsive
- Form nhập liệu
- Phân trang (pagination)
- Status badges (PAID, PENDING, EXPIRED)
- Thanh tìm kiếm
- Responsive design

---

## 9. API & Tích Hợp Bên Ngoài

### 9.1 Google OAuth 2.0

**Mục đích**: Cho phép người dùng đăng nhập bằng tài khoản Google.

**Luồng xử lý**:

1. **Khởi tạo** (`config-google.php`):
   - Tạo Google Client với Client ID, Secret, Redirect URI
   - Yêu cầu scope: email, profile
2. **Đăng nhập** (`login.php`):
   - Hiển thị nút Google với URL xác thực
3. **Callback** (`login-google.php`):
   - Nhận code từ Google
   - Đổi code lấy access token
   - Lấy thông tin user
   - Xử lý đăng nhập/tạo tài khoản

### 9.2 VNPAY Payment Gateway

**Mục đích**: Xử lý thanh toán trực tuyến qua cổng VNPAY.

**Luồng xử lý**:

1. **Tạo yêu cầu** (`vnpay_payment.php`):
   - Xây dựng URL thanh toán với các tham số
   - Tạo chữ ký hash bảo mật
   - Chuyển hướng người dùng
2. **Xử lý kết quả** (`vnpay_return.php`):
   - Xác thực chữ ký
   - Cập nhật trạng thái đơn hàng
   - Gửi email xác nhận

### 9.3 QR Code API

**Mục đích**: Tạo mã QR cho thanh toán chuyển khoản.

**API sử dụng**: `https://api.qrserver.com/v1/create-qr-code/`
**Tham số**: `size=250x250&data=QR_PAYMENT_{amount}_{bank}`

### 9.4 PHPMailer

**Mục đích**: Gửi email xác nhận vé.

**Cấu hình** (trong `Connect/sendMail.php`):

- SMTP server (Gmail, hoặc mail server riêng)
- SMTP port, authentication
- Email gửi đi
- Template email HTML

---

## 10. Các Chức Năng Khác

### 10.1 Slider phim (`Module/SliderMovies.php`)

**Mô tả**: Carousel hiển thị phim nổi bật trên trang chủ.

**Kỹ thuật**:

- JavaScript thuần (không thư viện)
- Tự động chuyển slide (setInterval)
- Điều khiển bằng nút prev/next
- Chấm tròn indicator
- CSS transitions cho hiệu ứng mượt

### 10.2 Modal Trailer (`Module/TrailerModal.php`)

**Mô tả**: Popup xem trailer phim từ YouTube.

**Kỹ thuật**:

- Modal overlay với background mờ
- Iframe YouTube nhúng
- Đóng modal khi click outside hoặc nút close
- CSS animation cho hiệu ứng mở/đóng

### 10.3 Gửi email (`Connect/sendMail.php`)

**Mô tả**: Gửi email xác nhận vé sau khi thanh toán.

**Nội dung email**:

- Thông tin phim: Tên phim, thời lượng
- Suất chiếu: Ngày, giờ, phòng
- Danh sách ghế
- Combo (nếu có)
- Tổng tiền
- Mã giao dịch
- Mã QR (tùy chọn)

### 10.4 Xử lý phiên giữ ghế

**Cơ chế**:

- Ghế được giữ tạm thời 4 phút (bảng `ghe_tam_giu`)
- Tự động giải phóng khi hết hạn (cron job hoặc kiểm tra khi load trang)
- Gia hạn khi người dùng chuyển bước
- Ngăn chặn xung đột: 2 người không thể giữ cùng 1 ghế

### 10.5 Tìm kiếm & Phân trang

**Tìm kiếm**:

- Admin: Tìm kiếm giao dịch theo ID, tên khách hàng, tên phim
- Public: Tìm kiếm phim theo tên

**Phân trang**:

- Admin dashboard: 20 giao dịch/trang
- Danh sách phim: Tùy chỉnh số lượng
- Hiển thị: Trang đầu, Trang cuối, số trang, tổng số bản ghi

---

## 11. Kiến Trúc Hệ Thống & Luồng Xử Lý

### 11.1 Mô hình kiến trúc

Hệ thống sử dụng mô hình **Client-Server** truyền thống với kiến trúc **3 lớp**:

```
┌─────────────────┐     HTTP/HTTPS     ┌──────────────────┐     SQL     ┌──────────────┐
│   Client        │ ◄─────────────────► │   Server (PHP)   │ ◄─────────► │   MySQL DB   │
│   (Browser)     │                     │   (XAMPP/Apache) │             │   (group8)   │
└─────────────────┘                     └──────────────────┘             └──────────────┘
```

- **Lớp Presentation**: HTML, CSS, JavaScript (trong `Pages/`, `Module/`)
- **Lớp Business Logic**: PHP xử lý nghiệp vụ (trong `Pages/`, `admin/`, `LoginAndSign-up/`)
- **Lớp Data Access**: MySQL queries qua mysqli

### 11.2 Luồng xử lý chính - Đặt vé

```
1. Người dùng chọn phim → Click "Mua Vé"
   ↓
2. Chuyển đến buyticket.php?id={movie_id}
   ↓
3. Server load: Thông tin phim, suất chiếu hôm nay, sơ đồ ghế, ghế đã đặt
   ↓
4. BƯỚC 1: Người dùng chọn ghế
   ↓
5. Mỗi lần click → AJAX hold/release → Cập nhật ghe_tam_giu
   ↓
6. BƯỚC 2: Người dùng chọn Combo
   ↓
7. AJAX refresh_hold → Gia hạn giữ ghế thêm 4 phút
   ↓
8. BƯỚC 3: Người dùng thanh toán
   ↓
9. AJAX checkout → Tạo đơn trong datve (PENDING) + chitietve
   ↓
10a. VNPAY: Chuyển hướng → VNPAY → Return → Cập nhật PAID → Gửi email
10b. QR: Hiển thị QR → Người dùng chuyển khoản → Xác nhận → Cập nhật PAID → Gửi email
```

### 11.3 Luồng xử lý AJAX

Tất cả các tương tác trong quá trình đặt vé đều sử dụng **AJAX** (Asynchronous JavaScript and XML):

```
JavaScript (buyticket.js)                PHP (buyticket.php)
─────────────────────                    ────────────────────
fetch("buyticket.php", {                 if (isset($_POST['action'])) {
  method: "POST",                           switch ($action) {
  body: "action=hold&..."                       case 'hold': ...
})                                              case 'release': ...
  .then(res => res.json())                      case 'checkout': ...
  .then(data => {                            }
    // Xử lý kết quả                       echo json_encode([...]);
  })                                      }
```

### 11.4 Luồng xử lý Admin

```
Admin login → admin/admin.php (Dashboard)
  ↓
├── Thống kê: Vé bán, doanh thu, khách hàng
├── Lịch sử giao dịch (phân trang, tìm kiếm)
├── Quản lý Phim → CRUD
├── Quản lý Suất chiếu → CRUD
├── Quản lý Nhân viên → CRUD
├── Quản lý Khách hàng → Xem, Xóa
└── Báo cáo → Thống kê doanh thu
```

### 11.5 Quản lý Session

Session được sử dụng để duy trì trạng thái đăng nhập:

```php
session_start(); // Đầu mỗi trang

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Cấu trúc session
$_SESSION['user'] = [
    'id' => $userId,
    'name' => $userName,
    'email' => $userEmail,
    'role' => 'user', // hoặc 'admin'
    'type' => 'local' // hoặc 'google'
];
```

### 11.6 Bảo mật (Các vấn đề hiện tại và cần cải thiện)

**Đã triển khai**:

- Sử dụng prepared statements (`mysqli_prepare`, `bind_param`) để chống SQL Injection ở hầu hết các truy vấn
- `htmlspecialchars()` khi hiển thị dữ liệu người dùng (chống XSS)
- Kiểm tra session trước các thao tác nhạy cảm
- timeout cho phiên giữ ghế

**Cần cải thiện**:

- Mật khẩu lưu plain text (cần `password_hash()` và `password_verify()`)
- CSRF protection chưa có (cần token)
- Chưa có rate limiting cho đăng nhập
- File upload chưa kiểm tra MIME type kỹ
- Chưa có bảo vệ path traversal

---

## 12. Hướng Dẫn Triển Khai & Cài Đặt

### 12.1 Yêu cầu hệ thống

- **Web Server**: Apache (XAMPP, WAMP, LAMP)
- **PHP**: 8.0 hoặc cao hơn
- **MySQL**: 5.7 hoặc cao hơn
- **Composer**: Để cài đặt PHPMailer
- **Trình duyệt**: Chrome, Firefox, Edge (hỗ trợ ES6+)

### 12.2 Cài đặt

1. **Clone hoặc tải mã nguồn**:

```bash
git clone https://github.com/duytan055/WebBooking.git
```

2. **Copy vào thư mục web server**:
   - XAMPP: `C:\xampp\htdocs\WebBooking`

3. **Import cơ sở dữ liệu**:
   - Mở phpMyAdmin: `http://localhost/phpmyadmin`
   - Tạo database mới tên `group8`
   - Import file `Group8.sql`

4. **Cấu hình kết nối DB**:
   - Mở `Connect/connecDB.php`
   - Điều chỉnh thông số (nếu cần):

   ```php
   $host = 'localhost';
   $user = 'root';
   $pass = '';
   $db = 'group8';
   ```

5. **Cài đặt Composer dependencies**:

```bash
cd C:\xampp\htdocs\WebBooking
composer install
```

(Cài đặt PHPMailer và Google API Client)

6. **Cấu hình Google OAuth** (`API/config-google.php`):
   - Tạo project trên Google Cloud Console
   - Kích hoạt Google OAuth 2.0
   - Điền Client ID và Client Secret

7. **Cấu hình VNPAY** (`config/vnpay.php`):
   - Đăng ký tài khoản VNPAY merchant
   - Điền TMN Code và Hash Secret

8. **Cấu hình email** (`Connect/sendMail.php`):
   - Cấu hình SMTP server
   - Điền email và mật khẩu

### 12.3 Chạy ứng dụng

- Mở trình duyệt: `http://localhost/WebBooking/Pages/trangChu.php`
- Admin: `http://localhost/WebBooking/admin/admin.php`

### 12.4 Tài khoản mặc định

- **Admin**: username = `admin`, password = `admin`
- **User**: Đăng ký tại trang Sign-up

---

## Kết luận

Dự án **WebBooking** là một hệ thống đặt vé xem phim trực tuyến hoàn chỉnh, bao gồm:

- **Giao diện người dùng** thân thiện với quy trình đặt vé 3 bước
- **Hệ thống giữ ghế thông minh** với cơ chế timeout và tự động giải phóng
- **Đa dạng phương thức thanh toán**: VNPAY, QR Banking
- **Giao diện quản trị** mạnh mẽ với CRUD đầy đủ và thống kê chi tiết
- **Xác thực an toàn** với hỗ trợ đăng nhập Google
- **Tự động gửi email xác nhận** sau thanh toán

Hệ thống được xây dựng bằng PHP thuần với kiến trúc đơn giản, dễ hiểu và dễ mở rộng. Các module được tổ chức rõ ràng, tách biệt giữa xử lý logic và hiển thị giao diện. Mặc dù còn một số hạn chế về bảo mật cần cải thiện, nhưng nhìn chung đây là một dự án hoàn chỉnh, đáp ứng được các yêu cầu cơ bản của một hệ thống đặt vé xem phim trực tuyến.

---

_Tài liệu được tạo ngày: 28/06/2026_
