const seatMap = document.getElementById("seatMap");
const seatPage = document.getElementById("seatPage");
const comboPage = document.getElementById("comboPage");
const showtimeSelect = document.getElementById("showtimeSelect");
const infoMovie = document.getElementById("infoMovie");
const infoRoom = document.getElementById("infoRoom");
const paymentPage = document.getElementById("paymentPage");
const selectedSeatsLabel = document.getElementById("selectedSeats");
const seatTotalLabel = document.getElementById("seatTotal");
const comboTotalLabel = document.getElementById("comboTotal");
const seatCountLabel = document.getElementById("seatCount");
const seatList = document.getElementById("seatList");
const seatMoneyLabel = document.getElementById("seatMoney");
const comboInfoLabel = document.getElementById("comboInfo");
const comboMoneyLabel = document.getElementById("comboMoney");
const discountMoneyLabel = document.getElementById("discountMoney");
const finalTotalLabel = document.getElementById("finalTotal");
const discountInput = document.getElementById("discountInput");
const infoShowtime = document.getElementById("infoShowtime");
const summaryMovieName = document.getElementById("summaryMovieName");

let selectedSeats = []; // Stores seat codes (e.g., "A01")
let selectedSeatIds = []; // Stores actual id_ghe (integers)
let seatPrices = {};
let comboTotal = 0;
let comboName = "";
let discount = 0;
let selectedPaymentMethod = null; // Mặc định chưa chọn phương thức nào
let seatMoney = 0;

myHoldingSeats.forEach((id) => {
  const seatData = roomSeats.find((s) => s.id_ghe == id);
  if (seatData) {
    selectedSeats.push(seatData.ma_ghe);
    selectedSeatIds.push(id);
  }
});

function createSeatMap(seatsData, bookedSeatIds) {
  seatMap.innerHTML = ""; // Clear existing seats

  // Group seats by row letter
  const rows = {};
  seatsData.forEach((seat) => {
    const rowLetter = seat.ma_ghe.charAt(0);
    if (!rows[rowLetter]) {
      rows[rowLetter] = [];
    }
    rows[rowLetter].push(seat);
  });

  // Sort rows by letter and seats within rows by number
  const sortedRowLetters = Object.keys(rows).sort();

  sortedRowLetters.forEach((rowLetter) => {
    const rowDiv = document.createElement("div");
    rowDiv.className = "row";
    // Sort seats within the row by their number (e.g., A01, A02)
    rows[rowLetter].sort((a, b) => {
      const numA = parseInt(a.ma_ghe.substring(1));
      const numB = parseInt(b.ma_ghe.substring(1));
      return numA - numB;
    });

    rows[rowLetter].forEach((seatData) => {
      const seatElement = document.createElement("div");
      const seatIdInt = parseInt(seatData.id_ghe);
      const isBooked = bookedSeatIds.includes(seatIdInt);

      const isHoldingOther =
        otherHoldingSeats.includes(seatIdInt) ||
        pendingSeats.includes(seatIdInt);

      const isHoldingMe = myHoldingSeats.includes(seatIdInt);

      const type = (seatData.loai_ghe || "").trim().toLowerCase();

      // CLASS
      let seatClass = "seat";

      if (type === "vip") seatClass += " vip";
      else if (type === "couple") seatClass += " couple";
      else seatClass += " normal";

      // trạng thái
      if (isBooked) seatClass += " booked";
      else if (isHoldingOther) seatClass += " holding";
      else if (isHoldingMe) seatClass += " selected";

      // GIÁ GHẾ (CHỈ GIỮ 1 LẦN DUY NHẤT)
      if (type === "vip") {
        seatPrices[seatData.ma_ghe] = 80000;
      } else if (type === "couple") {
        seatPrices[seatData.ma_ghe] = 100000;
      } else {
        seatPrices[seatData.ma_ghe] = 50000;
      }

      seatElement.className = seatClass;
      seatElement.textContent = seatData.ma_ghe;
      seatElement.dataset.id = seatData.id_ghe; // Store actual seat ID
      seatElement.dataset.code = seatData.ma_ghe; // Store seat code
      seatElement.dataset.type = seatData.loai_ghe; // Store seat type

      if (!isBooked && !isHoldingOther) {
        // Only add click listener if not booked
        seatElement.addEventListener("click", () =>
          selectSeat(seatElement, seatData.ma_ghe, seatData.id_ghe),
        );
      }
      rowDiv.appendChild(seatElement);
    });
    seatMap.appendChild(rowDiv);
  });
}

