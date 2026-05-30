<?php
include __DIR__ . '/../Connect/connecDB.php';
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
            justify-content: flex-start;
            align-items: center;
            margin: 40px 0 20px;
            padding-left: 5%;
        }

        .box3 h2 {
            font-size: 28px;
            letter-spacing: 2px;
            position: relative;
            color: white;
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
            position: relative;
            display: flex;
            align-items: center;
            padding: 0 5%;
            overflow: visible;
        }

        .movie-container {
            width: 100%;
            overflow: hidden;
            padding: 20px 0;
        }

        .box_img {
            position: relative;
            width: 100%;
            aspect-ratio: 2/3;
            background-color: black;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        .movie-slider {
            display: flex;
            gap: 20px;
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            list-style: none;
            padding: 0;
        }

        .movie {
            flex: 0 0 calc(20% - 16px);
            /* Hiển thị 5 phim một lúc */
            position: relative;
            transition: transform 0.3s ease;
        }

        @media (max-width: 1024px) {
            .movie {
                flex: 0 0 calc(33.33% - 14px);
            }
        }

        @media (max-width: 768px) {
            .movie {
                flex: 0 0 calc(50% - 10px);
            }
        }

        .movie:hover {
            transform: translateY(-10px);
            z-index: 2;
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
            justify-content: flex-end;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 50%, transparent 100%);
            color: white;
            border-radius: 15px;
            opacity: 0;
            transition: opacity 0.4s ease;
            padding-bottom: 20px;
        }

        .play-icon {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 60px;
            color: white;
            text-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .prev:hover,
        .next:hover {
            background: red;
            transform: translateY(-50%) scale(1.1);
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .description_button {
            width: 100%;
            text-align: center;
            padding: 0 10px;
        }

        .description_button h1 {
            font-size: 18px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .btn_detail {
            display: inline-block;
            padding: 8px 15px;
            background: transparent;
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
            font-size: 13px;
            margin: 2px;
        }

        .btn_detail:hover {
            background: red;
            color: white;
            border-color: red;
        }
    </style>
</head>

<div class="box3">
    <h2><strong>PHIM ĐỀ CỬ</strong></h2>
</div>
<div class="movie-box">
    <button class="prev">&#10094;</button>
    <div class="movie-container">
        <ul class="movie-slider" id="slider">
            <?php while ($row = $result->fetch_assoc()) {
                // Kiểm tra trạng thái phim
                $currentDate = date('Y-m-d');
                $releaseDate = $row['ngay_khoi_chieu'];
                $isUpcoming = (strtotime($releaseDate) > strtotime($currentDate));
            ?>
                <li class="movie">
                    <div class="box_img">
                        <img src="<?= $row['poster'] ?>">
                    </div>
                    <div class="box_hover">
                        <a href="../Pages/SeeMoreMovies.php?id=<?= $row['id_phim'] ?>" class="play-icon"><i class="fa-solid fa-circle-play"></i></a>
                        <div class="description_button">
                            <h1><?= $row['ten_phim'] ?></h1>
                            <a href="../Pages/SeeMoreMovies.php?id=<?= $row['id_phim'] ?>" class="btn_detail">
                                <strong>Xem thêm</strong>
                            </a>
                            <?php if (!$isUpcoming): ?>
                                <a href="../Pages/buyticket.php?id=<?= $row['id_phim'] ?>" class="btn_detail">
                                    <strong>Đặt vé</strong>
                                </a>
                            <?php endif; ?>
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
    const slider = document.getElementById("slider");
    const nextBtn = document.querySelector(".next");
    const prevBtn = document.querySelector(".prev");

    let scrollAmount = 0;
    let autoPlayInterval;

    function getStep() {
        const firstMovie = slider.querySelector('.movie');
        return firstMovie ? firstMovie.offsetWidth + 20 : 300;
    }

    function slideNext() {
        const maxScroll = slider.scrollWidth - slider.clientWidth;
        const step = getStep();

        if (scrollAmount >= maxScroll - 5) {
            scrollAmount = 0;
        } else {
            scrollAmount += step;
            if (scrollAmount > maxScroll) scrollAmount = maxScroll;
        }
        slider.style.transform = `translateX(-${scrollAmount}px)`;
    }

    function slidePrev() {
        const step = getStep();
        if (scrollAmount <= 5) {
            scrollAmount = slider.scrollWidth - slider.clientWidth;
        } else {
            scrollAmount -= step;
            if (scrollAmount < 0) scrollAmount = 0;
        }
        slider.style.transform = `translateX(-${scrollAmount}px)`;
    }

    // Start AutoPlay
    function startAutoPlay() {
        autoPlayInterval = setInterval(slideNext, 2000);
    }

    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        startAutoPlay();
    }

    nextBtn.addEventListener('click', () => {
        slideNext();
        resetAutoPlay();
    });

    prevBtn.addEventListener('click', () => {
        slidePrev();
        resetAutoPlay();
    });

    // Pause on hover
    slider.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
    slider.addEventListener('mouseleave', startAutoPlay);

    // Init
    startAutoPlay();
</script>