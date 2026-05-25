<?php
session_start();

$policies = [

'dieu-khoan-chung' => [
'title' => 'ĐIỀU KHOẢN CHUNG',
'content' => 'StarLight là chủ bản quyền của trang website này. Việc chỉnh sửa trang, nội dung, và sắp xếp thuộc về thẩm quyền của StarLight.

Sự chỉnh sửa, thay đổi, phân phối hoặc tái sử dụng nội dung mà không được phép là vi phạm quyền lợi hợp pháp của StarLight.'
],

'tai-du-lieu' => [
'title' => 'VỀ VIỆC TẢI DỮ LIỆU',
'content' => 'Nếu bạn tải dữ liệu từ website, bạn chỉ có quyền sử dụng cá nhân.

Bạn không được phép bán lại, phân phối lại hoặc bẻ khóa phần mềm/dữ liệu thuộc StarLight.'
],

'thay-doi-noi-dung' => [
'title' => 'THAY ĐỔI NỘI DUNG',
'content' => 'StarLight có quyền thay đổi, chỉnh sửa hoặc xóa nội dung website bất kỳ lúc nào mà không cần báo trước.'
],

'lien-ket' => [
'title' => 'LIÊN KẾT VỚI TRANG KHÁC',
'content' => 'Website có thể chứa liên kết đến trang khác, nhưng StarLight không chịu trách nhiệm về nội dung của các trang đó.'
],

'noi-dung-nguoi-dung' => [
'title' => 'NỘI DUNG NGƯỜI DÙNG',
'content' => 'Người dùng không được đăng tải nội dung vi phạm pháp luật, xúc phạm, thô tục hoặc gây ảnh hưởng xấu đến hệ thống.'
],

'luat-ap-dung' => [
'title' => 'LUẬT ÁP DỤNG',
'content' => 'Mọi hoạt động phát sinh từ website được áp dụng theo luật pháp Việt Nam.

Việc sử dụng website đồng nghĩa bạn đã đồng ý với các điều khoản trên.'
]
,

'thanh-toan' => [
    'title' => 'CHÍNH SÁCH THANH TOÁN',
    'content' => 'Starlight Cinemas hỗ trợ thanh toán trực tuyến qua cổng ONEPAY nhằm mang lại sự thuận tiện và nhanh chóng cho khách hàng.

Sau khi thanh toán thành công, hệ thống sẽ gửi email xác nhận và mã vé. Nếu khách hàng không nhận được mã vé trong thời gian cam kết, vui lòng liên hệ hotline 1900 1722 để được hỗ trợ.

1. Phương thức thanh toán:
Khách hàng có thể thanh toán qua OnePay bằng:
- Thẻ ATM nội địa (đã đăng ký Internet Banking)
- Thẻ tín dụng / ghi nợ / trả trước quốc tế (Visa, MasterCard, JCB, UnionPay, Amex)

2. Điều kiện giao dịch thành công:
- Thẻ đã đăng ký thanh toán trực tuyến
- Nhập đúng thông tin và xác thực (OTP / SecureCode nếu có)
- Tài khoản đủ số dư hoặc hạn mức

3. Trường hợp thanh toán thất bại:
- Chưa đăng ký Internet Banking
- Không hoàn tất xác thực bảo mật
- Thẻ không đủ số dư / vượt hạn mức
- Sai thông tin thẻ

4. Bảo mật thông tin:
Hệ thống sử dụng dịch vụ trung gian OnePAY đạt chuẩn bảo mật tại Việt Nam.
Không cung cấp thông tin thẻ, mật khẩu cho bên thứ ba.
Khách hàng tự chịu trách nhiệm nếu để lộ thông tin thanh toán.

5. Quy định sử dụng hệ thống:
- Không xâm nhập hoặc can thiệp hệ thống website
- Không thay đổi dữ liệu hoặc gây ảnh hưởng đến hệ thống
- Vi phạm sẽ chịu trách nhiệm theo pháp luật Việt Nam.'
],

'giao-nhan' => [
    'title' => 'CHÍNH SÁCH GIAO NHẬN VÉ',
    'content' => 'Khi khách hàng đặt vé tại Starlight Cinemas, hệ thống sẽ gửi mã vé qua email, Zalo hoặc hiển thị trong tài khoản đã đăng ký trong vòng 10 phút kể từ khi đặt vé thành công.

Khách hàng vui lòng kiểm tra email, Zalo hoặc tài khoản đăng nhập trong thời gian này. Nếu quá 10 phút vẫn chưa nhận được mã vé, vui lòng liên hệ email Cskh@starlight.vn hoặc hotline 1900 1722 để được hỗ trợ.

Starlight Cinemas có trách nhiệm tiếp nhận và xác minh thông tin khiếu nại. Trong vòng 24 giờ làm việc, hệ thống sẽ phản hồi và xử lý cho khách hàng.

Mọi thắc mắc, góp ý hoặc khiếu nại liên quan đến dịch vụ, khách hàng vui lòng liên hệ hotline 1900 1722 hoặc email Cskh@starlight.vn để được hỗ trợ kịp thời.'
],

'bao-mat' => [
    'title' => 'CHÍNH SÁCH BẢO MẬT',
    'content' => 'a. Mục đích và phạm vi thu thập:

Starlight Cinemas thu thập các thông tin cá nhân như: họ tên, ngày sinh, số điện thoại, email, giới tính, địa chỉ, thông tin thanh toán, hình ảnh, thiết bị truy cập, dữ liệu giao dịch, lịch sử sử dụng dịch vụ và thông tin tương tác trên hệ thống.

Dữ liệu này được dùng để xác thực người dùng, hỗ trợ đặt vé, chăm sóc khách hàng và đảm bảo quyền lợi khi giao dịch.

Trong quá trình thanh toán, hệ thống chỉ lưu thông tin đơn hàng, không lưu thông tin thẻ ngân hàng.

b. Phạm vi sử dụng thông tin:

Thông tin được sử dụng để:
- Cung cấp dịch vụ và xử lý giao dịch đặt vé
- Gửi thông báo, khuyến mãi, chăm sóc khách hàng
- Hỗ trợ người dùng và giải quyết khiếu nại
- Phân tích, thống kê và cải thiện dịch vụ
- Tuân thủ yêu cầu pháp luật khi cần thiết

c. Thời gian lưu trữ:

Dữ liệu cá nhân được lưu trữ cho đến khi có yêu cầu hủy bỏ, và luôn được bảo mật theo quy định.

d. Đối tượng có thể tiếp cận thông tin:

- Công ty Cổ phần Entertainment 2020
- Đối tác cung cấp dịch vụ liên quan
- Cơ quan nhà nước khi có yêu cầu hợp pháp
- Các bên liên quan trong trường hợp tranh chấp

e. Đơn vị quản lý thông tin:

CÔNG TY CỔ PHẦN ENTERTAINMENT 2020  
Địa chỉ: Tầng 4, Tòa nhà Siêu thị chợ Đà Nẵng, 46 Điện Biên Phủ, Đà Nẵng  
Email: Cskh@starlight.vn  
Hotline: 1900 1722

f. Quyền của khách hàng:

Khách hàng có quyền kiểm tra, chỉnh sửa hoặc yêu cầu xóa dữ liệu cá nhân. Mọi phản hồi sẽ được xác minh và xử lý theo quy định.

g. Cam kết bảo mật:

Starlight Cinemas cam kết bảo mật tuyệt đối thông tin cá nhân. Không chia sẻ cho bên thứ ba nếu không có sự đồng ý, trừ trường hợp pháp luật yêu cầu.

Trong trường hợp bị tấn công hoặc rò rỉ dữ liệu, công ty sẽ thông báo và phối hợp cơ quan chức năng xử lý.

h. Khiếu nại:

Khách hàng có thể gửi khiếu nại qua hotline 1900 1722 hoặc email Cskh@starlight.vn. Thời gian xử lý tối đa 15 ngày làm việc.'
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
html{
    scroll-behavior:smooth;
}

body{
    margin:0;
    min-height:100vh;
    font-family:'Segoe UI',sans-serif;
    background: linear-gradient(180deg,#0f172a,#0f172a);
    color:#fff;
}

.page-shell{
    width:min(1100px,92%);
    margin:auto;
    padding:40px 0;
}

.policy-container{
    background:rgba(15,23,42,.75);
    border:1px solid rgba(255,255,255,.12);
    border-radius:20px;
    padding:30px;
    box-shadow:0 20px 50px rgba(0,0,0,.3);
    animation:fade .25s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateY(10px);}
    to{opacity:1; transform:translateY(0);}
}

.policy-container {
    background: #ffffff; /* Đổi thành màu trắng nguyên bản */
    border: 1px solid #e2e8f0; /* Đổi viền sang màu xám nhạt cho hợp với nền trắng */
    border-radius: 20px;
    padding: 40px; /* Tăng padding một chút cho thoáng */
    box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Đổ bóng nhẹ hơn */
    animation: fade .25s ease;
}

.policy-title {
    font-size: 30px;
    margin-bottom: 20px;
    color: #1e293b; 
    font-weight: bold;
    border-bottom: 2px solid #e50914; 
    padding-bottom: 10px;
    display: inline-block;
}

.policy-content {
    color: #334155; 
    line-height: 1.8;
    font-size: 16px;
    white-space: pre-line;
}

#top{
    position:absolute;
    top:0;
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

window.onload = function () {
    window.scrollTo(0, 0);
};
</script>
