<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

$id_km = isset($_GET['id']) ? intval($_GET['id']) : 0;
$row = null;

if ($id_km > 0) {
    $stmt = $conn->prepare("SELECT ten_khuyenmai, anh_khuyen_mai, noi_dung_km, giam_gia, ngay_bat_dau, ngay_ket_thuc, ma_km FROM khuyenmai WHERE id_km = ?");
    $stmt->bind_param("i", $id_km);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

if (!$row) {
    header("Location: KhuyenMai.php");
    exit();
}
?>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: radial-gradient(circle at 20% 50%, rgba(56, 189, 248, 0.15), transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(168, 85, 247, 0.15), transparent 50%),
            linear-gradient(180deg, #0a0e1a 0%, #0f172a 50%, #1e293b 100%);
        color: #f8fafc;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 10% 20%, rgba(56, 189, 248, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 90% 80%, rgba(168, 85, 247, 0.03) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .detail-wrapper {
        width: min(1280px, 96%);
        margin: 0 auto;
        padding: 40px 0 80px;
        position: relative;
        z-index: 1;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        margin-bottom: 32px;
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.2) 0%, rgba(14, 165, 233, 0.15) 100%);
        border: 1px solid rgba(56, 189, 248, 0.4);
        border-radius: 16px;
        color: #38bdf8;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(56, 189, 248, 0.15);
        position: relative;
        overflow: hidden;
    }

    .back-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s;
    }

    .back-button:hover::before {
        left: 100%;
    }

    .back-button:hover {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.3) 0%, rgba(14, 165, 233, 0.25) 100%);
        border-color: rgba(56, 189, 248, 0.6);
        transform: translateX(-6px) scale(1.02);
        box-shadow: 0 8px 30px rgba(56, 189, 248, 0.3);
    }

    .back-button svg {
        width: 22px;
        height: 22px;
        transition: transform 0.3s ease;
    }

    .back-button:hover svg {
        transform: translateX(-4px);
    }

    .detail-container {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.95) 100%);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 32px;
        overflow: hidden;
        box-shadow:
            0 30px 80px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        backdrop-filter: blur(20px);
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .detail-header {
        padding: 50px 50px 40px;
        background:
            linear-gradient(135deg, rgba(56, 189, 248, 0.12) 0%, rgba(168, 85, 247, 0.08) 100%),
            linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, transparent 100%);
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .detail-title {
        margin: 0 0 24px 0;
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        line-height: 1.15;
        color: #f8fafc;
        font-weight: 800;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 20px rgba(56, 189, 248, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #38bdf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        color: #cbd5e1;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }

    .detail-meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.15);
        border-radius: 14px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .detail-meta-item:hover {
        background: rgba(30, 41, 59, 0.8);
        border-color: rgba(56, 189, 248, 0.3);
        transform: translateY(-2px);
    }

    .discount-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 26px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
        border-radius: 16px;
        color: #ffffff;
        font-weight: 800;
        font-size: 1.2rem;
        box-shadow:
            0 8px 24px rgba(239, 68, 68, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.2) inset;
        position: relative;
        overflow: hidden;
        animation: glow 2s ease-in-out infinite;
    }

    @keyframes glow {

        0%,
        100% {
            box-shadow:
                0 8px 24px rgba(239, 68, 68, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset;
        }

        50% {
            box-shadow:
                0 8px 32px rgba(239, 68, 68, 0.7),
                0 0 20px rgba(239, 68, 68, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.3) inset;
        }
    }

    .discount-badge::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0% {
            left: -50%;
        }

        100% {
            left: 150%;
        }
    }

    .detail-content {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 50px;
        padding: 50px;
    }

    .content-main {
        display: flex;
        flex-direction: column;
        gap: 35px;
    }

    .promo-image-wrapper {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow:
            0 20px 60px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        transition: transform 0.5s ease;
        animation: imageFloat 0.8s ease-out;
        max-height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.2);
    }

    @keyframes imageFloat {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .promo-image-wrapper:hover {
        transform: scale(1.02);
    }

    .promo-image-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
        z-index: 1;
        pointer-events: none;
    }

    .promo-image {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .promo-image-wrapper:hover .promo-image {
        transform: scale(1.05);
    }

    .content-section {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.6) 100%);
        padding: 35px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        animation: slideInLeft 0.8s ease-out 0.2s both;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .content-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #38bdf8 0%, #a855f7 100%);
    }

    .section-title {
        margin: 0 0 20px 0;
        font-size: 1.6rem;
        color: #38bdf8;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-content {
        color: #e2e8f0;
        font-size: 1.08rem;
        line-height: 1.9;
        white-space: pre-wrap;
        text-align: justify;
    }

    .content-sidebar {
        display: flex;
        flex-direction: column;
        gap: 28px;
        animation: slideInRight 0.8s ease-out 0.3s both;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .sidebar-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.7) 100%);
        padding: 28px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: all 0.4s ease;
    }

    .sidebar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #38bdf8 0%, #a855f7 100%);
    }

    .sidebar-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(56, 189, 248, 0.2);
        border-color: rgba(56, 189, 248, 0.4);
    }

    .sidebar-card h3 {
        margin: 0 0 20px 0;
        font-size: 1.3rem;
        color: #f8fafc;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        color: #cbd5e1;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .info-row:hover {
        padding-left: 8px;
        border-color: rgba(56, 189, 248, 0.3);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #94a3b8;
        font-weight: 500;
    }

    .info-value {
        font-weight: 700;
        color: #38bdf8;
        font-size: 1.05rem;
    }

    .promo-code-box {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.2) 0%, rgba(168, 85, 247, 0.15) 100%);
        padding: 24px;
        border-radius: 16px;
        border: 2px dashed rgba(56, 189, 248, 0.5);
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: codePulse 2s ease-in-out infinite;
    }

    @keyframes codePulse {

        0%,
        100% {
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
        }

        50% {
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.5);
        }
    }

    .promo-code-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transform: rotate(45deg);
        animation: codeShine 3s infinite;
    }

    @keyframes codeShine {
        0% {
            left: -50%;
        }

        100% {
            left: 150%;
        }
    }

    .promo-code-label {
        display: block;
        margin-bottom: 12px;
        color: #cbd5e1;
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .promo-code {
        display: block;
        font-size: 1.8rem;
        font-weight: 800;
        color: #38bdf8;
        letter-spacing: 4px;
        font-family: 'Courier New', monospace;
        text-shadow: 0 0 20px rgba(56, 189, 248, 0.5);
        position: relative;
        z-index: 1;
    }

    @media (max-width: 1024px) {
        .detail-content {
            grid-template-columns: 1fr;
        }

        .content-sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .detail-header {
            padding: 30px 24px 24px;
        }

        .detail-content {
            padding: 30px 24px;
        }

        .detail-title {
            font-size: 1.8rem;
        }

        .content-section {
            padding: 20px;
        }
    }
</style>

<?php include '../Module/Header.php' ?>

<div class="detail-wrapper">
    <!-- Nút quay lại -->
    <a href="KhuyenMai.php" class="back-button">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Quay lại trang khuyến mãi
    </a>

    <div class="detail-container">
        <!-- Header với tiêu đề -->
        <div class="detail-header">
            <h1 class="detail-title"><?= htmlspecialchars($row['ten_khuyenmai']) ?></h1>
            <div class="detail-meta">
                <div class="detail-meta-item">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <?= date('d/m/Y', strtotime($row['ngay_bat_dau'])) ?> - <?= date('d/m/Y', strtotime($row['ngay_ket_thuc'])) ?>
                </div>
                <div class="discount-badge">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Giảm <?= htmlspecialchars($row['giam_gia']) ?>%
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="detail-content">
            <div class="content-main">
                <!-- Hình ảnh khuyến mãi -->
                <div class="promo-image-wrapper">
                    <img class="promo-image" src="../Anh_Khuyen_Mai/<?= htmlspecialchars($row['anh_khuyen_mai']) ?>" alt="<?= htmlspecialchars($row['ten_khuyenmai']) ?>">
                </div>

                <!-- Nội dung khuyến mãi -->
                <div class="content-section">
                    <h2 class="section-title">📋 Chi tiết chương trình</h2>
                    <div class="section-content"><?= nl2br(htmlspecialchars($row['noi_dung_km'])) ?></div>
                </div>
            </div>

            <!-- Sidebar thông tin -->
            <div class="content-sidebar">
                <!-- Thông tin khuyến mãi -->
                <div class="sidebar-card">
                    <h3>📊 Thông tin khuyến mãi</h3>
                    <div class="info-row">
                        <span class="info-label">Mức giảm giá:</span>
                        <span class="info-value"><?= htmlspecialchars($row['giam_gia']) ?>%</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày bắt đầu:</span>
                        <span class="info-value"><?= date('d/m/Y', strtotime($row['ngay_bat_dau'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày kết thúc:</span>
                        <span class="info-value"><?= date('d/m/Y', strtotime($row['ngay_ket_thuc'])) ?></span>
                    </div>
                </div>

                <!-- Mã khuyến mãi -->
                <?php if (!empty($row['ma_km'])): ?>
                    <div class="sidebar-card">
                        <h3>🎟️ Mã khuyến mãi</h3>
                        <div class="promo-code-box">
                            <span class="promo-code-label">Sử dụng mã:</span>
                            <span class="promo-code"><?= htmlspecialchars($row['ma_km']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../Module/Footer.php' ?>