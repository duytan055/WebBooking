<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

// Kiểm tra ID phim
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = 'Không tìm thấy ID phim!';
    header('Location: phim.php');
    exit;
}

$id_phim = mysqli_real_escape_string($conn, $_GET['id']);

// Lấy thông tin phim để xóa file ảnh
$sql = "SELECT poster, hinh_anh FROM phim WHERE id_phim = '$id_phim'";
$kq = mysqli_query($conn, $sql);
$phim = mysqli_fetch_assoc($kq);

if ($phim) {
    // Xóa các bản ghi liên quan trong bảng phim_dienvien
    $sql_delete_dienvien = "DELETE FROM phim_dienvien WHERE id_phim = '$id_phim'";
    mysqli_query($conn, $sql_delete_dienvien);

    // Xóa các bản ghi liên quan trong bảng phim_daodien
    $sql_delete_daodien = "DELETE FROM phim_daodien WHERE id_phim = '$id_phim'";
    mysqli_query($conn, $sql_delete_daodien);

    // Xóa các suất chiếu liên quan
    $sql_delete_suat = "DELETE FROM suatchieu WHERE id_phim = '$id_phim'";
    mysqli_query($conn, $sql_delete_suat);

    // Xóa phim
    $sql_delete = "DELETE FROM phim WHERE id_phim = '$id_phim'";

    if (mysqli_query($conn, $sql_delete)) {
        // Xóa file ảnh nếu tồn tại
        if ($phim['poster'] && file_exists("../poster/" . $phim['poster'])) {
            unlink("../poster/" . $phim['poster']);
        }
        if ($phim['hinh_anh'] && file_exists("../poster/" . $phim['hinh_anh'])) {
            unlink("../poster/" . $phim['hinh_anh']);
        }

        $_SESSION['success_message'] = 'Xóa phim thành công!';
        header('Location: phim.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'Lỗi: ' . mysqli_error($conn);
        header('Location: phim.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = 'Không tìm thấy phim!';
    header('Location: phim.php');
    exit;
}
