<?php
require_once '../Connect/connecDB.php';

$id_datve = (int)$_GET['id_datve'];

$sql = "SELECT
    dv.id_datve,
    dv.tong_tien,
    dv.ma_giao_dich,
    dv.ten_nguoi_dat,
    dv.so_dien_thoai,
    dv.trang_thai,
    dv.phuong_thuc_thanh_toan,
    nd.email,
    p.ten_phim,
    sc.date_chieu,
    sc.thoi_gian,
    ph.ten_phong
FROM datve dv
JOIN nguoidung nd ON dv.id_user = nd.id_user
JOIN suatchieu sc ON dv.id_suat = sc.id_suat
JOIN phim p ON sc.id_phim = p.id_phim
JOIN phongchieu ph ON sc.id_phong = ph.id_phong
WHERE dv.id_datve = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_datve);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

$sqlSeat = "SELECT g.ma_ghe
FROM chitietve ct
JOIN ghe g ON ct.id_ghe = g.id_ghe
WHERE ct.id_datve = ?
";

$stmtSeat = $conn->prepare($sqlSeat);
$stmtSeat->bind_param("i", $id_datve);
$stmtSeat->execute();
$rsSeat = $stmtSeat->get_result();

$seats = [];
while ($row = $rsSeat->fetch_assoc()) {
    $seats[] = $row['ma_ghe'];
}

// Format date
$dateObj = new DateTime($ticket['date_chieu']);
$dayOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
$dayName = $dayOfWeek[$dateObj->format('w')];
$formattedDate = $dayName . ', ' . $dateObj->format('d/m/Y');

// Payment status config
$statusMap = [
    'PAID' => ['label' => 'Đã thanh toán', 'class' => 'status-paid', 'icon' => 'fa-check-circle'],
    'PENDING' => ['label' => 'Chưa thanh toán', 'class' => 'status-pending', 'icon' => 'fa-clock'],
    'CANCELLED' => ['label' => 'Đã hủy', 'class' => 'status-cancelled', 'icon' => 'fa-times-circle'],
];
$status = $statusMap[$ticket['trang_thai']] ?? $statusMap['PENDING'];

