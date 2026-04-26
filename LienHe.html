<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - Starlight</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<div class="contact-container">
    <div class="contact-content">
        
        <div class="info-section">
            <h2><span class="orange-dash">—</span> THÔNG TIN</h2>
            <div class="info-details">
                <p><strong>Hotline:</strong> 19001011</p>
                <p><strong>E-Mail:</strong> tranvoxuanvien3105@gmail.com</p>
            </div>
        </div>

        <div class="form-section">
            <h2><span class="orange-dash">—</span> GỬI LIÊN HỆ</h2>
            <form id="contact-form">
                <input type="text" name="name" placeholder="Họ tên" required>
                <input type="email" name="email" placeholder="your-email@gmail.com" required>
                <input type="tel" name="phone" placeholder="Số điện thoại" required>
                <textarea name="message" placeholder="Nội dung cần liên hệ..." rows="8" required></textarea>
                
                <div class="btn-container">
                    <button type="submit" id="submit-btn" class="send-btn">Send</button>
                </div>
                <p id="status-msg" style="margin-top: 15px; text-align: center; font-weight: bold;"></p>
            </form>
        </div>

    </div>
</div>

<script>
    // DÁN LINK GOOGLE SHEETS (CÁI LINK KẾT THÚC BẰNG /EXEC) VÀO GIỮA DẤU NHÁY DƯỚI ĐÂY
    const scriptURL = 'https://script.google.com/macros/s/AKfycbxLY0Q5rHsxIvs61uY0LxcGmIK0a4fE8nTLji1sGDs7lTnLpvaYFZ0_yxLsVy7Rw6Cz/exec';

    const form = document.getElementById("contact-form");
    const statusMsg = document.getElementById("status-msg");
    const submitBtn = document.getElementById("submit-btn");

    form.addEventListener("submit", e => {
        e.preventDefault(); // Ngăn trang web bị load lại khi bấm nút
        
        // Trạng thái khi đang gửi
        submitBtn.innerHTML = "Đang gửi...";
        submitBtn.disabled = true;
        statusMsg.innerHTML = "Đang xử lý, vui lòng đợi...";
        statusMsg.style.color = "#444";

        // Gửi dữ liệu đi
        fetch(scriptURL, { 
            method: 'POST', 
            body: new FormData(form)
        })
        .then(response => {
            // Thông báo khi thành công
            statusMsg.style.color = "#28a745";
            statusMsg.innerHTML = "✔ Đã gửi liên hệ thành công! Dữ liệu đã vào Google Sheets.";
            form.reset(); // Xóa sạch các ô nhập sau khi gửi xong
        })
        .catch(error => {
            // Thông báo khi có lỗi
            statusMsg.style.color = "#dc3545";
            statusMsg.innerHTML = "❌ Lỗi kết nối! Vui lòng kiểm tra lại đường dẫn.";
            console.error('Lỗi:', error.message);
        })
        .finally(() => {
            // Trả lại trạng thái nút bấm ban đầu
            submitBtn.innerHTML = "Send";
            submitBtn.disabled = false;
        });
    });
</script>

</body>
</html>