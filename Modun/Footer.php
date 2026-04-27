<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .box_footer {
            width: 100%;
            background-color: rgb(30, 29, 29);
            color: #fff;
            padding-top: 50px;
            margin-top: 60px;
        }

        .footer_container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 0 80px;
        }

        .footer-col {
            width: 230px;
            margin-bottom: 30px;
        }

        .text_footer {
            margin-left: 30px;
        }

        .footer-col h3 {
            margin-bottom: 15px;
            position: relative;
        }

        .footer-col h3::after {
            content: "";
            width: 40px;
            height: 2px;
            background: #e50914;
            position: absolute;
            left: 0;
            bottom: -5px;
        }

        .footer-col p,
        .footer-col a {
            color: #bbb;
            font-size: 14px;
            text-decoration: none;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col a:hover {
            color: #e50914;
            transition: 0.3s;
        }

        .social a {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 12px;
            background: #1a1a1a;
            border-radius: 5px;
            transition: 0.3s;
        }

        .social a:hover {
            background: #e50914;
        }

        .footer-bottom {
            text-align: center;
            border-top: 1px solid #222;
            padding: 15px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="box_footer">
        <div class="footer_container">
            <div class="footer-col">
                <h3>Thông tin</h3>
                <ul>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Phim</a></li>
                    <li><a href="#">Góc điện ảnh</a></li>
                    <li><a href="#">Khuyến mãi</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Hỗ trợ</h3>
                <ul>
                    <li><a href="#">Liên hệ</a></li>
                    <li><a href="#">Chính sách</a></li>
                    <li><a href="#">Điều khoản</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Liên hệ</h3>
                <p>Email: cinema@gmail.com</p>
                <p>Hotline: 1900 1234</p>
                <div class="social">
                    <a href="#">FB</a>
                    <a href="#">IG</a>
                    <a href="#">YT</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2026 CINEMA BOOKING | All Rights Reserved</p>
            </div>
        </div>
    </div>
</body>

</html>