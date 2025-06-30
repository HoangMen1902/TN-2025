
    let wheel = document.querySelector(".wheel");
    let btn = document.getElementById("spin");
    let isSpinning = false;

    btn.onclick = function () {
        if (isSpinning) return;

        isSpinning = true;
        btn.disabled = true;

        let spins = Math.floor(Math.random() * 5) + 5; // từ 5 đến 9 vòng
        let extraDegree = Math.floor(Math.random() * 360);
        let totalDegree = spins * 360 + extraDegree;

        // Quay vòng
        wheel.style.transition = "transform 4s ease-out";
        wheel.style.transform = `rotate(${totalDegree}deg)`;

        setTimeout(() => {
            // Dừng quay và tính toán kết quả
            wheel.style.transition = "none";
            let normalizedDegree = totalDegree % 360;
            wheel.style.transform = `rotate(${normalizedDegree}deg)`;

            let segmentDegree = 360 / segments.length;
            let selectedIndex = Math.floor(
                (360 - normalizedDegree + segmentDegree / 2) % 360 / segmentDegree
            );

            let prize = segments[selectedIndex] ?? "Không xác định";

            // Hiển thị kết quả
            document.getElementById("modalResult").textContent = prize;
            document.getElementById("resultModal").classList.remove("hidden");

            // Reset
            isSpinning = false;
            btn.disabled = false;
        }, 4000);
    };

    // Đóng modal 
    document.getElementById("closeModal").onclick = function () {
        document.getElementById("resultModal").classList.add("hidden");
    };

