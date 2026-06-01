<?php
include __DIR__ . '/../Connect/connecDB.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Xóa suất chiếu
    $sql = "DELETE FROM suatchieu WHERE id_suat = $id";

    if (mysqli_query($conn, $sql)) {
        header('Location: suatchieu.php?msg=deleted');
    } else {
        header('Location: suatchieu.php?msg=error');
    }
} else {
    header('Location: suatchieu.php');
}
exit();
