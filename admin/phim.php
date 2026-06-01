<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

// Get notification messages from session
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';

// Clear messages after reading
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

$timkiem = "";

// Phân trang
$limit = 10; // Số phim trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_GET['timkiem'])) {
    $timkiem = $_GET['timkiem'];

    $sql_count = "SELECT COUNT(*) AS total FROM phim WHERE ten_phim LIKE '%$timkiem%'";
    $sql = "SELECT * FROM phim
            WHERE ten_phim LIKE '%$timkiem%'
            ORDER BY id_phim DESC
            LIMIT $limit OFFSET $offset";
} else {
    $sql_count = "SELECT COUNT(*) AS total FROM phim";
    $sql = "SELECT * FROM phim ORDER BY id_phim DESC LIMIT $limit OFFSET $offset";
}

$kq_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($kq_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

$kq = mysqli_query($conn, $sql);

$today = date('Y-m-d');

// Đếm tổng phim (không bao gồm đã kết thúc)
$sql_phim = "SELECT COUNT(*) AS tongphim FROM phim 
             WHERE ngay_ket_thuc IS NULL OR ngay_ket_thuc >= '$today'";
$kq_phim = mysqli_query($conn, $sql_phim);
$row_phim = mysqli_fetch_assoc($kq_phim);

// Đếm phim đang chiếu (ngày khởi chiếu <= hôm nay <= ngày kết thúc)
$sql_dangchieu = "SELECT COUNT(*) AS dangchieu
                  FROM phim
                  WHERE ngay_khoi_chieu <= '$today'
                  AND (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= '$today')";
$kq_dc = mysqli_query($conn, $sql_dangchieu);
$row_dc = mysqli_fetch_assoc($kq_dc);

// Đếm phim sắp chiếu (ngày khởi chiếu > hôm nay)
$sql_sapchieu = "SELECT COUNT(*) AS sapchieu
                 FROM phim
                 WHERE ngay_khoi_chieu > '$today'";
$kq_sc = mysqli_query($conn, $sql_sapchieu);
$row_sc = mysqli_fetch_assoc($kq_sc);

?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Quản Lý Phim</title>

    <link rel="stylesheet" href="admin.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            max-width: 400px;
        }

        .notification.error {
            background: #f44336;
            color: white;
        }

        .notification.success {
            background: #4CAF50;
            color: white;
        }

        .notification i {
            font-size: 20px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>

</head>

<body>

    <?php if ($error_message): ?>
        <div class="notification error" id="notification">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $error_message; ?></span>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="notification success" id="notification">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $success_message; ?></span>
        </div>
    <?php endif; ?>

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
                            placeholder="Tìm phim..."
                            value="<?php echo $timkiem; ?>">

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
                            while ($row = mysqli_fetch_assoc($kq)) {
                            ?>

                                <tr>

                                    <td>
                                        <?php echo $row['id_phim']; ?>
                                    </td>

                                    <td>

                                        <img
                                            src="<?php echo $row['poster']; ?>"
                                            width="70"
                                            height="100"
                                            style="border-radius:10px">

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
                                        // Xác định trạng thái dựa trên ngày
                                        $ngay_khoi_chieu = $row['ngay_khoi_chieu'];
                                        $ngay_ket_thuc = $row['ngay_ket_thuc'];

                                        if ($ngay_ket_thuc && $ngay_ket_thuc < $today) {
                                            // Đã kết thúc
                                            echo "<span class='status' style='background: #999;'>Đã kết thúc</span>";
                                        } elseif ($ngay_khoi_chieu <= $today) {
                                            // Đang chiếu
                                            echo "<span class='status completed'>Đang chiếu</span>";
                                        } else {
                                            // Sắp chiếu
                                            echo "<span class='status pending'>Sắp chiếu</span>";
                                        }
                                        ?>

                                    </td>

                                    <td>

                                        <a
                                            href="suaphim.php?id=<?php echo $row['id_phim']; ?>"
                                            class="btn-sua">
                                            Sửa
                                        </a>

                                        <a
                                            href="xoaphim.php?id=<?php echo $row['id_phim']; ?>"
                                            class="btn-xoa"
                                            onclick="return confirm('Bạn có chắc muốn xóa phim?')">
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

                <!-- Pagination -->
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
                        Trang <?php echo $page; ?> / <?php echo $total_pages; ?> (Tổng <?php echo $total_records; ?> phim)
                    </div>
                <?php endif; ?>

            </section>

        </main>

    </div>

    <script>
        // Auto hide notification after 4 seconds
        const notification = document.getElementById('notification');
        if (notification) {
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 4000);
        }
    </script>

</body>

</html>