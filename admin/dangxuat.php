<?php
session_start();

/* ===== XÓA SESSION ===== */

session_unset();

session_destroy();

/* ===== CHUYỂN TRANG ===== */

header("location:dangnhap.php");

exit();

?>

<li>
    <a href="dangxuat.php">
        <i class="fas fa-right-from-bracket"></i>
        Đăng xuất
    </a>
</li>