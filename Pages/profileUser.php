<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../LoginAndSign-up/login.php');
    exit;
}


$user_id = intval($_SESSION['user']['id']);
$user = null;
$saveMessage = '';
$changePasswordMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $ten = trim($_POST['ten'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sdt = trim($_POST['sdt'] ?? '');
    $cccd = trim($_POST['cccd'] ?? '');
    $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');

    if ($ten === '' || $email === '') {
        $saveMessage = 'Tên và email không được để trống.';
    } else {
        $stmt = $conn->prepare('UPDATE nguoidung SET ten = ?, email = ?, sdt = ?, cccd = ?, ngay_sinh = ? WHERE id_user = ?');
        if ($stmt) {
            $stmt->bind_param('sssssi', $ten, $email, $sdt, $cccd, $ngay_sinh, $user_id);
            if ($stmt->execute()) {
                $saveMessage = 'Cập nhật thông tin thành công.';
                $_SESSION['username'] = $ten;
                $_SESSION['email'] = $email;
            } else {
                $saveMessage = 'Không thể cập nhật thông tin. Vui lòng thử lại.';
            }
            $stmt->close();
        } else {
            $saveMessage = 'Lỗi hệ thống. Vui lòng thử lại sau.';
        }
    }
}

// Xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = trim($_POST['old_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($old_password)) {
        $changePasswordMessage = 'Vui lòng nhập mật khẩu cũ';
    } elseif (empty($new_password)) {
        $changePasswordMessage = 'Vui lòng nhập mật khẩu mới';
    } elseif (empty($confirm_password)) {
        $changePasswordMessage = 'Vui lòng xác nhận mật khẩu mới';
    } elseif ($new_password !== $confirm_password) {
        $changePasswordMessage = 'Mật khẩu xác nhận không khớp';
    } elseif (strlen($new_password) < 8) {
        $changePasswordMessage = 'Mật khẩu phải có ít nhất 8 ký tự';
    } elseif (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[!@#$%^&*]/', $new_password)) {
        $changePasswordMessage = 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt';
    } else {
        // Kiểm tra mật khẩu cũ
        $stmt = $conn->prepare('SELECT mat_khau FROM nguoidung WHERE id_user = ?');
        if ($stmt) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['mat_khau'] === $old_password) {
                    // Cập nhật mật khẩu mới
                    $stmt_update = $conn->prepare('UPDATE nguoidung SET mat_khau = ? WHERE id_user = ?');
                    if ($stmt_update) {
                        $stmt_update->bind_param('si', $new_password, $user_id);
                        if ($stmt_update->execute()) {
                            $changePasswordMessage = 'Đổi mật khẩu thành công';
                        } else {
                            $changePasswordMessage = 'Lỗi hệ thống. Vui lòng thử lại';
                        }
                        $stmt_update->close();
                    }
                } else {
                    $changePasswordMessage = 'Mật khẩu cũ không chính xác';
                }
            }
            $stmt->close();
        }
    }
}

$sql = "SELECT * FROM nguoidung WHERE id_user = $user_id";
$result = $conn->query($sql);
$history_sql = "SELECT
    d.id_datve,
    p.ten_phim,
    CONCAT(sc.date_chieu,' ',sc.thoi_gian) AS suat_chieu,
    COALESCE(GROUP_CONCAT(g.ma_ghe ORDER BY g.ma_ghe SEPARATOR ', '), '---') AS danh_sach_ghe,
    d.tong_tien,
    d.trang_thai,
    d.thoi_gian_dat
FROM datve d
LEFT JOIN suatchieu sc ON d.id_suat = sc.id_suat
LEFT JOIN phim p ON sc.id_phim = p.id_phim
LEFT JOIN chitietve ct ON d.id_datve = ct.id_datve
LEFT JOIN ghe g ON ct.id_ghe = g.id_ghe
WHERE d.id_user = $user_id
GROUP BY d.id_datve
ORDER BY d.thoi_gian_dat DESC
";

$history_result = mysqli_query($conn, $history_sql);
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $user = [
        'ten' => $_SESSION['username'] ?? 'Khách',
        'email' => $_SESSION['email'] ?? 'Không có',
        'cccd' => 'Chưa cập nhật',
        'sdt' => 'Chưa cập nhật',
        'ngay_sinh' => 'Chưa cập nhật',
    ];
}

