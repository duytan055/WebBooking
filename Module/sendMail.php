<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendTicketEmail($idDatVe, $conn)
{
    // Lấy thông tin vé + user
    $sql = "SELECT 
                dv.id_datve,
                dv.tong_tien,
                dv.ma_giao_dich,
                dv.ten_nguoi_dat,
                dv.so_dien_thoai,
                dv.trang_thai,
                dv.phuong_thuc_thanh_toan,
                dv.giam_gia,
                nd.email,
                p.ten_phim,
                sc.date_chieu,
                ph.ten_phong
            FROM datve dv
            JOIN suatchieu sc ON dv.id_suat = sc.id_suat
            JOIN phim p ON sc.id_phim = p.id_phim
            JOIN phongchieu ph ON sc.id_phong = ph.id_phong
            JOIN nguoidung nd ON dv.id_user = nd.id_user
            WHERE dv.id_datve = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idDatVe);
    $stmt->execute();
    $ticket = $stmt->get_result()->fetch_assoc();

    if (!$ticket || empty($ticket['email'])) {
        error_log("sendMail: Không tìm thấy vé $idDatVe hoặc email trống");
        return false;
    }

    // Lấy danh sách ghế
    $sqlSeat = "SELECT g.ma_ghe FROM chitietve ct JOIN ghe g ON ct.id_ghe = g.id_ghe WHERE ct.id_datve = ?";
    $stmtSeat = $conn->prepare($sqlSeat);
    $stmtSeat->bind_param("i", $idDatVe);
    $stmtSeat->execute();
    $rsSeat = $stmtSeat->get_result();
    $seats = [];
    while ($row = $rsSeat->fetch_assoc()) {
        $seats[] = $row['ma_ghe'];
    }

    // Format ngày
    $dateObj = new DateTime($ticket['date_chieu']);
    $dayOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
    $dayName = $dayOfWeek[$dateObj->format('w')];
    $formattedDate = $dayName . ', ' . $dateObj->format('d/m/Y');

    // Trạng thái
    $statusLabel = '⏳ Chờ thanh toán';

    if ($ticket['trang_thai'] == 'PAID') {
        $statusLabel = '✅ Đã thanh toán';
    } elseif ($ticket['trang_thai'] == 'PENDING') {
        $statusLabel = '⏳ Chờ thanh toán';
    } elseif (in_array($ticket['trang_thai'], ['CANCELLED', 'EXPIRED'])) {
        $statusLabel = '❌ Đã hủy';
    }


    $seatList = !empty($seats) ? implode(', ', $seats) : 'Chưa có ghế';

    // Tạo nội dung email HTML
    $subject = 'Xác nhận đặt vé - WebBooking';
    $body = '
    <!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"></head>
    <body style="font-family: Arial, Helvetica, sans-serif; background: #f4f4f6; margin: 0; padding: 30px 10px;">
        <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <!-- Header -->
            <tr>
                <td style="background: linear-gradient(135deg, #1e3a5f, #0f172a); padding: 32px 24px; text-align: center;">
                    <h1 style="color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 1px;">🎬 WEBBOOKING</h1>
                    <p style="color: #94a3b8; margin: 8px 0 0; font-size: 13px;">Xác nhận đặt vé</p>
                </td>
            </tr>
            <!-- Status -->
            <tr>
                <td style="padding: 20px 24px; text-align: center; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-size: 15px; font-weight: 700; color: #1e293b;">' . $statusLabel . '</span>
                </td>
            </tr>
            <!-- Movie Info -->
            <tr>
                <td style="padding: 24px;">
                    <h2 style="font-size: 20px; color: #0f172a; margin: 0 0 6px;">' . htmlspecialchars($ticket['ten_phim']) . '</h2>
                    <p style="font-size: 12px; color: #64748b; margin: 0 0 14px;">Mã vé: #' . str_pad($ticket['id_datve'], 6, '0', STR_PAD_LEFT) . '</p>

                    <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 12px; background: #f8fafc; border-radius: 8px;">
                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">📅 Ngày chiếu</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . $formattedDate . '</td></tr>
                                    <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">⏰ Giờ chiếu</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($ticket['thoi_gian']) . '</td></tr>
                                    <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">🚪 Phòng</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($ticket['ten_phong']) . '</td></tr>
                                    <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">💺 Ghế</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($seatList) . '</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Customer Info -->
            <tr>
                <td style="padding: 0 24px;">
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;">
                </td>
            </tr>
            <tr>
                <td style="padding: 16px 24px;">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">👤 Khách hàng</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($ticket['ten_nguoi_dat']) . '</td></tr>
                        <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">📧 Email</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($ticket['email']) . '</td></tr>
                        <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">📞 SĐT</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($ticket['so_dien_thoai']) . '</td></tr>
                    </table>
                </td>
            </tr>
            <!-- Payment -->
            <tr>
                <td style="padding: 0 24px;">
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;">
                </td>
            </tr>
            <tr>
                <td style="padding: 16px 24px;">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tr><td style="font-size: 12px; color: #64748b;">💳 Phương thức</td><td style="font-size: 13px; font-weight: 600; color: #0f172a; text-align: right;">' . htmlspecialchars($ticket['phuong_thuc_thanh_toan'] ?? 'Chưa xác định') . '</td></tr>
                        <tr><td style="font-size: 12px; color: #64748b; padding: 4px 0;">📄 Mã giao dịch</td><td style="font-size: 13px; font-weight: 700; color: #0f172a; text-align: right; font-family: monospace;">' . htmlspecialchars($ticket['ma_giao_dich'] ?? 'N/A') . '</td></tr>
                    </table>
                </td>
            </tr>
            <!-- Total -->
            <tr>
                <td style="padding: 16px 24px 24px;">
                    <table cellpadding="0" cellspacing="0" style="width: 100%; background: #f1f5f9; border-radius: 12px; padding: 16px;">
                        <tr>
                            <td style="font-size: 14px; color: #64748b;">Tổng thanh toán</td>
                            <td style="font-size: 24px; font-weight: 800; color: #0f172a; text-align: right;">' . number_format($ticket['tong_tien'], 0, ',', '.') . ' ₫</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Footer -->
            <tr>
                <td style="background: #0f172a; padding: 20px 24px; text-align: center;">
                    <p style="color: #94a3b8; font-size: 12px; margin: 0;">Vui lòng đến rạp trước giờ chiếu 15 phút để nhận vé.</p>
                    <p style="color: #64748b; font-size: 11px; margin: 8px 0 0;">WebBooking - Hệ thống đặt vé xem phim trực tuyến</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';

    // Cấu hình SMTP
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        define('MAIL_USER', 'tann14195@gmail.com');
        define('MAIL_PASS', 'APP_PASSWORD');
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS; // App Password Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('tann14195@gmail.com', 'WebBooking Cinema');
        $mail->addAddress($ticket['email'], $ticket['ten_nguoi_dat']);
        $mail->addReplyTo('tann14195@gmail.com', 'Hỗ trợ WebBooking');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->CharSet = 'UTF-8';

        $mail->send();
        error_log("sendMail: Đã gửi email thành công cho {$ticket['email']} (vé #$idDatVe)");
        return true;
    } catch (Exception $e) {
        error_log("sendMail: Lỗi gửi email cho vé #$idDatVe: " . $mail->ErrorInfo);
        return false;
    }
}
