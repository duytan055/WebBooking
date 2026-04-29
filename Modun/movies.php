<?php
include '../Connect/connecDB.php';
$sql = "SELECT * FROM phim";
$result = $conn->query($sql);

?>

<head>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
        .box3 {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            margin-left: 5%;
        }

        .box3 h2 {
            font-size: 28px;
            letter-spacing: 2px;
            position: relative;
        }

        .box3 h2::after {
            content: "";
            width: 100px;
            height: 3px;
            background: red;
            display: block;
            margin: 10px auto 0;
        }

        .movie-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .movie-container {
            position: relative;
            width: 80%;
            margin: 0;
            overflow: hidden;
        }

        .box_img {
            position: relative;
            width: 250px;
            height: 360px;
            background-color: black;
            overflow: hidden;
            border-radius: 15px;
        }

        .movie-slider {
            display: flex;
            width: 100%;
            transition: 0.5s;
            list-style: none;
            padding: 0;
        }

        .movie {
            position: relative;
            margin-right: 15px;
        }

        .movie:hover .box_hover {
            opacity: 1;
        }

        .box_img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s;
        }

        .movie:hover img {
            transform: scale(1.1);
        }

        .box_hover {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 15px;
            opacity: 0;
            transition: 0.5s;
        }

        .box_hover a {
            margin: auto;
        }

        .prev {
            color: black;
            background: transparent;
            font-size: 50px;
            margin-right: 10px;
        }

        .next {
            color: black;
            background: transparent;
            font-size: 50px;
            margin-left: 10px;
        }

        button {
            border: none;
        }

        .description_button {
            width: 100%;
            height: auto;
            background: rgba(0, 0, 0, 0.7);
            text-align: center;
            margin-bottom: 10px;
        }

        .description_button h1 {
            font-size: 16px;
            overflow: hidden;
        }

        .description_button button {
            width: 100px;
            height: 30px;
            background-color: white;
            color: black;
            border-radius: 5px;
            border: none;
        }

        .description_button button:hover {
            background-color: red;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<div class="box3">
    <h2><strong>MOVIES SELECTION</strong></h2>
</div>
<div class="movie-box">
    <button class="prev">&#10094;</button>
    <div class="movie-container">
        <ul class="movie-slider" id="slider">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li class="movie">
                    <div class="box_img">
                        <img src="<?= $row['poster'] ?>">
                    </div>
                    <div class="box_hover">
                        <a href=""><i class="fa-solid fa-circle-play" style="font-size: 60px; color: white;"></i></a>
                        <div class="description_button">
                            <h1><?= $row['ten_phim'] ?></h1>
                            <button> <strong>Xem thêm</strong> </button>
                            <button> <strong>Đặt vé</strong> </button>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <?php $conn->close(); ?>
    </div>
    <button class="next">&#10095;</button>
</div>

<script>
    let slider = document.getElementById("slider");
    let scrollAmount = 0;

    function getMaxScroll() {
        return slider.scrollWidth - slider.clientWidth;
    }

    document.querySelector(".next").onclick = () => {
        scrollAmount += 300;

        let maxScroll = getMaxScroll();
        if (scrollAmount > maxScroll) {
            scrollAmount = maxScroll;
        }

        slider.style.transform = `translateX(-${scrollAmount}px)`;
    };

    document.querySelector(".prev").onclick = () => {
        scrollAmount -= 300;

        if (scrollAmount < 0) {
            scrollAmount = 0;
        }

        slider.style.transform = `translateX(-${scrollAmount}px)`;
    };
</script>