<?php
include __DIR__ . '/../Connect/connecDB.php';


$timkiem = "";

if(isset($_GET['timkiem']))
{
    $timkiem = $_GET['timkiem'];

    $sql = "SELECT * FROM phim
            WHERE ten_phim LIKE '%$timkiem%'
            ORDER BY id_phim DESC";
}
else
{
    $sql = "SELECT * FROM phim ORDER BY id_phim DESC";
}

$kq = mysqli_query($conn,$sql);


$sql_phim = "SELECT COUNT(*) AS tongphim FROM phim";
$kq_phim = mysqli_query($conn,$sql_phim);
$row_phim = mysqli_fetch_assoc($kq_phim);

$sql_dangchieu = "SELECT COUNT(*) AS dangchieu
                  FROM phim
                  WHERE trang_thai='Đang chiếu'";

$kq_dc = mysqli_query($conn,$sql_dangchieu);
$row_dc = mysqli_fetch_assoc($kq_dc);

$sql_sapchieu = "SELECT COUNT(*) AS sapchieu
                 FROM phim
                 WHERE trang_thai='Sắp chiếu'";

$kq_sc = mysqli_query($conn,$sql_sapchieu);
$row_sc = mysqli_fetch_assoc($kq_sc);

?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quản Lý Phim</title>

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

            <li class="active">
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
                        placeholder="Tìm phim..."
                        value="<?php echo $timkiem; ?>"
                    >

                </div>

            </form>

            <div class="user-info">
                Quản lý phim
            </div>

        </header>

        <!-- CARD -->

        <section class="dashboard-cards">

            <div class="card blue">

                <div class="info">

                    <h3>
                        <?php echo $row_phim['tongphim']; ?>
                    </h3>

                    <p>Tổng phim</p>

                </div>

                <i class="fas fa-film"></i>

            </div>

            <div class="card green">

                <div class="info">

                    <h3>
                        <?php echo $row_dc['dangchieu']; ?>
                    </h3>

                    <p>Đang chiếu</p>

                </div>

                <i class="fas fa-play"></i>

            </div>

            <div class="card orange">

                <div class="info">

                    <h3>
                        <?php echo $row_sc['sapchieu']; ?>
                    </h3>

                    <p>Sắp chiếu</p>

                </div>

                <i class="fas fa-clock"></i>

            </div>

        </section>

        <!-- TABLE -->

        <section class="data-section">

            <div class="table-title">

                <h3>Danh sách phim</h3>

                <a href="themphim.php" class="btn-them">
                    + Thêm phim
                </a>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Poster</th>
                            <th>Tên phim</th>
                            <th>Thể loại</th>
                            <th>Thời lượng</th>
                            <th>Trạng thái</th>
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
                            <?php echo $row['id_phim']; ?>
                        </td>

                        <td>

                            <img
                                src="img/<?php echo $row['poster']; ?>"
                                width="70"
                                height="100"
                                style="border-radius:10px"
                            >

                        </td>

                        <td>
                            <?php echo $row['ten_phim']; ?>
                        </td>

                        <td>
                            <?php echo $row['the_loai']; ?>
                        </td>

                        <td>
                            <?php echo $row['thoi_luong']; ?> phút
                        </td>

                        <td>

                        <?php
                        if($row['trang_thai'] == 'Đang chiếu')
                        {
                            echo "<span class='status completed'>Đang chiếu</span>";
                        }
                        else
                        {
                            echo "<span class='status pending'>Sắp chiếu</span>";
                        }
                        ?>

                        </td>

                        <td>

                            <a
                                href="suaphim.php?id=<?php echo $row['id_phim']; ?>"
                                class="btn-sua"
                            >
                                Sửa
                            </a>

                            <a
                                href="xoaphim.php?id=<?php echo $row['id_phim']; ?>"
                                class="btn-xoa"
                                onclick="return confirm('Bạn có chắc muốn xóa phim?')"
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