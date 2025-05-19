<div class="price flex items-center pt-[8px]">
    <div class="main-price text-[32px]" style="color: #C92127">
        <span>{{number_format($sale_price, 0, ',', '.')}} đ</span>
    </div>
    <div class="old-price line-through text-sm mr-[8px] ml-[8px]" style="color: #888888">
        {{number_format($price, 0, ',', '.')}} đ
    </div>
    <div class="discount-percent font-bold text-white flex items-center justify-center py-[4x] px-[2px]">
        {{ '-' . round($sale_percent, 2) . '%' }}
    </div>
</div>