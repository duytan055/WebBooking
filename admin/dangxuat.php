<?php
session_start();

/* ===== XÓA SESSION ===== */

session_unset();

session_destroy();

/* ===== CHUYỂN TRANG ===== */

header("../LoginAndSign-up/login.php");

exit();
