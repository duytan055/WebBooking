-- =====================================================
-- FILE DU LIEU TEST CHO TRANG TOP PHIM
-- =====================================================
-- File nay chua cac cau lenh INSERT de tao du lieu dat ve test
-- Muc dich: Test trang TopPhim.php hien thi bang xep hang phim theo so ve ban
--
-- Luu y:
-- - Chi chay file nay sau khi da co du lieu co ban (phim, phong chieu, suat chieu, ghe, user)
-- - File nay TAO du lieu dat ve va chi tiet ve
-- - KHONG tu dong chay, can import thu cong vao database
-- =====================================================

USE demosqlWeb;

-- =====================================================
-- PHAN 1: DU LIEU DAT VE (DATVE)
-- =====================================================

-- Phim #2: MOT BO PHIM CUA LEE CRONIN: XAC UOP (Top 1 - 50 ve)
INSERT INTO
    datve (
        id_datve,
        id_nhanvien,
        id_user,
        id_suat,
        id_km,
        thoi_gian_dat,
        tong_tien,
        phuong_thuc_thanh_toan,
        ma_giao_dich,
        ten_nguoi_dat,
        so_dien_thoai,
        giam_gia,
        trang_thai,
        created_at
    )
VALUES (
        1,
        NULL,
        1,
        2,
        NULL,
        '2026-04-16 09:30:00',
        200000.00,
        'MoMo',
        'MM001',
        'Nguyen Van A',
        '912345678',
        0,
        'Da thanh toan',
        '2026-04-16 09:30:00'
    ),
    (
        2,
        NULL,
        2,
        2,
        NULL,
        '2026-04-16 09:35:00',
        400000.00,
        'VNPay',
        'VNP001',
        'Tran Thi Ngoc',
        '987654321',
        0,
        'Da thanh toan',
        '2026-04-16 09:35:00'
    ),
    (
        3,
        NULL,
        3,
        2,
        NULL,
        '2026-04-16 09:40:00',
        300000.00,
        'Banking',
        'BNK001',
        'Le Minh Hieu',
        '905123456',
        0,
        'Da thanh toan',
        '2026-04-16 09:40:00'
    ),
    (
        4,
        NULL,
        4,
        2,
        NULL,
        '2026-04-16 09:45:00',
        250000.00,
        'MoMo',
        'MM002',
        'Pham Thuy Duong',
        '345678901',
        0,
        'Da thanh toan',
        '2026-04-16 09:45:00'
    ),
    (
        5,
        NULL,
        5,
        2,
        NULL,
        '2026-04-16 09:50:00',
        350000.00,
        'VNPay',
        'VNP002',
        'Hoang Quan',
        '919887766',
        0,
        'Da thanh toan',
        '2026-04-16 09:50:00'
    ),
    (
        6,
        NULL,
        6,
        2,
        NULL,
        '2026-04-16 10:00:00',
        180000.00,
        'MoMo',
        'MM003',
        'Vu Thi Lan',
        '977112233',
        0,
        'Da thanh toan',
        '2026-04-16 10:00:00'
    ),
    (
        7,
        NULL,
        7,
        2,
        NULL,
        '2026-04-16 10:10:00',
        220000.00,
        'Banking',
        'BNK002',
        'Dang Khoa',
        '933445566',
        0,
        'Da thanh toan',
        '2026-04-16 10:10:00'
    ),
    (
        8,
        NULL,
        8,
        2,
        NULL,
        '2026-04-16 10:20:00',
        280000.00,
        'VNPay',
        'VNP003',
        'Bich Ngoc',
        '966778899',
        0,
        'Da thanh toan',
        '2026-04-16 10:20:00'
    ),
    (
        9,
        NULL,
        9,
        2,
        NULL,
        '2026-04-16 10:30:00',
        320000.00,
        'MoMo',
        'MM004',
        'Cuong Nguyen',
        '988001122',
        0,
        'Da thanh toan',
        '2026-04-16 10:30:00'
    ),
    (
        10,
        NULL,
        10,
        2,
        NULL,
        '2026-04-16 10:40:00',
        240000.00,
        'Banking',
        'BNK003',
        'Tuan Anh',
        '944556677',
        0,
        'Da thanh toan',
        '2026-04-16 10:40:00'
    );

