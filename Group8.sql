CREATE DATABASE demosqlWeb;

USE demosqlWeb;

-- admin
CREATE TABLE adminn (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    ten_dang_nhap VARCHAR(100),
    mat_khau VARCHAR(100)
);

-- nhanvien
CREATE TABLE nhanvien (
    id_nhanvien INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100),
    mat_khau VARCHAR(100),
    sdt VARCHAR(20)
);

-- nguoidung
CREATE TABLE nguoidung (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    cccd VARCHAR(20),
    sdt VARCHAR(20),
    ngay_sinh DATE,
    mat_khau VARCHAR(100)
);

-- dotuoi
CREATE TABLE dotuoi (
    id_do_tuoi INT AUTO_INCREMENT PRIMARY KEY,
    do_tuoi VARCHAR(10),
    mo_ta VARCHAR(50)
);

-- daodien
CREATE TABLE daodien (
    id_daodien INT AUTO_INCREMENT PRIMARY KEY,
    ten_dao_dien VARCHAR(150)
);

-- dienvien
CREATE TABLE dienvien (
    id_dienvien INT AUTO_INCREMENT PRIMARY KEY,
    ten_dien_vien VARCHAR(150)
);

-- phim
CREATE TABLE phim (
    id_phim INT AUTO_INCREMENT PRIMARY KEY,
    ten_phim VARCHAR(200),
    the_loai VARCHAR(100),
    thoi_luong INT,
    ngay_khoi_chieu DATE NULL,
    ngay_ket_thuc DATE NULL,
    poster VARCHAR(255),
    hinh_anh VARCHAR(255),
    trailer_phim VARCHAR(255),
    mo_ta TEXT,
    trang_thai ENUM(
        'sap_chieu',
        'dang_chieu',
        'tam_ngung',
        'ngung_chieu'
    ) DEFAULT 'sap_chieu',
    id_do_tuoi INT,
    FOREIGN KEY (id_do_tuoi) REFERENCES dotuoi (id_do_tuoi)
);

-- phim_dienvien
CREATE TABLE phim_dienvien (
    id_phim INT,
    id_dienvien INT,
    PRIMARY KEY (id_phim, id_dienvien),
    FOREIGN KEY (id_phim) REFERENCES phim (id_phim),
    FOREIGN KEY (id_dienvien) REFERENCES dienvien (id_dienvien)
);

-- phim_daodien
CREATE TABLE phim_daodien (
    id_phim INT,
    id_daodien INT,
    PRIMARY KEY (id_phim, id_daodien),
    FOREIGN KEY (id_phim) REFERENCES phim (id_phim),
    FOREIGN KEY (id_daodien) REFERENCES daodien (id_daodien)
);

-- phongchieu
CREATE TABLE phongchieu (
    id_phong INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong VARCHAR(50),
    ten_phong VARCHAR(50),
    loai_phong VARCHAR(50)
);

-- suatchieu
CREATE TABLE suatchieu (
    id_suat INT AUTO_INCREMENT PRIMARY KEY,
    id_phim INT,
    id_phong INT,
    date_chieu DATE,
    thoi_gian TIME,
    FOREIGN KEY (id_phim) REFERENCES phim (id_phim),
    FOREIGN KEY (id_phong) REFERENCES phongchieu (id_phong)
);

-- ghe
CREATE TABLE ghe (
    id_ghe INT AUTO_INCREMENT PRIMARY KEY,
    ma_ghe VARCHAR(10),
    id_phong INT,
    loai_ghe VARCHAR(20),
    FOREIGN KEY (id_phong) REFERENCES phongchieu (id_phong)
);

-- khuyenmai
CREATE TABLE khuyenmai (
    id_km INT AUTO_INCREMENT PRIMARY KEY,
    ma_km VARCHAR(20) UNIQUE,
    ten_khuyenmai VARCHAR(100),
    noi_dung_km TEXT,
    anh_khuyen_mai VARCHAR(200),
    giam_gia INT,
    ngay_bat_dau DATETIME,
    ngay_ket_thuc DATETIME
);

-- datve
CREATE TABLE datve (
    id_datve INT AUTO_INCREMENT PRIMARY KEY,
    id_nhanvien INT,
    id_user INT,
    id_suat INT,
    id_km INT,
    thoi_gian_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
    tong_tien DECIMAL(10, 2),
    phuong_thuc_thanh_toan VARCHAR(100),
    ma_giao_dich VARCHAR(50),
    ten_nguoi_dat VARCHAR(100),
    so_dien_thoai VARCHAR(20),
    giam_gia INT,
    trang_thai VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CHECK (tong_tien >= 0),
    FOREIGN KEY (id_user) REFERENCES nguoidung (id_user),
    FOREIGN KEY (id_suat) REFERENCES suatchieu (id_suat),
    FOREIGN KEY (id_km) REFERENCES khuyenmai (id_km),
    FOREIGN KEY (id_nhanvien) REFERENCES nhanvien (id_nhanvien)
);

-- chitietve
CREATE TABLE chitietve (
    id_ve INT AUTO_INCREMENT PRIMARY KEY,
    id_datve INT,
    id_suat INT,
    id_ghe INT,
    gia_ve DECIMAL(10, 2),
    trang_thai VARCHAR(100),
    UNIQUE (id_suat, id_ghe),
    FOREIGN KEY (id_datve) REFERENCES datve (id_datve),
    FOREIGN KEY (id_suat) REFERENCES suatchieu (id_suat),
    FOREIGN KEY (id_ghe) REFERENCES ghe (id_ghe)
);

-- chinhsach
CREATE TABLE chinhsach (
    id_chinhsach INT AUTO_INCREMENT PRIMARY KEY,
    tieu_de VARCHAR(255),
    loai VARCHAR(50),
    noi_dung LONGTEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--forgot password OTP table
CREATE TABLE IF NOT EXISTS password_reset_otp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    otp VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (email) REFERENCES nguoidung (email) ON DELETE CASCADE
);

--giữ ghế tạm thời
CREATE TABLE IF NOT EXISTS ghe_tam_giu (
    id_suat INT,
    id_ghe INT,
    id_user INT,
    expires_at DATETIME,
    PRIMARY KEY (id_suat, id_ghe)
);

CREATE EVENT cleanup_hold_seat
ON SCHEDULE EVERY 1 MINUTE
DO
  DELETE FROM ghe_tam_giu WHERE expires_at < NOW();

ALTER TABLE ghe_tam_giu ADD UNIQUE KEY uniq_seat (id_suat, id_ghe);