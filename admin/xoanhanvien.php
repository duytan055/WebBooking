<?php
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_GET['id'])) {
    header('Location: nhanvien.php');
    exit;
}

$id_nhanvien = mysqli_real_escape_string($conn, $_GET['id']);
$sql_delete = "DELETE FROM nhanvien WHERE id_nhanvien = '$id_nhanvien'";

if (mysqli_query($conn, $sql_delete)) {
    echo "<script>alert('Xóa nhân viên thành công!'); window.location.href='nhanvien.php';</script>";
} else {
    echo "<script>alert('Lỗi: " . mysqli_error($conn) . "'); window.location.href='nhanvien.php';</script>";
}
