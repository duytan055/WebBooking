<?php
include 'connecDB.php';
$sql = "SELECT * FROM phim WHERE trang_thai = 'Sắp khởi chiếu' ";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data = [
        'title' => $row['ten_phim'],
        'time' => $row['thoi_luong'],
        'date' => $row['ngay_khoi_chieu'],
        'img' => $row['poster'],
        'trailer' => $row['trailer']
    ];
}
echo json_encode($data);