-- Phim #3: BAY TIEN (Top 2 - 40 ve)
INSERT INTO
    datve (
        id_datve,
        id_nhanvien,
        id_user,
        id_suat,
        id_km,
        thoi_gian_dat,
        tong_tien,
        phuong_thuc_thanh_toan,
        ma_giao_dich,
        ten_nguoi_dat,
        so_dien_thoai,
        giam_gia,
        trang_thai,
        created_at
    )
VALUES (
        11,
        NULL,
        11,
        3,
        NULL,
        '2026-04-16 11:00:00',
        190000.00,
        'VNPay',
        'VNP004',
        'Mai Phuong',
        '915223344',
        0,
        'Da thanh toan',
        '2026-04-16 11:00:00'
    ),
    (
        12,
        NULL,
        12,
        3,
        NULL,
        '2026-04-16 11:10:00',
        210000.00,
        'MoMo',
        'MM005',
        'Son Tung',
        '909090909',
        0,
        'Da thanh toan',
        '2026-04-16 11:10:00'
    ),
    (
        13,
        NULL,
        13,
        3,
        NULL,
        '2026-04-16 11:20:00',
        270000.00,
        'Banking',
        'BNK004',
        'Thanh Huyen',
        '399112233',
        0,
        'Da thanh toan',
        '2026-04-16 11:20:00'
    ),
    (
        14,
        NULL,
        14,
        3,
        NULL,
        '2026-04-16 11:30:00',
        230000.00,
        'VNPay',
        'VNP005',
        'Minh Triet',
        '971234888',
        0,
        'Da thanh toan',
        '2026-04-16 11:30:00'
    ),
    (
        15,
        NULL,
        15,
        3,
        NULL,
        '2026-04-16 11:40:00',
        290000.00,
        'MoMo',
        'MM006',
        'Hoa Nang',
        '922334455',
        0,
        'Da thanh toan',
        '2026-04-16 11:40:00'
    ),
    (
        16,
        NULL,
        16,
        3,
        NULL,
        '2026-04-16 11:50:00',
        260000.00,
        'Banking',
        'BNK005',
        'Duc Thanh',
        '981556677',
        0,
        'Da thanh toan',
        '2026-04-16 11:50:00'
    ),
    (
        17,
        NULL,
        17,
        3,
        NULL,
        '2026-04-16 12:00:00',
        200000.00,
        'VNPay',
        'VNP006',
        'Yen Nhi',
        '902334411',
        0,
        'Da thanh toan',
        '2026-04-16 12:00:00'
    ),
    (
        18,
        NULL,
        18,
        3,
        NULL,
        '2026-04-16 12:10:00',
        250000.00,
        'MoMo',
        'MM007',
        'Quang Huy',
        '938112244',
        0,
        'Da thanh toan',
        '2026-04-16 12:10:00'
    );

-- Phim #7: HEN EM NGAY NHAT THUC (Top 3 - 35 ve)
INSERT INTO
    datve (
        id_datve,
        id_nhanvien,
        id_user,
        id_suat,
        id_km,
        thoi_gian_dat,
        tong_tien,
        phuong_thuc_thanh_toan,
        ma_giao_dich,
        ten_nguoi_dat,
        so_dien_thoai,
        giam_gia,
        trang_thai,
        created_at
    )
VALUES (
        19,
        NULL,
        19,
        7,
        NULL,
        '2026-04-16 12:30:00',
        180000.00,
        'Banking',
        'BNK006',
        'Thuy Linh',
        '961223344',
        0,
        'Da thanh toan',
        '2026-04-16 12:30:00'
    ),
    (
        20,
        NULL,
        20,
        7,
        NULL,
        '2026-04-16 12:40:00',
        220000.00,
        'VNPay',
        'VNP007',
        'Bao Long',
        '918445566',
        0,
        'Da thanh toan',
        '2026-04-16 12:40:00'
    ),
    (
        21,
        NULL,
        21,
        7,
        NULL,
        '2026-04-16 12:50:00',
        240000.00,
        'MoMo',
        'MM008',
        'Khanh Huyen',
        '355112288',
        0,
        'Da thanh toan',
        '2026-04-16 12:50:00'
    ),
    (
        22,
        NULL,
        22,
        7,
        NULL,
        '2026-04-16 13:00:00',
        280000.00,
        'Banking',
        'BNK007',
        'Viet Anh',
        '974556677',
        0,
        'Da thanh toan',
        '2026-04-16 13:00:00'
    ),
    (
        23,
        NULL,
        23,
        7,
        NULL,
        '2026-04-16 13:10:00',
        260000.00,
        'VNPay',
        'VNP008',
        'Minh Thu',
        '912445566',
        0,
        'Da thanh toan',
        '2026-04-16 13:10:00'
    ),
    (
        24,
        NULL,
        24,
        7,
        NULL,
        '2026-04-16 13:20:00',
        300000.00,
        'MoMo',
        'MM009',
        'Gia Huy',
        '903112233',
        0,
        'Da thanh toan',
        '2026-04-16 13:20:00'
    ),
    (
        25,
        NULL,
        25,
        7,
        NULL,
        '2026-04-16 13:30:00',
        210000.00,
        'Banking',
        'BNK008',
        'Kim Ngan',
        '969223344',
        0,
        'Da thanh toan',
        '2026-04-16 13:30:00'
    );

