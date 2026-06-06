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
let seatMoney = 0;

// Global variables passed from PHP (assuming they are defined in the PHP script block before this JS file)
// const movieId = ...;
// const roomSeats = ...; // Array of {id_ghe, ma_ghe, loai_ghe}
// const bookedSeats = ...; // Array of id_ghe (integers)

// Initialize selected seats from server-side holding state
myHoldingSeats.forEach((id) => {
  const seatData = roomSeats.find((s) => s.id_ghe == id);
  if (seatData) {
    selectedSeats.push(seatData.ma_ghe);
    selectedSeatIds.push(id);
  }
});

// New function to create the seat map using data from PHP
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
      const isHoldingOther = otherHoldingSeats.includes(seatIdInt);
      const isHoldingMe = myHoldingSeats.includes(seatIdInt);

      let seatClass = `seat ${seatData.loai_ghe}`.trim();
      if (isBooked) seatClass += " booked";
      else if (isHoldingOther) seatClass += " holding";
      else if (isHoldingMe) seatClass += " selected";

      seatElement.className = seatClass;
      seatElement.textContent = seatData.ma_ghe;
      seatElement.dataset.id = seatData.id_ghe; // Store actual seat ID
      seatElement.dataset.code = seatData.ma_ghe; // Store seat code
      seatElement.dataset.type = seatData.loai_ghe; // Store seat type

      // Populate seatPrices map
      seatPrices[seatData.ma_ghe] =
        seatData.loai_ghe === "vip"
          ? 70000
          : seatData.loai_ghe === "couple"
            ? 90000
            : 50000;

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

// Modify selectSeat to use actual seat ID and code
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
  discount = code === "STARLIGHT" ? 20000 : 0;
  discountMoneyLabel.textContent = discount.toLocaleString() + " đ";
  if (paymentPage.classList.contains("active")) {
    updateFinalTotal();
  }
}

// --- LOGIC THANH TOÁN (GỬI DỮ LIỆU VỀ SERVER) ---
async function checkout() {
  if (selectedSeatIds.length === 0) {
    alert("Vui lòng chọn ghế trước khi thanh toán!");
    return;
  }

  const paymentMethod = "VNPAY"; // Có thể lấy từ UI nếu có nhiều phương thức

  const formData = new URLSearchParams();
  formData.append("action", "checkout");
  formData.append("suat_id", selectedShowtime);
  formData.append("combo_money", comboTotal);
  formData.append("discount", discount);
  formData.append("payment_method", paymentMethod);

  try {
    const response = await fetch("buyticket.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: formData.toString(),
    });
    const result = await response.json();

    if (result.status === "success") {
      alert(result.message);
      window.location.href = "../Pages/trangChu.php"; // Quay về trang chủ sau khi thành công
    } else {
      alert(result.message);
      window.location.reload(); // Reload nếu có lỗi (ví dụ hết hạn giữ ghế)
    }
  } catch (err) {
    console.error("Lỗi thanh toán:", err);
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

let timeLeft = 240; // 4 minutes
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

// Legend interaction: highlight legend swatches when seats of that type are selected
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

// Initial call to create seat map with data from PHP
createSeatMap(roomSeats, bookedSeats);

// initialize legend state
updateLegend(); // Initial call
updateUI(); // Reflect holding state from PHP

// Gắn sự kiện cho nút Thanh Toán
const payBtn = document.querySelector(".payBtn");
if (payBtn) {
  payBtn.addEventListener("click", checkout);
}

// Handle showtime change
if (showtimeSelect) {
  showtimeSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const newShowtimeId = selectedOption.value;
    // Reload the page with the new showtime ID to fetch updated seat data
    // movieId is passed from PHP
    window.location.href = `buyticket.php?id=${movieId}&showtime=${newShowtimeId}`;
  });
}
