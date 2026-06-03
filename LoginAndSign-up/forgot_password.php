<?php
session_start();
include __DIR__ . "/../Connect/connecDB.php";
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$messageType = '';
$session_email = $_SESSION['reset_email'] ?? '';

// Xử lý khi nhấn nút Hủy (xóa session và quay về login)
if (isset($_GET['action']) && $_GET['action'] === 'cancel') {
    unset($_SESSION['reset_email']);
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_otp'])) {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $message = 'Vui lòng nhập email';
        $messageType = 'error';
    } else {
        $stmt = $conn->prepare('SELECT id_user FROM nguoidung WHERE email = ?');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $stmt_insert = $conn->prepare('INSERT INTO password_reset_otp (email, otp, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))');
                if ($stmt_insert) {
                    $stmt_insert->bind_param('ss', $email, $otp);
                    if ($stmt_insert->execute()) {
                        $mail = new PHPMailer(true);

                        try {

                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth   = true;

                            $mail->Username = 'tann14195@gmail.com';
                            $mail->Password = 'dsgc wqzl gklo pipe';

                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 587;

                            $mail->CharSet = 'UTF-8';

                            $mail->setFrom(
                                'tann14195@gmail.com',
                                'WebBooking'
                            );

                            $mail->addAddress($email);

                            $mail->isHTML(true);

                            $mail->Subject = 'Mã OTP Đặt Lại Mật Khẩu';

                            $mail->Body = "
                        <h2>Đặt lại mật khẩu</h2>
                        
                        <p>Mã OTP của bạn là:</p>
                        
                        <h1 style='color:red'>$otp</h1>
                        
                        <p>Mã sẽ hết hạn sau 10 phút.</p>
                        ";

                            $mail->send();

                            $message = 'OTP đã được gửi tới email của bạn.';
                            $messageType = 'success';

                            $_SESSION['reset_email'] = $email;
                        } catch (Exception $e) {

                            $message = 'Lỗi gửi email: ' . $mail->ErrorInfo;
                            $messageType = 'error';
                        }
                    } else {
                        $message = 'Không thể gửi email. Vui lòng thử lại.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Lỗi hệ thống. Vui lòng thử lại.';
                    $messageType = 'error';
                }
                $stmt_insert->close();
            } else {
                $message = 'Email chưa tồn tại trong hệ thống.';
                $messageType = 'error';
            }
            $stmt->close();
        } else {
            $message = 'Lỗi hệ thống. Vui lòng thử lại.';
            $messageType = 'error';
        }
    }
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
        .form-login {
            background: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px) !important;
            max-width: 380px !important;
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
            box-sizing: border-box !important;
        }

        .info-box {
            color: #94a3b8;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-sizing: border-box !important;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2) !important;
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

        .success-box {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: #4CAF50;
            margin-bottom: 25px;
        }

        .success-box i {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }

        .continue-btn {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: 0.3s;
            margin: 15px 0 0 0 !important;
            box-sizing: border-box !important;
        }

        .continue-btn:hover {
            background: #0ea5e9;
            transform: translateY(-2px);
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
            <div style="text-align: center; margin-bottom: 20px;">
                <i class="fas fa-lock-open" style="font-size: 40px; color: #38bdf8;"></i>
            </div>
            <h1 class="login" style="font-size: 28px; margin-bottom: 10px;">Quên Mật Khẩu</h1>
            <p style="text-align: center; color: #94a3b8; margin-bottom: 25px; font-size: 14px;">Nhập email của bạn để nhận mã OTP xác thực</p>

            <?php if (!isset($_SESSION['reset_email']) || empty($_SESSION['reset_email'])): ?>
                <div class="info-box">
                    <span>Nhập email liên kết với tài khoản của bạn</span>
                </div>

                <form method="post">
                    <div class="form-input">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Nhập email của bạn"
                            required>
                    </div>

                    <button type="submit" name="send_otp" class="button">
                        <i class="fas fa-paper-plane"></i> Gửi OTP
                    </button>

                    <button type="button" onclick="window.location.href='forgot_password.php?action=cancel'" class="button btn-secondary">
                        Hủy bỏ
                    </button>
                </form>
            <?php else: ?>
                <div class="success-box">
                    <i class="fas fa-check-circle"></i>
                    <p style="font-weight: 600; font-size: 15px;">OTP Đã Được Gửi Thành Công!</p>
                    <p>Hãy kiểm tra email: <strong><?= htmlspecialchars($session_email) ?></strong></p>
                </div>

                <p style="text-align: center; color: #718096; font-size: 14px; margin-bottom: 20px;">
                    Mã OTP sẽ hết hạn sau 10 phút
                </p>

                <a href="verify_otp_reset.php" class="continue-btn" style="background: linear-gradient(135deg, #38bdf8, #0ea5e9);">
                    <i class="fas fa-arrow-right"></i> Nhập Mã OTP
                </a>

                <button type="button" onclick="window.location.href='forgot_password.php?action=cancel'" class="button btn-secondary">
                    Hủy và làm lại
                </button>
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
    </script>
</body>

</html>