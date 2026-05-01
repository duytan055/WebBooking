<?php
include "ketnoi.php";

/* ===== TÌM KIẾM ===== */

$timkiem = "";

if(isset($_GET['timkiem']))
{
    $timkiem = $_GET['timkiem'];

    $sql = "SELECT * FROM nhanvien
            WHERE hoten LIKE '%$timkiem%'
            OR manv LIKE '%$timkiem%'
            ORDER BY id DESC";
}
else
{
    $sql = "SELECT * FROM nhanvien ORDER BY id DESC";
}

$kq = mysqli_query($conn,$sql);

/* ===== THỐNG KÊ ===== */

$sql_tong = "SELECT COUNT(*) AS tongnv FROM nhanvien";
$kq_tong = mysqli_query($conn,$sql_tong);
$row_tong = mysqli_fetch_assoc($kq_tong);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quản Lý Nhân Viên</title>

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

            <li class="active">
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
                        placeholder="Tìm nhân viên..."
                        value="<?php echo $timkiem; ?>"
                    >

                </div>

            </form>

            <div class="user-info">
                Quản lý nhân viên
            </div>

        </header>

        <!-- CARD -->

        <section class="dashboard-cards">

            <div class="card blue">

                <div class="info">

                    <h3>
                        <?php echo $row_tong['tongnv']; ?>
                    </h3>

                    <p>Tổng nhân viên</p>

                </div>

                <i class="fas fa-users"></i>

            </div>

        </section>

        <!-- TABLE -->

        <section class="data-section">

            <div class="table-title">

                <h3>Danh sách nhân viên</h3>

                <a href="themnhanvien.php" class="btn-them">
                    + Thêm nhân viên
                </a>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Mã NV</th>
                            <th>Họ tên</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Chức vụ</th>
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
                            <?php echo $row['id']; ?>
                        </td>

                        <td>
                            <?php echo $row['manv']; ?>
                        </td>

                        <td>
                            <?php echo $row['hoten']; ?>
                        </td>

                        <td>
                            <?php echo $row['sdt']; ?>
                        </td>

                        <td>
                            <?php echo $row['email']; ?>
                        </td>

                        <td>

                            <span class="staff-id">
                                <?php echo $row['chucvu']; ?>
                            </span>

                        </td>

                        <td>

                            <a
                                href="suanhanvien.php?id=<?php echo $row['id']; ?>"
                                class="btn-sua"
                            >
                                Sửa
                            </a>

                            <a
                                href="xoanhanvien.php?id=<?php echo $row['id']; ?>"
                                class="btn-xoa"
                                onclick="return confirm('Bạn có chắc muốn xóa nhân viên?')"
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