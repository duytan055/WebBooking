<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT 
    p.*,
    GROUP_CONCAT(DISTINCT dd.ten_dao_dien SEPARATOR ', ') AS dao_dien,
    GROUP_CONCAT(DISTINCT dv.ten_dien_vien SEPARATOR ', ') AS dien_vien
FROM phim p
LEFT JOIN phim_daodien pdd ON p.id_phim = pdd.id_phim
LEFT JOIN daodien dd ON pdd.id_daodien = dd.id_daodien
LEFT JOIN phim_dienvien pdv ON p.id_phim = pdv.id_phim
LEFT JOIN dienvien dv ON pdv.id_dienvien = dv.id_dienvien
WHERE p.trang_thai = 'sap_chieu'
GROUP BY p.id_phim";
$result = $conn->query($sql);
?>
<style>
    body {
        margin: 0;
        position: relative;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f8fafc;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: url('../LoginAndSign-up/image1.webp') center/cover no-repeat;
        background-attachment: fixed;
        filter: brightness(0.45) contrast(1.05);
        z-index: -1;
    }

    body::after {
        content: "";
        position: fixed;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.35), rgba(15, 23, 42, 0.85));
        z-index: -1;
    }

    .page-shell {
        width: min(1180px, 94%);
        margin: 0 auto 60px;
        padding: 40px 0;
    }

    .page-shell h2 {
        margin: 0 0 16px;
        font-size: clamp(2.4rem, 4vw, 3.6rem);
        letter-spacing: 0.04em;
        color: #ffffff;
    }

    .page-shell .subtitle {
        margin: 0;
        max-width: 760px;
        color: #cbd5e1;
        font-size: 1rem;
        line-height: 1.75;
    }

    .NowMovies {
        display: grid;
        gap: 28px;
    }

    .NowMovies header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        flex-wrap: wrap;
        padding: 24px 24px 20px;
        border-radius: 24px;
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .NowMovies header a {
        color: #93c5fd;
        text-decoration: none;
        font-size: 1rem;
        border: 1px solid rgba(147, 197, 253, 0.4);
        border-radius: 999px;
        padding: 12px 18px;
        transition: background 0.25s ease, color 0.25s ease;
    }

    .NowMovies header a:hover {
        background: rgba(56, 189, 248, 0.16);
        color: #38bdf8;
    }

    .movie-box {
        width: 100%;
        background: transparent;
    }

    .movie-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .movie-item {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.18);
        background: rgba(15, 23, 42, 0.75);
        border: 1px solid rgba(148, 163, 184, 0.12);
    }

    .movie-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 28px 72px rgba(15, 23, 42, 0.28);
    }

    .box_img {
        width: 100%;
        height: 420px;
        overflow: hidden;
    }

    .box_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .movie-item:hover .box_img img {
        transform: scale(1.06);
    }

    .box_hover {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 24px;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.88) 65%);
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.35s ease, transform 0.35s ease;
        color: #f8fafc;
    }

    .movie-item:hover .box_hover {
        opacity: 1;
        transform: translateY(0);
    }

    .box_hover .top {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .movie-item .title {
        font-size: 1.2rem;
        margin: 0;
        color: #f8fafc;
    }

    .movie-item .time {
        font-size: 0.95rem;
        color: #38bdf8;
        margin: 0;
    }

    .movie-item .date {
        margin: 0;
        color: #cbd5e1;
        font-size: 0.92rem;
    }

    .desc {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #e2e8f0;
        margin: 0;
        max-height: 90px;
        overflow: hidden;
    }

    .box_button {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin: 16px 0 12px;
        justify-content: center;
    }

    .box_button button {
        min-width: 120px;
        padding: 12px 18px;
        border: none;
        border-radius: 999px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.25s ease, background 0.25s ease;
    }

    .box_button button:first-child {
        background-color: rgba(56, 189, 248, 0.95);
        color: #0f172a;
    }

    .box_button button:last-child {
        background-color: rgba(255, 255, 255, 0.12);
        color: #f8fafc;
    }

    .box_button button:hover {
        transform: translateY(-2px);
    }

    .bottom {
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: grid;
        gap: 8px;
        color: #cbd5e1;
        font-size: 0.92rem;
    }

    .bottom strong {
        color: #f8fafc;
    }

    .movie-name {
        padding: 18px 24px 20px;
        margin: 0;
        font-size: 1.1rem;
        color: #f8fafc;
        background: rgba(15, 23, 42, 0.8);
        text-align: center;
    }

    @media (max-width: 860px) {
        .box_img {
            height: 320px;
        }

        .movie-item .title {
            font-size: 1.1rem;
        }

        .box_button {
            gap: 10px;
        }
    }

    @media (max-width: 620px) {
        .page-shell {
            padding: 24px 0;
        }

        .movie-list {
            gap: 18px;
        }

        .box_img {
            height: 280px;
        }

        .page-shell h2 {
            font-size: 2rem;
        }
    }
</style>
<?php include '../Module/header.php' ?>
<?php include '../Module/TrailerModal.php' ?>
<div class="page-shell">
    <div class="NowMovies">
        <header>
            <div>
                <h2>Phim sắp khởi chiếu</h2>
                <p class="subtitle">Khám phá danh sách phim mới sắp ra rạp kèm thông tin nhanh và trailer hấp dẫn.</p>
            </div>
            <a href="NowMovies.php">Phim đang chiếu</a>
        </header>

        <div class="movie-box">
            <ul class="movie-list">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <li class="movie-item">
                        <div class="box_img">
                            <img src="<?= htmlspecialchars($row['poster']) ?>" alt="<?= htmlspecialchars($row['ten_phim']) ?>">
                        </div>

                        <div class="box_hover">
                            <div class="top">
                                <h3 class="title"><?= htmlspecialchars($row['ten_phim']) ?></h3>
                                <p class="time">⏱ <?= htmlspecialchars($row['thoi_luong']) ?> phút</p>
                                <p class="date">Ngày khởi chiếu: <?= htmlspecialchars($row['ngay_khoi_chieu']) ?></p>
                                <p class="desc"><?= htmlspecialchars($row['mo_ta']) ?></p>
                            </div>
                            <div class="box_button">
                                <button type="button" class="openTrailer" data-trailer="<?= htmlspecialchars($row['trailer_phim']) ?>"><strong>▶ Trailer</strong></button>
                            </div>
                            <div class="bottom">
                                <p><strong>Đạo diễn:</strong> <?= htmlspecialchars($row['dao_dien']) ?></p>
                                <p><strong>Diễn viên:</strong> <?= htmlspecialchars($row['dien_vien']) ?></p>
                            </div>
                        </div>
                        <p class="movie-name"><?= htmlspecialchars($row['ten_phim']) ?></p>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<?php include '../Module/footer.php' ?>