<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = 'Không tìm thấy ID nhân viên!';
    header('Location: nhanvien.php');
    exit;
}

$id_nhanvien = mysqli_real_escape_string($conn, $_GET['id']);
$sql_delete = "DELETE FROM nhanvien WHERE id_nhanvien = '$id_nhanvien'";

if (mysqli_query($conn, $sql_delete)) {
    $_SESSION['success_message'] = 'Xóa nhân viên thành công!';
    header('Location: nhanvien.php');
    exit;
} else {
    $_SESSION['error_message'] = 'Lỗi: ' . mysqli_error($conn);
    header('Location: nhanvien.php');
    exit;
}
