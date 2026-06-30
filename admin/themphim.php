<?php
include __DIR__ . '/../Connect/connecDB.php';

// Lấy danh sách độ tuổi
$sql_dotuoi = "SELECT * FROM dotuoi";
$kq_dotuoi = mysqli_query($conn, $sql_dotuoi);

$success_message = '';
$error_message = '';

// Xử lý thêm phim
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

    // Xử lý upload poster
    $poster = '';
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $target_dir = "../poster/";
        $file_extension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            $poster = $new_filename;
        }
    }

    // Xử lý upload hình ảnh
    $hinh_anh = '';
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "../poster/";
        $file_extension = pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
            $hinh_anh = $new_filename;
        }
    }

    $sql = "INSERT INTO phim (ten_phim, the_loai, thoi_luong, ngay_khoi_chieu, ngay_ket_thuc, poster, hinh_anh, trailer_phim, mo_ta, id_do_tuoi, trang_thai) 
            VALUES ('$ten_phim', '$the_loai', '$thoi_luong', '$ngay_khoi_chieu', '$ngay_ket_thuc', '$poster', '$hinh_anh', '$trailer_phim', '$mo_ta', '$id_do_tuoi', '$trang_thai')";

    if (mysqli_query($conn, $sql)) {
        $id_phim_moi = mysqli_insert_id($conn);

        if (!empty($_POST['dao_dien'])) {
            $arr_dd = explode(',', $_POST['dao_dien']);
            foreach ($arr_dd as $dd_name) {
                $dd_name = mysqli_real_escape_string($conn, trim($dd_name));
                if (empty($dd_name)) continue;


                $check_dd = mysqli_query($conn, "SELECT id_daodien FROM daodien WHERE ten_dao_dien = '$dd_name'");
                if ($row_dd = mysqli_fetch_assoc($check_dd)) {
                    $id_dd = $row_dd['id_daodien'];
                } else {
                    mysqli_query($conn, "INSERT INTO daodien (ten_dao_dien) VALUES ('$dd_name')");
                    $id_dd = mysqli_insert_id($conn);
                }
                mysqli_query($conn, "INSERT INTO phim_daodien (id_phim, id_daodien) VALUES ('$id_phim_moi', '$id_dd')");
            }
        }

        // Xử lý thêm Diễn viên
        if (!empty($_POST['dien_vien'])) {
            $arr_dv = explode(',', $_POST['dien_vien']);
            foreach ($arr_dv as $dv_name) {
                $dv_name = mysqli_real_escape_string($conn, trim($dv_name));
                if (empty($dv_name)) continue;


                $check_dv = mysqli_query($conn, "SELECT id_dienvien FROM dienvien WHERE ten_dien_vien = '$dv_name'");
                if ($row_dv = mysqli_fetch_assoc($check_dv)) {
                    $id_dv = $row_dv['id_dienvien'];
                } else {
                    mysqli_query($conn, "INSERT INTO dienvien (ten_dien_vien) VALUES ('$dv_name')");
                    $id_dv = mysqli_insert_id($conn);
                }
                mysqli_query($conn, "INSERT INTO phim_dienvien (id_phim, id_dienvien) VALUES ('$id_phim_moi', '$id_dv')");
            }
        }


        if (isset($_POST['them_suat_chieu']) && $_POST['them_suat_chieu'] == '1') {
            $ngay_chieu = mysqli_real_escape_string($conn, $_POST['ngay_chieu']);
            $so_suat = (int)$_POST['so_suat'];

            for ($i = 1; $i <= $so_suat; $i++) {
                if (isset($_POST["phong_$i"]) && isset($_POST["gio_$i"])) {
                    $phong = mysqli_real_escape_string($conn, $_POST["phong_$i"]);
                    $gio = mysqli_real_escape_string($conn, $_POST["gio_$i"]);

                    $sql_suat = "INSERT INTO suatchieu (id_phim, id_phong, date_chieu, thoi_gian) 
                                VALUES ('$id_phim_moi', '$phong', '$ngay_chieu', '$gio')";
                    mysqli_query($conn, $sql_suat);
                }
            }
        }

        $success_message = 'Thêm phim thành công!';
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
    <title>Thêm Phim</title>
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
                <h2>Thêm Phim Mới</h2>
                <div class="user-info">
                    <a href="phim.php" style="color: white; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </header>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên phim <span style="color: red;">*</span></label>
                        <input type="text" name="ten_phim" required>
                    </div>

                    <div class="form-group">
                        <label>Thể loại <span style="color: red;">*</span></label>
                        <input type="text" name="the_loai" placeholder="VD: Hành động, Kinh dị, Hài" required>
                    </div>

                    <div class="form-group">
                        <label>Thời lượng (phút) <span style="color: red;">*</span></label>
                        <input type="number" name="thoi_luong" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Đạo diễn (Cách nhau bằng dấu phẩy)</label>
                        <input type="text" name="dao_dien" placeholder="VD: Christopher Nolan, James Cameron">
                    </div>

                    <div class="form-group">
                        <label>Diễn viên (Cách nhau bằng dấu phẩy)</label>
                        <input type="text" name="dien_vien" placeholder="VD: Leonardo DiCaprio, Cillian Murphy">
                    </div>

                    <div class="form-group">
                        <label>Ngày khởi chiếu <span style="color: red;">*</span></label>
                        <input type="date" name="ngay_khoi_chieu" required>
                    </div>

                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="date" name="ngay_ket_thuc">
                    </div>

                    <div class="form-group">
                        <label>Độ tuổi <span style="color: red;">*</span></label>
                        <select name="id_do_tuoi" required>
                            <option value="">-- Chọn độ tuổi --</option>
                            <?php while ($row = mysqli_fetch_assoc($kq_dotuoi)) { ?>
                                <option value="<?php echo $row['id_do_tuoi']; ?>">
                                    <?php echo $row['do_tuoi'] . ' - ' . $row['mo_ta']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Trạng thái <span style="color: red;">*</span></label>
                        <select name="trang_thai" required>
                            <option value="Đang chiếu">Đang chiếu</option>
                            <option value="Sắp chiếu">Sắp chiếu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Poster</label>
                        <input type="file" name="poster" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh</label>
                        <input type="file" name="hinh_anh" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Link Trailer</label>
                        <input type="text" name="trailer_phim" placeholder="https://youtube.com/...">
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta" placeholder="Nhập mô tả phim..."></textarea>
                    </div>

                    <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

                    <h3 style="margin-bottom: 20px; color: #333;">
                        <i class="fas fa-clock"></i> Thêm Suất Chiếu
                    </h3>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="toggle_suat_chieu" style="width: auto; margin-right: 10px;">
                            Thêm suất chiếu cho phim này
                        </label>
                    </div>

                    <div id="suat_chieu_container" style="display: none; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 15px;">
                        <input type="hidden" name="them_suat_chieu" id="them_suat_chieu" value="0">

                        <div class="form-group">
                            <label>Ngày chiếu <span style="color: red;">*</span></label>
                            <input type="date" name="ngay_chieu" id="ngay_chieu" min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label>Số suất chiếu <span style="color: red;">*</span></label>
                            <select name="so_suat" id="so_suat">
                                <option value="1">1 suất</option>
                                <option value="2">2 suất</option>
                                <option value="3">3 suất</option>
                                <option value="4" selected>4 suất</option>
                                <option value="5">5 suất</option>
                            </select>
                        </div>

                        <div id="suat_chieu_list"></div>
                    </div>

                    <div class="form-actions">
                        <a href="phim.php" class="btn-cancel">Hủy</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Lưu phim
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

        const toggleSuatChieu = document.getElementById('toggle_suat_chieu');
        const suatChieuContainer = document.getElementById('suat_chieu_container');
        const themSuatChieu = document.getElementById('them_suat_chieu');
        const soSuatSelect = document.getElementById('so_suat');
        const suatChieuList = document.getElementById('suat_chieu_list');

        const khungGio = ['09:00', '13:00', '17:00', '21:00', '22:00'];

        toggleSuatChieu.addEventListener('change', function() {
            if (this.checked) {
                suatChieuContainer.style.display = 'block';
                themSuatChieu.value = '1';
                generateSuatChieu();
            } else {
                suatChieuContainer.style.display = 'none';
                themSuatChieu.value = '0';
            }
        });

        soSuatSelect.addEventListener('change', generateSuatChieu);

        function generateSuatChieu() {
            const soSuat = parseInt(soSuatSelect.value);
            let html = '';

            for (let i = 1; i <= soSuat; i++) {
                html += `
                    <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #ddd;">
                        <h4 style="margin: 0 0 15px 0; color: #555;">Suất ${i}</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Phòng chiếu</label>
                                <select name="phong_${i}" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                    <option value="">-- Chọn phòng --</option>
                                    <option value="1">Phòng 1</option>
                                    <option value="2">Phòng 2</option>
                                    <option value="3">Phòng 3</option>
                                    <option value="4">Phòng 4</option>
                                    <option value="5">Phòng 5</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Giờ chiếu</label>
                                <select name="gio_${i}" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                    <option value="">-- Chọn giờ --</option>
                                    ${khungGio.map(gio => `<option value="${gio}" ${i <= 4 && gio === khungGio[i-1] ? 'selected' : ''}>${gio}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            }

            suatChieuList.innerHTML = html;
        }
    </script>
</body>

</html>