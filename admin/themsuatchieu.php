<?php
include __DIR__ . '/../Connect/connecDB.php';

// Lấy danh sách phim
$sql_phim = "SELECT id_phim, ten_phim FROM phim ORDER BY ten_phim";
$kq_phim = mysqli_query($conn, $sql_phim);

// Lấy danh sách phòng chiếu
$sql_phong = "SELECT id_phong, ten_phong FROM phongchieu ORDER BY id_phong";
$kq_phong = mysqli_query($conn, $sql_phong);

$success_message = '';
$error_message = '';

// Xử lý thêm suất chiếu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_phim = mysqli_real_escape_string($conn, $_POST['id_phim']);
    $id_phong = mysqli_real_escape_string($conn, $_POST['id_phong']);
    $date_chieu = mysqli_real_escape_string($conn, $_POST['date_chieu']);
    $thoi_gian = mysqli_real_escape_string($conn, $_POST['thoi_gian']);

    // Kiểm tra trùng lặp (cùng phòng, cùng ngày, cùng giờ)
    $check_sql = "SELECT * FROM suatchieu 
                  WHERE id_phong = '$id_phong' 
                  AND date_chieu = '$date_chieu' 
                  AND thoi_gian = '$thoi_gian'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $error_message = 'Suất chiếu này đã tồn tại! Phòng ' . $id_phong . ' đã có lịch chiếu vào ' . $thoi_gian . ' ngày ' . $date_chieu;
    } else {
        $sql = "INSERT INTO suatchieu (id_phim, id_phong, date_chieu, thoi_gian) 
                VALUES ('$id_phim', '$id_phong', '$date_chieu', '$thoi_gian')";

        if (mysqli_query($conn, $sql)) {
            $success_message = 'Thêm suất chiếu thành công!';
        } else {
            $error_message = 'Lỗi: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Suất Chiếu</title>
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

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-submit {
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #45a049;
        }

        .btn-cancel {
            background: #f44336;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: #da190b;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-box i {
            color: #2196F3;
            margin-right: 10px;
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

        <main class="main-content">
            <header class="box_search_bar">
                <h2>Thêm Suất Chiếu Mới</h2>
                <div class="user-info">
                    <a href="suatchieu.php" style="color: #333; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </header>

            <div class="form-container">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Lưu ý:</strong> Hệ thống sẽ kiểm tra trùng lặp. Không thể thêm 2 suất chiếu cùng phòng, cùng ngày, cùng giờ.
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label>Phim <span style="color: red;">*</span></label>
                        <select name="id_phim" required>
                            <option value="">-- Chọn phim --</option>
                            <?php while ($row = mysqli_fetch_assoc($kq_phim)) { ?>
                                <option value="<?php echo $row['id_phim']; ?>">
                                    <?php echo $row['ten_phim']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Phòng chiếu <span style="color: red;">*</span></label>
                        <select name="id_phong" required>
                            <option value="">-- Chọn phòng --</option>
                            <?php while ($row = mysqli_fetch_assoc($kq_phong)) { ?>
                                <option value="<?php echo $row['id_phong']; ?>">
                                    <?php echo $row['ten_phong'] ?? 'Phòng ' . $row['id_phong']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ngày chiếu <span style="color: red;">*</span></label>
                        <input type="date" name="date_chieu" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Giờ chiếu <span style="color: red;">*</span></label>
                        <input type="time" name="thoi_gian" required>
                        <small style="color: #666; display: block; margin-top: 5px;">
                            Khung giờ gợi ý: 09:00, 13:00, 17:00, 21:00
                        </small>
                    </div>

                    <div class="form-actions">
                        <a href="suatchieu.php" class="btn-cancel">Hủy</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Lưu suất chiếu
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const notification = document.getElementById('notification');
        if (notification) {
            <?php if ($success_message): ?>
                setTimeout(() => {
                    window.location.href = 'suatchieu.php';
                }, 2000);
            <?php else: ?>
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            <?php endif; ?>
        }
    </script>
</body>

</html>