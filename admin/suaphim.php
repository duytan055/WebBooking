<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

// Lấy ID phim
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = 'Không tìm thấy ID phim!';
    header('Location: phim.php');
    exit;
}

$id_phim = mysqli_real_escape_string($conn, $_GET['id']);

// Lấy thông tin phim
$sql = "SELECT * FROM phim WHERE id_phim = '$id_phim'";
$kq = mysqli_query($conn, $sql);
$phim = mysqli_fetch_assoc($kq);

if (!$phim) {
    $_SESSION['error_message'] = 'Không tìm thấy phim!';
    header('Location: phim.php');
    exit;
}

// Lấy danh sách độ tuổi
$sql_dotuoi = "SELECT * FROM dotuoi";
$kq_dotuoi = mysqli_query($conn, $sql_dotuoi);

// Phân trang cho suất chiếu
$limit_suat = 10;
$page_suat = isset($_GET['page_suat']) ? (int)$_GET['page_suat'] : 1;
$offset_suat = ($page_suat - 1) * $limit_suat;

// Đếm tổng số suất chiếu
$sql_count_suat = "SELECT COUNT(*) AS total FROM suatchieu WHERE id_phim = '$id_phim'";
$kq_count_suat = mysqli_query($conn, $sql_count_suat);
$row_count_suat = mysqli_fetch_assoc($kq_count_suat);
$total_suat = $row_count_suat['total'];
$total_pages_suat = ceil($total_suat / $limit_suat);

// Lấy danh sách suất chiếu của phim này
$sql_suatchieu = "SELECT s.*, pc.ten_phong 
                  FROM suatchieu s
                  LEFT JOIN phongchieu pc ON s.id_phong = pc.id_phong
                  WHERE s.id_phim = '$id_phim'
                  ORDER BY s.date_chieu DESC, s.thoi_gian DESC
                  LIMIT $limit_suat OFFSET $offset_suat";
$kq_suatchieu = mysqli_query($conn, $sql_suatchieu);

$success_message = '';
$error_message = '';

// Xử lý xóa suất chiếu
if (isset($_GET['delete_suat'])) {
    $id_suat = (int)$_GET['delete_suat'];
    mysqli_query($conn, "DELETE FROM suatchieu WHERE id_suat = $id_suat AND id_phim = '$id_phim'");
    header("Location: suaphim.php?id=$id_phim&msg=deleted_suat");
    exit;
}

// Xử lý thêm suất chiếu mới
if (isset($_POST['them_suat_moi'])) {
    $ngay_chieu = mysqli_real_escape_string($conn, $_POST['ngay_chieu_moi']);
    $id_phong = mysqli_real_escape_string($conn, $_POST['phong_moi']);
    $thoi_gian = mysqli_real_escape_string($conn, $_POST['gio_moi']);

    $sql_them = "INSERT INTO suatchieu (id_phim, id_phong, date_chieu, thoi_gian) 
                 VALUES ('$id_phim', '$id_phong', '$ngay_chieu', '$thoi_gian')";
    if (mysqli_query($conn, $sql_them)) {
        header("Location: suaphim.php?id=$id_phim&msg=added_suat");
        exit;
    }
}

