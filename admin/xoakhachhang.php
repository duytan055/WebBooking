<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = 'Không tìm thấy ID khách hàng!';
    header('Location: khachhang.php');
    exit;
}

$id_user = mysqli_real_escape_string($conn, $_GET['id']);

// Xóa các bản ghi liên quan trong bảng datve và chitietve
$sql_get_datve = "SELECT id_datve FROM datve WHERE id_user = '$id_user'";
$kq_datve = mysqli_query($conn, $sql_get_datve);

while ($row = mysqli_fetch_assoc($kq_datve)) {
    $id_datve = $row['id_datve'];
    // Xóa chi tiết vé
    $sql_delete_chitietve = "DELETE FROM chitietve WHERE id_datve = '$id_datve'";
    mysqli_query($conn, $sql_delete_chitietve);
}

// Xóa đặt vé
$sql_delete_datve = "DELETE FROM datve WHERE id_user = '$id_user'";
mysqli_query($conn, $sql_delete_datve);

// Xóa khách hàng
$sql_delete = "DELETE FROM nguoidung WHERE id_user = '$id_user'";

if (mysqli_query($conn, $sql_delete)) {
    $_SESSION['success_message'] = 'Xóa khách hàng thành công!';
    header('Location: khachhang.php');
    exit;
} else {
    $_SESSION['error_message'] = 'Lỗi: ' . mysqli_error($conn);
    header('Location: khachhang.php');
    exit;
}
