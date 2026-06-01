<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = 'Không tìm thấy ID nhân viên!';
    header('Location: nhanvien.php');
    exit;
}

$id_nhanvien = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM nhanvien WHERE id_nhanvien = '$id_nhanvien'";
$kq = mysqli_query($conn, $sql);
$nhanvien = mysqli_fetch_assoc($kq);

if (!$nhanvien) {
    $_SESSION['error_message'] = 'Không tìm thấy nhân viên!';
    header('Location: nhanvien.php');
    exit;
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);

    $sql_update = "UPDATE nhanvien SET ten = '$ten', sdt = '$sdt'";

    if (!empty($_POST['mat_khau'])) {
        $mat_khau = mysqli_real_escape_string($conn, $_POST['mat_khau']);
        $mat_khau_hash = password_hash($mat_khau, PASSWORD_DEFAULT);
        $sql_update .= ", mat_khau = '$mat_khau_hash'";
    }

    $sql_update .= " WHERE id_nhanvien = '$id_nhanvien'";

    if (mysqli_query($conn, $sql_update)) {
        $success_message = 'Cập nhật nhân viên thành công!';
    } else {
        $error_message = 'Lỗi: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Nhân Viên</title>
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

        .form-group input {
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
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: #da190b;
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
                <h2>Sửa Nhân Viên</h2>
                <div class="user-info">
                    <a href="nhanvien.php" style="color: #333; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </header>
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label>Tên nhân viên <span style="color: red;">*</span></label>
                        <input type="text" name="ten" value="<?php echo htmlspecialchars($nhanvien['ten']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại <span style="color: red;">*</span></label>
                        <input type="text" name="sdt" value="<?php echo htmlspecialchars($nhanvien['sdt']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới (để trống nếu không đổi)</label>
                        <input type="password" name="mat_khau">
                    </div>
                    <div class="form-actions">
                        <a href="nhanvien.php" class="btn-cancel">Hủy</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Cập nhật
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
                    window.location.href = 'nhanvien.php';
                }, 2000);
            <?php else: ?>
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 4000);
            <?php endif; ?>
        }
    </script>
</body>

</html>