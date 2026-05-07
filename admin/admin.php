<?php
include __DIR__ . '/../Connect/connecDB.php';


$sql_ve = "SELECT COUNT(*) AS tongve FROM chitietve";
$kq_ve = mysqli_query($conn,$sql_ve);
$row_ve = mysqli_fetch_assoc($kq_ve);

$sql_doanhthu = "SELECT SUM(tong_tien) AS doanhthu FROM datve";
$kq_dt = mysqli_query($conn,$sql_doanhthu);
$row_dt = mysqli_fetch_assoc($kq_dt);

$sql_khach = "SELECT COUNT(*) AS tongkhach FROM nguoidung";
$kq_khach = mysqli_query($conn,$sql_khach);
$row_khach = mysqli_fetch_assoc($kq_khach);


$sql = "SELECT d.id_datve, p.ten_phim, s.thoi_gian, g.ma_ghe, n.ten, d.trang_thai, d.tong_tien FROM datve d JOIN suatchieu s ON d.id_suat = s.id_suat JOIN phim p ON s.id_phim = p.id_phim JOIN chitietve c ON d.id_datve = c.id_datve JOIN ghe g ON c.id_ghe = g.id_ghe JOIN nhanvien n ON d.id_nhanvien = n.id_nhanvien ORDER BY d.id_datve DESC";
$kq = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

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

            <li class="active">
                <a href="#">
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

            <li>
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

        <!-- SEARCH -->

        <header class="box_search_bar">

            <form method="GET">

                <div class="search-bar">
                    <input
                        type="text"
                        name="timkiem"
                        placeholder="Tìm kiếm ID đặt vé..."
                    >
                </div>

            </form>

            <div class="user-info">
                Xin chào,
                <strong>Admin</strong>
            </div>

        </header>

        <!-- CARD -->

        <section class="dashboard-cards">

            <div class="card blue">

                <div class="info">
                    <h3>
                        <?php echo $row_ve['tongve']; ?>
                    </h3>

                    <p>Vé đã bán</p>
                </div>

                <i class="fas fa-ticket"></i>

            </div>

            <div class="card green">

                <div class="info">

                    <h3>
                        <?php
                        echo number_format($row_dt['doanhthu']);
                        ?>đ
                    </h3>

                    <p>Doanh thu</p>

                </div>

                <i class="fas fa-dollar-sign"></i>

            </div>

            <div class="card orange">

                <div class="info">

                    <h3>
                        <?php echo $row_khach['tongkhach']; ?>
                    </h3>

                    <p>Khách hàng</p>

                </div>

                <i class="fas fa-user-plus"></i>

            </div>

        </section>

        <!-- TABLE -->

        <section class="data-section">

            <div class="table-title">

                <h3>Lịch sử giao dịch vé</h3>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID Đặt Vé</th>
                            <th>Phim</th>
                            <th>Suất Chiếu</th>
                            <th>Ghế</th>
                            <th>Tên NV</th>
                            <th>Trạng thái</th>
                            <th>Tổng tiền</th>
                            <th>Chức năng</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php
                    while($row = mysqli_fetch_assoc($kq))
                    {
                    ?>

                    <tr>

                        <td>
                            #<?php echo $row['id_datve']; ?>
                        </td>

                        <td>
                            <?php echo $row['ten_phim']; ?>
                        </td>

                        <td>
                            <?php echo $row['thoi_gian']; ?>
                        </td>

                        <td>
                            <?php echo $row['ma_ghe']; ?>
                        </td>

                        <td>
                            <span class="staff-id">
                                <?php echo $row['ten']; ?>
                            </span>
                        </td>

                        <td>

                            <?php
                            if($row['trang_thai'] == 'Đã in vé')
                            {
                                echo "<span class='status completed'>Đã in vé</span>";
                            }
                            else
                            {
                                echo "<span class='status pending'>Chờ thanh toán</span>";
                            }
                            ?>

                        </td>

                        <td>
                            <?php
                            echo number_format($row['tong_tien']);
                            ?>đ
                        </td>

                        <td>

                            <a
                                class="btn-sua"
                                href="suave.php?id=<?php echo $row['id_datve']; ?>"
                            >
                                Sửa
                            </a>

                            <a
                                class="btn-xoa"
                                href="xoave.php?id=<?php echo $row['id_datve']; ?>"
                                onclick="return confirm('Bạn có chắc muốn xóa?')"
                            >
                                Xóa
                            </a>

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