// Xử lý cập nhật phim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_phim = mysqli_real_escape_string($conn, $_POST['ten_phim']);
    $the_loai = mysqli_real_escape_string($conn, $_POST['the_loai']);
    $thoi_luong = mysqli_real_escape_string($conn, $_POST['thoi_luong']);
    $ngay_khoi_chieu = mysqli_real_escape_string($conn, $_POST['ngay_khoi_chieu']);
    $ngay_ket_thuc = mysqli_real_escape_string($conn, $_POST['ngay_ket_thuc']);
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
                   ngay_ket_thuc = '$ngay_ket_thuc',
                   poster = '$poster',
                   hinh_anh = '$hinh_anh',
                   trailer_phim = '$trailer_phim',
                   mo_ta = '$mo_ta',
                   id_do_tuoi = '$id_do_tuoi',
                   trang_thai = '$trang_thai'
                   WHERE id_phim = '$id_phim'";

    if (mysqli_query($conn, $sql_update)) {
        $success_message = 'Cập nhật phim thành công!';
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
    <title>Sửa Phim</title>
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
                        <label>Ngày kết thúc</label>
                        <input type="date" name="ngay_ket_thuc" value="<?php echo $phim['ngay_ket_thuc']; ?>">
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

                <hr style="margin: 40px 0; border: none; border-top: 2px solid #eee;">

                <!-- Quản lý suất chiếu -->
                <h3 style="margin-bottom: 20px; color: #333;">
                    <i class="fas fa-clock"></i> Quản Lý Suất Chiếu
                </h3>

                <?php if (isset($_GET['msg'])): ?>
                    <div style="padding: 10px; margin-bottom: 20px; border-radius: 5px; background: #4CAF50; color: white;">
                        <?php if ($_GET['msg'] == 'added_suat'): ?>
                            ✓ Thêm suất chiếu thành công!
                        <?php elseif ($_GET['msg'] == 'deleted_suat'): ?>
                            ✓ Xóa suất chiếu thành công!
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Form thêm suất chiếu mới -->
                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="margin: 0 0 15px 0; color: #555;">Thêm Suất Chiếu Mới</h4>
                    <form method="POST" style="display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 15px; align-items: end;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px;">Ngày chiếu</label>
                            <input type="date" name="ngay_chieu_moi" required min="<?php echo date('Y-m-d'); ?>"
                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px;">Phòng</label>
                            <select name="phong_moi" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="">-- Chọn --</option>
                                <option value="1">Phòng 1</option>
                                <option value="2">Phòng 2</option>
                                <option value="3">Phòng 3</option>
                                <option value="4">Phòng 4</option>
                                <option value="5">Phòng 5</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px;">Giờ chiếu</label>
                            <select name="gio_moi" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="">-- Chọn --</option>
                                <option value="09:00">09:00</option>
                                <option value="13:00">13:00</option>
                                <option value="17:00">17:00</option>
                                <option value="21:00">21:00</option>
                                <option value="22:00">22:00</option>
                            </select>
                        </div>
                        <button type="submit" name="them_suat_moi"
                            style="background: #4CAF50; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer;">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </form>
                </div>

                <!-- Danh sách suất chiếu -->
                <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; color: #333;">ID</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; color: #333;">Ngày chiếu</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; color: #333;">Giờ chiếu</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; color: #333;">Phòng</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; color: #333;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            while ($suat = mysqli_fetch_assoc($kq_suatchieu)) {
                                $count++;
                            ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; color: #333;"><?php echo $suat['id_suat']; ?></td>
                                    <td style="padding: 12px; color: #333;"><?php echo date('d/m/Y', strtotime($suat['date_chieu'])); ?></td>
                                    <td style="padding: 12px; color: #333;"><?php echo date('H:i', strtotime($suat['thoi_gian'])); ?></td>
                                    <td style="padding: 12px; color: #333;"><?php echo $suat['ten_phong'] ?? 'Phòng ' . $suat['id_phong']; ?></td>
                                    <td style="padding: 12px; text-align: center;">
                                        <a href="?id=<?php echo $id_phim; ?>&delete_suat=<?php echo $suat['id_suat']; ?>"
                                            onclick="return confirm('Bạn có chắc muốn xóa suất chiếu này?')"
                                            style="color: #f44336; text-decoration: none; padding: 5px 10px;">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($count == 0): ?>
                                <tr>
                                    <td colspan="5" style="padding: 20px; text-align: center; color: #999;">
                                        Chưa có suất chiếu nào. Thêm suất chiếu mới ở trên.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination for showtimes -->
                    <?php if ($total_pages_suat > 1): ?>
                        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px; padding: 20px;">
                            <?php if ($page_suat > 1): ?>
                                <a href="?id=<?php echo $id_phim; ?>&page_suat=1"
                                    style="padding: 8px 12px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                                    « Trang đầu
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages_suat; $i++): ?>
                                <?php if ($i == $page_suat): ?>
                                    <span style="padding: 8px 12px; background: #764ba2; color: white; border-radius: 5px; font-weight: bold;">
                                        <?php echo $i; ?>
                                    </span>
                                <?php else: ?>
                                    <a href="?id=<?php echo $id_phim; ?>&page_suat=<?php echo $i; ?>"
                                        style="padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 5px;">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page_suat < $total_pages_suat): ?>
                                <a href="?id=<?php echo $id_phim; ?>&page_suat=<?php echo $total_pages_suat; ?>"
                                    style="padding: 8px 12px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                                    Trang cuối »
                                </a>
                            <?php endif; ?>
                        </div>
                        <div style="text-align: center; color: #666; margin-top: 10px; padding-bottom: 20px;">
                            Trang <?php echo $page_suat; ?> / <?php echo $total_pages_suat; ?> (Tổng <?php echo $total_suat; ?> suất chiếu)
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        const notification = document.getElementById('notification');
        if (notification) {
            <?php if ($success_message): ?>
                setTimeout(() => {
                    window.location.href = 'phim.php';
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