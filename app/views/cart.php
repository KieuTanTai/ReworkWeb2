<?php
function renderCart()
{
     ?>
     <section id="cart-content" class="root-session-content cart grid-col col-l-12 col-m-12 col-s-12 margin-y-12 disable">
          <div class="cart-title padding-bottom-8">
               <span class="uppercase font-size-20">giỏ hàng</span>
               <span class="cart-count font-size-16 padding-left-8">(0 sản phẩm)</span>
          </div>

          <div class="cart-ui-content">
               <?php
               if (true)
                    renderBlankCart();
               else
                    renderCartWidthProduct();
               ?>
          </div>
     </section>
     <?php
}
?>

<?php
function renderBlankCart()
{
     ?>
     <section id="blank-cart" class="margin-y-12 cart-ui active">
          <img src="./assets/images/BackGrounds/kettle-desaturated._CB445243794_.svg" />
          <span class="font-size-16 padding-left-16 margin-y-12">Không có sản phẩm
               nào trong giỏ hàng. Quay lại
               cửa hàng
               để tiếp tục mua sắm.</span>
          <section class="flex justify-center align-center font-bold capitalize margin-y-12">
               <a href=" " target="_blank" class="category-btn button">trang chủ</a>
          </section>
     </section>
     <?php
}
?>

