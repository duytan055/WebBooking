    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            position: relative;
        }

        .close-modal {
            position: absolute;
            right: -15px;
            top: -15px;
            width: 35px;
            height: 35px;
            background: red;
            color: white;
            text-align: center;
            line-height: 35px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 22px;
        }
    </style>
    <div id="trailerModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>

            <iframe
                id="trailerFrame"
                width="100%"
                height="500"
                src=""
                frameborder="0"
                allowfullscreen>
            </iframe>
        </div>
    </div>

    <script>
        const modal = document.getElementById("trailerModal");
        const frame = document.getElementById("trailerFrame");
        const closeBtn = document.querySelector(".close-modal");

        document.addEventListener("click", function(e) {

            const btn = e.target.closest(".openTrailer");

            if (!btn) return;

            e.preventDefault();

            let trailer = btn.dataset.trailer;

            if (trailer.includes("watch?v=")) {
                trailer = trailer.replace("watch?v=", "embed/");
            }

            frame.src = trailer;
            modal.style.display = "block";
        });

        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
            frame.src = "";
        });

        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
                frame.src = "";
            }
        });
    </script>