// Format mã giao dịch đồng bộ: chữ + số, mỗi mã duy nhất
$maGiaoDich = $ticket['ma_giao_dich'] ?? '';
if (empty($maGiaoDich)) {
    // Chưa có mã → tạo mã tạm theo ID vé (trường hợp thanh toán tại quầy)
    $maGiaoDich = 'DH' . date('ymd') . str_pad($ticket['id_datve'], 5, '0', STR_PAD_LEFT);
} elseif (ctype_digit($maGiaoDich)) {
    // Mã từ VNPay là số thuần → format lại cho đồng bộ
    $maGiaoDich = 'VNPAY' . str_pad($maGiaoDich, 10, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết vé - WebBooking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, rgba(56, 189, 248, 0.08), transparent 28%),
                linear-gradient(180deg, #0f172a 0%, #111827 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #e2e8f0;
        }

        .container {
            width: 100%;
            max-width: 500px;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .page-header .icon-wrap {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
        }

        .page-header .icon-wrap i {
            font-size: 28px;
            color: #fff;
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
        }

        .page-header p {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Ticket Card */
        .ticket-card {
            background: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Status Banner */
        .status-banner {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .status-banner i {
            font-size: 14px;
        }

        .status-banner.status-paid {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
        }

        .status-banner.status-pending {
            background: rgba(250, 204, 21, 0.12);
            color: #facc15;
        }

        .status-banner.status-cancelled {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
        }

        /* Movie Info */
        .movie-section {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .movie-section .movie-name {
            font-size: 18px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .movie-section .ticket-id {
            font-size: 12px;
            color: #64748b;
        }

        .movie-meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .movie-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #94a3b8;
        }

        .movie-meta .meta-item i {
            width: 16px;
            color: #3b82f6;
            font-size: 12px;
        }

        /* Detail Rows */
        .detail-section {
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-row .label {
            font-size: 13px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-row .label i {
            width: 16px;
            font-size: 12px;
            color: #3b82f6;
        }

        .detail-row .value {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
            text-align: right;
        }

        .seats-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: flex-end;
        }

        .seat-tag {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        /* Total & Transaction */
        .footer-section {
            padding: 16px 20px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-row .label {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }

        .total-row .amount {
            font-size: 22px;
            font-weight: 800;
            color: #facc15;
        }

        .txn-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            border: 1px dashed rgba(255, 255, 255, 0.08);
        }

        .txn-row .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .txn-row .code {
            font-size: 13px;
            font-weight: 700;
            color: #e2e8f0;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .payment-method {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Buttons */
        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: inherit;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn:active {
            transform: scale(0.97);
        }

        .note {
            text-align: center;
            margin-top: 16px;
            font-size: 12px;
            color: #475569;
        }

        .note strong {
            color: #64748b;
        }

        /* Print */
        @media print {
            body {
                background: #fff;
                padding: 0;
                color: #333;
            }

            .page-header,
            .buttons,
            .note {
                display: none;
            }

            .ticket-card {
                border: 2px solid #333;
                box-shadow: none;
            }

            .ticket-card {
                background: #fff;
            }

            .status-banner {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .movie-section .movie-name {
                color: #111;
            }

            .detail-row .value {
                color: #111;
            }

            .total-row .amount {
                color: #d97706;
            }

            .txn-row .code {
                color: #333;
            }

            .seat-tag {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Responsive */
        @media (max-width: 540px) {
            body {
                padding: 16px;
            }

            .page-header h1 {
                font-size: 18px;
            }

            .movie-section .movie-name {
                font-size: 16px;
            }

            .total-row .amount {
                font-size: 18px;
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="page-header">
            <div class="icon-wrap">
                <i class="fas fa-check"></i>
            </div>
            <h1>ĐẶT VÉ THÀNH CÔNG</h1>
            <p>Vui lòng đến rạp trước giờ chiếu 15 phút</p>
        </div>

        <!-- Ticket Card -->
        <div class="ticket-card">

            <!-- Status Banner -->
            <div class="status-banner <?= $status['class'] ?>">
                <i class="fas <?= $status['icon'] ?>"></i>
                <span><?= $status['label'] ?></span>
            </div>

            <!-- Movie Info -->
            <div class="movie-section">
                <div class="movie-name"><?= htmlspecialchars($ticket['ten_phim']) ?></div>
                <div class="ticket-id">Mã vé: #<?= str_pad($ticket['id_datve'], 6, '0', STR_PAD_LEFT) ?></div>

                <div class="movie-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?= $formattedDate ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?= $ticket['thoi_gian'] ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-door-open"></i>
                        <span><?= htmlspecialchars($ticket['ten_phong']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="detail-section">
                <div class="detail-row">
                    <span class="label"><i class="fas fa-user"></i> Khách hàng</span>
                    <span class="value"><?= htmlspecialchars($ticket['ten_nguoi_dat']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label"><i class="fas fa-envelope"></i> Email</span>
                    <span class="value"><?= htmlspecialchars($ticket['email']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label"><i class="fas fa-chair"></i> Ghế ngồi</span>
                    <div class="seats-group">
                        <?php foreach ($seats as $seat): ?>
                            <span class="seat-tag"><?= htmlspecialchars($seat) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if (!empty($ticket['phuong_thuc_thanh_toan'])): ?>
                    <div class="detail-row">
                        <span class="label"><i class="fas fa-credit-card"></i> Thanh toán</span>
                        <span class="value payment-method"><?= htmlspecialchars($ticket['phuong_thuc_thanh_toan']) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="footer-section">
                <div class="total-row">
                    <span class="label">Tổng thanh toán</span>
                    <span class="amount"><?= number_format($ticket['tong_tien'], 0, ',', '.') ?> ₫</span>
                </div>

                <div class="txn-row">
                    <span class="label">Mã giao dịch</span>
                    <span class="code"><?= htmlspecialchars($maGiaoDich) ?></span>
                </div>
            </div>

        </div>

        <!-- Buttons -->
        <div class="buttons">
            <a href="trangChu.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Trang chủ
            </a>
        </div>

        <p class="note">
            <i class="fas fa-info-circle"></i>
            Vé đã được gửi đến số điện thoại đã đăng ký.
            Mã vé: <strong>#<?= str_pad($ticket['id_datve'], 6, '0', STR_PAD_LEFT) ?></strong>
        </p>

    </div>

</body>

</html>