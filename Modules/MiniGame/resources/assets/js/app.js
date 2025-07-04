console.log('Minigame initialized');

const segments = window.segments || [];
const wheel = document.querySelector(".wheel");
const spinBtn = document.getElementById("spin");
const modal = document.getElementById("resultModal");
const modalResult = document.getElementById("modalResult");
const closeModalBtn = document.getElementById("closeModal");

// Lịch sử phần thưởng
const historyBtn = document.getElementById("historyBtn");
const historyModal = document.getElementById("historyModal");
const closeHistoryModalBtn = document.getElementById("closeHistoryModal");

let isSpinning = false;
let spinTimeout = null;

if (!wheel || !spinBtn || segments.length === 0) {
    console.error("Không tìm thấy wheel hoặc nút spin, hoặc danh sách phần thưởng trống.");
    if (spinBtn) spinBtn.disabled = true;
}

spinBtn.onclick = () => {
    if (isSpinning || segments.length === 0) return;

    isSpinning = true;
    spinBtn.disabled = true;

    if (spinTimeout) clearTimeout(spinTimeout);

    // Reset wheel
    wheel.style.transition = "none";
    wheel.style.transform = "rotate(0deg)";
    wheel.offsetHeight;

    const spins = Math.floor(Math.random() * 5) + 5; // 5–9 vòng
    const extraDegree = Math.floor(Math.random() * 360);
    const totalDegree = spins * 360 + extraDegree;

    wheel.style.transition = "transform 4s ease-out";
    wheel.style.transform = `rotate(${totalDegree}deg)`;

    spinTimeout = setTimeout(() => {
        const segmentDegree = 360 / segments.length;
        const normalizedDegree = totalDegree % 360;
        const selectedIndex = Math.floor(
            (360 - normalizedDegree + segmentDegree / 2) % 360 / segmentDegree
        );

        const prize = segments[selectedIndex] ?? "Không xác định";

        if (prize === "Không xác định" || !segments.includes(prize)) {
            modalResult.textContent = "Lỗi: Phần thưởng không hợp lệ!";
            modal.classList.remove("hidden");
            modal.dataset.visible = "true";
            resetSpinState();
            return;
        }

        // Gửi sự kiện claim về Livewire
        window.Livewire.dispatch('claimPrize', { prizeName: prize });

        modalResult.textContent = `Bạn nhận được: ${prize}`;
        modal.classList.remove("hidden");
        modal.dataset.visible = "true";

        resetSpinState();
    }, 4200);
};

closeModalBtn.onclick = () => {
    modal.classList.add("hidden");
    modal.dataset.visible = "false";
};

// Lắng nghe thông báo từ Livewire
window.addEventListener('notify', (event) => {
    console.log("Thông báo từ Livewire:", event.detail.message);
    if (modal.dataset.visible === "true") {
        modalResult.textContent = event.detail.message;
        modal.classList.remove("hidden");
    }
});

// Lịch sử phần thưởng - mở modal
if (historyBtn && historyModal && closeHistoryModalBtn) {
    historyBtn.addEventListener("click", () => {
        historyModal.classList.remove("hidden");
    });

    closeHistoryModalBtn.addEventListener("click", () => {
        historyModal.classList.add("hidden");
    });
}

function resetSpinState() {
    isSpinning = false;
    spinBtn.disabled = false;
    spinTimeout = null;
}