-- =====================================================
-- PHAN 2: CHI TIET VE (CHITIETVE)
-- =====================================================
-- Moi booking co 2-5 ve (ghe) khac nhau
-- Gia ve: Ghe thuong = 100000 VND, Ghe VIP = 150000 VND

-- Chi tiet ve cho Phim #2 (10 bookings x 5 ve = 50 ve)
INSERT INTO
    chitietve (
        id_ve,
        id_datve,
        id_suat,
        id_ghe,
        gia_ve,
        trang_thai
    )
VALUES
    -- Booking 1: 2 ve
    (
        1,
        1,
        2,
        51,
        100000.00,
        'Da thanh toan'
    ),
    (
        2,
        1,
        2,
        52,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 2: 4 ve
    (
        3,
        2,
        2,
        53,
        100000.00,
        'Da thanh toan'
    ),
    (
        4,
        2,
        2,
        54,
        100000.00,
        'Da thanh toan'
    ),
    (
        5,
        2,
        2,
        55,
        100000.00,
        'Da thanh toan'
    ),
    (
        6,
        2,
        2,
        56,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 3: 3 ve
    (
        7,
        3,
        2,
        57,
        100000.00,
        'Da thanh toan'
    ),
    (
        8,
        3,
        2,
        58,
        100000.00,
        'Da thanh toan'
    ),
    (
        9,
        3,
        2,
        59,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 4: 2 ve (1 VIP)
    (
        10,
        4,
        2,
        60,
        100000.00,
        'Da thanh toan'
    ),
    (
        11,
        4,
        2,
        101,
        150000.00,
        'Da thanh toan'
    ),
    -- Booking 5: 3 ve (1 VIP)
    (
        12,
        5,
        2,
        61,
        100000.00,
        'Da thanh toan'
    ),
    (
        13,
        5,
        2,
        62,
        100000.00,
        'Da thanh toan'
    ),
    (
        14,
        5,
        2,
        102,
        150000.00,
        'Da thanh toan'
    ),
    -- Booking 6: 2 ve
    (
        15,
        6,
        2,
        63,
        100000.00,
        'Da thanh toan'
    ),
    (
        16,
        6,
        2,
        64,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 7: 2 ve
    (
        17,
        7,
        2,
        65,
        100000.00,
        'Da thanh toan'
    ),
    (
        18,
        7,
        2,
        66,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 8: 3 ve
    (
        19,
        8,
        2,
        67,
        100000.00,
        'Da thanh toan'
    ),
    (
        20,
        8,
        2,
        68,
        100000.00,
        'Da thanh toan'
    ),
    (
        21,
        8,
        2,
        69,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 9: 3 ve
    (
        22,
        9,
        2,
        70,
        100000.00,
        'Da thanh toan'
    ),
    (
        23,
        9,
        2,
        71,
        100000.00,
        'Da thanh toan'
    ),
    (
        24,
        9,
        2,
        72,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 10: 2 ve
    (
        25,
        10,
        2,
        73,
        100000.00,
        'Da thanh toan'
    ),
    (
        26,
        10,
        2,
        74,
        100000.00,
        'Da thanh toan'
    );

-- Chi tiet ve cho Phim #3 (8 bookings x 5 ve = 40 ve)
INSERT INTO
    chitietve (
        id_ve,
        id_datve,
        id_suat,
        id_ghe,
        gia_ve,
        trang_thai
    )
VALUES
    -- Booking 11: 2 ve
    (
        27,
        11,
        3,
        111,
        100000.00,
        'Da thanh toan'
    ),
    (
        28,
        11,
        3,
        112,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 12: 2 ve
    (
        29,
        12,
        3,
        113,
        100000.00,
        'Da thanh toan'
    ),
    (
        30,
        12,
        3,
        114,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 13: 3 ve
    (
        31,
        13,
        3,
        115,
        100000.00,
        'Da thanh toan'
    ),
    (
        32,
        13,
        3,
        116,
        100000.00,
        'Da thanh toan'
    ),
    (
        33,
        13,
        3,
        117,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 14: 2 ve (1 VIP)
    (
        34,
        14,
        3,
        118,
        100000.00,
        'Da thanh toan'
    ),
    (
        35,
        14,
        3,
        151,
        150000.00,
        'Da thanh toan'
    ),
    -- Booking 15: 3 ve
    (
        36,
        15,
        3,
        119,
        100000.00,
        'Da thanh toan'
    ),
    (
        37,
        15,
        3,
        120,
        100000.00,
        'Da thanh toan'
    ),
    (
        38,
        15,
        3,
        121,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 16: 3 ve
    (
        39,
        16,
        3,
        122,
        100000.00,
        'Da thanh toan'
    ),
    (
        40,
        16,
        3,
        123,
        100000.00,
        'Da thanh toan'
    ),
    (
        41,
        16,
        3,
        124,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 17: 2 ve
    (
        42,
        17,
        3,
        125,
        100000.00,
        'Da thanh toan'
    ),
    (
        43,
        17,
        3,
        126,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 18: 3 ve
    (
        44,
        18,
        3,
        127,
        100000.00,
        'Da thanh toan'
    ),
    (
        45,
        18,
        3,
        128,
        100000.00,
        'Da thanh toan'
    ),
    (
        46,
        18,
        3,
        129,
        100000.00,
        'Da thanh toan'
    );

-- Chi tiet ve cho Phim #7 (7 bookings x 5 ve = 35 ve)
INSERT INTO
    chitietve (
        id_ve,
        id_datve,
        id_suat,
        id_ghe,
        gia_ve,
        trang_thai
    )
VALUES
    -- Booking 19: 2 ve
    (
        47,
        19,
        7,
        11,
        100000.00,
        'Da thanh toan'
    ),
    (
        48,
        19,
        7,
        12,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 20: 2 ve
    (
        49,
        20,
        7,
        13,
        100000.00,
        'Da thanh toan'
    ),
    (
        50,
        20,
        7,
        14,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 21: 2 ve (1 VIP)
    (
        51,
        21,
        7,
        15,
        100000.00,
        'Da thanh toan'
    ),
    (
        52,
        21,
        7,
        41,
        150000.00,
        'Da thanh toan'
    ),
    -- Booking 22: 3 ve
    (
        53,
        22,
        7,
        16,
        100000.00,
        'Da thanh toan'
    ),
    (
        54,
        22,
        7,
        17,
        100000.00,
        'Da thanh toan'
    ),
    (
        55,
        22,
        7,
        18,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 23: 3 ve
    (
        56,
        23,
        7,
        19,
        100000.00,
        'Da thanh toan'
    ),
    (
        57,
        23,
        7,
        20,
        100000.00,
        'Da thanh toan'
    ),
    (
        58,
        23,
        7,
        21,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 24: 3 ve
    (
        59,
        24,
        7,
        22,
        100000.00,
        'Da thanh toan'
    ),
    (
        60,
        24,
        7,
        23,
        100000.00,
        'Da thanh toan'
    ),
    (
        61,
        24,
        7,
        24,
        100000.00,
        'Da thanh toan'
    ),
    -- Booking 25: 2 ve
    (
        62,
        25,
        7,
        25,
        100000.00,
        'Da thanh toan'
    ),
    (
        63,
        25,
        7,
        26,
        100000.00,
        'Da thanh toan'
    );

-- =====================================================
-- KET THUC FILE TEST DATA
-- =====================================================
-- Tong ket:
-- - Phim #2 (XAC UOP): 50 ve - Top 1
-- - Phim #3 (BAY TIEN): 40 ve - Top 2
-- - Phim #7 (HEN EM NGAY NHAT THUC): 35 ve - Top 3
--
-- Cach su dung:
-- 1. Dam bao da co du lieu: phim, phongchieu, suatchieu, ghe, nguoidung
-- 2. Import file nay vao database: mysql -u root -p demosqlWeb < test_data_booking.sql
-- 3. Truy cap trang: http://localhost/WebBooking/Pages/TopPhim.php
-- 4. Kiem tra bang xep hang hien thi dung thu tu
-- =====================================================