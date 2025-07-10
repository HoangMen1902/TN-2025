<div class="price flex items-center pt-[8px]">
    <div class="main-price text-[32px]" style="font-weight:600;color: #C92127" wire:loading>
        <span>Đang kiểm tra giá..</span>
    </div>
    <div class="main-price text-[32px]" style="font-weight:600;color: #C92127" wire:loading.remove>
        <span>{{ number_format($sale_price, 0, ',', '.') }} đ</span>
    </div>

    @if ($price > $sale_price)
        <div class="old-price line-through text-sm mr-[8px] ml-[8px]" style="color: #888888" wire:loading.remove>
            {{ number_format($price, 0, ',', '.') }} đ
        </div>
        <div wire:loading.remove
            class="discount-percent font-bold text-white flex items-center justify-center py-[4px] px-[2px] bg-red-500 rounded {{$sale_percent <= 0 ? 'hidden' : ''}}">
            {{ '-' . round($sale_percent, 0) . '%' }}
        </div>
    @endif
</div>