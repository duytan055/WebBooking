<link rel="stylesheet" href="admin.css">
<nav class="sidebar">
    <div class="logo">
        <h2>🎬 ADMIN</h2>
    </div>

    <ul>
        <li class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">
            <a href="admin.php">
                <i class="fas fa-home"></i>
                Tổng quan
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'phim.php' ? 'active' : '' ?>">
            <a href="phim.php">
                <i class="fas fa-film"></i>
                Quản lý Phim
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'khachhang.php' ? 'active' : '' ?>">
            <a href="khachhang.php">
                <i class="fas fa-user"></i>
                Khách hàng
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'nhanvien.php' ? 'active' : '' ?>">
            <a href="nhanvien.php">
                <i class="fas fa-users"></i>
                Nhân viên
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'baocao.php' ? 'active' : '' ?>">
            <a href="baocao.php">
                <i class="fas fa-chart-line"></i>
                Báo cáo
            </a>
        </li>

        <li>
            <a href="../LoginAndSign-up/logout.php" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                <i class="fas fa-right-from-bracket"></i>
                Đăng xuất
            </a>
        </li>
    </ul>
</nav>