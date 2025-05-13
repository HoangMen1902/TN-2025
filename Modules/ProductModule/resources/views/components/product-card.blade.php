<div class="product">
    <div class="product__viewport">
        <!-- Gán class product-grid để áp dụng lưới 5 cột -->
        <div class="products__container product-grid">

            <?php for ($i = 1; $i <= 10; $i++): ?>
            
            <div class="product-card mt-4">
    <div style="cursor:pointer" onclick="window.location.href='/product-detail/<?= $i ?>';">
        <img src="https://cdn1.fahasa.com/media/catalog/product/u/n/untitled-1-2_1.jpg"
             alt="Sản phẩm <?= $i ?>" class="embla__slide__background block w-full h-full">
    </div>
    <div class="flex items-center mt-2.5 mb-5">
        <div class="flex items-center space-x-1">
            <?php for ($j = 1; $j <= 5; $j++): ?>
            <svg class="w-4 h-4 <?= $j <= 4 ? 'text-yellow-300' : 'text-gray-200' ?>"
                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
            </svg>
            <?php endfor; ?>
        </div>
        <span
            class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-sm ms-3">5.0</span>
    </div>
    <div class="title">Sản phẩm <?= $i ?></div>
    <div class="price-button-container">
        <div class="price">
            <?= number_format($i * 100000) ?>đ
        </div>
        <form action="/add-to-cart" method="post">
            <input type="hidden" name="method" value="POST">
            <input type="hidden" name="id" value="<?= $i ?>">
            <button name="add-to-cart" class="select-button">Mua ngay</button>
        </form>
    </div>
</div>
            <?php endfor; ?>

        </div>
    </div>
</div>
