<?php
include __DIR__ . '/../Connect/connecDB.php';

/* ===== DOANH THU ===== */

$sql_doanhthu = "SELECT SUM(tong_tien) AS doanhthu FROM datve";
$kq_doanhthu = mysqli_query($conn, $sql_doanhthu);
$row_doanhthu = mysqli_fetch_assoc($kq_doanhthu);

/* ===== TỔNG VÉ ===== */

$sql_ve = "SELECT COUNT(*) AS tongve FROM chitietve";
$kq_ve = mysqli_query($conn, $sql_ve);
$row_ve = mysqli_fetch_assoc($kq_ve);

/* ===== TỔNG PHIM ===== */

$sql_phim = "SELECT COUNT(*) AS tongphim FROM phim";
$kq_phim = mysqli_query($conn, $sql_phim);
$row_phim = mysqli_fetch_assoc($kq_phim);

/* ===== KHÁCH HÀNG ===== */

$sql_khach = "SELECT COUNT(*) AS tongkhach FROM nguoidung";
$kq_khach = mysqli_query($conn, $sql_khach);
$row_khach = mysqli_fetch_assoc($kq_khach);

/* ===== TOP PHIM ===== */

$sql_top = "SELECT p.ten_phim,
            COUNT(c.id_ve) AS soluong,
            SUM(d.tong_tien) AS tongtien
            FROM datve d
            JOIN chitietve c ON d.id_datve = c.id_datve
            JOIN suatchieu s ON d.id_suat = s.id_suat
            JOIN phim p ON s.id_phim = p.id_phim
            GROUP BY p.ten_phim
            ORDER BY tongtien DESC";

$kq_top = mysqli_query($conn, $sql_top);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Báo Cáo</title>

    <link rel="stylesheet" href="admin.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .chart-wrapper {
            position: relative;
            height: 400px;
            margin-top: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <div class="container">

        <?php include 'sidebar.php'; ?>

        <!-- MAIN -->

        <main class="main-content">

            <header class="box_search_bar">

                <h2>Báo Cáo Hệ Thống</h2>

                <div class="user-info">
                    ADMIN
                </div>

            </header>

            <!-- CARD -->

            <section class="dashboard-cards">

                <div class="card blue">

                    <div class="info">

                        <h3>
                            <?php
                            echo number_format($row_doanhthu['doanhthu']);
                            ?>đ
                        </h3>

                        <p>Tổng doanh thu</p>

                    </div>

                    <i class="fas fa-dollar-sign"></i>

                </div>

                <div class="card green">

                    <div class="info">

                        <h3>
                            <?php echo $row_ve['tongve']; ?>
                        </h3>

                        <p>Tổng vé bán</p>

                    </div>

                    <i class="fas fa-ticket"></i>

                </div>

                <div class="card orange">

                    <div class="info">

                        <h3>
                            <?php echo $row_khach['tongkhach']; ?>
                        </h3>

                        <p>Khách hàng</p>

                    </div>

                    <i class="fas fa-user"></i>

                </div>

                <div class="card red">

                    <div class="info">

                        <h3>
                            <?php echo $row_phim['tongphim']; ?>
                        </h3>

                        <p>Phim</p>

                    </div>

                    <i class="fas fa-film"></i>

                </div>

            </section>

            <!-- BIỂU ĐỒ -->

            <div class="charts-grid">

                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-chart-bar"></i> Top 5 Phim Doanh Thu Cao Nhất
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-chart-pie"></i> Phân Bố Số Vé Bán
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="ticketChart"></canvas>
                    </div>
                </div>

            </div>

            <script>
                // Dữ liệu từ PHP
                const movieData = [
                    <?php
                    $count = 0;
                    while ($row = mysqli_fetch_assoc($kq_top)) {
                        if ($count >= 5) break;
                        echo "{";
                        echo "name: '" . addslashes($row['ten_phim']) . "',";
                        echo "revenue: " . $row['tongtien'] . ",";
                        echo "tickets: " . $row['soluong'];
                        echo "},";
                        $count++;
                    }
                    ?>
                ];

                // Biểu đồ cột - Doanh thu
                const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: movieData.map(m => m.name),
                        datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: movieData.map(m => m.revenue),
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(255, 159, 64, 0.8)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + 'đ';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN') + 'đ';
                                    }
                                }
                            }
                        }
                    }
                });

                // Biểu đồ tròn - Số vé
                const ticketCtx = document.getElementById('ticketChart').getContext('2d');
                new Chart(ticketCtx, {
                    type: 'pie',
                    data: {
                        labels: movieData.map(m => m.name),
                        datasets: [{
                            label: 'Số vé bán',
                            data: movieData.map(m => m.tickets),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' vé ';
                                    }
                                }
                            }
                        }
                    }
                });
            </script>

        </main>

    </div>

</body>

</html>