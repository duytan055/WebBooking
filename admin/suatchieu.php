<?php
include __DIR__ . '/../Connect/connecDB.php';

// Lấy danh sách suất chiếu với thông tin phim
$sql = "SELECT s.*, p.ten_phim, pc.ten_phong 
        FROM suatchieu s
        LEFT JOIN phim p ON s.id_phim = p.id_phim
        LEFT JOIN phongchieu pc ON s.id_phong = pc.id_phong
        ORDER BY s.date_chieu DESC, s.thoi_gian DESC
        LIMIT 100";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Suất Chiếu</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 10px;
            color: white;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-add {
            background: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            background: #45a049;
        }
    </style>
</head>

<body>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="notification success" id="notification">
            <i class="fas fa-check-circle"></i>
            <span>Xóa suất chiếu thành công!</span>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header class="box_search_bar">
                <h2><i class="fas fa-clock"></i> Quản Lý Suất Chiếu</h2>
                <div class="user-info">
                    <span>Admin</span>
                </div>
            </header>

            <div class="action-buttons">
                <a href="themsuatchieu.php" class="btn-add">
                    <i class="fas fa-plus"></i> Thêm Suất Chiếu
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Phim</th>
                            <th>Phòng</th>
                            <th>Ngày chiếu</th>
                            <th>Giờ chiếu</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id_suat']; ?></td>
                                <td><?php echo $row['ten_phim']; ?></td>
                                <td><?php echo $row['ten_phong'] ?? 'Phòng ' . $row['id_phong']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['date_chieu'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['thoi_gian'])); ?></td>
                                <td>
                                    <a href="suasuatchieu.php?id=<?php echo $row['id_suat']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="xoasuatchieu.php?id=<?php echo $row['id_suat']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa suất chiếu này?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
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
            }, 3000);
        }
    </script>
</body>

</html>