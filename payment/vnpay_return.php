<?php
require_once __DIR__ . '/../Connect/connecDB.php';
require_once __DIR__ . '/../Module/sendMail.php';

if ($_GET['vnp_ResponseCode'] == '00') {

    $id_datve = $_GET['vnp_TxnRef'];
    $ma_giao_dich = $_GET['vnp_TransactionNo'];

    $sql = "UPDATE datve
            SET trang_thai = 'PAID',
                phuong_thuc_thanh_toan = 'VNPAY',
                ma_giao_dich = ?
            WHERE id_datve = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $ma_giao_dich, $id_datve);
    $stmt->execute();

    // TEST LOG
    error_log("CALL SEND MAIL: " . $id_datve);

    $result = sendTicketEmail($id_datve, $conn);

    if (!$result) {
        error_log("SEND MAIL FAILED: " . $id_datve);
    }

    header("Location: ../Pages/ticket_success.php?id_datve=" . $id_datve);
    exit();
}
