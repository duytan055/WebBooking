<?php
include __DIR__ . '/../Connect/connecDB.php';

$sql = "SELECT * FROM tintuc
        WHERE trang_thai = 'Đã xuất bản'
        ORDER BY ngay_dang DESC";

$result = $conn->query($sql);
?>

<style>
    body {
        margin: 0;
        font-family: 'Inter', Arial, Helvetica, sans-serif;
        color: #f4f4f4;
        background: #070b16;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: linear-gradient(135deg, rgba(19, 24, 44, 0.92), rgba(3, 10, 29, 0.92)),
            url('../LoginAndSign-up/image1.webp') center/cover no-repeat;
        filter: brightness(0.55);
        z-index: -1;
    }

    .CinemaNews {
        width: min(1200px, 100%);
        margin: 0 auto;
        padding: 40px 24px 80px;
    }

    .CinemaNews__hero {
        text-align: center;
        padding: 40px 30px 50px;
        margin-bottom: 32px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.28);
        backdrop-filter: blur(14px);
    }

    .CinemaNews__hero h2 {
        margin: 0 0 18px;
        font-size: clamp(2.4rem, 4vw, 4.2rem);
        letter-spacing: -0.04em;
        color: #ffffff;
    }

    .CinemaNews__hero p {
        margin: 0 auto;
        max-width: 760px;
        line-height: 1.8;
        color: #cbd5e1;
        font-size: 1rem;
    }

    .news-box {
        display: grid;
        gap: 24px;
    }

    .news-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
    }

    .news-card {
        display: grid;
        grid-template-rows: auto 1fr;
        background: rgba(12, 18, 40, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 28px 75px rgba(0, 0, 0, 0.25);
        transition: transform 0.35s ease, border-color 0.35s ease;
    }

    .news-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.16);
    }

    .news-card__image {
        width: 100%;
        min-height: 220px;
        overflow: hidden;
        position: relative;
    }

    .news-card__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .news-card:hover .news-card__image img {
        transform: scale(1.05);
    }

    .news-card__body {
        padding: 24px;
        display: grid;
        gap: 16px;
    }

    .news-card__title {
        margin: 0;
        font-size: 1.5rem;
        line-height: 1.2;
        color: #fff;
    }

    .news-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        color: #94a3b8;
        font-size: 0.95rem;
    }

    .news-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .news-desc {
        margin: 0;
        color: #d1d5db;
        line-height: 1.8;
        min-height: 5.2rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
    }

    .news-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .news-footer__tag {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.9rem;
    }

    .news-btn a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        color: #fff;
        background: linear-gradient(135deg, #e11d48, #0ea5e9);
        border-radius: 999px;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .news-btn a:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 32px rgba(14, 165, 233, 0.25);
    }

    .news-empty {
        padding: 60px 20px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        text-align: center;
        color: #cbd5e1;
    }

    .news-empty h3 {
        margin-bottom: 12px;
        font-size: 1.8rem;
        color: #fff;
    }

    @media (max-width: 820px) {
        .CinemaNews {
            padding: 28px 18px 60px;
        }
    }

    @media (max-width: 560px) {
        .news-card__title {
            font-size: 1.35rem;
        }

        .news-btn a {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php include __DIR__ . '/../Module/header.php'; ?>

<div class="CinemaNews">
    <section class="CinemaNews__hero">
        <h2>Góc điện ảnh</h2>
        <p>Đón đọc những bài viết mới nhất về phim ảnh, khuyến mãi, review và tin tức ngành giải trí. Dữ liệu được lấy trực tiếp từ cơ sở dữ liệu và hiển thị theo thứ tự mới nhất.</p>
    </section>

    <div class="news-box">
        <?php if ($result && $result->num_rows > 0) : ?>
            <div class="news-list">
                <?php while ($row = $result->fetch_assoc()) :
                    $image = trim($row['hinh_anh']) !== '' ? $row['hinh_anh'] : '../LoginAndSign-up/image1.webp';
                    $text = trim($row['mo_ta']);
                    $excerpt = htmlspecialchars(function_exists('mb_substr') ? mb_substr($text, 0, 160, 'UTF-8') : substr($text, 0, 160));
                ?>
                    <article class="news-card">
                        <div class="news-card__image">
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($row['tieu_de']) ?>">
                        </div>
                        <div class="news-card__body">
                            <h3 class="news-card__title"><?= htmlspecialchars($row['tieu_de']) ?></h3>
                            <div class="news-meta">
                                <span>👤 Admin</span>
                                <span>📅 <?= date('d/m/Y', strtotime($row['ngay_dang'])) ?></span>
                            </div>
                            <p class="news-desc"><?= $excerpt ?></p>
                            <div class="news-footer">
                                <span class="news-footer__tag">Tin tức phim ảnh</span>
                                <div class="news-btn">
                                    <a href="ChiTietTinTuc.php?id=<?= (int)$row['id_tintuc'] ?>">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="news-empty">
                <h3>Chưa có bài viết nào</h3>
                <p>Danh sách tin tức đang trống. Vui lòng kiểm tra lại sau hoặc thêm dữ liệu vào bảng <strong>tintuc</strong> trong cơ sở dữ liệu.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../Module/footer.php'; ?>