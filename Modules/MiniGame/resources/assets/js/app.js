console.log('Minigame initialized');

// DOM caching
const segments = window.segments || [];
const wheel = document.querySelector(".wheel");
const spinBtn = document.getElementById("spin");
const modal = document.getElementById("resultModal");
const modalTitle = document.getElementById("modalTitle");
const modalResult = document.getElementById("modalResult");
const closeModalBtn = document.getElementById("closeModal");
const historyBtn = document.getElementById("historyBtn");
const historyModal = document.getElementById("historyModal");
const closeHistoryModalBtn = document.getElementById("closeHistoryModal");

let isSpinning = false;
let spinTimeout = null;

if (!wheel || !spinBtn || segments.length === 0) {
    console.error("Không tìm thấy wheel, nút spin hoặc danh sách phần thưởng rỗng.");
    if (spinBtn) spinBtn.disabled = true;
}

function showModal(title, message, isSuccess = false) {
    modalTitle.textContent = title;
    modalTitle.classList.toggle("text-green-600", isSuccess);
    modalTitle.classList.toggle("text-yellow-600", !isSuccess);
    modalResult.textContent = message;

    modal.classList.remove("hidden");
    modal.dataset.visible = "true";
}

function resetSpinState() {
    isSpinning = false;
    spinBtn.disabled = false;
    spinTimeout = null;
}

spinBtn.onclick = () => {
    if (isSpinning || segments.length === 0) return;

    if (spinBtn.dataset.requiresLogin === "true") {
        window.location.href = "/dang-nhap";
        return;
    }

    if (window.nextSpinTime) {
        const now = Date.now();
        const nextTime = new Date(window.nextSpinTime).getTime();

        if (now < nextTime) {
            const timeStr = new Date(nextTime).toLocaleTimeString('vi-VN', {
                hour: '2-digit', minute: '2-digit'
            });
            showModal("⚠️ Thông báo", `⏳ Bạn cần chờ đến ${timeStr} để quay tiếp!`);
            return;
        }
    }

    // Bắt đầu quay
    isSpinning = true;
    spinBtn.disabled = true;

    if (spinTimeout) clearTimeout(spinTimeout);
    wheel.style.transition = "none";
    wheel.style.transform = "rotate(0deg)";
    wheel.offsetHeight;

    // Gửi yêu cầu quay – không truyền prizeName
    window.Livewire.dispatch('claimPrize');
};

// Đóng modal
closeModalBtn.onclick = () => {
    modal.classList.add("hidden");
    modal.dataset.visible = "false";
};

// Lịch sử phần thưởng
if (historyBtn && historyModal && closeHistoryModalBtn) {
    historyBtn.onclick = () => historyModal.classList.remove("hidden");
    closeHistoryModalBtn.onclick = () => historyModal.classList.add("hidden");
}

// Nhận lỗi từ backend
window.addEventListener('notify', (event) => {
    const message = event.detail.message;
    const isError = [
        "Bạn đã hết lượt",
        "Phần thưởng không hợp lệ",
        "Bạn cần đăng nhập",
        "Phần thưởng đã hết",
        "Không có phần thưởng khả dụng"
    ].some(msg => message.includes(msg));

    if (isError) {
        showModal("⚠️ Thông báo", message);
        resetSpinState();
    }
});

// ✅ Nhận kết quả trúng từ backend và xoay chính xác đến phần thưởng
window.addEventListener('spinResult', (event) => {
    const prizeName = event.detail.prize;
    const index = segments.findIndex(name => name === prizeName);

    if (index === -1) {
        showModal("⚠️ Thông báo", "Không tìm thấy phần thưởng trên vòng quay!");
        resetSpinState();
        return;
    }

    const segmentCount = segments.length;
    const segmentDegree = 360 / segmentCount;
    const spins = Math.floor(Math.random() * 3) + 5;

    // ✅ Tính chính giữa phần thưởng được chọn
    const offset = index * segmentDegree; // Không cần cộng segmentDegree / 2 nếu ảnh vẽ chính giữa

    const totalDegree = spins * 360 + (360 - offset);

    wheel.style.transition = "transform 4s ease-out";
    wheel.style.transform = `rotate(${totalDegree}deg)`;

    setTimeout(() => {
        showModal("🎉 Chúc mừng!", `🎁 Bạn nhận được: ${prizeName}`, true);
        resetSpinState();
    }, 4200);

    console.log("Segments:", segments);
    console.log("Prize:", prizeName, "Index:", index);
});

