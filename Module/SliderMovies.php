<?php
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT * FROM phim WHERE hinh_anh IS NOT NULL";
$result = $conn->query($sql);

$images = [];
while ($row = $result->fetch_assoc()) {
    if ($row['hinh_anh'] != null) {
        $images[] = $row['hinh_anh'];
    }
}
?>
<style>
    .box2 {
        width: 100%;
        height: 500px;
        background-color: #000;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #slide_img {
        height: 100%;
        width: 100%;
        object-fit: contain;
        animation: fadeInOut 0.8s ease-in-out;
    }

    @keyframes fadeInOut {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .slider-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .indicator-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.4s ease;
        border: 2px solid transparent;
    }

    .indicator-dot:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: scale(1.2);
    }

    .indicator-dot.active {
        background: #ff3d3d;
        width: 32px;
        border-radius: 6px;
        box-shadow: 0 0 15px rgba(255, 61, 61, 0.6);
    }

    .slider-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 24px;
        transition: all 0.3s ease;
        z-index: 9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slider-nav-btn:hover {
        background: rgba(255, 61, 61, 0.8);
        transform: translateY(-50%) scale(1.1);
    }

    .slider-nav-btn.prev {
        left: 15px;
    }

    .slider-nav-btn.next {
        right: 15px;
    }

    @media screen and (max-width: 768px) {
        .box2 {
            height: 300px;
        }

        .slider-nav-btn {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .slider-nav-btn.prev {
            left: 10px;
        }

        .slider-nav-btn.next {
            right: 10px;
        }

        .slider-indicators {
            bottom: 15px;
            gap: 8px;
        }

        .indicator-dot {
            width: 10px;
            height: 10px;
        }

        .indicator-dot.active {
            width: 28px;
        }
    }

    @media screen and (max-width: 480px) {
        .box2 {
            height: 200px;
        }

        .slider-nav-btn {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }

        .indicator-dot {
            width: 8px;
            height: 8px;
            gap: 6px;
        }
    }
</style>

<div class="box2">
    <img id="slide_img" />
    <div class="slider-indicators" id="indicators"></div>
    <button class="slider-nav-btn prev" id="prevBtn">❮</button>
    <button class="slider-nav-btn next" id="nextBtn">❯</button>
</div>

<script>
    let images = <?php echo json_encode($images); ?>;
    let index = 0;
    const img = document.getElementById("slide_img");
    const indicatorsContainer = document.getElementById("indicators");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    // Tạo các nút chỉ báo
    function createIndicators() {
        images.forEach((_, i) => {
            const dot = document.createElement("div");
            dot.className = "indicator-dot" + (i === 0 ? " active" : "");
            dot.addEventListener("click", () => goToSlide(i));
            indicatorsContainer.appendChild(dot);
        });
    }

    // Cập nhật ảnh và nút chỉ báo
    function updateSlide() {
        img.src = images[index];
        document.querySelectorAll(".indicator-dot").forEach((dot, i) => {
            dot.classList.toggle("active", i === index);
        });
    }

    // Chuyển đến slide cụ thể
    function goToSlide(n) {
        index = n;
        updateSlide();
    }

    // Chuyển tiếp tục
    function nextSlide() {
        index = (index + 1) % images.length;
        updateSlide();
    }

    // Chuyển lùi
    function prevSlide() {
        index = (index - 1 + images.length) % images.length;
        updateSlide();
    }

    if (images.length > 0) {
        createIndicators();
        updateSlide();

        // Tự động chuyển slide sau 4 giây
        let autoSlideInterval = setInterval(nextSlide, 4000);

        // Nút điều khiển
        prevBtn.addEventListener("click", () => {
            clearInterval(autoSlideInterval);
            prevSlide();
            autoSlideInterval = setInterval(nextSlide, 4000);
        });

        nextBtn.addEventListener("click", () => {
            clearInterval(autoSlideInterval);
            nextSlide();
            autoSlideInterval = setInterval(nextSlide, 4000);
        });

        // Chỉ báo cũng có thể điều khiển
        document.querySelectorAll(".indicator-dot").forEach((dot) => {
            dot.addEventListener("click", () => {
                clearInterval(autoSlideInterval);
                autoSlideInterval = setInterval(nextSlide, 4000);
            });
        });
    }
</script>