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

// Kiểm tra trạng thái phim: so sánh ngày khởi chiếu với ngày hiện tại
$currentDate = date('Y-m-d');
$releaseDate = $row['ngay_khoi_chieu'];
$isUpcoming = (strtotime($releaseDate) > strtotime($currentDate));

// Lấy lịch chiếu của phim (chỉ lấy nếu phim đang chiếu)
// Chỉ hiển thị suất chiếu của ngày hôm nay
$showtimesByDate = [];
if (!$isUpcoming) {
    // Lấy ngày hôm nay
    $today = date('Y-m-d');

    $sqlShowtimes = "SELECT id_suat, id_phong, date_chieu, thoi_gian 
                     FROM suatchieu 
                     WHERE id_phim = $id 
                     AND date_chieu = '$today'
                     ORDER BY thoi_gian";
    $showtimesResult = $conn->query($sqlShowtimes);

    // Nhóm suất chiếu theo ngày
    if ($showtimesResult && $showtimesResult->num_rows > 0) {
        while ($showtime = $showtimesResult->fetch_assoc()) {
            $date = $showtime['date_chieu'];
            if (!isset($showtimesByDate[$date])) {
                $showtimesByDate[$date] = [];
            }
            $showtimesByDate[$date][] = $showtime;
        }
    }
}
?>

