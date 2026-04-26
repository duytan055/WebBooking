<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Pages/style.css">
    <link rel="stylesheet" href="main.js">
</head>

<body>
    <div class="box3">
        <div class="box3_nav"></div>
        <div style="font-size: 20px"><strong>Phim</strong></div>
        <div class="box3_menu">
            <ul class="menu_btn">
                <li class="nav_menu_items active" style="font-size: 20px">
                    Sắp chiếu
                </li>
            </ul>
        </div>
    </div>
    <div class="box_movies">
        <ul class="Movies_list" id="soonmovies_list"></ul>
        <div
            id="videoModal"
            style="
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.9);
          z-index: 9999;
          align-items: center;
          justify-content: center;
        ">
            <div style="position: relative; width: 80%; max-width: 800px">
                <span
                    id="closeBtn"
                    style="
              position: absolute;
              top: -40px;
              right: 0;
              color: white;
              font-size: 30px;
              cursor: pointer;
            ">&times; Đóng</span>
                <div style="padding-bottom: 56.25%; position: relative; height: 0">
                    <iframe
                        id="modalIframe"
                        style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
              "
                        frameborder="0"
                        allowfullscreen
                        allow="autoplay"></iframe>
                </div>
            </div>
        </div>
    </div>
</body>

</html>