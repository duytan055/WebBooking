<?php
include '../Connect/connecDB.php';
$sql = "SELECT 
    p.*,
    GROUP_CONCAT(DISTINCT dd.ten_dao_dien SEPARATOR ', ') AS dao_dien,
    GROUP_CONCAT(DISTINCT dv.ten_dien_vien SEPARATOR ', ') AS dien_vien
FROM phim p
LEFT JOIN phim_daodien pdd ON p.id_phim = pdd.id_phim
LEFT JOIN daodien dd ON pdd.id_daodien = dd.id_daodien
LEFT JOIN phim_dienvien pdv ON p.id_phim = pdv.id_phim
LEFT JOIN dienvien dv ON pdv.id_dienvien = dv.id_dienvien
WHERE p.trang_thai = 'Đang chiếu'
GROUP BY p.id_phim";

$result = $conn->query($sql);
?>
<style>
    body {
        margin: 0;
        position: relative;
        font-family: Arial, sans-serif;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;

        background: url('../LoginAndSign-up/image1.webp') center/cover no-repeat;

        filter: brightness(0.6);
        z-index: -1;
    }

    .NowMovies h2 {
        display: flex;
        justify-content: space-between;
        align-items: center;

        width: 80%;

        font-size: 50px;
        font-weight: bold;
        color: white;

        margin-left: 10%;
        margin-top: 50px;
        position: relative;
    }

    .NowMovies h2 a {
        font-size: 20px;
        font-weight: normal;
        color: #ccc;
        text-decoration: none;
        margin-top: 30px;
    }

    .NowMovies h2 a:hover {
        color: red;
    }

    .NowMovies h2::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -6px;

        width: 100%;
        height: 3px;
        background: white;
    }

    .movie-box {
        width: 80%;
        height: auto;
        margin-left: 10%;
        background-color: rgba(0, 0, 0, 0.2);
    }

    .movie-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        column-gap: 5px;
        row-gap: 5px;
        transition: 0.5s;
        list-style: none;
        padding: 20px;
        overflow: visible;
    }

    .movie-item {
        justify-self: center;
        position: relative;
        overflow: visible;
        transition: 0.3s;
        margin-top: 20px;
    }

    .movie-item:hover {
        z-index: 10;
    }

    .movie-item:hover .box_hover {
        opacity: 1;
    }

    .box_img {
        width: 100%;
        height: 360px;
        overflow: hidden;
        border-radius: 10px;
    }

    .box_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s;
    }

    .box_hover {

        position: absolute;

        top: -5%;
        left: -20%;
        width: 140%;
        height: auto;
        min-height: 100%;

        background: rgb(30, 29, 29);
        color: white;

        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;

        opacity: 0;
        transform: scaleY(0);
        transform-origin: center;

        transition: 0.5s ease;

        z-index: 10;
    }

    .box_hover>* {
        opacity: 0;
        transition: 0.2s;
    }

    .movie-item:hover .box_hover>* {
        opacity: 1;
        transition-delay: 0.2s;
    }

    .movie-item:hover .box_hover {
        opacity: 1;
        transform: scaleY(1);
    }

    .top {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 10px 10px 0 10px;
    }

    .title {
        font-size: 18px;
        margin: 0;
        color: white;
    }

    .time {
        font-size: 16px;
        color: #ce1414;
    }

    .desc {
        font-size: 15px;
        line-height: 1.5;
        color: white;

        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;

        overflow: hidden;
    }

    .title,
    .time,
    .desc {
        margin: 0;
    }

    .box_button {
        margin: 10px 0;
    }

    .box_button button {
        width: 100px;
        height: 30px;
        background-color: white;
        color: black;
        border-radius: 5px;
        border: none;
        list-style: none;
    }

    .box_button button:hover {
        background-color: red;
        color: white;
        cursor: pointer;
    }

    .bottom {
        border-top: 2px solid #555;
        padding-top: 10px;
        margin-top: 10px;
        padding: 5px 10px 10px 10px;
    }

    h4 {
        color: white;
        max-width: 200px;
        text-align: center;
        margin-left: 10%;
        margin-top: 5px;
    }
</style>
<?php include '../Modun/Header.php' ?>
<div class="NowMovies">
    <h2>Phim đang chiếu <a href="SoonMovies.php">Phim sắp chiếu</a></h2>

    <div class="movie-box">
        <ul class="movie-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li class="movie-item">
                    <div class="box_img">
                        <img src="<?= $row['poster'] ?>" alt="<?= $row['ten_phim'] ?>">
                    </div>

                    <div class="box_hover">
                        <div class="top">
                            <h3 class="title"><?= $row['ten_phim'] ?></h3>

                            <p class="time">⏱ <?= $row['thoi_luong'] ?> phút</p>
                            <p class="date"><i class="fa-solid fa-calendar-days"></i> Ngày khởi chiếu : <?= $row['ngay_khoi_chieu'] ?></p>
                            <p class="desc"><?= $row['mo_ta'] ?></p>
                        </div>
                        <div class="box_button">
                            <button><strong>▶ Trailer</strong></button>
                            <button><strong>🎟 Đặt vé</strong></button>
                        </div>
                        <div class="bottom">
                            <p><strong>Đạo diễn :</strong>
                                <span style="color: #ccc;"><?= $row['dao_dien'] ?></span>
                            </p>
                            <p><strong>Diễn viên :</strong>
                                <span style="color: #ccc;"><?= $row['dien_vien'] ?></span>
                            </p>
                        </div>
                    </div>
                    <h4><?= $row['ten_phim'] ?></h4>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php include '../Modun/Footer.php' ?>