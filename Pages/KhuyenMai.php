<?php
// 1. Khởi tạo mảng dữ liệu khuyến mãi
$promotions = [
    [
        "title" => "ƯU ĐÃI U22",
        "image" => "https://starlight.vn/Areas/Admin/Content/Fileuploads/images/Poster2024/u22-fb.jpg",
        "price" => "45.000đ / vé",
        "desc"  => "Đồng giá siêu hấp dẫn dành cho thành viên U22",
        "url"   => "u22.html"
    ],
    [
        "title" => "HAPPY FAMILY KIDS DAY",
        "image" => "https://starlight.vn/Areas/Admin/Content/Fileuploads/images/Poster2024/u22-fb.jpg",
        "price" => "79k - 119k",
        "desc"  => "Thứ 6 hàng tuần",
        "url"   => "#"
    ],
    [
        "title" => "THỨ 3 PHIM VIỆT",
        "image" => "https://starlight.vn/Areas/Admin/Content/Fileuploads/images/Poster2024/u22-fb.jpg",
        "price" => "45.000đ",
        "desc"  => "Đồng giá tất cả phim Việt",
        "url"   => "#"
    ],
    [
        "title" => "GIẢM GIÁ 8/3",
        "image" => "https://starlight.vn/Areas/Admin/Content/Fileuploads/images/Poster2024/u22-fb.jpg",
        "price" => "45.000đ / vé",
        "desc"  => "Ưu đãi đặc biệt ngày Quốc tế Phụ nữ",
        "url"   => "#"
    ],
    [
        "title" => "ƯU ĐÃI 4/3",
        "image" => "https://starlight.vn/Areas/Admin/Content/Fileuploads/images/Poster2024/u22-fb.jpg",
        "price" => "50.000đ",
        "desc"  => "Áp dụng cho tất cả suất chiếu",
        "url"   => "#"
    ],
    [
        "title" => "ƯU ĐÃI 7/6",
        "image" => "https://starlight.vn/Areas/Admin/Content/Fileuploads/images/Poster2024/u22-fb.jpg",
        "price" => "55.000đ",
        "desc"  => "Giảm giá đặc biệt cuối tuần",
        "url"   => "#"
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khuyến mãi - Cinema</title>
    <link rel="stylesheet" href="style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="header">
    <div class="social">
        <i class="fa-brands fa-facebook"></i>
        <i class="fa-brands fa-linkedin"></i>
        <i class="fa-brands fa-x-twitter"></i>
    </div>

    <nav class="menu">
        <a href="#">Trang chủ</a>
        <a href="#">Phim</a>
        <a href="#">Góc điện ảnh</a>
        <a href="#" class="active">Khuyến mãi</a>
        <a href="#">Liên hệ</a>
    </nav>

    <div class="auth">
        <button class="login">Đăng Nhập</button>
        <button class="register">Đăng Ký</button>
    </div>
</header>

<section class="title">
    <h2>Khuyến mãi</h2>
</section>

<section class="promo-container">
    <ul class="promo-list">
        <?php foreach ($promotions as $item): ?>
            <a href="<?php echo $item['url']; ?>" style="text-decoration: none; color: inherit;">
                <li class="promo-card">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                    <h3><?php echo $item['title']; ?></h3>
                    <p class="price"><?php echo $item['price']; ?></p>
                    <p><?php echo $item['desc']; ?></p>
                </li>
            </a>
        <?php endforeach; ?>
    </ul>
</section>

</body>
</html>