<div class="flex flex-col items-center space-y-6 p-6 bg-gray-50 dark:bg-gray-800 rounded-xl">
    @if ($qrDataUrl)
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700 transition duration-300">
        <img src="{{ $qrDataUrl }}"
            alt="QR Code"
            class="mx-auto w-48 h-48 md:w-56 md:h-56 object-contain rounded-lg transition duration-300 transform hover:scale-105" />
    </div>
    @else
    <div class="p-4 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-700">
        <p class="text-red-700 dark:text-red-300 font-medium text-center">
            Không tạo được mã QR
        </p>
    </div>
    @endif

</div>