<?php
function renderCartWidthProduct()
{ ?>
     <section id="cart" class="active">
          <div class="grid-cols col-l-8 col-m-12 col-s-12 no-gutter">
               <div class="header-cart-content cart-ui">
                    <input type="checkbox" name="selection-item" id="selection-item"
                         class="grid-col col-l-1 col-m-1 col-s-1" />
                    <div class="grid-col col-l-6 col-m-11 col-s-11">
                         chọn sản phẩm
                         <span class="item-count">(1 sản phẩm)</span>
                    </div>

                    <div class="grid-col col-l-2 s-m-hidden text-center">
                         số lượng
                    </div>
                    <div class="grid-col col-l-2 s-m-hidden text-center">
                         thành tiền
                    </div>
                    <div class="grid-col col-l-1 s-m-hidden text-center">
                         xóa
                    </div>
               </div>

               <div class="list-carts cart-ui margin-top-16">
                    <div class="block-product">
                         <input type="checkbox" name="select-block-product" id="block-product-1"
                              class="grid-col col-l-1 col-m-1 col-s-1" />
                         <div class="product-cart grid-col col-l-1 col-m-1 col-s-1 no-gutter full-width">
                              <img src="" alt="" />
                         </div>
                         <div class="grid-col col-l-10 col-m-10 col-s-10 no-gutter flex align-center">
                              <div class="info-product-cart padding-left-8 grid-col col-l-6 col-m-12 col-s-12">
                                   <p class="font-bold capitalize margin-bottom-16">
                                   </p>
                                   <div class="block-product-price">
                                        <span class="new-price font-bold padding-right-8 price"></span>
                                        <del class="price old-price"></del>
                                   </div>
                              </div>
                              <div class="number-product-cart grid-col col-l-2 col-m-10 col-s-10 no-gutter">
                                   <input type="number" name="quantity-cart" id="update_12320" value="1" min="1" max="2"
                                        class="quantity-cart" />
                              </div>
                              <div class="price-per-item price font-bold grid-col col-l-3 s-m-hidden no-gutter text-center">
                              </div>
                              <div class="rm-cart-btn col-l col-l-1 col-m-2 col-s-2 flex justify-center">
                                   <div>
                                        <i class="fa-solid fa-trash fa-lg" style="color: var(--primary-dark)"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <div class="grid-col col-l-4 col-m-12 col-s-12">
               <div class="promotion-block-content cart-ui">
                    <div class="shop-voucher margin-bottom-12">
                         <p class="font-bold margin-bottom-8 capitalize">
                              Shop voucher
                         </p>
                         <form action="" method="post" class="flex justify-space-between">
                              <input type="text" name="voucher-code" id="voucher-code" placeholder="nhập mã khuyến mãi" />
                              <button type="submit" class="font-size-14 button">
                                   sử dụng
                              </button>
                         </form>
                    </div>
                    <div class="payment-methods margin-bottom-12">
                         <h4 class="capitalize margin-bottom-8">
                              phương thức thanh toán
                         </h4>
                         <ul>
                              <li class="flex align-center">
                                   <input type="radio" name="payment-option" id="payment-option-1" class="margin-right-8"
                                        checked />
                                   <img src="./assets/images/icons/payment_methods/ico_cashondelivery.svg" />
                                   <p class="padding-left-8 font-size-14">
                                        thanh toán khi nhận hàng (COD)
                                   </p>
                              </li>
                              <li class="flex align-center">
                                   <input type="radio" name="payment-option" id="payment-option-2" class="margin-right-8" />
                                   <img src="./assets/images/icons/payment_methods/ico_momopay.svg" />
                                   <p class="padding-left-8 font-size-14">Ví Momo</p>
                                   <div class="qr-code-container" id="qr-code-momo"
                                        style="display: none; text-align: center; margin-top: 10px;">
                                        <img src="./assets/images/ads/mm.jpg" alt="QR Code Momo"
                                             style="width: 12em; height: auto;" />
                                   </div>
                              </li>
                              <li class="flex align-center">
                                   <input type="radio" name="payment-option" id="payment-option-3" class="margin-right-8" />
                                   <img src="./assets/images/icons/payment_methods/ico_vnpayatm.svg" />
                                   <p class="padding-left-8 font-size-14">
                                        ATM / Internet Banking
                                   </p>
                                   <div class="qr-code-container" id="qr-code-atm"
                                        style="display: none; text-align: center; margin-top: 10px;">
                                        <img src="./assets/images/ads/bank.jpg" alt="QR Code ATM"
                                             style="width: 12em; height: auto;" />
                                   </div>
                              </li>
                         </ul>
                    </div>

                    <div class="order-address margin-bottom-12">
                         <h4 class="capitalize margin-bottom-8">địa chỉ nhận hàng
                         </h4>
                         <div class="margin-bottom-8">
                              <input type="checkbox" name="selection-address" id="selection-address"
                                   class="capitalize margin-bottom-8" />
                              <label class="font-size-14"> địa chỉ mặc định</label>
                         </div>
                         <ul>
                              <li class="flex flex-direction-y justify-center">
                                   <input type="text" name="user-address" id="user-address" class="margin-right-8 padding-8"
                                        value="" placeholder="địa chỉ nhận hàng" />
                              </li>
                         </ul>
                    </div>


                    <div class="order-summary">
                         <h4 class="capitalize margin-bottom-8">
                              chi tiết thanh toán
                         </h4>
                         <div>
                              <p class="font-size-14">thành tiền</p>
                              <span class="price prices font-size-14"></span>
                         </div>
                         <div>
                              <p class="font-size-14">tổng tiền phí vận chuyển</p>
                              <span class="price shipping-fee font-size-14"></span>
                         </div>
                         <div>
                              <p class="font-size-14">giảm giá phí vận chuyển</p>
                              <span class="price shipping-discount font-size-14"></span>
                         </div>
                         <div>
                              <p class="font-size-14">voucher giảm giá</p>
                              <span class="price voucher-discount font-size-14"></span>
                         </div>
                         <div>
                              <p class="font-bold">Tổng Số Tiền</p>
                              <span class="price total-price font-bold"></span>
                         </div>

                         <button type="submit" class="checkout-btn button margin-top-12">
                              <p class="uppercase font-size-16 font-bold">
                                   Đặt hàng
                              </p>
                         </button>
                    </div>
               </div>

               <div class="user-cart-note cart-ui margin-top-16">
                    <form action="" method="post">
                         <label for="user-note" class="capitalize font-bold">Ghi
                              Chú</label>
                         <textarea name="user-note" id="user-note"></textarea>
                    </form>
               </div>
          </div>
     </section>
<?php
}
?>