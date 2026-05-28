<?php
session_start();

$policies = [

    'dieu-khoan-chung' => [
        'title' => 'ĐIỀU KHOẢN CHUNG',
        'content' => 'Khi truy cập và sử dụng website StarLight Cinema, khách hàng đồng ý tuân thủ các điều khoản, quy định và chính sách vận hành hiện hành của hệ thống.

Mọi nội dung hiển thị trên website, bao gồm hình ảnh, văn bản, bố cục giao diện và dữ liệu liên quan, đều thuộc quyền sở hữu và quản lý của StarLight Cinema.

Khách hàng có trách nhiệm cung cấp thông tin chính xác, đầy đủ và cập nhật khi đăng ký tài khoản, đặt vé hoặc liên hệ hỗ trợ.

StarLight Cinema có quyền thay đổi, bổ sung hoặc điều chỉnh nội dung, quy trình và dịch vụ bất kỳ lúc nào nhằm nâng cao trải nghiệm người dùng, mà không cần thông báo trước.'
    ],

    'thanh-toan' => [
        'title' => 'CHÍNH SÁCH THANH TOÁN',
        'content' => 'StarLight Cinema hỗ trợ nhiều hình thức thanh toán hiện đại như thẻ ATM, Visa, MasterCard và các ví điện tử phổ biến.

Sau khi thanh toán thành công, hệ thống sẽ xác nhận đơn hàng ngay lập tức và gửi mã vé điện tử đến email hoặc tài khoản cá nhân của khách hàng.

Khách hàng vui lòng kiểm tra kỹ thông tin phim, suất chiếu, số ghế và tổng tiền trước khi xác nhận thanh toán.

Tất cả giao dịch đều được bảo vệ bằng các biện pháp an toàn và quy trình thanh toán bảo mật của hệ thống.'
    ],

    'giao-nhan' => [
        'title' => 'CHÍNH SÁCH HOÀN VÉ',
        'content' => 'Mỗi vé đã thanh toán sẽ được gửi đến email hoặc tài khoản cá nhân trên website của khách hàng.

Vé có thể được hỗ trợ đổi hoặc hoàn theo quy định hiện hành của rạp và theo chính sách áp dụng tại thời điểm đặt vé.

Khách hàng cần thực hiện yêu cầu đổi hoặc hoàn vé trước giờ chiếu theo thời gian quy định.

Trong trường hợp không nhận được mã vé hoặc gặp sự cố kỹ thuật, vui lòng liên hệ bộ phận hỗ trợ để được xử lý nhanh chóng và đúng quy trình.'
    ],

    'bao-mat' => [
        'title' => 'CHÍNH SÁCH BẢO MẬT',
        'content' => 'StarLight Cinema cam kết bảo vệ thông tin cá nhân của khách hàng với mức độ bảo mật cao nhất.

Thông tin như họ tên, email, số điện thoại, lịch sử đặt vé và các dữ liệu liên quan chỉ được sử dụng cho mục đích đặt vé, hỗ trợ khách hàng và gửi thông báo cần thiết.

Chúng tôi không chia sẻ, trao đổi hoặc cung cấp dữ liệu khách hàng cho bên thứ ba nếu chưa có sự đồng ý rõ ràng của khách hàng, trừ khi pháp luật yêu cầu.

Khách hàng có quyền kiểm tra, chỉnh sửa hoặc yêu cầu xóa thông tin cá nhân khỏi hệ thống bất cứ lúc nào.'
    ]

];

$slug = $_GET['page'] ?? 'thanh-toan';
$slug = strtolower(trim($slug));

if (!array_key_exists($slug, $policies)) {
    $slug = 'thanh-toan';
}

$policy = $policies[$slug];
?>

<?php include '../Module/header.php'; ?>

<style>
    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        min-height: 100vh;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(180deg, #0f172a, #0f172a);
        color: #fff;
    }

    .page-shell {
        width: min(1100px, 92%);
        margin: auto;
        padding: 40px 0;
    }

    .policy-container {
        background: rgba(15, 23, 42, .75);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .3);
        animation: fade .25s ease;
    }

    @keyframes fade {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .policy-title {
        font-size: 30px;
        margin-bottom: 15px;
    }

    .policy-content {
        color: #cbd5e1;
        line-height: 1.8;
        font-size: 16px;
        white-space: pre-line;
    }

    #top {
        position: absolute;
        top: 0;
    }
</style>

<div id="top"></div>

<main class="page-shell">

    <div class="policy-container">

        <h1 class="policy-title">
            <?= $policy['title'] ?>
        </h1>

        <div class="policy-content">
            <?= nl2br($policy['content']) ?>
        </div>

    </div>

</main>

<?php include '../Module/footer.php'; ?>

<script>
    window.onload = function() {
        window.scrollTo(0, 0);
    };
</script>