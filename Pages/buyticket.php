<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: ../LoginAndSign-up/login.php');
    exit();
}
?>
<?php
include __DIR__ . '/../Connect/connecDB.php';

// --- XỬ LÝ AJAX GIỮ GHẾ ---
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];
    $sId = (int)$_POST['suat_id'];
    $gId = isset($_POST['ghe_id']) ? (int)$_POST['ghe_id'] : 0;
    $uId = (int)$_SESSION['user']['id'];

    // --- HÀNH ĐỘNG: GIỮ GHẾ TẠM THỜI (4 PHÚT) ---
    if ($action === 'hold') {
        if ($action === 'hold') {

            $stmt = $conn->prepare("
        SELECT 1 FROM chitietve 
        WHERE id_suat = ? AND id_ghe = ?
        LIMIT 1
    ");
            $stmt->bind_param('ii', $sId, $gId);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Ghế đã bán']);
                exit;
            }

            $stmt = $conn->prepare("
        INSERT INTO ghe_tam_giu (id_suat, id_ghe, id_user, expires_at)
        VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 4 MINUTE))
        ON DUPLICATE KEY UPDATE
            id_user = VALUES(id_user),
            expires_at = VALUES(expires_at)
    ");

            $stmt->bind_param('iii', $sId, $gId, $uId);
            $stmt->execute();

            echo json_encode(['status' => 'success']);
            exit;
        }
    }

    // --- HÀNH ĐỘNG: HUỶ GIỮ GHẾ (KHI BỎ CHỌN) ---
    if ($action === 'release') {
        $stmt = $conn->prepare("DELETE FROM ghe_tam_giu WHERE id_suat = ? AND id_ghe = ? AND id_user = ?");
        $stmt->bind_param('iii', $sId, $gId, $uId);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    // --- HÀNH ĐỘNG: LÀM MỚI THỜI GIAN GIỮ (KHI CHUYỂN TRANG/BƯỚC) ---
    if ($action === 'refresh_hold') {
        // Reset lại 4 phút cho tất cả ghế mà user này đang giữ trong suất chiếu này
        $stmt = $conn->prepare("UPDATE ghe_tam_giu SET expires_at = DATE_ADD(NOW(), INTERVAL 4 MINUTE) 
                               WHERE id_suat = ? AND id_user = ?");
        $stmt->bind_param('ii', $sId, $uId);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    // --- LOGIC XỬ LÝ THANH TOÁN (CHECKOUT) ---
    if ($action === 'hold') {

        $stmt = $conn->prepare("
        SELECT 1 FROM chitietve 
        WHERE id_suat = ? AND id_ghe = ?
        LIMIT 1
    ");
        $stmt->bind_param('ii', $sId, $gId);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Ghế đã bán']);
            exit;
        }

        $stmt = $conn->prepare("
        INSERT INTO ghe_tam_giu (id_suat, id_ghe, id_user, expires_at)
        VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 4 MINUTE))
        ON DUPLICATE KEY UPDATE
            id_user = VALUES(id_user),
            expires_at = VALUES(expires_at)
    ");

        $stmt->bind_param('iii', $sId, $gId, $uId);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
        exit;
    }
}
?>
<?php
$movieName = '';
$duration = '';
$showtimes = [];
$selectedShowtime = isset($_GET['showtime']) ? (int)$_GET['showtime'] : 0;
$movieId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($movieId > 0) {
    $sqlMovie = "SELECT ten_phim, thoi_luong FROM phim WHERE id_phim = ?";
    $stmtMovie = $conn->prepare($sqlMovie);
    if ($stmtMovie) {
        $stmtMovie->bind_param('i', $movieId);
        $stmtMovie->execute();
        $res = $stmtMovie->get_result();
        if ($res && $res->num_rows) {
            $m = $res->fetch_assoc();
            $movieName = $m['ten_phim'];
            $duration = $m['thoi_luong'];
        }
        $stmtMovie->close();
    }

    // Chỉ lấy suất chiếu của ngày hôm nay
    $today = date('Y-m-d');

    $showtimeQuery = "SELECT id_suat, id_phong, date_chieu, thoi_gian FROM suatchieu WHERE id_phim = ? AND date_chieu = ? ORDER BY thoi_gian";
    $stmtShow = $conn->prepare($showtimeQuery);
    if ($stmtShow) {
        $stmtShow->bind_param('is', $movieId, $today);
        $stmtShow->execute();
        $res2 = $stmtShow->get_result();
        if ($res2) {
            while ($r = $res2->fetch_assoc()) {
                $showtimes[] = $r;
            }
        }
        $stmtShow->close();
    }

    if ($selectedShowtime === 0 && count($showtimes) > 0) {
        $selectedShowtime = $showtimes[0]['id_suat'];
    }
}

// --- LOGIC LẤY TẤT CẢ GHẾ CỦA PHÒNG ---
$roomSeats = [];
if ($selectedShowtime > 0) {
    // Tìm id_phong từ id_suat để biết suất chiếu này ở phòng nào
    $sqlGetRoom = "SELECT id_phong FROM suatchieu WHERE id_suat = ?";
    $stmtGetRoom = $conn->prepare($sqlGetRoom);
    if ($stmtGetRoom) {
        $stmtGetRoom->bind_param('i', $selectedShowtime);
        $stmtGetRoom->execute();
        $resRoom = $stmtGetRoom->get_result();
        if ($rowR = $resRoom->fetch_assoc()) {
            $id_phong = $rowR['id_phong'];
            // Lấy danh sách toàn bộ ghế của phòng đó
            $sqlSeats = "SELECT id_ghe, ma_ghe, loai_ghe FROM ghe WHERE id_phong = ? ORDER BY ma_ghe";
            $stmtSeats = $conn->prepare($sqlSeats);
            if ($stmtSeats) {
                $stmtSeats->bind_param('i', $id_phong);
                $stmtSeats->execute();
                $resS = $stmtSeats->get_result();
                while ($s = $resS->fetch_assoc()) {
                    $roomSeats[] = $s;
                }
                $stmtSeats->close();
            }
        }
        $stmtGetRoom->close();
    }
}

// --- LOGIC KIỂM TRA GHẾ ĐÃ ĐẶT ---
$bookedSeats = [];
if ($selectedShowtime > 0) {
    // Dọn dẹp các bản ghi giữ ghế đã hết hạn
    $conn->query("DELETE FROM ghe_tam_giu WHERE expires_at < NOW()");

    $sqlBooked = "SELECT id_ghe FROM chitietve WHERE id_suat = ?";
    $stmtBooked = $conn->prepare($sqlBooked);
    if ($stmtBooked) {
        $stmtBooked->bind_param('i', $selectedShowtime);
        $stmtBooked->execute();
        $resBooked = $stmtBooked->get_result();
        while ($rowB = $resBooked->fetch_assoc()) {
            $bookedSeats[] = (int)$rowB['id_ghe'];
        }
        $stmtBooked->close();
    }
}

// --- LOGIC LẤY GHẾ ĐANG ĐƯỢC GIỮ ---
$otherHoldingSeats = [];
$myHoldingSeats = [];
if ($selectedShowtime > 0) {
    $uId = (int)$_SESSION['user']['id'];
    $sqlHold = "SELECT id_ghe, id_user FROM ghe_tam_giu WHERE id_suat = ? AND expires_at > NOW()";
    $stmtHold = $conn->prepare($sqlHold);
    if ($stmtHold) {
        $stmtHold->bind_param('i', $selectedShowtime);
        $stmtHold->execute();
        $resHold = $stmtHold->get_result();
        while ($rowH = $resHold->fetch_assoc()) {
            if ((int)$rowH['id_user'] === $uId) {
                $myHoldingSeats[] = (int)$rowH['id_ghe'];
            } else {
                $otherHoldingSeats[] = (int)$rowH['id_ghe'];
            }
        }
        $stmtHold->close();
    }
}
?>
<style>
    body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background:
            radial-gradient(circle at top left,
                rgba(56, 189, 248, 0.22),
                transparent 28%),
            linear-gradient(180deg, #07101e 0%, #0f172a 100%);
        color: #f8fafc;
    }

    .ticket-shell {
        width: min(1180px, 96%);
        margin: 0 auto 60px;
        padding: 120px 0 40px;
    }

    .topBar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 18px 26px;
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(16px);
        border-radius: 18px;
        margin-bottom: 24px;
    }

    .topBar button {
        border: none;
        border-radius: 999px;
        padding: 12px 20px;
        background: rgba(56, 189, 248, 0.95);
        color: #0f172a;
        font-weight: 700;
        cursor: pointer;
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .topBar button:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(56, 189, 248, 0.22);
    }

    .topBar__title {
        display: flex;
        align-items: baseline;
        gap: 10px;
        font-size: 0.95rem;
        color: #cbd5e1;
    }

    .topBar__title strong {
        color: #ffffff;
        font-size: 1.15rem;
    }

    .page-section {
        display: none;
        width: 100%;
        gap: 24px;
    }

    .page-section.active {
        display: grid;
    }

    #seatPage,
    #comboPage {
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
    }

    #paymentPage {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .seat-panel {
        display: flex;
        flex-direction: column;
        gap: 18px;
        background: rgba(15, 23, 42, 0.64);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 28px;
        padding: 24px;
    }

    .seat-panel__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }

    .seat-panel__header h2 {
        margin: 0;
        font-size: 1.35rem;
        letter-spacing: 0.04em;
    }

    /* hide any explicit "MÀN HÌNH" heading in the seat page variants */
    #seatPage>h2,
    .seat-panel__header h2 {
        display: none;
    }

    /* Curved screen (arc) centered */
    .screen {
        width: 78%;
        max-width: 920px;
        height: 78px;
        margin: 8px auto 0;
        border-radius: 0 0 50% 50% / 0 0 100% 100%;
        background: linear-gradient(180deg,
                rgba(255, 255, 255, 0.22),
                rgba(255, 255, 255, 0.04));
        position: relative;
        box-shadow:
            0 22px 68px rgba(2, 6, 23, 0.75),
            inset 0 -18px 48px rgba(255, 255, 255, 0.04);
    }

    .screen::before {
        content: "";
        position: absolute;
        left: 50%;
        top: -36px;
        transform: translateX(-50%);
        width: 90%;
        height: 72px;
        border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        background: radial-gradient(ellipse at center,
                rgba(255, 255, 255, 0.5) 0%,
                rgba(255, 255, 255, 0.06) 40%,
                rgba(255, 255, 255, 0) 70%);
        filter: blur(12px);
        opacity: 0.9;
        pointer-events: none;
    }

    .screen::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 12px;
        transform: translateX(-50%);
        width: 40%;
        height: 18px;
        background: linear-gradient(180deg,
                rgba(255, 255, 255, 0.6),
                rgba(255, 255, 255, 0.02));
        border-radius: 50%;
        opacity: 0.6;
        pointer-events: none;
    }

    .seat-legend {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 12px;
        color: #cbd5e1;
        font-size: 0.95rem;
    }

    .seat-legend .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .legend-swatch {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        display: inline-block;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .legend-swatch.normal {
        background: rgba(56, 189, 248, 0.25);
    }

    .legend-swatch.vip {
        background: rgba(236, 72, 153, 0.45);
    }

    .legend-swatch.couple {
        background: rgba(168, 85, 247, 0.55);
    }

    /* Ghế đã thanh toán: Màu xám đậm */
    .seat.booked {
        background: #1e293b !important;
        color: #64748b !important;
        cursor: not-allowed !important;
        pointer-events: none;
        box-shadow: none !important;
        transform: none !important;
        opacity: 0.6;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    /* Ghế đang được người khác giữ: Màu cam đậm */
    .seat.holding {
        background: #92400e !important;
        color: #fcd34d !important;
        cursor: not-allowed !important;
        pointer-events: none;
        box-shadow: none !important;
        transform: none !important;
        opacity: 0.8;
        border: 1px solid rgba(251, 191, 36, 0.2) !important;
    }

    .legend-swatch.holding {
        background: #92400e;
    }

    .legend-swatch.booked {
        background: #1e293b;
    }

    .seat-map {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }


    .row {
        display: grid;
        grid-template-columns: repeat(10, minmax(40px, 1fr));
        gap: 14px;
        width: 100%;
        justify-items: center;
        align-items: center;
    }

    .seat {
        width: 100%;
        /* allow seat to fill its grid column */
        max-width: none;
        aspect-ratio: 1 / 1;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: rgba(56, 189, 248, 0.25);
        color: #ffffff;
        font-size: 0.82rem;
        cursor: pointer;
        transition:
            transform 0.25s ease,
            background 0.25s ease,
            box-shadow 0.25s ease;
    }

    .seat:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(56, 189, 248, 0.14);
    }

    .seat.vip {
        background: rgba(236, 72, 153, 0.45);
        color: #fee2e2;
    }

    .seat.couple {
        background: rgba(168, 85, 247, 0.55);
        color: #ede9fe;
        box-shadow: 0 6px 16px rgba(168, 85, 247, 0.12);
        border: 1px solid rgba(168, 85, 247, 0.2);
    }

    .seat.vip {
        box-shadow: 0 6px 16px rgba(236, 72, 153, 0.08);
        border: 1px solid rgba(236, 72, 153, 0.12);
    }

    .seat.selected {
        transform: scale(1.15) !important;
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.5) !important;
        border: 2px solid #ffffff !important;
        z-index: 10;
    }

    .seat.selected.normal {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        color: #0f172a;
    }

    .seat.selected.vip {
        background: linear-gradient(135deg, #f472b6, #fb7185);
        color: #0f172a;
    }

    .seat.selected.couple {
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        color: #0f172a;
    }

    .summary-card,
    .bottom-card,
    .paymentBox {
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 28px;
        backdrop-filter: blur(16px);
        padding: 26px;
    }

    .summary-card {
        display: grid;
        gap: 18px;
    }

    .summary-card__item {
        padding: 18px 20px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 20px;
    }

    .summary-card__item h3 {
        margin: 0 0 8px;
        font-size: 0.95rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .summary-card__item p {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #ffffff;
    }

    .summary-card__total {
        background: linear-gradient(135deg,
                rgba(56, 189, 248, 0.16),
                rgba(14, 165, 233, 0.22));
    }

    .primary-btn {
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 14px 20px;
        color: #0f172a;
        font-weight: 700;
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        cursor: pointer;
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(56, 189, 248, 0.22);
    }

    .page-header {
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 28px;
        padding: 28px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 1.95rem;
    }

    .page-header p {
        margin: 0;
        color: #cbd5e1;
        line-height: 1.75;
        max-width: 720px;
    }

    .combo-column,
    .payment-column {
        display: grid;
        gap: 24px;
    }

    .summary-aside {
        display: grid;
        gap: 24px;
        align-content: start;
    }

    .summary-aside .summary-card,
    .summary-aside .paymentBox {
        margin: 0;
    }

    .comboContainer {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 22px;
    }

    .combo-card {
        display: grid;
        grid-template-rows: 200px auto;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 26px;
        overflow: hidden;
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .combo-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.28);
    }

    .combo-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .combo-card__body {
        padding: 20px;
        display: grid;
        gap: 12px;
    }

    .combo-card__body h3 {
        margin: 0;
        font-size: 1.1rem;
    }

    .combo-card__body p {
        margin: 0;
        color: #cbd5e1;
    }

    .combo-card__body strong {
        font-size: 1.15rem;
        color: #ffffff;
    }

    .combo-card__body button {
        border: none;
        border-radius: 999px;
        padding: 12px 18px;
        background: rgba(56, 189, 248, 0.92);
        color: #0f172a;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.25s ease;
    }

    .combo-card__body button:hover {
        transform: translateY(-2px);
    }

    .bottom-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        gap: 24px;
    }

    .bottom-card h3 {
        margin: 0;
        color: #cbd5e1;
    }

    .bottom-card p {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .paymentBox {
        width: 100%;
        max-width: 100%;
        margin: 0;
        display: grid;
        gap: 26px;
        box-sizing: border-box;
    }

    .payment-column,
    .summary-aside {
        min-width: 0;
        width: 100%;
    }

    .paymentBox__details {
        display: grid;
        grid-template-columns: repeat(2, minmax(240px, 1fr));
        gap: 20px;
        align-items: start;
    }

    .paymentBox__details .info-block {
        min-height: auto;
        display: grid;
        gap: 14px;
        padding: 18px;
    }

    .paymentBox__details .info-block p:last-of-type {
        margin-top: 0;
    }

    .paymentBox__heading h2 {
        margin: 0;
        font-size: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 22px;
        padding: 22px;
        color: #cbd5e1;
    }

    .info-grid div {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-grid strong {
        font-size: 1.05rem;
        color: #ffffff;
    }

    .info-block {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 22px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .info-block h3 {
        margin: 0 0 14px;
        font-size: 1.1rem;
    }

    .info-block p {
        margin: 0 0 10px;
        color: #cbd5e1;
        line-height: 1.8;
    }

    .pricing {
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1rem;
        color: #cbd5e1;
    }

    .pricing strong {
        color: #ffffff;
    }

    .divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 999px;
    }

    .bankLogos {
        display: flex;
        gap: 18px;
        align-items: center;
        flex-wrap: wrap;
    }

    .bankLogos img,
    .method img {
        max-width: 100%;
        height: 40px;
        object-fit: contain;
    }

    .paymentMethods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
    }

    .method {
        padding: 18px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .method p {
        margin: 12px 0 0;
        color: #cbd5e1;
        font-size: 0.95rem;
    }

    .discount {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: center;
    }

    .discount input {
        width: 100%;
        padding: 16px 18px;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(15, 23, 42, 0.6);
        color: #f8fafc;
        outline: none;
    }

    .discount button {
        border: none;
        border-radius: 999px;
        padding: 16px 24px;
        background: rgba(56, 189, 248, 0.95);
        color: #0f172a;
        font-weight: 700;
        cursor: pointer;
    }

    .discount-note {
        margin: 0;
        color: #94a3b8;
    }

    .total-line {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 1.1rem;
    }

    .payBtn {
        width: 100%;
    }

    .seat-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .seatBox {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(56, 189, 248, 0.15);
        color: #f8fafc;
        font-weight: 600;
    }

    @media (max-width: 980px) {

        #seatPage,
        #comboPage,
        #paymentPage {
            grid-template-columns: 1fr;
        }

        .paymentBox__details {
            grid-template-columns: 1fr;
        }

        .paymentBox__heading {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 720px) {
        .ticket-shell {
            padding: 100px 14px 30px;
        }

        .topBar {
            flex-direction: column;
            align-items: stretch;
        }

        .page-header,
        .seat-panel,
        .summary-card,
        .paymentBox,
        .combo-card,
        .bottom-card,
        .info-block {
            padding: 20px;
        }

        .discount {
            grid-template-columns: 1fr;
        }
    }
</style>

<body>
    <?php include __DIR__ . '/../Module/header.php'; ?>

    <main class="ticket-shell">
        <section class="topBar">
            <button type="button" onclick="history.back()">← Quay lại</button>
            <div class="topBar__title">
                <span>Giữ ghế trong</span>
                <strong id="timer">05:00</strong>
            </div>
        </section>

        <section id="seatPage" class="page-section active">
            <div class="seat-panel">
                <div class="seat-panel__header">
                    <div class="screen"></div>
                </div>

                <div id="seatMap" class="seat-map"></div>

                <div id="seatLegend" class="seat-legend">
                    <div class="legend-item"><span class="legend-swatch normal" data-type="normal"></span> Ghế thường</div>
                    <div class="legend-item"><span class="legend-swatch vip" data-type="vip"></span> Ghế VIP</div>
                    <div class="legend-item"><span class="legend-swatch couple" data-type="couple"></span> Ghế đôi</div>
                    <div class="legend-item"><span class="legend-swatch holding"></span> Đang giữ</div>
                    <div class="legend-item"><span class="legend-swatch booked"></span> Ghế đã bán</div>
                </div>
            </div>

            <aside class="summary-card">
                <div class="summary-card__item">
                    <h3>Phim</h3>
                    <p id="summaryMovieName"><?= htmlspecialchars($movieName ?: '---') ?></p>
                </div>
                <div class="summary-card__item">
                    <h3>Thời lượng</h3>
                    <p id="summaryDuration"><?= htmlspecialchars($duration ? $duration . ' phút' : '---') ?></p>
                </div>
                <div class="summary-card__item">
                    <h3>Suất chiếu</h3>
                    <p>
                        <select id="showtimeSelect">
                            <?php if (count($showtimes) > 0): ?>
                                <?php foreach ($showtimes as $s): ?>
                                    <?php
                                    $optText = $s['thoi_gian'] . ' - Phòng ' . $s['id_phong'];
                                    $selected = ($selectedShowtime > 0 && $s['id_suat'] == $selectedShowtime) ? ' selected' : '';
                                    ?>
                                    <option value="<?= htmlspecialchars($s['id_suat']) ?>" data-phong="<?= htmlspecialchars($s['id_phong']) ?>" <?= $selected ?>><?= htmlspecialchars($optText) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Không có suất chiếu hôm nay</option>
                            <?php endif; ?>
                        </select>
                    </p>
                </div>
                <div class="summary-card__item">
                    <h3>Ghế đã chọn</h3>
                    <p id="selectedSeats">...</p>
                </div>
                <div class="summary-card__item summary-card__total">
                    <h3>Tổng tiền</h3>
                    <p id="seatTotal">0 đ</p>
                </div>
                <button class="primary-btn" type="button" onclick="goCombo()">TIẾP THEO</button>
            </aside>
        </section>

        <section id="comboPage" class="page-section">
            <div class="combo-column">
                <div class="page-header">
                    <h2>CHỌN COMBO</h2>
                    <p>Thêm đồ ăn, thức uống để trải nghiệm rạp phim trọn vẹn hơn.</p>
                </div>

                <div class="comboContainer">
                    <article class="combo-card">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/7a/Popcorn_and_soda.jpg" alt="Star Classic">
                        <div class="combo-card__body">
                            <h3>CLASSIC COMBO</h3>
                            <p>1 Bắp + 1 Nước</p>
                            <strong>80.000 đ</strong>
                            <button type="button" onclick="addCombo('CLASSIC COMBO',80000)">Thêm</button>
                        </div>
                    </article>

                    <article class="combo-card">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/66/Popcorn_and_Coke.jpg" alt="Star Premium">
                        <div class="combo-card__body">
                            <h3>PREMIUM COMBO</h3>
                            <p>2 Nước + 1 Bắp</p>
                            <strong>105.000 đ</strong>
                            <button type="button" onclick="addCombo('PREMIUM COMBO',105000)">Thêm</button>
                        </div>
                    </article>

                    <article class="combo-card">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5a/Popcorn_and_soft_drink.jpg" alt="Couple Combo">
                        <div class="combo-card__body">
                            <h3>COUPLE COMBO</h3>
                            <p>2 Bắp + 2 Nước</p>
                            <strong>150.000 đ</strong>
                            <button type="button" onclick="addCombo('COUPLE COMBO',150000)">Thêm</button>
                        </div>
                    </article>
                </div>
            </div>

            <aside class="summary-aside">
                <div class="summary-card">
                    <div class="summary-card__item">
                        <h3>Combo đã chọn</h3>
                        <p id="comboInfo">Không có</p>
                    </div>
                    <div class="summary-card__item summary-card__total">
                        <h3>Tổng tiền combo</h3>
                        <p id="comboTotal">0 đ</p>
                    </div>
                    <button class="primary-btn" type="button" onclick="goPayment()">TIẾP THEO</button>
                </div>
            </aside>
        </section>

        <section id="paymentPage" class="page-section">
            <div class="payment-column">
                <div class="paymentBox">
                    <div class="paymentBox__heading">
                        <h2>THÔNG TIN ĐẶT VÉ</h2>
                    </div>

                    <div class="info-grid">
                        <div><span>Phòng:</span> <strong id="infoRoom"></strong></div>
                        <div><span>Tên phim:</span> <strong id="infoMovie"><?= htmlspecialchars($movieName ?: '') ?></strong></div>
                        <div><span>Suất chiếu:</span> <strong id="infoShowtime"></strong></div>
                    </div>

                    <div class="paymentBox__details">
                        <div class="info-block">
                            <h3>Ghế đã chọn</h3>
                            <p>Số ghế: <span id="seatCount">0</span></p>
                            <div id="seatList" class="seat-list"></div>
                            <p class="pricing"><span>Tổng tiền ghế:</span> <strong id="seatMoney">0 đ</strong></p>
                        </div>

                        <div class="info-block">
                            <h3>Combo</h3>
                            <p id="comboInfo">Không có</p>
                            <p class="pricing"><span>Tổng tiền combo:</span> <strong id="comboMoney">0 đ</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="summary-aside">
                <div class="paymentBox">
                    <div class="info-block">
                        <h3>Ngân hàng hỗ trợ</h3>
                        <div class="bankLogos">
                            <img src="../Picture_Bank/Vietcombank.webp" alt="Vietcombank">
                            <img src="../Picture_Bank/MBBank.webp" alt="MBB">
                            <img src="../Picture_Bank/Techcombank.webp" alt="Techcombank">
                        </div>
                    </div>

                    <div class="info-block">
                        <h3>PHƯƠNG THỨC THANH TOÁN</h3>
                        <div class="paymentMethods">
                            <article class="method">
                                <img src="../Picture_Bank/VNPAY.webp" alt="VNPAY">
                                <p>VNPAY</p>
                            </article>
                            <article class="method">
                                <img src="../Picture_Bank/MoMo.webp" alt="MOMO">
                                <p>MOMO</p>
                            </article>
                            <article class="method">
                                <img src="../Picture_Bank/QR_Banking.webp" alt="QR Banking">
                                <p>QR Banking</p>
                            </article>
                        </div>
                    </div>

                    <div class="discount">
                        <input type="text" id="discountInput" placeholder="Nhập mã giảm giá">
                        <button type="button" onclick="applyDiscount()">Áp dụng</button>
                    </div>
                    <p class="pricing total-line">Giảm giá: <strong id="discountMoney">0 đ</strong></p>
                    <p class="pricing total-line"><span>Tổng thanh toán:</span> <strong id="finalTotal">0 đ</strong></p>

                    <button class="primary-btn payBtn" type="button">THANH TOÁN</button>
                </div>
            </aside>
        </section>
    </main>

    <script>
        // Truyền dữ liệu từ PHP sang JavaScript
        const movieId = <?php echo $movieId; ?>;
        const roomSeats = <?php echo json_encode($roomSeats); ?>;
        const bookedSeats = <?php echo json_encode($bookedSeats); ?>;
        const otherHoldingSeats = <?php echo json_encode($otherHoldingSeats); ?>;
        const myHoldingSeats = <?php echo json_encode($myHoldingSeats); ?>;
        const selectedShowtime = <?php echo $selectedShowtime; ?>;
    </script>
    <script src="buyticket.js"></script>
</body>


<?php include __DIR__ . '/../Module/footer.php'; ?>