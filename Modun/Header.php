<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<style>
    .box1 {
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
        position: relative;
        display: inline-block;
        margin-right: 30px;
    }

    .box1_menu a {
        color: black;
        text-decoration: none;
    }

    .hover_container {
        position: absolute;
        min-width: 200px;
        height: 85px;
        color: white;
        line-height: 25px;
        text-align: center;
        display: flex;
        flex-direction: column;
        background: rgb(30, 29, 29);

        top: 100%;
        left: 0;
        border-radius: 5px;

        opacity: 0;
        transform: scaleY(0);
        transform-origin: center;

        transition: 0.5s ease;

        z-index: 10;
    }

    .hover_container a {
        color: white;
        text-decoration: none;
        padding: 8px 10px;
        display: block;
        border-bottom: 1px solid #555;
    }

    .hover_container a:last-child {
        border-bottom: none;
    }

    .hover_container a:hover {
        color: red;
    }

    .menu_items:hover .hover_container {
        opacity: 1;
        transform: scaleY(1);
    }
</style>

<nav class="box1">
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
            <li class="menu_items" id="item_phim" style="font-size: 18px">
                <a href="#"><strong>Phim</strong></a>
                <div class="hover_container">
                    <a href="../Pages/NowMovies.php">Phim đang chiếu</a>
                    <a href="../Pages/SoonMovies.php">Phim sắp chiếu</a>
                </div>
            </li>
            <li class="menu_items" style="font-size: 18px">
                <a href="trangChitietPhim.html"><strong>Góc điện ảnh</strong></a>
            </li>
            <li class="menu_items" style="font-size: 18px">
                <a href="../Pages/KhuyenMai.php"><strong>Khuyến mãi</strong></a>
            </li>
            <li class="menu_items" style="font-size: 18px">
                <a href="../Pages/trangLienHe.php"><strong>Liên hệ</strong></a>
            </li>
        </ul>
    </div>

    <div class="box1_button">
        <div class="nav_button" style="font-size: 15px; color: white">
            <a href="../LoginAndSign-up/login.php">Đăng Nhập</a>
        </div>
        <div class="nav_button" style="font-size: 15px; color: white">
            <a href="../LoginAndSign-up/Sign-up.php"> Đăng Ký</a>
        </div>
    </div>
</nav>