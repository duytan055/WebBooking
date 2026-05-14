<?php
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT * FROM phim WHERE hinh_anh IS NOT NULL";
$result = $conn->query($sql);

$images = [];
while ($row = $result->fetch_assoc()) {
    if ($row['hinh_anh'] != null) {
        $images[] = $row['hinh_anh'];
    }
}
?>
<style>
    .box2 {
        width: 100%;
        height: 500px;
        background-color: black;
    }

    #slide_img {
        height: 100%;
        width: 80%;
        margin-left: 10%;
        object-fit: cover;
    }
</style>
<div class="box2">
    <img id="slide_img" />
</div>
<script>
    let images = <?php echo json_encode($images); ?>; // dùng để lấy giá trị từ php vào js (in danh sách vào trong js luôn)

    let index = 0;
    const img = document.getElementById("slide_img");

    if (images.length > 0) {
        img.src = images[0];

        setInterval(() => {
            index = (index + 1) % images.length; //sau mỗi vòng lặp thì chia cho độ dài của biến mảng images
            img.src = images[index]; //thay đổi ảnh 
        }, 3000); // sau mỗi 3s
    }
</script>