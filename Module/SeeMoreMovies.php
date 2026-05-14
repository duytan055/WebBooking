<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_GET['id'])) {
    echo "Không có ID phim";
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT 
    phim.*,

    dotuoi.do_tuoi,
    dotuoi.mo_ta AS mo_ta_do_tuoi,

    GROUP_CONCAT(DISTINCT daodien.ten_dao_dien SEPARATOR ', ') AS dao_dien,

    GROUP_CONCAT(DISTINCT dienvien.ten_dien_vien SEPARATOR ', ') AS dien_vien

FROM phim

LEFT JOIN dotuoi 
ON phim.id_do_tuoi = dotuoi.id_do_tuoi

LEFT JOIN phim_daodien 
ON phim.id_phim = phim_daodien.id_phim

LEFT JOIN daodien 
ON phim_daodien.id_daodien = daodien.id_daodien

LEFT JOIN phim_dienvien 
ON phim.id_phim = phim_dienvien.id_phim

LEFT JOIN dienvien 
ON phim_dienvien.id_dienvien = dienvien.id_dienvien

WHERE phim.id_phim = $id

GROUP BY phim.id_phim
";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Không tìm thấy phim";
    exit;
}

$row = $result->fetch_assoc();
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

    .box_seemore {
        width: 80%;
        margin: 60px auto;

        color: white;
        display: flex;
        flex-direction: column;
        background-color: rgba(25, 24, 24, 1);
    }

    .box_seemore h5 {
        padding-left: 20px;
        color: white;
        margin: 30px 0 0 0;
        font-size: 20px;
        border-bottom: 3px solid #c4c2c2da;
    }

    .seemore_container {
        width: 100%;
        height: auto;
        display: flex;
        align-items: flex-start;
    }

    .description_item {
        flex: 1;
        margin-left: 20px;
        margin-right: 40px;
    }

    .img_item {
        width: 275px;
        height: 405px;

        margin: 20px 40px;
        flex-shrink: 0;
    }

    .img_item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn_box {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        border-bottom: 2px solid #555;
        padding: 10px 0;
        margin-top: 10px;
    }

    .btn_box button {
        width: 100px;
        height: 30px;
        margin: 0 10px;
        background: white;
        border: none;
        border-radius: 5px;
    }

    .btn_box button:hover {
        background: red;
        color: white;
        cursor: pointer;
    }
</style>

<?php include __DIR__ . '/../Modun/header.php'; ?>

<body>
    <div class="box_seemore">
        <h5> Nội dung phim </h5>

        <div class="seemore_container">
            <div class="img_item">
                <img src="<?= $row['poster'] ?>" alt="" />
            </div>
            <div class="description_item">
                <p><?= $row['ten_phim'] ?></p>
                <p>Thể loại : <?= $row['the_loai'] ?></p>
                <p>Đạo diễn : <?= $row['dao_dien'] ?></p>
                <p>Diễn viên : <?= $row['dien_vien'] ?></p>
                <p>Khởi chiếu : <?= $row['ngay_khoi_chieu'] ?></p>
                <p>Thời lượng : <?= $row['thoi_luong'] ?> phút</p>
                <div class="btn_box">
                    <button><strong>▶ Trailer</strong></button>
                    <button><strong>🎟 Đặt vé</strong></button>
                </div>
                <p><?= $row['mo_ta'] ?></p>
            </div>
        </div>
        <div class="filmoja-movie-details-page">
            <h6>Lịch chiếu</h6>
            <div class="item_details">

            </div>
        </div>
    </div>
</body>
<?php include __DIR__ . '/../Modun/footer.php'; ?>