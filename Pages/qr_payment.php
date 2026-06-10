<?php
session_start();

// Buộc hệ thống PHP nhận diện đúng múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 1. Nhận dữ liệu thô gửi sang từ file buyticket.js
$method = isset($_POST['method']) ? trim($_POST['method']) : '';
$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 50000; 
$seats = isset($_POST['seats']) ? trim($_POST['seats']) : 'Ghe';
$combo = isset($_POST['combo']) ? $_POST['combo'] : 'Khong';

$_SESSION['last_selected_seats'] = $seats;
$method_upper = strtoupper($method);

// =================================================================
// LUỒNG THANH TOÁN VNPAY CHÍNH CHỦ - FIX ĐỒNG BỘ THỜI GIAN THỰC
// =================================================================
if (!empty($method_upper) && strpos($method_upper, 'VNPAY') !== false) {
    
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_TmnCode = "G3NW26SU"; // Mã của bạn
    $vnp_HashSecret = "WOPZAI748UCHDC1PDFJQUNDA42TEI8GF"; // Mã bảo mật của bạn
    $vnp_ReturnUrl = "http://localhost/ChayDocLap/payment_success.php"; 

    $vnp_TxnRef = time(); // Dùng timestamp hệ thống làm mã đơn hàng độc nhất
    $vnp_OrderInfo = "Thanh toan don hang ve xem phim";

    $vnp_Params = array(
        "vnp_Version" => "2.1.0",
        "vnp_Command" => "pay",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $amount * 100, // Đơn vị xu
        "vnp_CurrCode" => "VND",
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => "billpayment",
        "vnp_ReturnUrl" => $vnp_ReturnUrl,
        "vnp_Locale" => "vn",
        "vnp_IpAddr" => "127.0.0.1", // Ép thẳng IP cố định để tránh lệch chuỗi băm ở localhost
        "vnp_CreateDate" => date('YmdHis') // Tạo thời gian định dạng chuẩn YmdHis
    );

    // Bước 1: Sắp xếp mảng theo thứ tự chữ cái từ A-Z trước khi băm
    ksort($vnp_Params);
    
    // Bước 2: Tạo chuỗi băm (HashData) và chuỗi URL Request theo đúng chuẩn cổng kết nối VNPAY
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($vnp_Params as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . rawurlencode($key) . "=" . rawurlencode($value);
            $query .= '&' . rawurlencode($key) . "=" . rawurlencode($value);
        } else {
            $hashdata .= rawurlencode($key) . "=" . rawurlencode($value);
            $query .= rawurlencode($key) . "=" . rawurlencode($value);
            $i = 1;
        }
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    
    // Bước 3: Tạo mã băm SHA512 bảo mật
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
    }
    
    // Thực hiện chuyển hướng tự động sang cổng VNPAY Sandbox
    header('Location: ' . $vnp_Url);
    exit();
}

// =================================================================
// LUỒNG GIẢ LẬP KHÁC (GIỮ NGUYÊN ĐỂ KHÔNG LỖI HỆ THỐNG DỰ PHÒNG)
// =================================================================
if (!empty($method_upper) && strpos($method_upper, 'MOMO') !== false) {
    $qr_image_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode("MOMO_MOCK_" . $amount);
    $display_title = "CỔNG GIẢ LẬP VÍ ĐIỆN TỬ MOMO";
    $theme_color = "#a50064";
} else {
    $qr_image_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode("BANK_MOCK_" . $amount);
    $display_title = "CỔNG THANH TOÁN QR BANKING CHUYỂN KHOẢN";
    $theme_color = "#0056b3";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quét mã thanh toán</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #0f172a; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; color: #f8fafc; }
        .payment-card { background: #1e293b; border-radius: 12px; padding: 30px; width: 400px; text-align: center; border-top: 5px solid <?= $theme_color ?>; box-shadow: 0 8px 20px rgba(0,0,0,0.3); }
        .title { font-size: 1.2rem; font-weight: bold; margin-bottom: 20px; color: #f1f5f9; }
        .info-box { background: #334155; padding: 15px; border-radius: 8px; text-align: left; margin-bottom: 20px; font-size: 0.95rem; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .amount { color: #f43f5e; font-size: 1.2rem; font-weight: bold; }
        .qr-container { background: #fff; padding: 10px; border-radius: 8px; display: inline-block; margin-bottom: 20px; }
        .qr-container img { width: 220px; height: 220px; display: block; }
        .btn-success { background: #10b981; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; text-decoration: none; display: block; box-sizing: border-box; }
    </style>
</head>
<body>
<div class="payment-card">
    <div class="title"><?= $display_title ?></div>
    <div class="info-box">
        <div class="info-row"><span>Mã hóa đơn:</span><strong><?= date("YmdHis") ?></strong></div>
        <div class="info-row"><span>Ghế đặt mua:</span><strong><?= htmlspecialchars($seats) ?></strong></div>
        <div class="info-row" style="margin-top: 10px; border-top: 1px dashed #475569; padding-top: 10px;">
            <span>Số tiền:</span><span class="amount"><?= number_format($amount, 0, ',', '.') ?> đ</span>
        </div>
    </div>
    <div class="qr-container"><img src="<?= $qr_image_url ?>" alt="QR"></div>
    <a href="payment_success.php?vnp_ResponseCode=00&vnp_SecureHash=VALID_MOCK&vnp_TxnRef=<?= date("YmdHis") ?>&vnp_Amount=<?= $amount * 100 ?>" class="btn-success">XÁC NHẬN ĐÃ CHUYỂN TIỀN THÀNH CÔNG</a>
</div>
</body>
</html>