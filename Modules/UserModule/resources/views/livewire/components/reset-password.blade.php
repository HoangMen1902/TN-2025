<div>
    <form wire:submit.prevent="sendResetCode" class="space-y-4">
        <div>
            <input type="email" wire:model="email"
                class="w-full px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]"
                placeholder="Email của bạn"/>

            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full py-3 bg-[#5cb8b2] text-white font-semibold rounded-full hover:bg-[#469a95] transition">
            Gửi mail khôi phục
        </button>
    </form>
</div>
