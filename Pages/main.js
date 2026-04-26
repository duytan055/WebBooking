const imgages = [
  "../poter1/nha-ba-toi-mot-phong.jpg",
  "../poter1/quy-nhap-trang(1) (1).jpg",
  "../poter1/tho-oi(1).jpg",
];

let index = 0;
const img = document.getElementById("slide_img");

setInterval(() => {
  index = (index + 1) % imgages.length;
  img.src = imgages[index];
}, 3000);

// now Movies
function renderSoonMovies(list, elementId) {
  const container = document.getElementById(elementId);
  container.innerHTML = "";

  list.forEach((movies) => {
    // Chỉ render nút play nếu phim đó có link trailer
    const playBtn = movies.trailer
      ? `<a href="#" class="play-video-btn" data-video="${movies.trailer}">
            <i class="fa-solid fa-circle-play" style="font-size: 50px; color: white"></i>
         </a>`
      : "";

    container.innerHTML += `
      <li>
        <div class="box_img">
          <img src="${movies.img}" />
          <div class="box_hoverb">
            ${playBtn}
          </div>
        </div>
      </li>
    `;
  });
  setupVideoButtons();
}

function setupVideoButtons() {
  const modal = document.getElementById("videoModal");
  const iframe = document.getElementById("modalIframe");
  const closeBtn = document.getElementById("closeBtn");

  document.querySelectorAll(".play-video-btn").forEach((button) => {
    button.onclick = function (e) {
      e.preventDefault();
      const videoUrl = this.getAttribute("data-video");

      if (videoUrl) {
        // Hiện modal và gán link vào iframe, thêm autoplay để vừa hiện là chạy luôn
        iframe.src =
          videoUrl + (videoUrl.includes("?") ? "&" : "?") + "autoplay=1";
        modal.style.display = "flex";
      }
    };
  });

  // Hàm đóng Modal
  closeBtn.onclick = function () {
    modal.style.display = "none";
    iframe.src = ""; // Quan trọng: Xóa src để video dừng phát khi đóng
  };

  // Đóng khi nhấn ra ngoài vùng video
  modal.onclick = function (e) {
    if (e.target === modal) closeBtn.onclick();
  };
}

fetch("JSandphp.php")
  .then((response) => response.json())
  .then((data) => {
    renderSoonMovies(data, "soonmovies_list");
  })
  .catch((error) => console.error("Error:", error));
