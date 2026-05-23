<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

$sql = "SELECT 
            p.id_phim,
            p.ten_phim,
            p.poster,
            p.the_loai,
            p.thoi_luong,
            COUNT(DISTINCT ct.id_ve) as so_luong_ve,
            COALESCE(SUM(ct.gia_ve), 0) as doanh_thu,
            GROUP_CONCAT(DISTINCT dd.ten_dao_dien SEPARATOR ', ') as dao_dien
        FROM phim p
        LEFT JOIN suatchieu sc ON p.id_phim = sc.id_phim
        LEFT JOIN chitietve ct ON sc.id_suat = ct.id_suat AND ct.trang_thai = 'Đã thanh toán'
        LEFT JOIN phim_daodien pd ON p.id_phim = pd.id_phim
        LEFT JOIN daodien dd ON pd.id_daodien = dd.id_daodien
        WHERE p.trang_thai IN ('Đang chiếu', 'Sắp chiếu')
        GROUP BY p.id_phim
        HAVING so_luong_ve > 0
        ORDER BY so_luong_ve DESC, doanh_thu DESC
        LIMIT 50";

$result = $conn->query($sql);
$topMovies = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $topMovies[] = $row;
    }
}

$totalMovies = count($topMovies);
$totalRevenue = array_sum(array_column($topMovies, 'doanh_thu'));
$totalTickets = array_sum(array_column($topMovies, 'so_luong_ve'));
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Phim - Bảng Xếp Hạng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top, rgba(56, 189, 248, 0.12), transparent 28%),
                linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #f8fafc;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            padding-bottom: 60px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Section */
        .header-section {
            padding: 40px 0 32px;
            margin-bottom: 32px;
        }

        .header-content {
            display: flex;
            align-items: flex-end;
            gap: 32px;
            margin-bottom: 32px;
        }

        .header-icon {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.2), rgba(56, 189, 248, 0.05));
            border: 2px solid rgba(56, 189, 248, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: #38bdf8;
            box-shadow: 0 8px 32px rgba(56, 189, 248, 0.2);
        }

        .header-info h1 {
            font-size: 72px;
            font-weight: 900;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }

        .header-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-meta i {
            color: #38bdf8;
        }

        .dot {
            width: 4px;
            height: 4px;
            background: #38bdf8;
            border-radius: 50%;
        }

        /* Stats Bar */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(56, 189, 248, 0.1);
            padding: 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-2px);
        }

        .stat-label {
            font-size: 13px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #38bdf8;
        }

        /* Table Section */
        .table-section {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(56, 189, 248, 0.1);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 40px;
        }

        /* Table Header */
        .table-header {
            display: grid;
            grid-template-columns: 70px 1fr 180px 140px 140px;
            gap: 16px;
            padding: 12px 20px;
            border-bottom: 2px solid rgba(56, 189, 248, 0.2);
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* Movie List */
        .movie-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .movie-item {
            display: grid;
            grid-template-columns: 70px 1fr 180px 140px 140px;
            gap: 16px;
            padding: 16px 20px;
            border-radius: 12px;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .movie-item:hover {
            background: rgba(56, 189, 248, 0.05);
            border-color: rgba(56, 189, 248, 0.2);
            transform: translateX(4px);
        }

        /* Rank Column */
        .rank {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
        }

        .rank.top-1 {
            color: #fbbf24;
            font-size: 28px;
        }

        .rank.top-2 {
            color: #d1d5db;
            font-size: 26px;
        }

        .rank.top-3 {
            color: #fb923c;
            font-size: 24px;
        }

        /* Movie Info */
        .movie-info {
            display: flex;
            gap: 16px;
            align-items: center;
            min-width: 0;
        }

        .movie-poster {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid rgba(56, 189, 248, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .movie-details {
            min-width: 0;
            flex: 1;
        }

        .movie-title {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .movie-item:hover .movie-title {
            color: #38bdf8;
        }

        .movie-meta {
            font-size: 13px;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Stats Columns */
        .genre {
            font-size: 14px;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tickets {
            font-size: 15px;
            color: #ffffff;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tickets i {
            color: #38bdf8;
            font-size: 16px;
        }

        .revenue {
            font-size: 15px;
            color: #38bdf8;
            font-weight: 700;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #38bdf8;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .empty-state p {
            font-size: 16px;
            color: #94a3b8;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .header-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .header-icon {
                width: 160px;
                height: 160px;
                font-size: 64px;
            }

            .header-info h1 {
                font-size: 56px;
            }

            .table-header,
            .movie-item {
                grid-template-columns: 60px 1fr 120px 110px;
            }

            .genre {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }

            .header-icon {
                width: 140px;
                height: 140px;
                font-size: 48px;
            }

            .header-info h1 {
                font-size: 42px;
            }

            .stats-bar {
                grid-template-columns: 1fr;
            }

            .table-header {
                display: none;
            }

            .movie-item {
                grid-template-columns: 50px 1fr 90px;
                gap: 12px;
                padding: 12px 16px;
            }

            .tickets {
                display: none;
            }

            .movie-poster {
                width: 50px;
                height: 50px;
            }

            .movie-title {
                font-size: 14px;
            }

            .movie-meta {
                font-size: 12px;
            }

            .revenue {
                font-size: 13px;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .movie-item {
            animation: fadeInUp 0.4s ease-out backwards;
        }

        .movie-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .movie-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .movie-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .movie-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .movie-item:nth-child(5) {
            animation-delay: 0.25s;
        }
    </style>
</head>

<?php include __DIR__ . '/../Module/header.php'; ?>

<body>
    <div class="page-wrapper">
        <div class="content-wrapper">
            <div class="container">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="header-info">
                            <h1>Top Phim</h1>
                            <div class="header-meta">
                                <span><i class="fas fa-chart-line"></i> Bảng xếp hạng</span>
                                <span class="dot"></span>
                                <span><?= $totalMovies ?> phim</span>
                                <span class="dot"></span>
                                <span><?= number_format($totalTickets) ?> vé đã bán</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Bar -->
                    <div class="stats-bar">
                        <div class="stat-card">
                            <div class="stat-label"><i class="fas fa-film"></i> Tổng phim</div>
                            <div class="stat-value"><?= $totalMovies ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label"><i class="fas fa-ticket-alt"></i> Tổng vé bán</div>
                            <div class="stat-value"><?= number_format($totalTickets) ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label"><i class="fas fa-money-bill-wave"></i> Tổng doanh thu</div>
                            <div class="stat-value"><?= number_format($totalRevenue) ?>đ</div>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <?php if ($totalMovies > 0) : ?>
                    <div class="table-section">
                        <!-- Table Header -->
                        <div class="table-header">
                            <div>#</div>
                            <div>Tên phim</div>
                            <div>Thể loại</div>
                            <div>Số vé bán</div>
                            <div>Doanh thu</div>
                        </div>

                        <!-- Movie List -->
                        <div class="movie-list">
                            <?php foreach ($topMovies as $index => $movie) :
                                $rank = $index + 1;
                                $rankClass = '';
                                if ($rank === 1) $rankClass = 'top-1';
                                elseif ($rank === 2) $rankClass = 'top-2';
                                elseif ($rank === 3) $rankClass = 'top-3';

                                $poster = !empty($movie['poster']) ? $movie['poster'] : '../LoginAndSign-up/image1.webp';
                                $director = !empty($movie['dao_dien']) ? $movie['dao_dien'] : 'Đang cập nhật';
                            ?>
                                <div class="movie-item">
                                    <div class="rank <?= $rankClass ?>">
                                        <?php if ($rank <= 3) : ?>
                                            <i class="fas fa-crown"></i>
                                        <?php else : ?>
                                            <?= $rank ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="movie-info">
                                        <img src="<?= htmlspecialchars($poster) ?>"
                                            alt="<?= htmlspecialchars($movie['ten_phim']) ?>"
                                            class="movie-poster"
                                            onerror="this.src='../LoginAndSign-up/image1.webp'">
                                        <div class="movie-details">
                                            <div class="movie-title"><?= htmlspecialchars($movie['ten_phim']) ?></div>
                                            <div class="movie-meta">
                                                <?= htmlspecialchars($director) ?>
                                                <?php if ($movie['thoi_luong']) : ?>
                                                    • <?= $movie['thoi_luong'] ?> phút
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="genre"><?= htmlspecialchars($movie['the_loai']) ?></div>

                                    <div class="tickets">
                                        <i class="fas fa-ticket-alt"></i>
                                        <?= number_format($movie['so_luong_ve']) ?>
                                    </div>

                                    <div class="revenue">
                                        <?= number_format($movie['doanh_thu']) ?>đ
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="table-section">
                        <div class="empty-state">
                            <i class="fas fa-film"></i>
                            <h3>Chưa có dữ liệu</h3>
                            <p>Hiện tại chưa có phim nào được đặt vé. Hãy quay lại sau!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include __DIR__ . '/../Module/footer.php'; ?>
    </div>
</body>

</html>