function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<style>
    body {
        margin: 0;
        min-height: 100vh;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background:
            radial-gradient(circle at top left,
                rgba(56, 189, 248, 0.14),
                transparent 24%),
            linear-gradient(180deg, #0b1420 0%, #111d2f 100%);
        color: #edf2f7;
    }

    .profile-frame {
        width: min(1160px, 96%);
        margin: 0 auto 60px;
        padding: 40px 0 20px;
    }

    .top-actions {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 20px;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .profile-header h1 {
        margin: 0;
        font-size: clamp(2rem, 3vw, 3.4rem);
        letter-spacing: 0.04em;
        color: #f8fafc;
    }

    .profile-header p {
        margin: 14px auto 0;
        max-width: 760px;
        color: #cbd5e1;
        font-size: 1rem;
        line-height: 1.75;
    }

    .profile-hero {
        display: flex;
        flex-direction: column;
        gap: 28px;
        margin-bottom: 28px;
    }

    .card {
        background: rgba(15, 23, 42, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.18);
        padding: 28px;
        display: grid;
        gap: 22px;
    }

    .profile-card {
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }

    .profile-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right,
                rgba(56, 189, 248, 0.16),
                transparent 28%);
        pointer-events: none;
    }

    .profile-card-content,
    .history-card-content,
    .account-card-content {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 20px;
    }

    .profile-summary {
        display: grid;
        gap: 20px;
    }

    .profile-top {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 20px;
        align-items: center;
    }

    .profile-photo {
        width: 112px;
        height: 112px;
        border-radius: 28px;
        overflow: hidden;
        border: 1px solid rgba(56, 189, 248, 0.24);
        background: #0f172a;
        display: grid;
        place-items: center;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-title {
        display: grid;
        gap: 4px;
    }

    .profile-title h2 {
        margin: 0;
        font-size: 1.8rem;
        color: #f8fafc;
    }

    .profile-title p {
        margin: 0;
        color: #cbd5e1;
        line-height: 1.6;
    }

    .profile-details {
        display: grid;
        gap: 12px;
        padding: 0 4px;
    }

    .profile-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .profile-row span {
        color: #94a3b8;
        font-size: 0.95rem;
    }

    .profile-row strong {
        color: #f8fafc;
        font-weight: 600;
        text-align: right;
    }

    .profile-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
    }

    .save-message {
        padding: 14px 18px;
        border-radius: 18px;
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.24);
        text-align: center;
    }

    .button,
    .primary-btn,
    .secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 20px;
        border-radius: 999px;
        border: none;
        color: #ffffff;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .primary-btn {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        box-shadow: 0 12px 30px rgba(56, 189, 248, 0.18);
    }

    .secondary-btn {
        background: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .primary-btn,
    .secondary-btn {
        width: 190px;
    }

    .primary-btn:hover,
    .secondary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(56, 189, 248, 0.18);
    }

    .primaryB-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 20px;
        border-radius: 999px;
        border: none;
        color: #ffffff;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .primaryB-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(56, 189, 248, 0.18);
    }

    .profile-actions .primaryB-btn {
        width: 190px;
        margin-top: 15px;
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        box-shadow: 0 12px 30px rgba(56, 189, 248, 0.18);
    }

    .account-card-content {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 20px;
        flex: 1;
        min-height: 0;
    }

    .detail-list {
        display: grid;
        gap: 12px;
    }

    .detail-item {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        padding: 16px 18px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .detail-item span {
        color: #94a3b8;
        font-size: 0.94rem;
    }

    .detail-item strong,
    .detail-item input,
    .detail-item textarea {
        color: #f8fafc;
        font-size: 0.99rem;
        text-align: right;
    }

    .detail-item input,
    .detail-item textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(15, 23, 42, 0.9);
        color: #f8fafc;
        outline: none;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .detail-item input:focus,
    .detail-item textarea:focus {
        border-color: rgba(56, 189, 248, 0.5);
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.08);
    }

    .account-card .profile-actions a {
        width: auto;
    }

    .logout-actions {
        display: flex;
        justify-content: center;
        margin-top: 24px;
    }

    .logout-actions .primary-btn {
        min-width: 180px;
    }

    .history-header {
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 15px;
    }

    .history-header h3 {
        margin: 0;
        font-size: 1.5rem;
        color: #38bdf8;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
        overflow: hidden;
        background: transparent;
    }

    .history-table thead th {
        color: #94a3b8;
        font-weight: 700;
        text-align: left;
        padding: 12px 20px;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .history-table tbody tr {
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .history-table tbody tr:hover {
        background: rgba(56, 189, 248, 0.08);
        transform: scale(1.005);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .history-table tbody td {
        padding: 20px;
        color: #cbd5e1;
        vertical-align: middle;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .history-table tbody tr td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.05);
        border-top-left-radius: 16px;
        border-bottom-left-radius: 16px;
        color: #38bdf8;
        font-weight: 600;
        font-family: monospace;
        font-size: 1.1rem;
    }

    .history-table tbody tr td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
    }

    .movie-name-cell {
        color: #f8fafc !important;
        font-weight: 600;
    }

    .price-cell {
        color: #fbbf24 !important;
        font-weight: 700;
    }

    .history-table tbody tr td[colspan] {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
        font-style: italic;
        border-radius: 16px;
    }

    .history-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .history-status.complete {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .history-status.pending {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.2);
    }

    .history-status.cancelled {
        background: rgba(248, 113, 113, 0.1);
        color: #f87171;
        border: 1px solid rgba(248, 113, 113, 0.2);
    }

    .history-empty {
        padding: 26px 24px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px dashed rgba(255, 255, 255, 0.18);
        color: #94a3b8;
        text-align: center;
    }

    .history-empty strong {
        color: #f8fafc;
        display: block;
        margin-bottom: 10px;
    }

    @media (max-width: 980px) {

        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 680px) {
        .profile-frame {
            padding: 24px 0 20px;
        }

        .profile-hero {
            gap: 22px;
        }

        .card {
            padding: 22px;
        }

        .profile-top {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .profile-top .profile-title {
            align-items: center;
        }

        .profile-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .profile-actions {
            justify-content: center;
        }
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background: rgba(0, 0, 0, 0.6);
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        padding: 40px;
        margin: auto;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.4);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.6rem;
        color: #f8fafc;
    }

    .close-modal {
        font-size: 28px;
        font-weight: bold;
        color: #94a3b8;
        cursor: pointer;
        border: none;
        background: none;
        transition: color 0.2s ease;
    }

    .close-modal:hover {
        color: #f8fafc;
    }

    .password-requirements {
        background: rgba(249, 115, 22, 0.08);
        border: 1px solid rgba(249, 115, 22, 0.24);
        border-radius: 12px;
        padding: 12px 16px;
        color: #fcd34d;
        font-size: 0.85rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .requirement {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 4px 0;
    }

    .requirement i {
        width: 16px;
        text-align: center;
        font-size: 0.9rem;
    }

    .requirement.met {
        color: #86efac;
    }

    .requirement.unmet {
        color: #fca5a5;
    }

    .modal-form-group {
        margin-bottom: 18px;
    }

    .modal-form-group label {
        display: block;
        margin-bottom: 8px;
        color: #e2e8f0;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .modal-form-group input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(15, 23, 42, 0.9);
        color: #f8fafc;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .modal-form-group input:focus {
        border-color: rgba(56, 189, 248, 0.5);
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.08);
    }

    .modal-message {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 16px;
        font-size: 0.95rem;
    }

    .modal-message.error {
        background: rgba(248, 113, 113, 0.12);
        border: 1px solid rgba(248, 113, 113, 0.24);
        color: #fca5a5;
    }

    .modal-message.success {
        background: rgba(34, 197, 94, 0.12);
        border: 1px solid rgba(34, 197, 94, 0.24);
        color: #86efac;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 24px;
    }

    .modal-btn {
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        flex: 1;
    }

    .modal-btn.primary {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        color: white;
        box-shadow: 0 12px 30px rgba(56, 189, 248, 0.18);
    }

    .modal-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(56, 189, 248, 0.25);
    }

    .modal-btn.secondary {
        background: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .modal-btn.secondary:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #f8fafc;
    }
