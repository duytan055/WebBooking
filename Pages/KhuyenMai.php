<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';
$sql = "SELECT * FROM khuyenmai";
$result = $conn->query($sql);
?>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: rgba(232, 226, 226, 0.32);
    }

    .title {
        text-align: center;
    }

    .title::after {
        content: "";
        width: 100px;
        height: 3px;
        background: red;
        display: block;
        margin: 10px auto 0;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 80px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .social {
        display: flex;
        gap: 20px;
        font-size: 22px;
    }

    .social i {
        cursor: pointer;
        color: #333;
    }

    .menu a {
        margin: 0 20px;
        text-decoration: none;
        color: black;
        font-weight: 500;
        font-size: 18px;
    }

    .menu a.active {
        color: #ff6600;
    }

    .auth button {
        padding: 8px 16px;
        margin-left: 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    .login,
    .register {
        background: #333;
        color: white;
    }

    .login:hover,
    .register:hover {
        background: #555;
    }

    .title {
        padding: 40px 100px 20px;
        font-size: 22px;
    }

    .promo-container {
        width: 90%;
        margin: 20px auto;
    }

    .promo-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        /* Tự động xuống dòng khi màn hình nhỏ */
        gap: 25px;
        list-style: none;
        padding: 0;
    }

    .promo-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: 0.3s;
        height: 100%;
    }

    .promo-card:hover {
        transform: translateY(-8px);
    }

    .promo-card img {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }

    .promo-card h3 {
        margin: 15px 10px;
        font-size: 1.2rem;
    }

    .price {
        color: #ff6600;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .promo-card p:last-child {
        padding: 0 20px 20px;
        color: #555;
        font-size: 14px;
    }
</style>
<?php include '../Modun/header.php'; ?>

<section class="title">
    <h2>Khuyến mãi</h2>
</section>

<section class="promo-container">
    <ul class="promo-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li class="promo-card">
                <a href="#" style="text-decoration: none; color: inherit; display: block;">
                    <img src="../Anh_Khuyen_Mai/<?= $row['anh_khuyen_mai']; ?>">
                    <h3><?= $row['ten_khuyenmai']; ?></h3>
                    <p>
                        <?= $row['ngay_bat_dau']; ?> -
                        <?= $row['ngay_ket_thuc']; ?>
                    </p>
                </a>
            </li>
        <?php } ?>
    </ul>
</section>

<?php include '../Modun/footer.php'; ?>