document.addEventListener("DOMContentLoaded", () => {
  const elements = {
    wheel: document.getElementById("wheel"),
    spinBtn: document.getElementById("spin"),
    prizeModal: document.getElementById("prizeModal"),
    prizeText: document.getElementById("prizeText"),
    closeBtn: document.querySelector(".close"),
  };

  if (Object.values(elements).some((el) => !el)) {
    console.error("Không tìm thấy phần tử DOM.");
    return;
  }

  const { wheel, spinBtn, prizeModal, prizeText, closeBtn } = elements;
  // const prizes = [
  // "Giảm 10%",
  // "-20K đơn ≥150K",
  // "Free Ship",
  // "-50K đơn ≥300K",
  // "Mua 2 tặng bookmark",
  // "Giảm 15% sách mới",
  // "-100K khách mới",
  // "+1 lượt quay",
  // ];

  const prizeCount = prizes.length;
  const prizeAngle = 360 / prizeCount;
  const spinDuration = 5000;
  let currentAngle = 0;
  const audio = new Audio("147239759.mp3");

  // HIển thị tên phần thưởng
 prizes.forEach((prize, index) => {
    const label = document.createElement("div");
    const prizeLabels = document.getElementById("prizeLabels");
    label.className = "prize-label";
    label.textContent = prize;
    const angle = index * prizeAngle + prizeAngle / 2;
    label.style.transform = `rotate(${angle}deg) translateY(-195px) rotate(1deg)`;

    prizeLabels.appendChild(label);
  });

  // Hiển thị phần thưởng
  function showPrize(prize) {
    prizeText.textContent = `🎉 Bạn đã trúng: ${prize}`;
    prizeModal.classList.remove("hidden");
    prizeModal.classList.add("flex", "animate-fade-in");
  }

  
  closeBtn.addEventListener("click", () => {
    prizeModal.classList.add("hidden");
    prizeModal.classList.remove("flex", "animate-fade-in");
  });

  window.addEventListener("click", (e) => {
    if (e.target === prizeModal) {
      prizeModal.classList.add("hidden");
      prizeModal.classList.remove("flex", "animate-fade-in");
    }
  });

  // Logic quay
  spinBtn.addEventListener("click", () => {
    if (spinBtn.disabled) return;

    
    if (audio) {
      audio.play().catch((e) => console.warn("Không phát được âm thanh:", e));
    }

    spinBtn.disabled = true;
    spinBtn.classList.add("grayscale");

    // Random các phần thưởng
    const selectedIndex = Math.floor(Math.random() * prizeCount);
    const selectedPrize = prizes[selectedIndex];

    // Tính góc dừng lại cho phần thưởng
    const stopAngle = selectedIndex * prizeAngle + prizeAngle / 2;

    // Tính góc quay: 6 vòng + góc dừng + góc ngẫu nhiên nhỏ
    const extraSpin = 360 * 6 + Math.random() * prizeAngle * 0.5;
    currentAngle +=
      extraSpin - (currentAngle % 360) + (360 - (stopAngle % 360));

    // sử dụng animation
    wheel.style.transition = `transform ${spinDuration}ms ease-out`;
    wheel.style.transform = `rotate(${currentAngle}deg)`;

    // Hiển thị phần thưởng cuối cùng sau khi quay xong
    setTimeout(() => {
      showPrize(selectedPrize);
      spinBtn.disabled = false;
      spinBtn.classList.remove("grayscale");
    }, spinDuration + 200);
  });
});
