<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <title>Chính sách - BookingMovies</title>
    <style>
        body {
            height: 100%;
            margin: 0;
            background: radial-gradient(circle at top, rgba(56, 189, 248, 0.12), transparent 28%),
                linear-gradient(180deg, #0f172a 0%, #111827 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .policy-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            color: white;
            min-height: 500px;
        }

        .policy-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #e50914;
            border-bottom: 2px solid #e50914;
            padding-bottom: 15px;
        }

        .policy-content {
            font-size: 16px;
            line-height: 1.8;
            color: #ddd;
            text-align: justify;
        }

        .policy-date {
            font-size: 14px;
            color: #999;
            margin-top: 30px;
            font-style: italic;
        }

        .policy-nav {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .policy-nav a {
            padding: 10px 20px;
            background: rgba(229, 9, 20, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            border: 1px solid #e50914;
        }

        .policy-nav a:hover,
        .policy-nav a.active {
            background: #e50914;
        }

        .error-message {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #999;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    include '../Connect/connecDB.php';
    include '../Module/header.php';

    // Get page parameter
    $page = isset($_GET['page']) ? $_GET['page'] : 'bao-mat';

    // Map URL parameters to database types
    $pageMap = [
        'bao-mat' => 'baomat',
        'thanh-toan' => 'thanhtoan',
        'giao-nhan' => 'hoanve',
        'dieu-khoan-chung' => 'dieukhoan'
    ];

    $loai = isset($pageMap[$page]) ? $pageMap[$page] : 'baomat';

    // Fetch policy from database
    $sql = "SELECT * FROM chinhsach WHERE loai = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $loai);
    $stmt->execute();
    $result = $stmt->get_result();
    $policy = $result->fetch_assoc();
    ?>

    <div class="policy-container">
        <div class="policy-nav">
            <a href="?page=bao-mat" class="<?php echo $page == 'bao-mat' ? 'active' : ''; ?>">Chính sách bảo mật</a>
            <a href="?page=thanh-toan" class="<?php echo $page == 'thanh-toan' ? 'active' : ''; ?>">Chính sách thanh toán</a>
            <a href="?page=giao-nhan" class="<?php echo $page == 'giao-nhan' ? 'active' : ''; ?>">Chính sách hoàn vé</a>
            <a href="?page=dieu-khoan-chung" class="<?php echo $page == 'dieu-khoan-chung' ? 'active' : ''; ?>">Điều khoản sử dụng</a>
        </div>

        <?php if ($policy): ?>
            <h1 class="policy-title"><?php echo htmlspecialchars($policy['tieu_de']); ?></h1>
            <div class="policy-content">
                <?php echo nl2br(htmlspecialchars($policy['noi_dung'])); ?>
            </div>
            <div class="policy-date">
                Cập nhật lần cuối: <?php echo date('d/m/Y H:i', strtotime($policy['ngay_cap_nhat'])); ?>
            </div>
        <?php else: ?>
            <div class="error-message">
                <i class="fa-solid fa-exclamation-circle" style="font-size: 48px; margin-bottom: 20px;"></i>
                <p>Không tìm thấy nội dung chính sách.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../Module/footer.php'; ?>
</body>

</html>