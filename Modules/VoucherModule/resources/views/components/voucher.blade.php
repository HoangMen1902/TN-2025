<div class="bg-gray-100 font-sans antialiased py-8">
    <div class="container mx-auto px-4 max-w-screen-xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Mã Giảm Giá</h1>

        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('voucher') }}" class="bg-white p-4 rounded-lg shadow-sm mb-6 flex items-center space-x-4">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                placeholder="Tìm kiếm mã giảm giá..."
                class="flex-grow p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                Tìm kiếm
            </button>
        </form>

        <!-- Danh sách voucher -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($vouchers as $voucher)
            @php
            $colors = [
            'point' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
            'manual' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
            ];
            $colorSet = $colors[$voucher->issued_by] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
            $discount = $voucher->voucher_type === 'percent'
            ? $voucher->reduced_amount . '%'
            : number_format($voucher->reduced_amount, 0) . 'K';
            $expiredSoon = \Carbon\Carbon::parse($voucher->expired_at)->diffInDays(now()) <= 3;
                $isSaved=$voucher->usedByCurrentUser ?? false;
                @endphp

                <div class="relative flex bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="flex-none w-32 p-4 {{ $colorSet['bg'] }} flex items-center justify-center rounded-l-lg">
                        <span class="{{ $colorSet['text'] }} font-bold text-xl text-center leading-tight">
                            Giảm <br> {{ $discount }}
                        </span>
                    </div>

                    <div class="relative w-5 flex-none bg-gray-100 flex flex-col justify-around items-center">
                        <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-5 h-5 bg-gray-100 rounded-full border border-dashed border-gray-300 z-10"></div>
                        <div class="absolute h-full w-px bg-repeat-y bg-transparent"
                            style="background-image: linear-gradient(to bottom, #d1d5db 50%, transparent 50%); background-size: 1px 10px;">
                        </div>
                        <div class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 w-5 h-5 bg-gray-100 rounded-full border border-dashed border-gray-300 z-10"></div>
                    </div>

                    <div class="flex-grow p-4">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $voucher->voucher_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">Đơn từ {{ number_format($voucher->requirement_price, 0) }} VNĐ</p>
                        <p class="text-xs mt-2 {{ $expiredSoon ? 'text-red-500' : 'text-gray-500' }}">
                            HSD: {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d/m/Y') }}
                        </p>

                        <div class="flex justify-end mt-3">
                            @if ($voucher->quantity <= 0)
                                {{-- Trường hợp hết số lượng --}}
                                <button disabled
                                class="bg-gray-400 text-white px-5 py-2 rounded-full font-medium cursor-not-allowed">
                                Hết số lượng
                                </button>
                                @elseif ($voucher->usedByCurrentUser)
                                {{-- Trường hợp đã lưu hoặc đã đổi --}}
                                <button disabled
                                    class="bg-gray-400 text-white px-5 py-2 rounded-full font-medium cursor-not-allowed">
                                    {{ $voucher->issued_by === 'point' ? 'Đã đổi' : 'Đã lưu' }}
                                </button>
                                @else
                                {{-- Trường hợp có thể đổi hoặc lưu --}}
                                @if ($voucher->issued_by === 'point')
                                <form method="POST" action="{{ route('voucher.redeem') }}">
                                    @csrf
                                    <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                    <button type="submit"
                                        class="bg-orange-500 text-white px-5 py-2 rounded-full font-medium hover:bg-orange-600 transition duration-300 shadow-md">
                                        Đổi {{ $voucher->required_points }} điểm
                                    </button>
                                </form>
                                @elseif ($voucher->issued_by === 'manual')
                                <form method="POST" action="{{ route('voucher.store') }}">
                                    @csrf
                                    <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                    <button type="submit"
                                        class="bg-green-500 text-white px-5 py-2 rounded-full font-medium hover:bg-green-600 transition duration-300 shadow-md">
                                        Lưu
                                    </button>
                                </form>
                                @endif
                                @endif
                        </div>

                    </div>
                </div>
                @endforeach
        </div>
    </div>
</div>