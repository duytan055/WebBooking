<?php
$scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
$prefix = strpos($scriptPath, '/Pages/') !== false ? '..' : '.';
?>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<style>
    .box1 {
        display: inline-flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        width: 100%;
        height: auto;
        min-height: 70px;
        top: 0;
        z-index: 9999;
        position: relative;
    }

    .box1_icon img {
        height: 80px;
        width: 250px;

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

    .menu_list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: center;
    }

    .menu_items {
        position: relative;
        display: inline-block;
        margin-right: 30px;
        color: white;
    }

    .box1_menu a {
        color: white;
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

        z-index: 10000;
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

    .user_menu {
        position: relative;
        cursor: pointer;
    }

    .user_name {
        font-size: 16px;
        color: white;
    }

    .user_dropdown {
        position: absolute;

        top: 120%;
        right: 0;

        width: 150px;

        background: rgb(30, 29, 29);

        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);

        display: flex;
        flex-direction: column;

        opacity: 0;
        visibility: hidden;

        transform: translateY(10px);

        transition: 0.3s;

        z-index: 10000;
    }

    .user_dropdown a {
        padding: 12px;
        text-decoration: none;
        color: white;

        border-bottom: 1px solid #555;
    }

    .user_dropdown a:last-child {
        border-bottom: none;
    }

    .user_dropdown a:hover {

        color: red;
    }

    .user_menu:hover .user_dropdown {

        opacity: 1;

        visibility: visible;

        transform: translateY(0);
    }
</style>

<nav class="box1">
    <div class="box1_icon">
        <a href="<?php echo $prefix; ?>/Pages/trangChu.php">
            <img src="<?php echo $prefix; ?>/Logo/logo1.png" alt="Logo">
        </a>
    </div>
    <div class="box1_menu">
        <ul class="menu_list">
            <li class="menu_items" style="font-size: 18px">
                <a href="<?php echo $prefix; ?>/Pages/trangChu.php"><strong>Trang chủ</strong></a>
            </li>
            <li class="menu_items" id="item_phim" style="font-size: 18px">
                <a href="#"><strong>Phim</strong></a>
                <div class="hover_container">
                    <a href="<?php echo $prefix; ?>/Pages/NowMovies.php">Phim đang chiếu</a>
                    <a href="<?php echo $prefix; ?>/Pages/SoonMovies.php">Phim sắp chiếu</a>
                </div>
            </li>
            <li class="menu_items" style="font-size: 18px">
                <a href="<?php echo $prefix; ?>/Pages/TopPhim.php"><strong>Top Phim</strong></a>
            </li>
            <li class="menu_items" style="font-size: 18px">
                <a href="<?php echo $prefix; ?>/Pages/KhuyenMai.php"><strong>Khuyến mãi</strong></a>
            </li>
            <li class="menu_items" style="font-size: 18px">
                <a href="<?php echo $prefix; ?>/Pages/trangLienHe.php"><strong>Liên hệ</strong></a>
            </li>
        </ul>
    </div>

    <div class="box1_button">
        <?php
        if (isset($_SESSION['username'])) {
        ?>
            <div class="user_menu">

                <p class="user_name">
                    Xin chào,
                    <strong>
                        <?php echo $_SESSION['username']; ?>
                    </strong>
                </p>

                <div class="user_dropdown">

                    <a href="<?php echo $prefix; ?>/Pages/profileUser.php">
                        <i class="fa-solid fa-user"></i> Hồ sơ
                    </a>

                    <a href="<?php echo $prefix; ?>/LoginAndSign-up/logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>

                </div>

            </div>

        <?php
        } else {
        ?>
            <div class="nav_button" style="font-size: 15px; color: white">
                <a href="<?php echo $prefix; ?>/LoginAndSign-up/login.php">Đăng Nhập</a>
            </div>
            <div class="nav_button" style="font-size: 15px; color: white">
                <a href="<?php echo $prefix; ?>/LoginAndSign-up/Sign-up.php"> Đăng Ký</a>
            </div>
        <?php
        } ?>
    </div>
</nav>