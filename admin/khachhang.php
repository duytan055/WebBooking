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
$limit = 10; // Số khách hàng trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_GET['timkiem'])) {
    $timkiem = $_GET['timkiem'];

    $sql_count = "SELECT COUNT(*) AS total FROM nguoidung WHERE ten LIKE '%$timkiem%'";
    $sql = "SELECT * FROM nguoidung
            WHERE ten LIKE '%$timkiem%'
            ORDER BY id_user DESC
            LIMIT $limit OFFSET $offset";
} else {
    $sql_count = "SELECT COUNT(*) AS total FROM nguoidung";
    $sql = "SELECT * FROM nguoidung ORDER BY id_user DESC LIMIT $limit OFFSET $offset";
}

$kq_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($kq_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

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
                        Trang <?php echo $page; ?> / <?php echo $total_pages; ?> (Tổng <?php echo $total_records; ?> khách hàng)
                    </div>
                <?php endif; ?>

            </section>

        </main>

    </div>

    <script>
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