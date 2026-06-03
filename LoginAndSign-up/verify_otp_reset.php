<?php
session_start();
include __DIR__ . "/../Connect/connecDB.php";

$message = '';
$messageType = '';

// Kiểm tra email trong session
if (!isset($_SESSION['reset_email']) || empty($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit;
}

$reset_email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_and_reset'])) {
    $otp = trim($_POST['otp'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validate
    if (empty($otp)) {
        $message = 'Vui lòng nhập mã OTP';
        $messageType = 'error';
    } elseif (empty($new_password)) {
        $message = 'Vui lòng nhập mật khẩu mới';
        $messageType = 'error';
    } elseif (empty($confirm_password)) {
        $message = 'Vui lòng xác nhận mật khẩu';
        $messageType = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = 'Mật khẩu xác nhận không khớp';
        $messageType = 'error';
    } elseif (strlen($new_password) < 8) {
        $message = 'Mật khẩu phải có ít nhất 8 ký tự';
        $messageType = 'error';
    } elseif (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[!@#$%^&*]/', $new_password)) {
        $message = 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt';
        $messageType = 'error';
    } else {
        // Kiểm tra OTP
        $stmt = $conn->prepare('SELECT id, is_used FROM password_reset_otp WHERE email = ? AND otp = ? AND expires_at > NOW() AND is_used = FALSE ORDER BY created_at DESC LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('ss', $reset_email, $otp);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $otp_record = $result->fetch_assoc();
                $otp_id = $otp_record['id'];

                // Cập nhật mật khẩu
                $stmt_update = $conn->prepare('UPDATE nguoidung SET mat_khau = ? WHERE email = ?');
                if ($stmt_update) {
                    $stmt_update->bind_param('ss', $new_password, $reset_email);
                    if ($stmt_update->execute()) {
                        // Đánh dấu OTP là đã sử dụng
                        $stmt_mark = $conn->prepare('UPDATE password_reset_otp SET is_used = TRUE WHERE id = ?');
                        if ($stmt_mark) {
                            $stmt_mark->bind_param('i', $otp_id);
                            $stmt_mark->execute();
                            $stmt_mark->close();
                        }

                        $message = 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.';
                        $messageType = 'success';
                        unset($_SESSION['reset_email']);
                    } else {
                        $message = 'Không thể cập nhật mật khẩu. Vui lòng thử lại.';
                        $messageType = 'error';
                    }
                    $stmt_update->close();
                }
            } else {
                $message = 'Mã OTP không hợp lệ hoặc đã hết hạn';
                $messageType = 'error';
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Xác Nhận OTP Và Đặt Lại Mật Khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
        .form-login {
            background: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px) !important;
            max-width: 400px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            padding: 40px 30px !important;
        }

        .button {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9) !important;
            margin: 15px 0 0 0 !important;
            width: 100% !important;
            border-radius: 12px !important;
            padding: 12px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .form-input>input {
            width: 100% !important;
            margin: 0 0 15px 0 !important;
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            padding: 12px 15px !important;
            box-sizing: border-box !important;
        }

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

        .password-requirements {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px;
            color: #cbd5e1;
            font-size: 13px;
            margin: 15px 0;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0;
        }

        .requirement i {
            width: 14px;
            text-align: center;
            font-size: 12px;
        }

        .requirement.met {
            color: #86efac;
        }

        .requirement.unmet {
            color: #fca5a5;
        }

        .success-info {
            color: #4CAF50;
            text-align: center;
            margin: 20px 0;
        }

        .success-info a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: bold;
        }

        .success-info a:hover {
            text-decoration: underline;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php if ($message): ?>
        <div class="notification <?= $messageType ?>" id="notification">
            <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <span><?php echo $message; ?></span>
        </div>
    <?php endif; ?>

    <div class="kkk">
        <div class="form-login">
            <div style="text-align: center; margin-bottom: 15px;">
                <i class="fas fa-user-shield" style="font-size: 40px; color: #38bdf8;"></i>
            </div>
            <h1 class="login" style="font-size: 26px; margin-bottom: 5px;">Đặt Lại Mật Khẩu</h1>
            <p style="text-align: center; color: #94a3b8; margin-bottom: 20px; font-size: 13px;">Nhập mã OTP và thiết lập mật khẩu mới</p>

            <?php if ($messageType === 'success'): ?>
                <div class="success-info">
                    <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <p style="font-weight: 600; font-size: 15px; margin-bottom: 10px;">Đặt Lại Mật Khẩu Thành Công!</p>
                    <p style="color: #cbd5e1">Hãy <a href="login.php">đăng nhập</a> với mật khẩu mới của bạn</p>
                </div>
                <button type="button" onclick="window.location.href='login.php'" class="button">Đăng nhập ngay</button>
            <?php else: ?>
                <div style="color: #cbd5e1; text-align: center; margin-bottom: 15px; font-size: 13px;">
                    <span>Gửi mã OTP tới: <strong style="color: #38bdf8;"><?= htmlspecialchars($reset_email) ?></strong></span>
                </div>

                <form method="post">
                    <div class="password-requirements">
                        <strong style="display: block; margin-bottom: 8px;">Yêu Cầu Mật Khẩu:</strong>
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

                    <div class="form-input">
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            placeholder="000000"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            required>
                    </div>

                    <div class="form-input">
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            placeholder="Mật khẩu mới"
                            required>
                    </div>

                    <div class="form-input">
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Xác nhận mật khẩu"
                            required>
                    </div>

                    <button type="submit" name="verify_and_reset" class="button">
                        <i class="fas fa-lock"></i> Đặt Lại Mật Khẩu
                    </button>

                    <button type="button" onclick="window.location.href='forgot_password.php?action=cancel'" class="button btn-secondary">
                        Hủy bỏ
                    </button>
                </form>
            <?php endif; ?>
        </div>
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

        // Password validation
        const passwordInput = document.getElementById('new_password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
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

        // OTP input - only allow numbers
        const otpInput = document.getElementById('otp');
        if (otpInput) {
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    </script>
</body>

</html>