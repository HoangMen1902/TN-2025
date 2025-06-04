<form wire:submit.prevent="sendResetCode" novalidate>
    <div class="mb-6">
        <label for="email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-800">
            Nhập email của bạn
        </label>

        <input 
            type="email" 
            id="email" 
            wire:model.defer="email"
            required
            autofocus
            placeholder="you@example.com"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 
                focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm
                @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
            aria-describedby="email-error"
        >

        @error('email')
            <p class="mt-2 text-red-600 text-sm" id="email-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" 
        wire:loading.attr="disabled"
        wire:target="sendResetCode"
        class="w-full py-4 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 
        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition 
        duration-300">
        <span wire:loading.remove wire:target="sendResetCode">Gửi liên kết khôi phục</span>
        <span wire:loading wire:target="sendResetCode">Đang gửi...</span>
    </button>
</form>