/** Hủy đơn hàng */
function cancelOrder() {
  if (!confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) return;

  fetch("buyticket.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=cancel_booking&suat_id=${selectedShowtime}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        window.location.href = "trangChu.php";
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch(() => alert("Lỗi kết nối, vui lòng thử lại."));
}

async function selectSeat(seatElement, seatCode, seatId) {
  if (
    seatElement.classList.contains("booked") ||
    seatElement.classList.contains("holding")
  ) {
    return; // Cannot select booked seats
  }

  const isSelected = seatElement.classList.contains("selected");
  const action = isSelected ? "release" : "hold";

  try {
    const response = await fetch("buyticket.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=${action}&suat_id=${selectedShowtime}&ghe_id=${seatId}`,
    });
    const result = await response.json();

    if (result.status === "success") {
      if (isSelected) {
        seatElement.classList.remove("selected");
        selectedSeats = selectedSeats.filter((item) => item !== seatCode);
        selectedSeatIds = selectedSeatIds.filter((item) => item !== seatId);
      } else {
        seatElement.classList.add("selected");
        selectedSeats.push(seatCode);
        selectedSeatIds.push(seatId);
      }
      updateUI();
      updateLegend();
    } else {
      alert(result.message);
      window.location.reload();
    }
  } catch (err) {
    console.error("Lỗi giữ ghế:", err);
  }
}

function updateUI() {
  selectedSeatsLabel.textContent = selectedSeats.length
    ? selectedSeats.join(", ")
    : "...";
  seatMoney = selectedSeats.reduce((sum, seat) => sum + seatPrices[seat], 0);
  seatTotalLabel.textContent = seatMoney.toLocaleString() + " đ";
}

// Hàm gia hạn thời gian giữ ghế trên Server khi chuyển bước
async function refreshHoldOnServer() {
  try {
    const response = await fetch("buyticket.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=refresh_hold&suat_id=${selectedShowtime}`,
    });
    const result = await response.json();
    if (result.status !== "success") {
      alert("Phiên giữ ghế của bạn đã hết hạn!");
      window.location.reload();
      return false;
    }
    return true;
  } catch (err) {
    console.error("Lỗi refresh hold:", err);
    return false;
  }
}

async function goCombo() {
  if (!selectedSeats.length) {
    alert("Vui lòng chọn ghế");
    return;
  }

  // Kiểm tra và gia hạn giữ ghế trên DB trước khi qua trang Combo
  const ok = await refreshHoldOnServer();
  if (!ok) return;

  seatPage.classList.remove("active");
  comboPage.classList.add("active");
}

function addCombo(name, price) {
  comboTotal += price;
  comboName = name;
  comboTotalLabel.textContent = comboTotal.toLocaleString() + " đ";
}

async function goPayment() {
  // Tiếp tục gia hạn giữ ghế khi qua trang Thanh toán
  const ok = await refreshHoldOnServer();
  if (!ok) return;

  comboPage.classList.remove("active");
  paymentPage.classList.add("active");

  seatCountLabel.textContent = selectedSeats.length;
  seatList.innerHTML = "";
  selectedSeats.forEach((code) => {
    const seatNode = document.createElement("div");
    seatNode.className = "seatBox";
    seatNode.textContent = code;
    seatList.appendChild(seatNode);
  });

  seatMoneyLabel.textContent = seatMoney.toLocaleString() + " đ";
  comboInfoLabel.textContent = comboName || "Không có";
  comboMoneyLabel.textContent = comboTotal.toLocaleString() + " đ";
  // Fill payment info for movie and showtime
  if (infoMovie && summaryMovieName) {
    infoMovie.textContent = summaryMovieName.textContent;
  }
  if (showtimeSelect && infoRoom && infoShowtime) {
    const opt = showtimeSelect.options[showtimeSelect.selectedIndex];
    infoRoom.textContent = opt ? opt.dataset.phong : "";
    infoShowtime.textContent = opt ? opt.text : "";
  }
  updateFinalTotal();
}

