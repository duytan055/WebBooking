<style>
    body {
        margin: 0;
        background-color: rgba(232, 226, 226, 0.32);
    }

    .contact-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .contact-content {
        display: flex;
        gap: 50px;
        flex-wrap: wrap;
    }

    h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 25px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .orange-dash {
        color: #f37021;
        /* Màu cam Starlight */
        font-weight: bold;
        margin-right: 10px;
    }

    .info-section {
        flex: 1;
        min-width: 300px;
    }

    .info-details p {
        margin-bottom: 15px;
        line-height: 1.6;
        font-size: 15px;
        color: #444;
    }

    .form-section {
        flex: 1.5;
        min-width: 300px;
    }

    .form-section input,
    .form-section textarea {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }

    .form-section input:focus,
    .form-section textarea:focus {
        border-color: #f37021;
    }

    .btn-container {
        text-align: left;
    }

    .send-btn {
        background-color: #f37021;
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }

    .send-btn:hover {
        background-color: #d65a10;
    }

    @media (max-width: 768px) {
        .contact-content {
            flex-direction: column;
        }
    }
</style>
<?php include '../Modun/header.php'; ?>
<div class="contact-container">
    <div class="contact-content">

        <div class="info-section">
            <h2><span class="orange-dash">—</span> THÔNG TIN</h2>
            <div class="info-details">
                <p><strong>Hotline:</strong> 0779437588</p>
                <p><strong>E-Mail:</strong> tranvoxuanvien3105@gmail.com</p>
            </div>
        </div>

        <div class="form-section">
            <h2><span class="orange-dash">—</span> GỬI LIÊN HỆ</h2>
            <form id="contact-form">
                <input type="text" name="name" placeholder="Họ tên" required>
                <input type="email" name="email" 
                        placeholder="your-email@gmail.com"
                        pattern=".+@gmail\.com$"
                        title="Vui lòng sử dụng địa chỉ @gmail.com"
                        required>
                <input type="tel" name="phone"
                        placeholder="Số điện thoại"
                        pattern="[0-9]{10}"
                        title="Vui lòng nhập số điện thoại gồm 10 chữ số"
                        required>
                <textarea name="message" placeholder="Nội dung cần liên hệ..." rows="8" required></textarea>

                <div class="btn-container">
                    <button type="submit" id="submit-btn" class="send-btn">Send</button>
                </div>
                <p id="status-msg" style="margin-top: 15px; text-align: center; font-weight: bold;"></p>
            </form>
        </div>

    </div>
</div>
<?php include '../Modun/footer.php'; ?>
<script>
    const scriptURL = 'https://script.google.com/macros/s/AKfycbxLY0Q5rHsxIvs61uY0LxcGmIK0a4fE8nTLji1sGDs7lTnLpvaYFZ0_yxLsVy7Rw6Cz/exec';
    const form = document.getElementById("contact-form");
    const statusMsg = document.getElementById("status-msg");
    const submitBtn = document.getElementById("submit-btn");

    form.addEventListener("submit", e => {
        e.preventDefault();
        const email = form.email.value.trim();
        const phone = form.phone.value.trim();
        if (!email.endsWith("@gmail.com")) {
            statusMsg.style.color = "#dc3545";
            statusMsg.innerHTML = "❌ Email phải có định dạng @gmail.com";
            return;
        }
        if (phone.length !== 10 || isNaN(phone)) {
            statusMsg.style.color = "#dc3545";
            statusMsg.innerHTML = `❌ Số điện thoại phải có đúng 10 chữ số (Bạn đang nhập ${phone.length} số).`;
            return;
        }
        submitBtn.innerHTML = "Đang gửi...";
        submitBtn.disabled = true;
        statusMsg.innerHTML = "Đang xử lý, vui lòng đợi...";
        statusMsg.style.color = "#444";
        fetch(scriptURL, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => {
                statusMsg.style.color = "#28a745";
                statusMsg.innerHTML = "✔ Đã gửi liên hệ thành công! Dữ liệu đã vào Google Sheets.";
                form.reset();
            })
            .catch(error => {
                statusMsg.style.color = "#dc3545";
                statusMsg.innerHTML = "❌ Lỗi kết nối! Vui lòng kiểm tra lại đường dẫn.";
                console.error('Lỗi:', error.message);
            })
            .finally(() => {
                submitBtn.innerHTML = "Send";
                submitBtn.disabled = false;
            });
    });
</script>