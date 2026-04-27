<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demosqlweb";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra lỗi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fix lỗi tiếng Việt
$conn->set_charset("utf8");
