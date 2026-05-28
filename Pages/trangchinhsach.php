<?php
session_start();

$policies = [

    'dieu-khoan-chung' => [
        'title' => 'ĐIỀU KHOẢN CHUNG',
        'content' => 'Khi truy cập và sử dụng website StarLight Cinema, khách hàng đồng ý tuân thủ các điều khoản và quy định của hệ thống.

Mọi thông tin hiển thị trên website bao gồm hình ảnh, nội dung, giao diện và dữ liệu đều thuộc quyền quản lý của StarLight Cinema.

Khách hàng có trách nhiệm cung cấp thông tin chính xác khi đăng ký tài khoản hoặc đặt vé.

StarLight Cinema có quyền thay đổi, cập nhật nội dung hoặc điều chỉnh dịch vụ bất kỳ lúc nào nhằm nâng cao trải nghiệm người dùng mà không cần báo trước.'
    ],

    'thanh-toan' => [
        'title' => 'CHÍNH SÁCH THANH TOÁN',
        'content' => 'StarLight Cinema hỗ trợ nhiều hình thức thanh toán như thẻ ATM nội địa, Visa, MasterCard và ví điện tử.

Sau khi thanh toán thành công, hệ thống sẽ xác nhận đơn hàng ngay lập tức và gửi mã vé điện tử đến email hoặc tài khoản khách hàng.

Khách hàng vui lòng kiểm tra kỹ thông tin phim, suất chiếu, số ghế trước khi xác nhận thanh toán.

Mọi giao dịch đều được bảo mật qua hệ thống thanh toán an toàn.'
    ],

    'giao-nhan' => [
        'title' => 'CHÍNH SÁCH HOÀN VÉ',
        'content' => 'Sau khi đặt vé thành công, khách hàng sẽ nhận mã vé điện tử qua email hoặc tại tài khoản cá nhân trên website.

Vé đã thanh toán có thể được hỗ trợ đổi hoặc hoàn theo quy định hiện hành của rạp.

Yêu cầu đổi hoặc hoàn vé cần thực hiện trước giờ chiếu theo thời gian quy định.

Nếu gặp lỗi không nhận được mã vé hoặc phát sinh sự cố, khách hàng vui lòng liên hệ bộ phận hỗ trợ để được xử lý nhanh chóng.'
    ],

    'bao-mat' => [
        'title' => 'CHÍNH SÁCH BẢO MẬT',
        'content' => 'StarLight Cinema cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng.

Thông tin như họ tên, email, số điện thoại chỉ được sử dụng cho mục đích đặt vé, hỗ trợ khách hàng và gửi thông báo cần thiết.

Chúng tôi không chia sẻ, trao đổi hoặc cung cấp dữ liệu khách hàng cho bên thứ ba khi chưa có sự đồng ý.

Khách hàng có quyền kiểm tra, chỉnh sửa hoặc yêu cầu xoá thông tin cá nhân khỏi hệ thống bất cứ lúc nào.'
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