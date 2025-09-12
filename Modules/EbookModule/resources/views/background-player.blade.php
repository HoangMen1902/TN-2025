<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Audio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            overflow: hidden;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-neutral-800">

    <div class="w-full max-w-2xl p-4 rounded-2xl shadow-lg text-white bg-neutral-800">
        <audio id="bgAudio"></audio>

        <div class="flex items-center justify-between mb-3">
            <button id="rewindBtn"
                class="flex items-center gap-1 px-3 py-2 bg-neutral-700 rounded-lg hover:bg-neutral-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 16.8c0 .86-.93 1.4-1.68.97l-7.1-4.06a1.12 1.12 0 0 1 0-1.95l7.1-4.06A1.12 1.12 0 0 1 21 8.69v8.12ZM11.25 16.8c0 .86-.93 1.4-1.68.97l-7.1-4.06a1.12 1.12 0 0 1 0-1.95l7.1-4.06a1.12 1.12 0 0 1 1.68.97v8.12Z" />
                </svg>
                15s
            </button>

            <button id="playPauseBtn" class="p-3 bg-neutral-900 rounded-full hover:bg-neutral-700">
                <svg id="playIcon" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.91 11.67a.38.38 0 0 1 0 .66l-5.6 3.11a.38.38 0 0 1-.56-.33V8.89c0-.29.3-.47.56-.33l5.6 3.11Z" />
                </svg>
            </button>

            <button id="forwardBtn"
                class="flex items-center gap-1 px-3 py-2 bg-neutral-700 rounded-lg hover:bg-neutral-600">
                15s
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8.69c0-.86.93-1.4 1.68-.98l7.1 4.06a1.12 1.12 0 0 1 0 1.95l-7.1 4.06A1.12 1.12 0 0 1 3 16.81V8.69ZM12.75 8.69c0-.86.93-1.4 1.68-.98l7.1 4.06a1.12 1.12 0 0 1 0 1.95l-7.1 4.06a1.12 1.12 0 0 1-1.68-.98V8.69Z" />
                </svg>
            </button>
        </div>

        <div class="flex items-center space-x-3">
            <span id="currentTime" class="text-sm">0:00</span>
            <input id="progressBar" type="range" min="0" value="0"
                class="w-full h-2 bg-gray-500 rounded-lg cursor-pointer">
            <span id="duration" class="text-sm">0:00</span>
        </div>

        <div class="flex items-center mt-3 space-x-2">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                </svg>
            </span>
            <input id="volumeBar" type="range" min="0" max="1" step="0.01" value="1"
                class="w-full h-2 bg-gray-500 rounded-lg cursor-pointer">
        </div>
        
    </div>

    <script>
        const audio = document.getElementById('bgAudio');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const progressBar = document.getElementById('progressBar');
        const currentTimeEl = document.getElementById('currentTime');
        const durationEl = document.getElementById('duration');
        const rewindBtn = document.getElementById('rewindBtn');
        const forwardBtn = document.getElementById('forwardBtn');
        const volumeBar = document.getElementById('volumeBar');

        const params = new URLSearchParams(window.location.search);
        const src = params.get('src');
        if (src) {
            audio.src = src;
            audio.autoplay = true;
            audio.play().catch(err => console.warn("Tự động phát bị chặn:", err));
        }

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${mins}:${secs}`;
        }

        function setPlayIcon() {
            playPauseBtn.innerHTML = `
                <svg id="playIcon" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.67a.38.38 0 0 1 0 .66l-5.6 3.11a.38.38 0 0 1-.56-.33V8.89c0-.29.3-.47.56-.33l5.6 3.11Z"/>
                </svg>
            `;
        }

        function setPauseIcon() {
            playPauseBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5"/>
                </svg>
            `;
        }

        playPauseBtn.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
            } else {
                audio.pause();
            }
        });

        audio.addEventListener('play', setPauseIcon);
        audio.addEventListener('pause', setPlayIcon);
        audio.addEventListener('ended', setPlayIcon);

        audio.addEventListener('timeupdate', () => {
            progressBar.value = audio.currentTime;
            currentTimeEl.textContent = formatTime(audio.currentTime);
        });

        audio.addEventListener('loadedmetadata', () => {
            progressBar.max = audio.duration;
            durationEl.textContent = formatTime(audio.duration);
        });

        progressBar.addEventListener('input', () => {
            audio.currentTime = progressBar.value;
        });

        rewindBtn.addEventListener('click', () => {
            audio.currentTime = Math.max(audio.currentTime - 15, 0);
        });

        forwardBtn.addEventListener('click', () => {
            audio.currentTime = Math.min(audio.currentTime + 15, audio.duration);
        });

        volumeBar.addEventListener('input', () => {
            audio.volume = volumeBar.value;
        });
    </script>
</body>
</html>
