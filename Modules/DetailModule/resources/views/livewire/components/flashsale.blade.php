<div>
@if ($data)
<div class=" flex items-center justify-between flashsale px-6 py-3 rounded-md max-w-full">
    <div class="flex items-center gap-3 z-10">
        <div class="shrink-0 bg-white rounded-md px-4 py-1">
            <img src="{{ asset('assets/images/flashsale.png') }}"
                 alt="Flash Sale"
                 class="object-contain"
                 style="height: 30px; width: auto;" />
        </div>

        <div id="countdown" class="flex gap-1 text-white text-sm font-bold select-none">
            <div class="bg-black/30 px-2 py-1 rounded">{{ '00' }}</div> :
            <div class="bg-black/30 px-2 py-1 rounded">{{ '00' }}</div> :
            <div class="bg-black/30 px-2 py-1 rounded">{{ '00' }}</div>
        </div>
    </div>

    {{-- <div class="flex-1 ml-6">
        <div class="w-full bg-white/30 rounded-full h-2 relative overflow-hidden">
            <div id="progress-bar"
                 class="bg-yellow-400 h-2 rounded-full transition-all duration-300 ease-in-out"
                 style="width: 0%"></div>
        </div>
    </div> --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            
        }, 200);
        const endTime = new Date(@json($data['endTime']));
        const sold = {{ $data['sold'] }};
        const total = {{ $data['quantity'] }};
        // const progressBar = document.getElementById('progress-bar');

        console.log(endTime, sold, total);
        
        function updateCountdown() {
            const now = new Date();
            let diff = Math.floor((endTime - now) / 1000);
            if (diff < 0) diff = 0;

            const hours = String(Math.floor(diff / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
            const seconds = String(diff % 60).padStart(2, '0');

            const countdownElements = document.querySelectorAll('#countdown div');
            countdownElements[0].textContent = hours;
            countdownElements[1].textContent = minutes;
            countdownElements[2].textContent = seconds;

            if (diff === 0) {
                clearInterval(timer);
                document.querySelector('.flashsale')?.classList.add('hidden');
            }
        }

        // const percent = total > 0 ? Math.min(100, Math.round((sold / total) * 100)) : 0;
        // progressBar.style.width = percent + '%';

        updateCountdown();
        const timer = setInterval(updateCountdown, 1000);
    });
</script>
@endif
</div>