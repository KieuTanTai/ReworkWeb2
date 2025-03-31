<?php
function renderDetailProduct()
{
     ?>
     <!-- detail product -->
     <div id="detail-content" class="grid-col col-l-12 col-m-12 col-s-12">
          <section class="detail-product-container grid-col col-l-12 col-m-12 col-s-12 margin-bottom-16">
               <div class="detail-block">
                    <div class="block-product grid-col col-l-5 col-m-12 col-s-12">
                         <div class="product-image">
                              <div class="flex justify-center">
                                   <img src="" alt="">
                              </div>
                              <div class="sale-off font-bold">Hết hàng</div>
                         </div>
                         <div class="sale-label"></div>
                    </div>

                    <div class="grid-col col-l-7 col-m-12 col-s-12">
                         <div>
                              <div class="product-title">
                                   <h1 class="font-size-26 capitalize font-light"></h1>
                                   <div class="product-id margin-y-12 font-size-14 opacity-0-8">
                                   </div>
                              </div>
                              <div class="block-product-price margin-bottom-12">
                                   <span class="price new-price font-bold padding-right-8 font-size-26"></span>
                                   <del class="price old-price font-size-26"></del>
                              </div>
                              <div class="product-info grid-col col-l-12 col-m-12 col-s-12 no-gutter flex margin-bottom-12">
                                   <div class="grid-col col-l-6 col-m-6 col-s-12 no-gutter">
                                        <strong>Tác giả</strong>
                                        <div class="b-author"></div>
                                   </div>
                                   <div class="grid-col col-l-6 col-m-6 col-s-12 no-gutter">
                                        <strong>Năm xuất bản</strong>
                                        <div class="b-release opacity-0-8"></div>
                                   </div>
                                   <div class="grid-col col-l-6 col-m-6 col-s-12 no-gutter">
                                        <strong>Hình thức</strong>
                                        <div class="b-format opacity-0-8"></div>
                                   </div>
                                   <div class="grid-col col-l-6 col-m-6 col-s-12 no-gutter">
                                        <strong>Kích thước</strong>
                                        <div class="b-size opacity-0-8"></div>
                                   </div>
                              </div>

                              <div class="short-desc">
                                   <strong class="font-size-14">nội dung:</strong>
                                   <div class="font-size-14 opacity-0-8"></div>
                              </div>

                              <div class="product-selector margin-top-16">
                                   <label>
                                        <div class="margin-bottom-8">
                                             <p class="font-size-14 font-bold margin-bottom-8">
                                                  phiên bản</p>
                                        </div>
                                        <div class="margin-bottom-12 grid-col col-l-4 col-m-3 col-s-5 no-gutter">
                                             <select name="product-selector" id="product-selector-options">
                                                  <option value="special">bản đặc biệt</option>
                                                  <option value="normal" selected>bản thường
                                                  </option>
                                             </select>
                                        </div>
                                   </label>
                                   <div class="quantity-box margin-bottom-12 grid-col col-l-2-4 col-m-3 col-s-5 no-gutter">
                                        <input type="button" value="-" class="reduce">
                                        <input type="text" name="quantity" id="quantity" placeholder="1" value="1"
                                             class="quantity-cart">
                                        <input type="button" value="+" class="increase">
                                   </div>
                                   <div
                                        class="grid-col col-l-8 col-m-6 col-s-12 no-gutter flex justify-space-between margin-bottom-12">
                                        <div class="buy-btn button margin-bottom-8">mua ngay
                                        </div>
                                        <div class="add-to-cart button margin-bottom-8">thêm
                                             vào giỏ hàng</div>
                                   </div>
                              </div>
                              <div class="product-tags margin-top-16">
                                   <div class="flex font-size-14">
                                        <strong class="margin-right-8">tag:</strong>
                                        <p></p>
                                   </div>
                                   <div class="flex font-size-14">
                                        <strong class="margin-right-8">danh mục:</strong>
                                        <p></p>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </section>

          <!-- show books same author -->
          <section id="same-author-container" class="container grid-col col-l-12 col-m-12 col-s-12">
               <div class="category-tab">
                    <div class="list-title margin-bottom-12 padding-top-12 padding-bottom-12">
                         <strong class="font-size-20">sách cùng tác giả</strong>
                    </div>

                    <!-- container for products -->
                    <div class="product-container flex full-height align-center justify-start">
                    </div>
               </div>
          </section>

          <!-- show books same tags -->
          <section id="product-like-container" class="container grid-col col-l-12 col-m-12 col-s-12">
               <div class="category-tab">
                    <div class="list-title margin-bottom-12 padding-top-12 padding-bottom-12">
                         <strong class="font-size-20">sản phẩm liên quan</strong>
                    </div>

                    <!-- container for products -->
                    <div class="product-container flex full-height align-center justify-start">
                    </div>
               </div>
          </section>
     </div>
     <?php
}
?>

<!-- logic for setup container for detail, image is here! -->