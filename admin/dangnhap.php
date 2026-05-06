<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (isset($_POST['dangnhap'])) {
    $tk = $_POST['taikhoan'];
    $mk = $_POST['matkhau'];

    $sql = "SELECT * FROM admin
            WHERE taikhoan='$tk'
            AND matkhau='$mk'";

    $kq = mysqli_query($conn, $sql);

    if (mysqli_num_rows($kq) > 0) {
        $_SESSION['admin'] = $tk;

        header("location:main.php");
    } else {
        $tb = "Sai tài khoản hoặc mật khẩu";
    }
}
