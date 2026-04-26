<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard Concept</title>
    <link rel="stylesheet" href="admin.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
  </head>
  <body>
    <div class="container">
      <nav class="sidebar">
        <div class="logo">
          <h2>ADMIN</h2>
        </div>
        <ul>
          <li class="active">
            <a href="#"><i class="fas fa-home"></i> Tổng quan</a>
          </li>
          <li>
            <a href="listMovies.html"><i class="fas fa-box"></i> Sản phẩm</a>
          </li>
          <li>
            <a href="#"><i class="fas fa-user"></i> Người dùng</a>
          </li>
          <li>
            <a href="nhanvien.html"
              ><i class="fa-solid fa-people-group"></i> Nhân viên</a
            >
          </li>
          <li>
            <a href="#"><i class="fas fa-chart-line"></i> Báo cáo</a>
          </li>
          <li>
            <a href="#"><i class="fas fa-cog"></i> Cài đặt</a>
          </li>
        </ul>
      </nav>

      <main class="main-content">
        <header class="box_search_bar">
          <div class="search-bar">
            <input type="text" placeholder="Tìm kiếm..." />
          </div>
          <div class="user-info">
            <span>Xin chào, <strong>Admin</strong></span>
          </div>
        </header>

        <section class="dashboard-cards">
          <div class="card blue">
            <div class="info">
              <h3>542</h3>
              <p>Vé đã bán</p>
            </div>
            <i class="fas fa-shopping-bag"></i>
          </div>
          <div class="card green">
            <div class="info">
              <h3>53 650 000đ</h3>
              <p>Doanh thu</p>
            </div>
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="card orange">
            <div class="info">
              <h3>435</h3>
              <p>Khách hàng</p>
            </div>
            <i class="fas fa-user-plus"></i>
          </div>
        </section>

        <section class="data-section">
          <h3>Lịch sử giao dịch vé (Real-time)</h3>
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Mã Vé</th>
                  <th>Phim</th>
                  <th>Suất chiếu</th>
                  <th>Ghế</th>
                  <th>Mã NV Bán</th>
                  <th>Trạng thái</th>
                  <th>Tổng tiền</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>#TIC-8892</strong></td>
                  <td>Thỏ Ơi</td>
                  <td>19:30 | 07/02</td>
                  <td>H12, H13</td>
                  <td><span class="staff-id">NV-005</span></td>
                  <td><span class="status completed">Đã in vé</span></td>
                  <td>240,000đ</td>
                </tr>
                <tr>
                  <td><strong>#TIC-8893</strong></td>
                  <td>Báu Vật Trời Cho</td>
                  <td>20:00 | 07/02</td>
                  <td>A05</td>
                  <td><span class="staff-id">NV-012</span></td>
                  <td><span class="status pending">Chờ thanh toán</span></td>
                  <td>95,000đ</td>
                </tr>
                <tr>
                  <td><strong>#TIC-8894</strong></td>
                  <td>Nhà Ba Tôi Một Phòng</td>
                  <td>21:15 | 07/02</td>
                  <td>F01, F02</td>
                  <td><span class="staff-id">NV-005</span></td>
                  <td><span class="status completed">Đã in vé</span></td>
                  <td>180,000đ</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>
