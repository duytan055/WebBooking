const seatMap = document.getElementById("seatMap");
const seatPage = document.getElementById("seatPage");
const comboPage = document.getElementById("comboPage");
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
const submitBtn = document.getElementById("submit-btn");

const showtimeSelect = document.getElementById("showtimeSelect");
const infoMovie = document.getElementById("infoMovie");
const infoRoom = document.getElementById("infoRoom");
const infoShowtime = document.getElementById("infoShowtime");
const summaryMovieName = document.getElementById("summaryMovieName");

let selectedSeats = [];
let seatPrices = {};
let comboTotal = 0;
let comboName = "";
let discount = 0;
let seatMoney = 0;

function createSeats(rows, type, seatCount = 10) {
  rows.forEach((rowLetter) => {
    const row = document.createElement("div");
    row.className = "row";

    for (let i = 1; i <= seatCount; i++) {
      const seat = document.createElement("div");
      const code = `${rowLetter}${i.toString().padStart(2, "0")}`;
      seat.className = `seat ${type}`.trim();
      seat.textContent = code;
      seatPrices[code] =
        type === "vip" ? 70000 : type === "couple" ? 90000 : 50000;
      seat.addEventListener("click", () => selectSeat(seat, code));
      row.appendChild(seat);
    }

    seatMap.appendChild(row);
  });
}

function selectSeat(seat, code) {
  if (seat.classList.contains("selected")) {
    seat.classList.remove("selected");
    selectedSeats = selectedSeats.filter((item) => item !== code);
  } else {
    seat.classList.add("selected");
    selectedSeats.push(code);
  }
  updateUI();
  updateLegend();
}

function updateUI() {
  selectedSeatsLabel.textContent = selectedSeats.length
    ? selectedSeats.join(", ")
    : "...";
  seatMoney = selectedSeats.reduce((sum, seat) => sum + seatPrices[seat], 0);
  seatTotalLabel.textContent = seatMoney.toLocaleString() + " đ";
}

function goCombo() {
  if (!selectedSeats.length) {
    alert("Vui lòng chọn ghế");
    return;
  }
  seatPage.classList.remove("active");
  comboPage.classList.add("active");
}

function addCombo(name, price) {
  comboTotal += price;
  comboName = name;
  comboTotalLabel.textContent = comboTotal.toLocaleString() + " đ";
}

function goPayment() {
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

function goBack() {
  if (paymentPage.classList.contains("active")) {
    paymentPage.classList.remove("active");
    comboPage.classList.add("active");
  } else if (comboPage.classList.contains("active")) {
    comboPage.classList.remove("active");
    seatPage.classList.add("active");
  }
}

let timeLeft = 300;
const timerElement = document.getElementById("timer");
const timerInterval = setInterval(() => {
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;
  timerElement.textContent = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

  if (timeLeft <= 0) {
    clearInterval(timerInterval);
    alert("Hết thời gian giữ ghế");
    window.location.reload();
  }
  timeLeft -= 1;
}, 1000);

createSeats(["A", "B", "C", "D"], "normal", 10);
createSeats(["E"], "vip", 10);
createSeats(["G"], "couple", 10);

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

// initialize legend state
updateLegend();
