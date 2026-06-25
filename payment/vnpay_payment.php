<?php

require_once '../config/vnpay.php';
require_once '../Connect/connecDB.php';

$id_datve = $_GET['id_datve'] ?? null;

$sql = "SELECT *
FROM datve
WHERE id_datve = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_datve);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

date_default_timezone_set('Asia/Ho_Chi_Minh');

$amount = $row['tong_tien'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $amount * 100,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan ve phim #" . $id_datve,
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $id_datve
);

ksort($inputData);

$query = "";
$hashdata = "";

foreach ($inputData as $key => $value) {
    $hashdata .= '&' . urlencode($key)
        . "=" . urlencode($value);

    $query .= urlencode($key)
        . "=" . urlencode($value)
        . '&';
}

$hashdata = ltrim($hashdata, '&');

$vnpSecureHash = hash_hmac(
    "sha512",
    $hashdata,
    $vnp_HashSecret
);

$paymentUrl =
    $vnp_Url . "?"
    . $query
    . "vnp_SecureHash="
    . $vnpSecureHash;

header("Location: " . $paymentUrl);
exit;
