<?php
include __DIR__ . '/../Connect/connecDB.php';

// Lấy ID phim
if (!isset($_GET['id'])) {
    header('Location: phim.php');
    exit;
}

$id_phim = mysqli_real_escape_string($conn, $_GET['id']);

// Lấy thông tin phim
$sql = "SELECT * FROM phim WHERE id_phim = '$id_phim'";
$kq = mysqli_query($conn, $sql);
$phim = mysqli_fetch_assoc($kq);

if (!$phim) {
    echo "<script>alert('Không tìm thấy phim!'); window.location.href='phim.php';</script>";
    exit;
}

// Lấy danh sách độ tuổi
$sql_dotuoi = "SELECT * FROM dotuoi";
$kq_dotuoi = mysqli_query($conn, $sql_dotuoi);

// Xử lý cập nhật phim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_phim = mysqli_real_escape_string($conn, $_POST['ten_phim']);
    $the_loai = mysqli_real_escape_string($conn, $_POST['the_loai']);
    $thoi_luong = mysqli_real_escape_string($conn, $_POST['thoi_luong']);
    $ngay_khoi_chieu = mysqli_real_escape_string($conn, $_POST['ngay_khoi_chieu']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $id_do_tuoi = mysqli_real_escape_string($conn, $_POST['id_do_tuoi']);
    $trang_thai = mysqli_real_escape_string($conn, $_POST['trang_thai']);
    $trailer_phim = mysqli_real_escape_string($conn, $_POST['trailer_phim']);

    $poster = $phim['poster'];
    $hinh_anh = $phim['hinh_anh'];

    // Xử lý upload poster mới
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $target_dir = "img/";
        $file_extension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            // Xóa file cũ
            if ($poster && file_exists($target_dir . $poster)) {
                unlink($target_dir . $poster);
            }
            $poster = $new_filename;
        }
    }

    // Xử lý upload hình ảnh mới
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "img/";
        $file_extension = pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
            // Xóa file cũ
            if ($hinh_anh && file_exists($target_dir . $hinh_anh)) {
                unlink($target_dir . $hinh_anh);
            }
            $hinh_anh = $new_filename;
        }
    }

    $sql_update = "UPDATE phim SET 
                   ten_phim = '$ten_phim',
                   the_loai = '$the_loai',
                   thoi_luong = '$thoi_luong',
                   ngay_khoi_chieu = '$ngay_khoi_chieu',
                   poster = '$poster',
                   hinh_anh = '$hinh_anh',
                   trailer_phim = '$trailer_phim',
                   mo_ta = '$mo_ta',
                   id_do_tuoi = '$id_do_tuoi',
                   trang_thai = '$trang_thai'
                   WHERE id_phim = '$id_phim'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Cập nhật phim thành công!'); window.location.href='phim.php';</script>";
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
    <title>Sửa Phim</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .current-image {
            margin-top: 10px;
            max-width: 200px;
            border-radius: 5px;
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
    </style>
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header class="box_search_bar">
                <h2>Sửa Phim</h2>
                <div class="user-info">
                    <a href="phim.php" style="color: #333; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </header>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên phim <span style="color: red;">*</span></label>
                        <input type="text" name="ten_phim" value="<?php echo htmlspecialchars($phim['ten_phim']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Thể loại <span style="color: red;">*</span></label>
                        <input type="text" name="the_loai" value="<?php echo htmlspecialchars($phim['the_loai']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Thời lượng (phút) <span style="color: red;">*</span></label>
                        <input type="number" name="thoi_luong" value="<?php echo $phim['thoi_luong']; ?>" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Ngày khởi chiếu <span style="color: red;">*</span></label>
                        <input type="date" name="ngay_khoi_chieu" value="<?php echo $phim['ngay_khoi_chieu']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Độ tuổi <span style="color: red;">*</span></label>
                        <select name="id_do_tuoi" required>
                            <option value="">-- Chọn độ tuổi --</option>
                            <?php while ($row = mysqli_fetch_assoc($kq_dotuoi)) { ?>
                                <option value="<?php echo $row['id_do_tuoi']; ?>"
                                    <?php echo ($row['id_do_tuoi'] == $phim['id_do_tuoi']) ? 'selected' : ''; ?>>
                                    <?php echo $row['do_tuoi'] . ' - ' . $row['mo_ta']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Trạng thái <span style="color: red;">*</span></label>
                        <select name="trang_thai" required>
                            <option value="Đang chiếu" <?php echo ($phim['trang_thai'] == 'Đang chiếu') ? 'selected' : ''; ?>>Đang chiếu</option>
                            <option value="Sắp chiếu" <?php echo ($phim['trang_thai'] == 'Sắp chiếu') ? 'selected' : ''; ?>>Sắp chiếu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Poster</label>
                        <input type="file" name="poster" accept="image/*">
                        <?php if ($phim['poster']) { ?>
                            <img src="img/<?php echo $phim['poster']; ?>" class="current-image" alt="Current poster">
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh</label>
                        <input type="file" name="hinh_anh" accept="image/*">
                        <?php if ($phim['hinh_anh']) { ?>
                            <img src="img/<?php echo $phim['hinh_anh']; ?>" class="current-image" alt="Current image">
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Link Trailer</label>
                        <input type="text" name="trailer_phim" value="<?php echo htmlspecialchars($phim['trailer_phim']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta"><?php echo htmlspecialchars($phim['mo_ta']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="phim.php" class="btn-cancel">Hủy</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>