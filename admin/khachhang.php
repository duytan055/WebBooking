<?php
include __DIR__ . '/../Connect/connecDB.php';


$timkiem = "";

if (isset($_GET['timkiem'])) {
    $timkiem = $_GET['timkiem'];

    $sql = "SELECT * FROM nguoidung
            WHERE ten LIKE '%$timkiem%'
            ORDER BY id_user DESC";
} else {
    $sql = "SELECT * FROM nguoidung ORDER BY id_user DESC";
}

$kq = mysqli_query($conn, $sql);


$sql_tong = "SELECT COUNT(*) AS tongkhach FROM nguoidung";
$kq_tong = mysqli_query($conn, $sql_tong);
$row_tong = mysqli_fetch_assoc($kq_tong);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Quản Lý Khách Hàng</title>

    <link rel="stylesheet" href="admin.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

</head>

<body>

    <div class="container">

        <?php include 'sidebar.php'; ?>

        <!-- MAIN -->

        <main class="main-content">

            <!-- SEARCH -->

            <header class="box_search_bar">

                <form method="GET">

                    <div class="search-bar">

                        <input
                            type="text"
                            name="timkiem"
                            placeholder="Tìm khách hàng..."
                            value="<?php echo $timkiem; ?>">

                    </div>

                </form>

                <div class="user-info">
                    Quản lý khách hàng
                </div>

            </header>

            <!-- CARD -->

            <section class="dashboard-cards">

                <div class="card blue">

                    <div class="info">

                        <h3>
                            <?php echo $row_tong['tongkhach']; ?>
                        </h3>

                        <p>Tổng khách hàng</p>

                    </div>

                    <i class="fas fa-users"></i>

                </div>

            </section>

            <!-- TABLE -->

            <section class="data-section">

                <div class="table-title">

                    <h3>Danh sách khách hàng</h3>
                </div>

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>ID</th>
                                <th>ID User</th>
                                <th>Tên</th>
                                <th>SĐT</th>
                                <th>Email</th>
                                <th>Chức năng</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php
                            while ($row = mysqli_fetch_assoc($kq)) {
                            ?>

                                <tr>

                                    <td>
                                        <?php echo $row['id_user']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['id_user']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['ten']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['sdt']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['email']; ?>
                                    </td>

                                    <td>
                                        <a
                                            href="xoakhachhang.php?id=<?php echo $row['id_user']; ?>"
                                            class="btn-xoa"
                                            onclick="return confirm('Bạn có chắc muốn xóa khách hàng?')">
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