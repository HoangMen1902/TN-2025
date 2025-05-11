<x-layouts.layout>
    <section>
        <div class="payment__section">
            <div class="payment__section__left">
                <div class="payment__section__left-text">
                    <div class="payment__section__left-ttlh">
                        <h3>Thông tin liên hệ</h3>
                    </div>
                    <form action="/order" method="post" id="paymentForm">
                        <input type="hidden" name="method" value="POST">
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name='email' value='' placeholder="Email">
                        </div>


                        <div class="payment__section__container">
                            <input class="payment__section__checkbox" type="checkbox">
                            <label for="">Gửi cho tôi tin tức và ưu đãi qua email</label>
                        </div>

                        <h3 class="payment__section__left-ttlh ">Giao hàng</h3>

                        <div class="option">
                            <input type="radio" id="van_chuyen" name="shipping" value="van_chuyen" checked />
                            <label for="van_chuyen">
                                <span class="text">Vận chuyển</span>
                                <span class="icon">&#128663;</span>
                            </label>
                        </div>

                        <div class="option">
                            <input type="radio" id="nhan_tai_cua_hang" name="shipping" value="nhan_tai_cua_hang" />
                            <label for="nhan_tai_cua_hang">
                                <span class="text">Nhận hàng tại cửa hàng</span>
                                <span class="icon">&#127970;</span>
                            </label>
                        </div>

                        <div class="shipping-details">
                            <div class="name">
                                <input class="cnvc" type="text" placeholder="Tên" name="name" value="" />
                            </div>
     
                            <input class="cnvc" type="text" placeholder="Điện thoại" name="phone" value="" />
                            <div class="checkbox">
                                <input  type="checkbox" id="save-info" />
                                <label for="save-info">Lưu lại thông tin</label>
                            </div>
                        </div>

                        <div class="shipping-methods">
                            <h3>Phương thức vận chuyển</h3>
                            <div class="method">
                                <input type="radio" id="grab" name="delivery-method" />
                                <label for="grab">Khách tự book Grab (TP.HCM)</label>
                            </div>
                            <div class="method">
                                <input type="radio" id="free-hcm" name="delivery-method" checked />
                                <label for="free-hcm">Miễn phí HCM (trong ngày)</label>
                            </div>
                            <div class="method">
                                <input type="radio" id="free-national" name="delivery-method" />
                                <label for="free-national">Miễn phí toàn quốc (2 ~ 7 ngày)</label>
                            </div>
                        </div>
                        <button type="submit" class="button_thanhtoan">THANH TOÁN NGAY</button>

                    </form>
                </div>
            </div>

            <div class="payment__section__right">
                <div class="payment__section__right-ttlh">

                        <div class="payment__section__container">

                            <div class="payment__section__right-img" style="position: relative;">
                                <div class="payment__section__right-circle">1</div>
                                <img src="" style="object-fit:cover; width:100%; height:100%">
                            </div>
                            <div class="payment__section__right-description dlnonene ">
                                <p class="clamp-text" ></p>
                                <p></p>
                            </div>
                            <div class="payment__section__right-pcire">
                                <p> ?></p>
                            </div>
                        </div>

                    <input type="hidden" name="price" value="" form="paymentForm">


                    <div class="order-summary">
                        <div class="discount">
                            <input class="cnvc" type="text" placeholder="Mã giảm giá hoặc thẻ quà tặng" />
                            <button>Áp dụng</button>
                        </div>
                        <div class="totals">
                            <p>Vận chuyển: MIỄN PHÍ</p>
                            <h3>Tổng:  ₫</h3>
                            <p>Phương thức thanh toán: Tiền mặt</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</x-layouts.layout>