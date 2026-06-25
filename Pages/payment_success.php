<?php
session_start();

// MÃ KHÓA BẢO MẬT VNPAY SANDBOX (Phải khớp chính xác với mã trong qr_payment.php)
$vnp_HashSecret = "XALUTWNKLSNVDVIPYIUKPJDZOLXOLHRE"; 

// Khởi tạo các giá trị hiển thị mặc định tránh lỗi
$order_id = 'Trống';
$amount = 0;
$method = 'Chưa xác định';
$seats = 'Chưa chọn ghế';
$status_text = "XÁC NHẬN ĐƠN HÀNG";
$status_color = "#38bdf8"; 
$sub_text = "Hệ thống đang chờ thông tin thanh toán.";
$is_success = false;

// LUỒNG 1: XỬ LÝ KHI NHẬN PHẢN HỒI THẬT TỪ CỔNG VNPAY (GET)
if (isset($_GET['vnp_SecureHash'])) {
    $vnp_SecureHash = $_GET['vnp_SecureHash'];
    $inputData = array();
    
    foreach ($_GET as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }
    unset($inputData['vnp_SecureHash']);
    ksort($inputData);
    
    $hashData = "";
    $i = 0;
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashData .= '&' . rawurlencode($key) . "=" . rawurlencode($value);
        } else {
            $hashData .= rawurlencode($key) . "=" . rawurlencode($value);
            $i = 1;
        }
    }

    // Tính toán lại mã hóa từ dữ liệu nhận về xem có khớp với chữ ký của VNPAY gửi sang không
    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    
    if ($secureHash == $vnp_SecureHash) {
        $order_id = $_GET['vnp_TxnRef'];
        $amount = $_GET['vnp_Amount'] / 100;
        $method = "VNPAY SANDBOX";
        
        // Lấy lại danh sách ghế lưu trong session lúc nãy
        $seats = isset($_SESSION['last_selected_seats']) ? $_SESSION['last_selected_seats'] : 'Vé xem phim';
        
        // Mã '00' có nghĩa là bạn đã nhập tài khoản test thành công trên VNPAY
        if ($_GET['vnp_ResponseCode'] == '00') {
            $is_success = true;
            $status_text = "THANH TOÁN VNPAY THÀNH CÔNG";
            $status_color = "#10b981";
            $sub_text = "Giao dịch hợp lệ. Chúc mừng bạn đã đặt vé xem phim thành công!";
        } else {
            $status_text = "GIAO DỊCH THẤT BẠI / HỦY";
            $status_color = "#ef4444";
            $sub_text = "Giao dịch không thành công hoặc bạn đã hủy thao tác trên cổng VNPAY.";
        }
    } else {
        $status_text = "LỖI CHỮ KÝ BẢO MẬT";
        $status_color = "#f59e0b";
        $sub_text = "Chữ ký kiểm tra từ VNPAY không hợp lệ, vui lòng kiểm tra lại mã bí mật HashSecret.";
    }
} 
// LUỒNG 2: XỬ LÝ KHI CLICK THÀNH CÔNG ẢO TỪ TRANG TỰ CHẾ (MOMO/QR BANKING)
else if (isset($_GET['mock_success']) && $_GET['mock_success'] == '1') {
    $order_id = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'MOCK' . time();
    $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 0;
    $method = isset($_GET['method']) ? htmlspecialchars($_GET['method']) : 'MOMO';
    $seats = isset($_GET['seats']) ? htmlspecialchars(urldecode($_GET['seats'])) : 'Chưa chọn ghế';
    
    $is_success = true;
    $status_text = "THANH TOÁN THÀNH CÔNG (GIẢ LẬP)";
    $status_color = "#10b981";
    $sub_text = "Hệ thống đã ghi nhận thanh toán thành công thông qua phương thức mô phỏng [$method].";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả thanh toán</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #0f172a; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; color: #f8fafc; }
        .success-card { background: #1e293b; padding: 35px; border-radius: 12px; width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .icon { font-size: 48px; margin-bottom: 15px; color: <?= $status_color ?>; }
        h2 { margin: 0 0 10px 0; color: <?= $status_color ?>; font-size: 1.4rem; }
        .details { background: #273549; border-radius: 8px; padding: 15px; text-align: left; margin: 20px 0; font-size: 0.95rem; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; border-bottom: 1px solid #334155; padding-bottom: 6px; }
        .detail-row:last-child { margin-bottom: 0; border-bottom: none; padding-bottom: 0; }
        .label { color: #94a3b8; }
        .btn-home { background: #10b981; color: white; padding: 12px; width: 100%; border: none; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; text-decoration: none; display: block; box-sizing: border-box; }
        .btn-home:hover { background: #059669; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon"><?= $is_success ? '✓' : '✕' ?></div>
    <h2><?= $status_text ?></h2>
    <p style="color: #cbd5e1; font-size: 0.9rem; line-height: 1.4;"><?= $sub_text ?></p>
    
    <div class="details">
        <div class="detail-row">
            <span class="label">Mã hóa đơn:</span>
            <strong><?= htmlspecialchars($order_id) ?></strong>
        </div>
        <div class="detail-row">
            <span class="label">Phương thức:</span>
            <strong><?= htmlspecialchars($method) ?></strong>
        </div>
        <div class="detail-row">
            <span class="label">Ghế đặt xem:</span>
            <strong style="color: #38bdf8;"><?= htmlspecialchars($seats) ?></strong>
        </div>
        <div class="detail-row">
            <span class="label">Tổng tiền:</span>
            <strong style="color: #e11d48;"><?= number_format($amount, 0, ',', '.') ?> đ</strong>
        </div>
    </div>
    
    <a href="byesticker.php" class="btn-home">QUAY VỀ TRANG CHỦ</a>
</div>

</body>
</html>