  <div class="bg-gray-100 font-sans antialiased flex items-center justify-center min-h-screen py-8">

      <div class="container mx-auto px-4 max-w-screen-xl lg:max-w-[1200px] mt-8">

          <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Tiến Trình Hạng Thành Viên Của Bạn</h1>

          <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-6 mb-8">
              {{-- Hạng hiện tại --}}
              <div class="flex items-center justify-between bg-purple-50 p-4 rounded-md mb-6 shadow-sm">
                  <div class="flex items-center">
                      <div class="bg-purple-200 p-2 rounded-full mr-3">
                          <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.049 2.194A2.052 2.052 0 0113.111 2h.001a2.052 2.052 0 012.062 2.194l.872 3.655a1.026 1.026 0 00.742.742l3.655.872a2.052 2.052 0 010 4.124l-3.655.872a1.026 1.026 0 00-.742.742l-.872 3.655a2.052 2.052 0 01-4.124 0l-.872-3.655a1.026 1.026 0 00-.742-.742l-3.655-.872a2.052 2.052 0 010-4.124l3.655-.872a1.026 1.026 0 00.742-.742l.872-3.655z"></path>
                          </svg>
                      </div>
                      <div>
                          <p class="text-sm text-gray-600 font-medium">Hạng hiện tại:</p>
                          <p class="text-xl font-bold text-purple-700">{{ $currentMembershipName }}</p>
                      </div>
                  </div>
              </div>

              {{-- Thông tin điểm & hạng tiếp theo --}}
              <div class="bg-blue-50 p-4 rounded-md mb-6 shadow-sm">
                  <div class="flex justify-between items-center mb-2">
                      <span class="text-sm font-medium text-gray-600">Điểm hiện tại của bạn:</span>
                      <span class="text-lg font-bold text-blue-700">{{ number_format($currentPoints) }} điểm</span>
                  </div>
                  <div class="flex justify-between items-center mb-2">
                      <span class="text-sm font-medium text-gray-600">Điểm có thể tiêu còn lại:</span>
                      <span class="text-lg font-bold text-blue-700">{{ number_format($redeemable_points) }} điểm</span>
                  </div>
                  <div class="flex justify-between items-center">
                      <span class="text-sm font-medium text-gray-600">Để lên hạng {{ $nextMembershipName }} cần thêm:</span>
                      <span class="text-lg font-bold text-blue-700">{{ number_format($nextMembershipPoints) }} điểm</span>
                  </div>
                  <p class="text-xs text-gray-500 mt-2 text-right">
                      Cần thêm <span class="font-semibold text-blue-600">{{ number_format($pointsToNext) }} điểm</span> nữa
                  </p>
              </div>

              {{-- Tiến trình phần trăm --}}
              <div class="mb-6">
                  <p class="text-sm font-medium text-gray-700 mb-2">Tiến trình lên hạng:</p>
                  <div class="w-full bg-gray-200 rounded-full h-4 relative overflow-hidden">
                      <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-full rounded-full"
                          style="width: {{ $progressPercent }}%"></div>
                  </div>
                  <div class="flex justify-between text-xs text-gray-500 mt-1">
                      <span></span>
                      <span>{{ $nextMembershipName }}</span>
                  </div>
              </div>
          </div>


          <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Voucher Đặc Quyền Theo Hạng Thành Viên</h2>

          <div class="space-y-8">
              @foreach ($vouchersByTier as $tier)
              <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-6">
                  <h3 class="text-xl font-bold mb-4 flex items-center text-gray-700">
                      {{ $tier['name'] }}
                      <span class="text-sm text-gray-500 ml-2">({{ number_format($tier['points']) }} điểm)</span>
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      @php
                      $isTierLocked = $currentPoints < $tier['points'];
                          @endphp

                          @foreach ($tier['vouchers'] as $voucher)
                          {{-- Thêm class 'opacity-60' nếu hạng bị khóa --}}
                          <div class="relative flex bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden {{ $isTierLocked ? 'opacity-60' : '' }}">
                          <div class="flex-none w-28 p-3 bg-gray-50 flex items-center justify-center rounded-l-lg">
                              <span class="text-green-600 font-bold text-lg text-center leading-tight">
                                  {{ $voucher->voucher_type == 'amount' ? number_format($voucher->reduced_amount).'K' : $voucher->reduced_amount.'%' }}
                              </span>
                          </div>
                          <div class="relative w-5 flex-shrink-0 flex flex-col justify-between items-center py-1">
                              <div class="w-px h-full bg-gray-300 absolute left-1/2 -translate-x-1/2"></div>
                              <div class="absolute top-0 left-1/2 -translate-x-1/2 w-5 h-5 bg-gray-100 rounded-full border border-dashed border-gray-300 z-10 -mt-2.5"></div>
                              <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-5 h-5 bg-gray-100 rounded-full border border-dashed border-gray-300 z-10 -mb-2.5"></div>
                          </div>
                          <div class="flex-grow p-3 flex flex-col justify-between">
                              <div>
                                  <h4 class="font-semibold text-base text-gray-800">{{ $voucher->voucher_name }}</h4>
                                  <p class="text-xs text-gray-600 mt-1">Đơn từ {{ number_format($voucher->requirement_price) }}<span class="text-sm">đ</span></p>
                                  <p class="text-xs text-gray-500 mt-1">HSD: {{ $voucher->expired_at->format('d/m/Y') }}</p>
                              </div>
                              <div class="flex justify-end mt-2">
                                  {{-- Logic mới cho nút bấm --}}
                                  @if ($isTierLocked)
                                  <button class="bg-gray-300 text-gray-600 px-4 py-1.5 rounded-full text-sm font-medium cursor-not-allowed" disabled>
                                      Chưa đủ hạng
                                  </button>
                                  @elseif ($voucher->claimed)
                                  <button class="bg-gray-300 text-gray-600 px-4 py-1.5 rounded-full text-sm font-medium cursor-not-allowed">
                                      Đã lưu
                                  </button>
                                  @else
                                  <button wire:click="claimVoucher({{ $voucher->id }})"
                                      class="bg-green-500 text-white px-4 py-1.5 rounded-full text-sm font-medium hover:bg-green-600 transition duration-300 shadow-md">
                                      Nhận Voucher
                                  </button>
                                  @endif
                              </div>

                          </div>
                  </div>
                  @endforeach
              </div>
          </div>
          @endforeach
      </div>

  </div>

  </div>