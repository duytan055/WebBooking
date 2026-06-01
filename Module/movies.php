<?php
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT * FROM phim";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<style>
    .movies-section-title {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 40px 0 20px;
        padding-left: 5%;
    }

    .movies-section-title h2 {
        font-size: 28px;
        letter-spacing: 2px;
        position: relative;
        color: white;
    }

    .movies-section-title h2::after {
        content: "";
        width: 100px;
        height: 3px;
        background: red;
        display: block;
        margin: 10px auto 0;
    }

    .movies-box {
        position: relative;
        display: flex;
        align-items: center;
        padding: 0 5%;
        overflow: visible;
    }

    .movies-container {
        width: 100%;
        overflow: hidden;
        padding: 20px 0;
    }

    .movies-item-img {
        position: relative;
        width: 100%;
        aspect-ratio: 2/3;
        background-color: black;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
    }

    .movies-slider {
        display: flex;
        gap: 20px;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        list-style: none;
        padding: 0;
    }

    .movies-item {
        flex: 0 0 calc(20% - 16px);
        /* Hiển thị 5 phim một lúc */
        position: relative;
        transition: transform 0.3s ease;
    }

    @media (max-width: 1024px) {
        .movies-item {
            flex: 0 0 calc(33.33% - 14px);
        }
    }

    @media (max-width: 768px) {
        .movies-item {
            flex: 0 0 calc(50% - 10px);
        }
    }

    .movies-item:hover {
        transform: translateY(-10px);
        z-index: 2;
    }

    .movies-item:hover .movies-hover {
        opacity: 1;
    }

    .movies-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s;
    }

    .movies-item:hover img {
        transform: scale(1.1);
    }

    .movies-hover {
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

    .movies-play-icon {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 60px;
        color: white;
        text-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
    }

    .movies-prev,
    .movies-next {
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

    .movies-prev:hover,
    .movies-next:hover {
        background: red;
        transform: translateY(-50%) scale(1.1);
    }

    .movies-prev {
        left: 10px;
    }

    .movies-next {
        right: 10px;
    }

    .movies-description-button {
        width: 100%;
        text-align: center;
        padding: 0 10px;
    }

    .movies-description-button h1 {
        font-size: 18px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .movies-btn-detail {
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

    .movies-btn-detail:hover {
        background: red;
        color: white;
        border-color: red;
    }
</style>

<?php include __DIR__ . '/../Module/TrailerModal.php'; ?>

<div class="movies-section-title">
    <h2><strong>MOVIE SELECTION</strong></h2>
</div>

<div class="movies-box">
    <button class="movies-prev">&#10094;</button>
    <div class="movies-container">
        <ul class="movies-slider" id="moviesSlider">
            <?php while ($row = $result->fetch_assoc()) {
                // Kiểm tra trạng thái phim
                $currentDate = date('Y-m-d');
                $releaseDate = $row['ngay_khoi_chieu'];
                $isUpcoming = (strtotime($releaseDate) > strtotime($currentDate));
            ?>
                <li class="movies-item">
                    <div class="movies-item-img">
                        <img src="<?= $row['poster'] ?>" alt="<?= $row['ten_phim'] ?>">
                    </div>
                    <div class="movies-hover">
                        <a href="#" class="movies-play-icon openTrailer" data-trailer="<?= $row['trailer_phim'] ?>">
                            <i class="fa-solid fa-circle-play"></i>
                        </a>
                        <div class="movies-description-button">
                            <h1><?= $row['ten_phim'] ?></h1>
                            <a href="../Pages/SeeMoreMovies.php?id=<?= $row['id_phim'] ?>" class="movies-btn-detail">
                                <strong>Xem thêm</strong>
                            </a>
                            <?php if (!$isUpcoming): ?>
                                <a href="../Pages/buyticket.php?id=<?= $row['id_phim'] ?>" class="movies-btn-detail">
                                    <strong>Đặt vé</strong>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
    <button class="movies-next">&#10095;</button>
</div>

<script>
    (function() {
        // Sử dụng IIFE để tránh xung đột biến global
        const moviesSlider = document.getElementById("moviesSlider");
        const moviesNextBtn = document.querySelector(".movies-next");
        const moviesPrevBtn = document.querySelector(".movies-prev");

        if (!moviesSlider || !moviesNextBtn || !moviesPrevBtn) {
            console.error("Movies slider elements not found");
            return;
        }

        let moviesScrollAmount = 0;
        let moviesAutoPlayInterval;

        function getMoviesStep() {
            const firstMovie = moviesSlider.querySelector('.movies-item');
            return firstMovie ? firstMovie.offsetWidth + 20 : 300;
        }

        function moviesSlideNext() {
            const maxScroll = moviesSlider.scrollWidth - moviesSlider.clientWidth;
            const step = getMoviesStep();

            if (moviesScrollAmount >= maxScroll - 5) {
                moviesScrollAmount = 0;
            } else {
                moviesScrollAmount += step;
                if (moviesScrollAmount > maxScroll) moviesScrollAmount = maxScroll;
            }
            moviesSlider.style.transform = `translateX(-${moviesScrollAmount}px)`;
        }

        function moviesSlidePrev() {
            const step = getMoviesStep();
            if (moviesScrollAmount <= 5) {
                moviesScrollAmount = moviesSlider.scrollWidth - moviesSlider.clientWidth;
            } else {
                moviesScrollAmount -= step;
                if (moviesScrollAmount < 0) moviesScrollAmount = 0;
            }
            moviesSlider.style.transform = `translateX(-${moviesScrollAmount}px)`;
        }

        // Start AutoPlay
        function startMoviesAutoPlay() {
            moviesAutoPlayInterval = setInterval(moviesSlideNext, 2000);
        }

        function resetMoviesAutoPlay() {
            clearInterval(moviesAutoPlayInterval);
            startMoviesAutoPlay();
        }

        moviesNextBtn.addEventListener('click', () => {
            moviesSlideNext();
            resetMoviesAutoPlay();
        });

        moviesPrevBtn.addEventListener('click', () => {
            moviesSlidePrev();
            resetMoviesAutoPlay();
        });

        // Pause on hover
        moviesSlider.addEventListener('mouseenter', () => clearInterval(moviesAutoPlayInterval));
        moviesSlider.addEventListener('mouseleave', startMoviesAutoPlay);

        // Init
        startMoviesAutoPlay();
    })();
</script>