<style>
    body {
        margin: 0;
        position: relative;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f7f7f7;
        background: #050505;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: url('../LoginAndSign-up/image1.webp') center/cover no-repeat;
        filter: brightness(0.45) contrast(1.05);
        z-index: -1;
    }

    body::after {
        content: "";
        position: fixed;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0.82) 100%);
        z-index: -1;
    }

    .box_seemore {
        width: min(1180px, 95%);
        margin: 20px auto 50px;
        padding: 28px 26px 24px;
        color: #fcfcfc;
        display: flex;
        flex-direction: column;
        gap: 18px;
        background: rgba(10, 12, 18, 0.85);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(10px);
    }

    .top-actions {
        width: min(1180px, 95%);
        margin: 0 auto;
        display: flex;
        justify-content: flex-start;
        padding-left: 10px;
    }

    .top-actions button {
        padding: 10px 18px;
        background: rgba(255, 255, 255, 0.12);
        color: #f7f7f7;
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .top-actions button:hover {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    .box_seemore h5 {
        padding-left: 20px;
        color: #f7f7f7;
        margin: 14px 0 24px 0;
        font-size: 18px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.25);
        letter-spacing: 0.08em;
    }

    .seemore_container {
        width: 100%;
        height: auto;
        display: flex;
        align-items: flex-start;
        gap: 24px;
    }

    .description_item {
        flex: 1;
        margin-left: 10px;
        margin-right: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .movie-title {
        font-size: clamp(28px, 3vw, 42px);
        font-weight: 700;
        margin: 0 0 10px;
        letter-spacing: 0.03em;
        color: #ffffff;
    }

    .movie-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(140px, 1fr));
        gap: 12px 18px;
        margin-bottom: 12px;
        font-size: 14px;
        color: #d9d9d9;
    }

    .movie-meta span {
        display: inline-flex;
        align-items: center;
    }

    .movie-meta strong {
        min-width: 90px;
        color: #f1f1f1;
        font-weight: 600;
    }

    .movie-description {
        max-width: 100%;
        line-height: 1.75;
        font-size: 15px;
        color: #dcdcdc;
        margin-top: 8px;
    }

    .btn_box {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 16px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.12);
        padding: 14px 0;
        margin-top: 6px;
    }

    .btn_box button {
        width: auto;
        min-width: 130px;
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.14);
        color: #f9f9f9;
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        transition: all 0.25s ease;
    }

    .btn_box button:hover {
        background: #ff3d3d;
        color: white;
        border-color: transparent;
        transform: translateY(-1px);
    }

    .top-actions {
        width: min(1180px, 95%);
        margin: 40px auto 0;
        display: flex;
        justify-content: flex-start;
    }

    .top-actions button {
        padding: 10px 18px;
        background: rgba(255, 255, 255, 0.16);
        color: #f7f7f7;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .top-actions button:hover {
        background: #ff3d3d;
        border-color: transparent;
        transform: translateY(-1px);
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

    .filmoja-movie-details-page {
        width: 95%;
        padding: 24px 30px 28px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 18px;
        margin-top: 20px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
    }

    .filmoja-movie-details-page h6 {
        margin: 0 0 15px;
        font-size: 20px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #f7f7f7;
    }

    .item_details {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .showtime-date-group {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .showtime-date-header {
        font-size: 18px;
        font-weight: 600;
        color: #38bdf8;
        padding-bottom: 8px;
        border-bottom: 2px solid rgba(56, 189, 248, 0.3);
        letter-spacing: 0.03em;
    }

    .showtime-boxes {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .showtime-box {
        padding: 12px 20px;
        background: rgba(56, 189, 248, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 12px;
        color: #f0f9ff;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-block;
    }

    .showtime-box:hover {
        background: rgba(56, 189, 248, 0.35);
        border-color: rgba(56, 189, 248, 0.6);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(56, 189, 248, 0.2);
    }

    .showtime-box small {
        display: block;
        font-size: 12px;
        color: #cbd5e1;
        margin-top: 4px;
        font-weight: 400;
    }

    .no-showtimes {
        padding: 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 15px;
    }

    @media screen and (max-width: 900px) {
        .box_seemore {
            width: 92%;
            margin: 40px auto;
        }

        .seemore_container {
            flex-direction: column;
            align-items: center;
        }

        .description_item {
            margin: 0 20px 20px;
        }

        .img_item {
            width: 100%;
            max-width: 320px;
            height: 460px;
            margin: 20px auto 0;
        }
    }

    @media screen and (max-width: 620px) {
        .box_seemore {
            margin-top: 30px;
        }

        .box_seemore h5 {
            padding-left: 16px;
            font-size: 18px;
        }

        .description_item p {
            font-size: 14px;
        }

        .btn_box {
            flex-direction: column;
            gap: 10px;
        }

        .btn_box button {
            width: 100%;
            max-width: 220px;
        }
    }
</style>

<?php include __DIR__ . '/../Module/header.php'; ?>
<?php include __DIR__ . '/../Module/TrailerModal.php'; ?>

<body>
    <div class="top-actions">
        <button type="button" onclick="history.back()"><strong>← Quay lại</strong></button>
    </div>
    <div class="box_seemore">
        <h5> Nội dung phim </h5>

        <div class="seemore_container">
            <div class="img_item">
                <img src="<?= $row['poster'] ?>" alt="Poster <?= htmlspecialchars($row['ten_phim']) ?>" />
            </div>
            <div class="description_item">
                <h1 class="movie-title"><?= $row['ten_phim'] ?></h1>
                <div class="movie-meta">
                    <span><strong>Thể loại:</strong> <?= $row['the_loai'] ?></span>
                    <span><strong>Khởi chiếu:</strong> <?= $row['ngay_khoi_chieu'] ?></span>
                    <span><strong>Đạo diễn:</strong> <?= $row['dao_dien'] ?></span>
                    <span><strong>Thời lượng:</strong> <?= $row['thoi_luong'] ?> phút</span>
                    <span><strong>Diễn viên:</strong> <?= $row['dien_vien'] ?></span>
                    <span><strong>Độ tuổi:</strong> <?= $row['do_tuoi'] ?></span>
                </div>
                <div class="btn_box">
                    <button type="button" class="openTrailer" data-trailer="<?= htmlspecialchars($row['trailer_phim']) ?>"><strong>▶ Trailer</strong></button>
                    <?php if (!$isUpcoming): ?>
                        <a href="../Pages/buyticket.php?id=<?= $row['id_phim'] ?>"><button type="button"><strong>🎟 Đặt vé</strong></button></a>
                    <?php else: ?>
                        <button type="button" style="background: rgba(148, 163, 184, 0.3); cursor: not-allowed;" disabled><strong>🎬 Sắp chiếu</strong></button>
                    <?php endif; ?>
                </div>
                <p class="movie-description"><?= $row['mo_ta'] ?></p>
            </div>
        </div>
        <div class="filmoja-movie-details-page">
            <h6>Lịch chiếu</h6>
            <div class="item_details">
                <?php if (empty($showtimesByDate)): ?>
                    <div class="no-showtimes">
                        Hiện tại chưa có lịch chiếu cho phim này.
                    </div>
                <?php else: ?>
                    <?php foreach ($showtimesByDate as $date => $showtimes): ?>
                        <div class="showtime-date-group">
                            <div class="showtime-date-header">
                                📅 <?= date('d/m/Y', strtotime($date)) ?> - <?= date('l', strtotime($date)) ?>
                            </div>
                            <div class="showtime-boxes">
                                <?php foreach ($showtimes as $showtime): ?>
                                    <a href="../Pages/buyticket.php?id=<?= $row['id_phim'] ?>&showtime=<?= $showtime['id_suat'] ?>" class="showtime-box">
                                        🕐 <?= date('H:i', strtotime($showtime['thoi_gian'])) ?>
                                        <small>Phòng <?= $showtime['id_phong'] ?></small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<?php include __DIR__ . '/../Module/footer.php'; ?>