function updateFinalTotal() {
  const total = seatMoney + comboTotal - discount;
  finalTotalLabel.textContent = total.toLocaleString() + " đ";
}

function applyDiscount() {
  const code = discountInput.value.trim().toUpperCase();
  discount = code === "G8Cenima" ? 20000 : 0;
  discountMoneyLabel.textContent = discount.toLocaleString() + " đ";
  if (paymentPage.classList.contains("active")) {
    updateFinalTotal();
  }
}

// --- LOGIC THANH TOÁN (GỬI DỮ LIỆU VỀ SERVER) ---
async function checkout(method) {
  if (selectedSeatIds.length === 0) {
    alert("Vui lòng chọn ghế trước khi thanh toán!");
    return;
  }

  const formData = new URLSearchParams();
  formData.append("action", "checkout");
  formData.append("suat_id", selectedShowtime);
  formData.append("combo_money", comboTotal);
  formData.append("discount", discount);
  formData.append("payment_method", method || selectedPaymentMethod);

  try {
    const response = await fetch("buyticket.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: formData.toString(),
    });
    const result = await response.json();

    if (result.status === "success") {
      if (selectedPaymentMethod === "VNPAY") {
        window.location.href =
          "../payment/vnpay_payment.php?id_datve=" + result.id_datve;
        return;
      }
      // QR_BANKING: show QR code after order is created
      if (selectedPaymentMethod === "QR_BANKING") {
        showQRCode(result.id_datve);
        return;
      }
      window.location.href = "../Pages/trangChu.php";
    } else {
      alert(result.message);
      window.location.reload();
    }
  } catch (err) {
    console.error("Lỗi thanh toán:", err);
  }
}

function showQRCode(orderId) {
  document.getElementById("qrCodeDisplay").style.display = "block";
  document.getElementById("qrTransferContent").innerText =
    "WebBooking_ID" + orderId;
  document.getElementById("qrTransferAmount").innerText =
    document.getElementById("finalTotal").innerText;

  const totalAmountForQR = document
    .getElementById("finalTotal")
    .innerText.replace(/\D/g, "");
  const activeBank = document.querySelector(".bank-option.active");
  document.getElementById("qrCodeImage").src =
    `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=QR_PAYMENT_${totalAmountForQR}_${activeBank.dataset.bank}`;
  // Thay nút THANH TOÁN thành nút xác nhận đã chuyển khoản
  payBtn.disabled = false;
  payBtn.textContent = "TÔI ĐÃ THANH TOÁN";
  // Gắn sự kiện mới: xác nhận thanh toán
  payBtn.onclick = function () {
    confirmPayment(orderId);
  };
  alert(
    "Đơn hàng đã được tạo! Vui lòng quét mã QR bên dưới để hoàn tất thanh toán.\nSau khi chuyển khoản, nhấn 'TÔI ĐÃ THANH TOÁN' để xác nhận.",
  );
}

async function confirmPayment(orderId) {
  if (!confirm("Bạn đã chuyển khoản xong? Nhấn OK để xác nhận thanh toán."))
    return;

  try {
    const response = await fetch("buyticket.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=confirm_payment&id_datve=${orderId}`,
    });
    const result = await response.json();

    if (result.status === "success") {
      alert("Thanh toán thành công! Vé sẽ được gửi qua email của bạn.");
      window.location.href = "ticket_success.php?id_datve=" + orderId;
    } else {
      alert("Lỗi: " + result.message);
    }
  } catch (err) {
    console.error("Lỗi xác nhận thanh toán:", err);
  }
}

