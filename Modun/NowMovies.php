<?php
include '../connecDB.php';
$sql = "SELECT * FROM phim WHERE trang_thai = 'Đang chiếu'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Pages/style.css">
</head>

<body>
    <div class="box3">
        <div class="box3_nav"></div>
        <div style="font-size: 20px"><strong>Phim</strong></div>
        <div class="box3_menu">
            <ul class="menu_btn">
                <li class="nav_menu_items active" style="font-size: 20px">
                    Đang chiếu
                </li>
            </ul>
        </div>
    </div>
    <div class="box_movies">
        <ul class="Movies_list" id="movies_list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <div class="box_img">
                        <img src="<?= $row['poster'] ?>">
                        <div class="box_hover">
                            <button><a href="#" style="text-decoration: none; color: black;"><strong>Xem thêm</strong></a></button>
                            <br>
                            <button><a href="#" style="text-decoration: none; color: black;"><strong>Đặt vé</strong></a></button>
                        </div>
                    </div>
                    <div class="description_box">
                        <h2><?= $row['ten_phim'] ?></h2>
                        <p><?= $row['thoi_luong'] ?> phút</p>
                        <p><?= $row['ngay_khoi_chieu'] ?></p>
                    </div>
                </li>
            <?php } ?>
            <?php $conn->close(); ?>
        </ul>
    </div>
</body>

</html>