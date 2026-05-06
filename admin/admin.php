<?php
include __DIR__ . '/../Connect/connecDB.php';


$sql_ve = "SELECT COUNT(*) AS tongve FROM vedat";
$kq_ve = mysqli_query($conn, $sql_ve);
$row_ve = mysqli_fetch_assoc($kq_ve);

$sql_doanhthu = "SELECT SUM(tongtien) AS doanhthu FROM vedat";
$kq_dt = mysqli_query($conn, $sql_doanhthu);
$row_dt = mysqli_fetch_assoc($kq_dt);

$sql_khach = "SELECT COUNT(*) AS tongkhach FROM khachhang";
$kq_khach = mysqli_query($conn, $sql_khach);
$row_khach = mysqli_fetch_assoc($kq_khach);


$sql = "SELECT * FROM vedat ORDER BY id DESC";
$kq = mysqli_query($conn, $sql);
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
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
                            placeholder="Tìm kiếm mã vé...">
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

                                <th>Mã Vé</th>
                                <th>Phim</th>
                                <th>Suất Chiếu</th>
                                <th>Ghế</th>
                                <th>Mã NV</th>
                                <th>Trạng thái</th>
                                <th>Tổng tiền</th>
                                <th>Chức năng</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php
                            while ($row = mysqli_fetch_assoc($kq)) {
                            ?>

                                <tr>

                                    <td>
                                        #<?php echo $row['mave']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['tenphim']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['suatchieu']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['ghe']; ?>
                                    </td>

                                    <td>
                                        <span class="staff-id">
                                            <?php echo $row['manv']; ?>
                                        </span>
                                    </td>

                                    <td>

                                        <?php
                                        if ($row['trangthai'] == 'Đã in vé') {
                                            echo "<span class='status completed'>Đã in vé</span>";
                                        } else {
                                            echo "<span class='status pending'>Chờ thanh toán</span>";
                                        }
                                        ?>

                                    </td>

                                    <td>
                                        <?php
                                        echo number_format($row['tongtien']);
                                        ?>đ
                                    </td>

                                    <td>

                                        <a
                                            class="btn-sua"
                                            href="suave.php?id=<?php echo $row['id']; ?>">
                                            Sửa
                                        </a>

                                        <a
                                            class="btn-xoa"
                                            href="xoave.php?id=<?php echo $row['id']; ?>"
                                            onclick="return confirm('Bạn có chắc muốn xóa?')">
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