<?php
session_start();
include __DIR__ . '/../Connect/connecDB.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../LoginAndSign-up/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);
$user = null;
$saveMessage = '';

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

$sql = "SELECT * FROM nguoidung WHERE id_user = $user_id";
$result = $conn->query($sql);
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
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 28px;
        margin-bottom: 28px;
        align-items: start;
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
        color: #dbeafe;
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

    .account-panel {
        display: none !important;
    }

    .account-panel.active {
        display: flex !important;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 28px;
        margin-top: 18px;
    }

    .account-card {
        display: flex;
        flex-direction: column;
        background: rgba(15, 23, 42, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 22px 64px rgba(0, 0, 0, 0.16);
    }

    .account-card h3 {
        margin: 0 0 16px;
        font-size: 1.35rem;
        color: #f8fafc;
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

    .detail-item textarea {
        min-height: 90px;
        resize: vertical;
        font-family: inherit;
    }

    .account-card .profile-actions {
        justify-content: center;
        margin-top: auto;
        width: 100%;
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
        display: flex;
        align-items: flex-start;
    }

    .history-header p {
        margin: 4px 0 0;
        color: #cbd5e1;
        line-height: 1.7;
        max-width: 420px;
    }

    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        overflow: hidden;
        background: transparent;
    }

    .history-table thead th {
        color: #e2e8f0;
        font-weight: 600;
        text-align: left;
        padding: 16px 18px;
        background: rgba(15, 23, 42, 0.95);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .history-table tbody tr {
        background: rgba(255, 255, 255, 0.04);
        border-radius: 20px;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.06);
    }

    .history-table tbody td {
        padding: 18px 18px;
        color: #cbd5e1;
        vertical-align: middle;
        border: none;
    }

    .history-table tbody tr td:first-child {
        font-weight: 600;
        color: #ffffff;
    }

    .history-table tbody tr td[colspan] {
        padding: 32px 18px;
        text-align: center;
        color: #94a3b8;
        font-style: italic;
    }

    .history-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #ffffff;
        background: rgba(56, 189, 248, 0.18);
        border: 1px solid rgba(56, 189, 248, 0.28);
    }

    .history-status.complete {
        background: rgba(34, 197, 94, 0.16);
        border-color: rgba(34, 197, 94, 0.28);
    }

    .history-status.pending {
        background: rgba(234, 179, 8, 0.16);
        border-color: rgba(234, 179, 8, 0.28);
    }

    .history-status.cancelled {
        background: rgba(248, 113, 113, 0.16);
        border-color: rgba(248, 113, 113, 0.28);
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

        .profile-hero,
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
</style>

<?php include '../Module/header.php'; ?>

<div class="profile-frame">
    <section class="profile-header">
        <h1>Hồ sơ của tôi</h1>
    </section>

    <section class="profile-hero">
        <div class="card profile-card">
            <div class="profile-card-content">
                <div class="profile-top">
                    <div class="profile-photo">
                        <img src="https://i.pravatar.cc/320?u=<?= urlencode($user['email'] ?? 'guest') ?>" alt="Ảnh đại diện">
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
                    <button type="button" class="primary-btn">Đổi mật khẩu</button>
                    <button type="button" id="updateInfoBtn" class="secondary-btn">Cập nhật thông tin</button>
                </div>
            </div>
        </div>

        <div class="card history-card">
            <div class="history-card-content">
                <div class="history-header">
                    <div>
                        <p>Lịch sử giao dịch</p>
                    </div>
                </div>

                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Mã giao dịch</th>
                            <th>Ngày đặt</th>
                            <th>Thành tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="history-empty">
                                <strong>Chưa có giao dịch nào</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="profile-grid">
        <div class="account-card account-panel" id="accountPanel">
            <div class="account-card-content">
                <h3>Thông tin tài khoản</h3>
                <form method="post" class="account-form">
                    <input type="hidden" name="save_profile" value="1">
                    <div class="detail-list">
                        <div class="detail-item">
                            <span>Người dùng</span>
                            <input type="text" name="ten" value="<?= escape($user['ten']) ?>" required>
                        </div>
                        <div class="detail-item">
                            <span>Email</span>
                            <input type="email" name="email" value="<?= escape($user['email']) ?>" required>
                        </div>
                        <div class="detail-item">
                            <span>Số điện thoại</span>
                            <input type="text" name="sdt" value="<?= escape($user['sdt'] ?? '') ?>">
                        </div>
                        <div class="detail-item">
                            <span>CCCD</span>
                            <input type="text" name="cccd" value="<?= escape($user['cccd'] ?? '') ?>">
                        </div>
                        <div class="detail-item">
                            <span>Ngày sinh</span>
                            <input type="date" name="ngay_sinh" value="<?= escape($user['ngay_sinh'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="primaryB-btn">Lưu thay đổi</button>
                    </div>
                </form>
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
        const accountPanel = document.getElementById('accountPanel');

        if (!updateInfoBtn || !accountPanel) {
            return;
        }

        if (accountPanel.classList.contains('active')) {
            updateInfoBtn.textContent = 'Thu gọn thông tin';
        }

        updateInfoBtn.addEventListener('click', function(event) {
            event.preventDefault();
            accountPanel.classList.toggle('active');
            updateInfoBtn.textContent = accountPanel.classList.contains('active') ? 'Thu gọn thông tin' : 'Cập nhật thông tin';
        });
    });
</script>

<?php include '../Module/footer.php'; ?>