</style>

<!-- Modal Đổi Mật Khẩu -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Đổi Mật Khẩu</h2>
            <button class="close-modal">&times;</button>
        </div>

        <?php if (!empty($changePasswordMessage)): ?>
            <div class="modal-message <?= strpos($changePasswordMessage, 'thành công') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($changePasswordMessage) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="password-requirements">
                <strong>Yêu cầu mật khẩu mới:</strong>
                <div class="requirement" id="length-req">
                    <i class="fas fa-times"></i> Ít nhất 8 ký tự
                </div>
                <div class="requirement" id="upper-req">
                    <i class="fas fa-times"></i> Ít nhất 1 chữ hoa (A-Z)
                </div>
                <div class="requirement" id="lower-req">
                    <i class="fas fa-times"></i> Ít nhất 1 chữ thường (a-z)
                </div>
                <div class="requirement" id="number-req">
                    <i class="fas fa-times"></i> Ít nhất 1 số (0-9)
                </div>
                <div class="requirement" id="special-req">
                    <i class="fas fa-times"></i> Ít nhất 1 ký tự đặc biệt (!@#$%^&*)
                </div>
            </div>

            <div class="modal-form-group">
                <label for="old_password">Mật Khẩu Cũ</label>
                <input type="password" id="old_password" name="old_password" placeholder="Nhập mật khẩu cũ" required>
            </div>

            <div class="modal-form-group">
                <label for="new_password">Mật Khẩu Mới</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required>
            </div>

            <div class="modal-form-group">
                <label for="confirm_password">Xác Nhận Mật Khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>

            <div class="modal-actions">
                <button type="submit" name="change_password" class="modal-btn primary">Đổi Mật Khẩu</button>
                <button type="button" class="modal-btn secondary close-modal">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cập Nhật Thông Tin -->
<div id="updateInfoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Cập Nhật Thông Tin</h2>
            <button class="close-modal">&times;</button>
        </div>

        <?php if (!empty($saveMessage)): ?>
            <div class="modal-message <?= strpos($saveMessage, 'thành công') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($saveMessage) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="save_profile" value="1">
            <div class="modal-form-group">
                <label>Họ và tên</label>
                <input type="text" name="ten" value="<?= escape($user['ten']) ?>" required>
            </div>
            <div class="modal-form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= escape($user['email']) ?>" required>
            </div>
            <div class="modal-form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" value="<?= escape($user['sdt'] ?? '') ?>">
            </div>
            <div class="modal-form-group">
                <label>CCCD</label>
                <input type="text" name="cccd" value="<?= escape($user['cccd'] ?? '') ?>">
            </div>
            <div class="modal-form-group">
                <label>Ngày sinh</label>
                <input type="date" name="ngay_sinh" value="<?= escape($user['ngay_sinh'] ?? '') ?>">
            </div>
            <div class="modal-actions">
                <button type="submit" class="modal-btn primary">Lưu thay đổi</button>
                <button type="button" class="modal-btn secondary close-modal">Hủy</button>
            </div>
        </form>
    </div>
</div>

<div class="profile-frame">
    <div class="top-actions">
        <button type="button" onclick="history.back()" class="secondary-btn" style="width: auto; padding: 10px 22px;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </button>
    </div>

    <section class="profile-header">
        <h1>Hồ sơ của tôi</h1>
    </section>

    <section class="profile-hero">
        <div class="card profile-card">
            <div class="profile-card-content">
                <div class="profile-top">
                    <div class="profile-photo">
                        <img src="<?= !empty($user['avatar']) ? $user['avatar'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' ?>" alt="Ảnh đại diện">
                    </div>
                    <div>
                        <div class="profile-title">
                            <h2><?= escape($user['ten']) ?></h2>
                        </div>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="profile-row">
                        <span>Email</span>
                        <strong><?= escape($user['email']) ?></strong>
                    </div>
                    <div class="profile-row">
                        <span>Số điện thoại</span>
                        <strong><?= escape($user['sdt'] ?? 'Chưa cập nhật') ?></strong>
                    </div>
                    <div class="profile-row">
                        <span>Ngày sinh</span>
                        <strong><?= escape($user['ngay_sinh'] ?? 'Chưa cập nhật') ?></strong>
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="button" id="changePasswordBtn" class="primary-btn">Đổi mật khẩu</button>
                    <button type="button" id="updateInfoBtn" class="secondary-btn">Cập nhật thông tin</button>
                </div>
            </div>
        </div>

        <div class="card history-card">
            <div class="history-card-content">
                <div class="history-header">
                    <h3><i class="fas fa-history"></i> Lịch sử giao dịch</h3>
                </div>

                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Phim</th>
                            <th>Suất chiếu</th>
                            <th>Ghế</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (mysqli_num_rows($history_result) > 0): ?>

                            <?php while ($row = mysqli_fetch_assoc($history_result)): ?>

                                <tr>
                                    <td>#<?= $row['id_datve'] ?></td>

                                    <td class="movie-name-cell"><?= htmlspecialchars($row['ten_phim']) ?></td>

                                    <td><?= date('d/m/Y H:i', strtotime($row['suat_chieu'])) ?></td>

                                    <td><?= htmlspecialchars($row['danh_sach_ghe']) ?></td>

                                    <td class="price-cell"><?= number_format($row['tong_tien']) ?>đ</td>

                                    <td>
                                        <?php if ($row['trang_thai'] == 'Đã thanh toán' || $row['trang_thai'] == 'PAID'): ?>
                                            <span class="history-status complete">
                                                <i class="fas fa-check-circle"></i>
                                                Đã thanh toán
                                            </span>
                                        <?php elseif ($row['trang_thai'] == 'EXPIRED'): ?>
                                            <span class="history-status cancelled">
                                                <i class="fas fa-times-circle"></i>
                                                Đã hủy
                                            </span>
                                        <?php else: ?>
                                            <span class="history-status pending">
                                                <i class="fas fa-clock"></i>
                                                Chờ thanh toán
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($row['thoi_gian_dat'])) ?>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="history-empty">
                                    <strong>Chưa có giao dịch nào</strong>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="logout-actions">
        <a href="../LoginAndSign-up/logout.php" class="primary-btn">Đăng xuất</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateInfoBtn = document.getElementById('updateInfoBtn');
        const updateInfoModal = document.getElementById('updateInfoModal');
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        const changePasswordModal = document.getElementById('changePasswordModal');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        const newPasswordInput = document.getElementById('new_password');

        // Hiển thị modal tương ứng nếu có thông báo từ hệ thống
        <?php if (!empty($changePasswordMessage)): ?>
            changePasswordModal.classList.add('show');
        <?php endif; ?>

        <?php if (!empty($saveMessage)): ?>
            updateInfoModal.classList.add('show');
        <?php endif; ?>

        if (updateInfoBtn && updateInfoModal) {
            updateInfoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                updateInfoModal.classList.add('show');
            });
        }

        if (changePasswordBtn && changePasswordModal) {
            changePasswordBtn.addEventListener('click', function(event) {
                event.preventDefault();
                changePasswordModal.classList.add('show');
            });
        }

        closeModalButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                updateInfoModal.classList.remove('show');
                changePasswordModal.classList.remove('show');
            });
        });

        window.addEventListener('click', function(event) {
            if (event.target === updateInfoModal || event.target === changePasswordModal) {
                updateInfoModal.classList.remove('show');
                changePasswordModal.classList.remove('show');
            }
        });

        // Password validation for new password
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                updateRequirement('length-req', password.length >= 8);
                updateRequirement('upper-req', /[A-Z]/.test(password));
                updateRequirement('lower-req', /[a-z]/.test(password));
                updateRequirement('number-req', /[0-9]/.test(password));
                updateRequirement('special-req', /[!@#$%^&*]/.test(password));
            });
        }

        function updateRequirement(id, isMet) {
            const elem = document.getElementById(id);
            if (elem) {
                const icon = elem.querySelector('i');
                if (isMet) {
                    elem.classList.remove('unmet');
                    elem.classList.add('met');
                    icon.className = 'fas fa-check';
                } else {
                    elem.classList.remove('met');
                    elem.classList.add('unmet');
                    icon.className = 'fas fa-times';
                }
            }
        }
    });
</script>

<?php include '../Module/footer.php'; ?>