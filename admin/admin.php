<?php
include __DIR__ . '/../Connect/connecDB.php';

$timkiem = isset($_GET['timkiem']) ? trim($_GET['timkiem']) : '';
$sql_ve = "SELECT COUNT(*) AS tongve FROM chitietve";
$kq_ve = mysqli_query($conn, $sql_ve);
$row_ve = mysqli_fetch_assoc($kq_ve);

$sql_doanhthu = "SELECT SUM(tong_tien) AS doanhthu FROM datve";
$kq_dt = mysqli_query($conn, $sql_doanhthu);
$row_dt = mysqli_fetch_assoc($kq_dt);

$sql_khach = "SELECT COUNT(*) AS tongkhach FROM nguoidung";
$kq_khach = mysqli_query($conn, $sql_khach);
$row_khach = mysqli_fetch_assoc($kq_khach);

/* PHÂN TRANG */

$limit = 20;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$sql_count = "SELECT COUNT(DISTINCT d.id_datve) AS total
FROM datve d
LEFT JOIN nguoidung nd ON d.id_user = nd.id_user
LEFT JOIN suatchieu sc ON d.id_suat = sc.id_suat
LEFT JOIN phim p ON sc.id_phim = p.id_phim
WHERE (
    d.id_datve LIKE '%$timkiem%'
    OR nd.ten LIKE '%$timkiem%'
    OR p.ten_phim LIKE '%$timkiem%'
)";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);

$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

$offset = ($page - 1) * $limit;

/* LỊCH SỬ GIAO DỊCH */

$sql = "SELECT
    d.id_datve,
    nd.ten AS ten_khach_hang,
    p.ten_phim,
    CONCAT(sc.date_chieu, ' ', sc.thoi_gian) AS suat_chieu,
    GROUP_CONCAT(g.ma_ghe ORDER BY g.ma_ghe SEPARATOR ', ') AS danh_sach_ghe,
    d.tong_tien,
    d.trang_thai,
    d.thoi_gian_dat

FROM datve d

JOIN nguoidung nd
    ON d.id_user = nd.id_user

JOIN suatchieu sc
    ON d.id_suat = sc.id_suat

JOIN phim p
    ON sc.id_phim = p.id_phim

JOIN chitietve ct
    ON d.id_datve = ct.id_datve

JOIN ghe g
    ON ct.id_ghe = g.id_ghe
WHERE (
    d.id_datve LIKE '%$timkiem%'
    OR nd.ten LIKE '%$timkiem%'
    OR p.ten_phim LIKE '%$timkiem%'
)

GROUP BY d.id_datve

ORDER BY d.id_datve DESC

LIMIT $offset, $limit
";

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
                            placeholder="Tìm kiếm ID đặt vé...">
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
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Phim</th>
                                <th>Suất chiếu</th>
                                <th>Ghế</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thời gian đặt</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($kq)) { ?>

                                <tr>

                                    <td>#<?= $row['id_datve'] ?></td>

                                    <td><?= htmlspecialchars($row['ten_khach_hang']) ?></td>

                                    <td><?= htmlspecialchars($row['ten_phim']) ?></td>

                                    <td><?= date('d/m/Y H:i', strtotime($row['suat_chieu'])) ?></td>

                                    <td><?= htmlspecialchars($row['danh_sach_ghe']) ?></td>

                                    <td><?= number_format($row['tong_tien']) ?>đ</td>

                                    <td>
                                        <?php
                                        if ($row['trang_thai'] == 'Đã thanh toán') {
                                            echo "<span class='status completed'>Đã thanh toán</span>";
                                        } else {
                                            echo "<span class='status pending'>{$row['trang_thai']}</span>";
                                        }
                                        ?>
                                    </td>

                                    <td><?= date('d/m/Y H:i', strtotime($row['thoi_gian_dat'])) ?></td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>
                    <?php if ($total_pages > 1): ?>
                        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px; padding: 20px;">
                            <?php if ($page > 1): ?>
                                <a href="?page=1<?php echo $timkiem ? '&timkiem=' . urlencode($timkiem) : ''; ?>"
                                    style="padding: 8px 12px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                                    « Trang đầu
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span style="padding: 8px 12px; background: #764ba2; color: white; border-radius: 5px; font-weight: bold;">
                                        <?php echo $i; ?>
                                    </span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?><?php echo $timkiem ? '&timkiem=' . urlencode($timkiem) : ''; ?>"
                                        style="padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 5px;">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $total_pages; ?><?php echo $timkiem ? '&timkiem=' . urlencode($timkiem) : ''; ?>"
                                    style="padding: 8px 12px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                                    Trang cuối »
                                </a>
                            <?php endif; ?>
                        </div>
                        <div style="text-align: center; color: #666; margin-top: 10px;">
                            Trang <?php echo $page; ?> / <?php echo $total_pages; ?> (Tổng <?php echo $total_records; ?> Giao dịch)
                        </div>
                    <?php endif; ?>

                </div>

            </section>

        </main>

    </div>

</body>

</html>