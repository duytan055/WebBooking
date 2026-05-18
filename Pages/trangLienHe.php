<?php
session_start();
?>
<style>
    body {
        margin: 0;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: radial-gradient(circle at top, rgba(56, 189, 248, 0.14), transparent 22%),
            linear-gradient(180deg, #0f172a 0%, #0f172a 100%);
        color: #f8fafc;
    }

    .page-shell {
        width: min(1180px, 94%);
        margin: 0 auto 60px;
        padding: 110px 0 40px;
    }

    .contact-container {
        display: grid;
        gap: 32px;
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 28px;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(18px);
        padding: 32px;
    }

    .contact-content {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 32px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        font-size: 1.95rem;
        letter-spacing: 0.04em;
        color: #ffffff;
    }

    .section-title span {
        color: #38bdf8;
    }

    .info-section,
    .form-section {
        min-width: 0;
    }

    .info-details p {
        margin-bottom: 18px;
        line-height: 1.8;
        font-size: 1rem;
        color: #cbd5e1;
    }

    .info-details p strong {
        color: #f8fafc;
    }

    .form-section input,
    .form-section textarea {
        width: 100%;
        padding: 16px 18px;
        margin-bottom: 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.55);
        color: #f8fafc;
        font-size: 0.98rem;
        outline: none;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-section input::placeholder,
    .form-section textarea::placeholder {
        color: #94a3b8;
    }

    .form-section input:focus,
    .form-section textarea:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.12);
    }

    .btn-container {
        text-align: left;
    }

    .send-btn {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        color: #0f172a;
        border: none;
        padding: 14px 42px;
        border-radius: 999px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(56, 189, 248, 0.25);
    }

    @media (max-width: 900px) {
        .contact-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .page-shell {
            padding: 90px 0 30px;
        }

        .contact-container {
            padding: 24px;
        }

        .section-title {
            font-size: 1.6rem;
        }
    }
</style>
<?php include '../Module/header.php'; ?>
<main class="page-shell">
    <div class="contact-container">
        <div class="contact-content">

            <div class="info-section">
                <div class="section-title"><span>—</span> THÔNG TIN</div>
                <div class="info-details">
                    <p><strong>Hotline:</strong> 0779437588</p>
                    <p><strong>E-Mail:</strong> tranvoxuanvien3105@gmail.com</p>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title"><span>—</span> GỬI LIÊN HỆ</div>
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
</main>
<?php include '../Module/footer.php'; ?>
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
                statusMsg.innerHTML = "✔ Đã gửi liên hệ thành công!";
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