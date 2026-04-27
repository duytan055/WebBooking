<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
        .box1 {
            position: sticky;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            width: 100%;
            height: auto;
            min-height: 70px;
            top: 0;
            z-index: 1000;
        }

        .box1_icon i {
            font-size: 20px;
            margin: 0 12px;
        }

        .box1_icon {
            display: flex;
            margin-left: 28px;
        }

        .box1_button {
            display: flex;
            margin-right: 20px;
        }

        .nav_button {
            background-color: rgb(30, 29, 29);
            margin-right: 15px;
            border-radius: 5px;
            height: 40px;
            line-height: 40px;
            width: 100px;
            text-align: center;
        }

        .box1_button a {
            color: white;
            text-decoration: none;
        }

        .box1_menu {
            display: flex;
            flex-wrap: wrap;
            margin-right: 50px;
        }

        .menu_items {
            display: inline-block;
            margin-right: 30px;
        }

        .box1_menu a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="box1">
        <div class="box1_icon">
            <a href="https://www.facebook.com/duytan.801/" target="_blank">
                <i class="fa-brands fa-square-facebook" style="color: black"></i>
            </a>
            <a
                href="https://www.linkedin.com/in/jim-nguyen-814255390/"
                target="_blank">
                <i class="fa-brands fa-linkedin" style="color: black"></i>
            </a>

            <a href="https://x.com/NguynTn70344539" target="_blank">
                <i class="fa-brands fa-x-twitter" style="color: black"></i>
            </a>
        </div>
        <div class="box1_menu">
            <ul class="menu_list">
                <li class="menu_items" style="font-size: 18px">
                    <a href="../Pages/trangChu.php"><strong>Trang chủ</strong></a>
                </li>
                <li class="menu_items" style="font-size: 18px">
                    <a href="phim.html"><strong>Phim</strong></a>
                </li>
                <li class="menu_items" style="font-size: 18px">
                    <a href="trangChitietPhim.html"><strong>Góc điện ảnh</strong></a>
                </li>
                <li class="menu_items" style="font-size: 18px">
                    <a href="trangKhuyenmai.html"><strong>Khuyến mãi</strong></a>
                </li>
                <li class="menu_items" style="font-size: 18px">
                    <a href="../Pages/trangLienHe.php"><strong>Liên hệ</strong></a>
                </li>
            </ul>
        </div>

        <div class="box1_button">
            <div class="nav_button" style="font-size: 15px; color: white">
                <a href="../LoginAndSign-up/login.html">Đăng Nhập</a>
            </div>
            <div class="nav_button" style="font-size: 15px; color: white">
                <a href="../LoginAndSign-up/Sign-up.html"> Đăng Ký</a>
            </div>
        </div>
    </div>
</body>

</html>