function goBack() {
  if (paymentPage.classList.contains("active")) {
    paymentPage.classList.remove("active");
    comboPage.classList.add("active");
  } else if (comboPage.classList.contains("active")) {
    comboPage.classList.remove("active");
    seatPage.classList.add("active");
  }
}

let timeLeft = 240;
const timerElement = document.getElementById("timer");
const timerInterval = setInterval(() => {
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;
  timerElement.textContent = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
  if (timeLeft <= 0) {
    clearInterval(timerInterval);
    alert("Hết thời gian giữ ghế. Vui lòng chọn lại ghế.");
    window.location.reload();
  }
  timeLeft -= 1;
}, 1000);

function updateLegend() {
  const normalSw = document.querySelector(
    '#seatLegend .legend-swatch[data-type="normal"]',
  );
  const vipSw = document.querySelector(
    '#seatLegend .legend-swatch[data-type="vip"]',
  );
  const coupleSw = document.querySelector(
    '#seatLegend .legend-swatch[data-type="couple"]',
  );

  const normalSelected =
    document.querySelectorAll(".seat.selected.normal").length > 0;
  const vipSelected =
    document.querySelectorAll(".seat.selected.vip").length > 0;
  const coupleSelected =
    document.querySelectorAll(".seat.selected.couple").length > 0;

  if (normalSw) normalSw.classList.toggle("active", normalSelected);
  if (vipSw) vipSw.classList.toggle("active", vipSelected);
  if (coupleSw) coupleSw.classList.toggle("active", coupleSelected);
}

createSeatMap(roomSeats, bookedSeats);

updateLegend();

if (showtimeSelect) {
  showtimeSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const newShowtimeId = selectedOption.value;

    window.location.href = `buyticket.php?id=${movieId}&showtime=${newShowtimeId}`;
  });
}
const methods = document.querySelectorAll(".method");
const qrDetails = document.getElementById("qrBankingDetails");
const bankOptions = document.querySelectorAll(".bank-option");
const payBtn = document.querySelector(".payBtn");

// Xử lý chọn phương thức thanh toán
methods.forEach((m) => {
  m.addEventListener("click", function () {
    const clickedMethod = this.dataset.method;

    if (this.classList.contains("active")) {
      this.classList.remove("active");
      selectedPaymentMethod = null;
      if (qrDetails) qrDetails.style.display = "none";
      const qrDisp = document.getElementById("qrCodeDisplay");
      if (qrDisp) qrDisp.style.display = "none";
      payBtn.style.display = "none";
    } else {
      methods.forEach((el) => el.classList.remove("active"));
      this.classList.add("active");
      selectedPaymentMethod = clickedMethod;
      payBtn.style.display = "block";

      if (clickedMethod === "QR_BANKING") {
        qrDetails.style.display = "block";
      } else {
        qrDetails.style.display = "none";
      }
    }
  });
});

// Xử lý chọn ngân hàng cho QR Banking
bankOptions.forEach((b) => {
  b.addEventListener("click", function () {
    bankOptions.forEach((el) => el.classList.remove("active"));
    this.classList.add("active");
  });
});

if (payBtn) {
  payBtn.addEventListener("click", function (e) {
    if (!selectedPaymentMethod) {
      alert("Vui lòng chọn một phương thức thanh toán.");
      e.stopImmediatePropagation();
      return;
    }

    if (selectedPaymentMethod === "QR_BANKING") {
      const activeBank = document.querySelector(".bank-option.active");
      if (!activeBank) {
        alert("Vui lòng chọn một ngân hàng để thực hiện thanh toán QR.");
        e.stopImmediatePropagation();
        return;
      }
      // Gọi checkout để tạo đơn hàng trước, sau đó hiển thị QR
      checkout();
    } else {
      checkout();
    }
  });
}
