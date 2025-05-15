<div class="">
    <div class="product__viewport">
        <!-- Gán class product-grid để áp dụng lưới 5 cột -->
        <div class=" grid grid-cols-3 gap-3 gap-[3px]">

            <?php for ($i = 1; $i <= 10; $i++): ?>

            <div
                class="w-full overflow-hidden relative flex items-center justify-center h-[390px] rounded-[10px] aspect-[16/9] mt-4 border border-gray-300 group transition-all duration-500 ease-in-out transform hover:scale-[1.02] hover:shadow-lg">
                <div class="absolute bottom-12    right-4 z-10 
                             opacity-0 translate-y-4 
                             group-hover:opacity-100 group-hover:translate-y-0 
                             transition-all duration-500 ease-in-out">
                    <form action="/add-to-cart" method="post">
                        <input type="hidden" name="id" value="<?= $i ?>">
                        <button class="bb-primary text-white px-4 py-2 rounded-3xl shadow-md
                             border border-transparent
                             transition duration-300 ease-in-out
                             hover:bg-transparent hover:text-blue-600
                             hover:border-blue-600 hover:shadow-lg
                             hover:scale-105
                             hover:bg-white
                             ">
                            Mua ngay
                        </button>
                    </form>
                </div>

                <!-- Ảnh -->
                <div style="cursor:pointer" onclick="window.location.href='/product-detail/<?= $i ?>';"
                    class="product__slide__image border-b border-gray-300 overflow-hidden">
                    <img src="	https://cdn1.fahasa.com/media/catalog/product/b/_/b_a-tr_c-manifest_1_2_1_2.jpg"
                        alt="Sản phẩm <?= $i ?>" width="100px"
                        class="embla__slide__background block w-full h-full transition-transform duration-500 ease-in-out group-hover:scale-105">
                </div>

                <!-- Thông tin -->
                <div class="product__slide__number flex flex-wrap content-around p-2">
                    <div>
                        <p class="text-base line-clamp-2"><?= $i ?> tung tung tung tung tung tung tung tung tung tung
                            sahur tung</p>
                    </div>
                    <div class="flex items-center space-x-1 w-[100%]">
                        <?php    for ($j = 1; $j <= 5; $j++): ?>
                        <svg class="w-4 h-4 <?= $j <= 4 ? 'text-yellow-300' : 'text-gray-200' ?>"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path
                                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                        </svg>
                        <?php    endfor; ?>
                        <span class="text-xs">20</span>
                    </div>
                    <div>category</div>
                    <div class="product__slide__number__imgs">
                        <p class="product__slide__number__imgs__price"><?= number_format($i * 100000000) ?>đ</p>
                        <span
                            class="product__slide__number__imgs__price-sale ml-1 opacity-50"><?= number_format($i * 100000000) ?>đ</span>
                    </div>
                </div>
            </div>
            <?php endfor; ?>

        </div>
    </div>
</div>