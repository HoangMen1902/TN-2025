<div class="add-to-cart-responsive z-10">
    <div class="button-wrapper w-fit flex lg:hidden justify-between items-center bg-white">
        <div class="quantity-container border-r-1 border-r-neutral-300">
            <button class="btn minus bg-white hover:bg-white text-gray-400">-</button>
            <input type="number" id="quantity" value="1" min="1" class="font-bold" form="addToCart" />
            <button class="btn plus bg-white hover:bg-white text-gray-400">+</button>
        </div>
        <button class="font-bold h-max  rounded-lg"  name="add-to-cart" value="" style="color: #C92127">
            Thêm vào giỏ hàng</button>
        <button class="font-bold  rounded-lg text-white h-full buy-now-btn" name="checkout" value="" form="addToCart">
            Mua ngay
        </button>
                                              <livewire:detailmodule::components.product-preview-modal :data="$data">
                    </livewire:detailmodule::components.product-preview-modal>
    </div>

</div>