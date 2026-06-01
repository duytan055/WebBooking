<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

// Get notification messages from session
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';

// Clear messages after reading
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

/* ===== TÌM KIẾM ===== */

$timkiem = "";

if (isset($_GET['timkiem'])) {
    $timkiem = $_GET['timkiem'];

    $sql = "SELECT * FROM nhanvien
            WHERE ten LIKE '%$timkiem%'
            ORDER BY id_nhanvien DESC";
} else {
    $sql = "SELECT * FROM nhanvien ORDER BY id_nhanvien DESC";
}

$kq = mysqli_query($conn, $sql);

/* ===== THỐNG KÊ ===== */

$sql_tong = "SELECT COUNT(*) AS tongnv FROM nhanvien";
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

    <title>Quản Lý Nhân Viên</title>

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
                            placeholder="Tìm nhân viên..."
                            value="<?php echo $timkiem; ?>">

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
                                <th>ID NV</th>
                                <th>Tên</th>
                                <th>SĐT</th>
                                <th>Chức năng</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php
                            while ($row = mysqli_fetch_assoc($kq)) {
                            ?>

                                <tr>

                                    <td>
                                        <?php echo $row['id_nhanvien']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['id_nhanvien']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['ten']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['sdt']; ?>
                                    </td>

                                    <td>

                                        <a
                                            href="suanhanvien.php?id=<?php echo $row['id_nhanvien']; ?>"
                                            class="btn-sua">
                                            Sửa
                                        </a>

                                        <a
                                            href="xoanhanvien.php?id=<?php echo $row['id_nhanvien']; ?>"
                                            class="btn-xoa"
                                            onclick="return confirm('Bạn có chắc muốn xóa nhân viên?')">
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