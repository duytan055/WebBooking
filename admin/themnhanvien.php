<?php
include __DIR__ . '/../Connect/connecDB.php';

// Xử lý thêm nhân viên
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $mat_khau = mysqli_real_escape_string($conn, $_POST['mat_khau']);
    $mat_khau_hash = password_hash($mat_khau, PASSWORD_DEFAULT);

    $sql = "INSERT INTO nhanvien (ten, sdt, mat_khau) VALUES ('$ten', '$sdt', '$mat_khau_hash')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thêm nhân viên thành công!'); window.location.href='nhanvien.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhân Viên</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
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
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="box_search_bar">
                <h2>Thêm Nhân Viên Mới</h2>
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
                        <input type="text" name="ten" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại <span style="color: red;">*</span></label>
                        <input type="text" name="sdt" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu <span style="color: red;">*</span></label>
                        <input type="password" name="mat_khau" required>
                    </div>
                    <div class="form-actions">
                        <a href="nhanvien.php" class="btn-cancel">Hủy</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>