<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT * FROM khuyenmai";
$result = $conn->query($sql);
?>
<style>
    body {
        margin: 0;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: radial-gradient(circle at top, rgba(56, 189, 248, 0.12), transparent 28%),
            linear-gradient(180deg, #0f172a 0%, #111827 100%);
        color: #f8fafc;
    }

    .page-frame {
        width: min(1180px, 96%);
        margin: 0 auto 60px;
        padding: 40px 0 20px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 38px;
    }

    .page-header h2 {
        margin: 0;
        font-size: clamp(2.4rem, 3vw, 3.4rem);
        letter-spacing: 0.04em;
        line-height: 1.05;
    }

    .page-header p {
        margin: 16px auto 0;
        max-width: 760px;
        color: #cbd5e1;
        font-size: 1rem;
        line-height: 1.8;
    }

    .promo-container {
        width: 100%;
    }

    .promo-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .promo-card {
        display: flex;
        flex-direction: column;
        background: rgba(15, 23, 42, 0.98);
        border: 1px solid rgba(148, 163, 184, 0.12);
        border-radius: 24px;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        box-shadow: 0 22px 55px rgba(15, 23, 42, 0.25);
    }

    .promo-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 34px 88px rgba(15, 23, 42, 0.32);
    }

    .promo-card img {
        width: 100%;
        height: 260px;
        object-fit: cover;
        display: block;
    }

    .promo-card__content {
        flex: 1;
        padding: 22px 22px 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .promo-card__title {
        margin: 0;
        font-size: 1.35rem;
        line-height: 1.2;
        color: #f8fafc;
    }

    .promo-card__info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        color: #94a3b8;
        font-size: 0.95rem;
    }

    .promo-card__info span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 12px;
        background: rgba(148, 163, 184, 0.08);
        border-radius: 14px;
        min-height: 44px;
    }

    .promo-card__action {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .promo-card__action a {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 12px 16px;
        border-radius: 999px;
        text-decoration: none;
        color: #ffffff;
        font-weight: 600;
        transition: background 0.25s ease;
    }

    .promo-card__action a.primary {
        background: #38bdf8;
    }

    .promo-card__action a.secondary {
        background: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
    }

    .promo-card__action a.primary:hover {
        background: #0ea5e9;
    }

    .promo-card__action a.secondary:hover {
        background: rgba(255, 255, 255, 0.14);
    }

    .promo-card__description {
        margin: 0;
        color: #cbd5e1;
        font-size: 0.97rem;
        line-height: 1.75;
    }

    @media (max-width: 760px) {
        .page-frame {
            padding: 32px 0 24px;
        }

        .page-header h2 {
            font-size: clamp(2rem, 5vw, 2.6rem);
        }

        .promo-card img {
            height: 220px;
        }
    }
</style>
<?php include '../Module/header.php'; ?>

<div class="page-frame">
    <section class="page-header">
        <h2>Khuyến mãi rực rỡ</h2>
        <p>Khám phá những ưu đãi vé cùng combo hấp dẫn, chính sách giảm giá và quà tặng chỉ có trong thời gian giới hạn.</p>
    </section>

    <section class="promo-container">
        <ul class="promo-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li class="promo-card">
                    <img src="../Anh_Khuyen_Mai/<?= htmlspecialchars($row['anh_khuyen_mai']) ?>" alt="<?= htmlspecialchars($row['ten_khuyenmai']) ?>">
                    <div class="promo-card__content">
                        <h3 class="promo-card__title"><?= htmlspecialchars($row['ten_khuyenmai']) ?></h3>
                        <div class="promo-card__info">
                            <span>Bắt đầu: <?= date('d/m/Y', strtotime($row['ngay_bat_dau'])) ?></span>
                            <span>Kết thúc: <?= date('d/m/Y', strtotime($row['ngay_ket_thuc'])) ?></span>
                        </div>
                        <p class="promo-card__description">Đừng bỏ lỡ cơ hội nhận ưu đãi hấp dẫn cho phim chiếu rạp yêu thích.</p>
                        <div class="promo-card__action">
                            <a href="ChiTietKhuyenMai.php?id=<?= $row['id_km'] ?>" class="primary">Xem chi tiết</a>
                            <a href="../Pages/NowMovies.php" class="secondary">Đặt vé ngay</a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </section>
</div>

<?php include '../Module/footer.php'; ?>