<?php
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT * FROM khuyenmai";
$result = $conn->query($sql);
?>
<style>
    .box3 {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
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

    .proE-box {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .proE-container {
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
        border-radius: 12px;
    }

    .proE-slider {
        display: flex;
        width: max-content;
        transition: 0.5s;
        list-style: none;
        padding: 0;
        will-change: transform;
    }

    .proE-picture {
        position: relative;
        margin-right: 15px;
        border-radius: 12px;
        overflow: hidden;
    }

    .proE-picture:hover .box_hover {
        opacity: 1;
    }

    .box_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s;
    }

    .proE-picture:hover img {
        transform: scale(1.1);
    }

    .box_hover_proE {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        opacity: 0;
        transition: 0.5s;
    }

    .box_hover_proE a {
        margin: auto;
    }
</style>
<div class="box3">
    <h2><strong>Khuyến mãi & Sự kiện</strong></h2>
</div>
<div class="proE-box">
    <div class="proE-container">
        <ul class="proE-slider" id="proE">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li class="proE-picture">
                    <div class="box_img">
                        <img src="../Anh_Khuyen_Mai/<?= $row['anh_khuyen_mai'] ?>">
                    </div>
                    <div class="box_hover_proE">
                        <a href=""></a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
    window.onload = function() {
        const slider = document.getElementById("proE");

        if (!slider) {
            console.log("Không tìm thấy slider");
            return;
        }
        if (slider.children.length === 0) {
            console.log("Không có ảnh");
            return;
        }
        slider.innerHTML += slider.innerHTML;

        let position = 0;
        let speed = 0.5;

        function autoSlide() {
            position += speed;

            if (position >= slider.scrollWidth / 2) {
                position -= slider.scrollWidth / 2;
            }

            slider.style.transform = `translateX(-${position}px)`;

            requestAnimationFrame(autoSlide);
        }

        autoSlide();
    };
</script>