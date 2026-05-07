<?php
include __DIR__ . '/../Connect/connecDB.php';

/* ===== DOANH THU ===== */

$sql_doanhthu = "SELECT SUM(tong_tien) AS doanhthu FROM datve";
$kq_doanhthu = mysqli_query($conn,$sql_doanhthu);
$row_doanhthu = mysqli_fetch_assoc($kq_doanhthu);

/* ===== TỔNG VÉ ===== */

$sql_ve = "SELECT COUNT(*) AS tongve FROM chitietve";
$kq_ve = mysqli_query($conn,$sql_ve);
$row_ve = mysqli_fetch_assoc($kq_ve);

/* ===== TỔNG PHIM ===== */

$sql_phim = "SELECT COUNT(*) AS tongphim FROM phim";
$kq_phim = mysqli_query($conn,$sql_phim);
$row_phim = mysqli_fetch_assoc($kq_phim);

/* ===== KHÁCH HÀNG ===== */

$sql_khach = "SELECT COUNT(*) AS tongkhach FROM nguoidung";
$kq_khach = mysqli_query($conn,$sql_khach);
$row_khach = mysqli_fetch_assoc($kq_khach);

/* ===== TOP PHIM ===== */

$sql_top = "SELECT p.ten_phim,
            COUNT(c.id_ve) AS soluong,
            SUM(d.tong_tien) AS tongtien
            FROM datve d
            JOIN chitietve c ON d.id_datve = c.id_datve
            JOIN suatchieu s ON d.id_suat = s.id_suat
            JOIN phim p ON s.id_phim = p.id_phim
            GROUP BY p.ten_phim
            ORDER BY tongtien DESC";

$kq_top = mysqli_query($conn,$sql_top);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Báo Cáo</title>

    <link rel="stylesheet" href="admin.css">

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <nav class="sidebar">

        <div class="logo">
            <h2>ADMIN</h2>
        </div>

        <ul>

            <li>
                <a href="admin.php">
                    <i class="fas fa-home"></i>
                    Tổng quan
                </a>
            </li>

            <li>
                <a href="phim.php">
                    <i class="fas fa-film"></i>
                    Phim
                </a>
            </li>

            <li>
                <a href="khachhang.php">
                    <i class="fas fa-user"></i>
                    Khách hàng
                </a>
            </li>

            <li>
                <a href="nhanvien.php">
                    <i class="fas fa-users"></i>
                    Nhân viên
                </a>
            </li>

            <li class="active">
                <a href="baocao.php">
                    <i class="fas fa-chart-line"></i>
                    Báo cáo
                </a>
            </li>

            <li>
                <a href="dangxuat.php">
                    <i class="fas fa-right-from-bracket"></i>
                    Đăng xuất
                </a>
            </li>

        </ul>

    </nav>

    <!-- MAIN -->

    <main class="main-content">

        <header class="box_search_bar">

            <h2>Báo Cáo Hệ Thống</h2>

            <div class="user-info">
                ADMIN
            </div>

        </header>

        <!-- CARD -->

        <section class="dashboard-cards">

            <div class="card blue">

                <div class="info">

                    <h3>
                        <?php
                        echo number_format($row_doanhthu['doanhthu']);
                        ?>đ
                    </h3>

                    <p>Tổng doanh thu</p>

                </div>

                <i class="fas fa-dollar-sign"></i>

            </div>

            <div class="card green">

                <div class="info">

                    <h3>
                        <?php echo $row_ve['tongve']; ?>
                    </h3>

                    <p>Tổng vé bán</p>

                </div>

                <i class="fas fa-ticket"></i>

            </div>

            <div class="card orange">

                <div class="info">

                    <h3>
                        <?php echo $row_khach['tongkhach']; ?>
                    </h3>

                    <p>Khách hàng</p>

                </div>

                <i class="fas fa-user"></i>

            </div>

            <div class="card red">

                <div class="info">

                    <h3>
                        <?php echo $row_phim['tongphim']; ?>
                    </h3>

                    <p>Phim</p>

                </div>

                <i class="fas fa-film"></i>

            </div>

        </section>

        <!-- BẢNG BÁO CÁO -->

        <section class="data-section">

            <div class="table-title">

                <h3>Top Phim Doanh Thu</h3>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Tên phim</th>
                            <th>Số vé bán</th>
                            <th>Doanh thu</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php
                    while($row = mysqli_fetch_assoc($kq_top))
                    {
                    ?>

                    <tr>

                        <td>
                            <?php echo $row['ten_phim']; ?>
                        </td>

                        <td>
                            <?php echo $row['soluong']; ?>
                        </td>

                        <td>

                            <?php
                            echo number_format($row['tongtien']);
                            ?>đ

                        </td>

                    </tr>

                    <?php
                    }
                    ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>
</html>