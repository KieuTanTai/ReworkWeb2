<?php
function renderDetailProduct()
{
     ?>
     <!-- detail product -->
     <div id="detail-content" class="root-session-content grid-col col-l-12 col-m-12 col-s-12 no-gutter disable">
          <section class="detail-product-container grid-col col-l-12 col-m-12 col-s-12 no-gutter margin-bottom-16">
               <div class="detail-block">
                    <div class="block-product grid-col col-l-3 col-m-12 col-s-12">
                         <div class="product-image">
                              <div class="flex justify-center">
                                   <img src="" alt="">
                              </div>
                              <div class="sale-off font-bold">Hết hàng</div>
                         </div>
                         <div class="sale-label"></div>
                    </div>

                    <div class="grid-col col-l-5 col-m-12 col-s-12">
                         <div>
                              <div class="product-title">
                                   <h1 class="font-size-26 capitalize font-light"></h1>
                                   <div class="product-id margin-y-12 font-size-14 opacity-0-8"></div>
                              </div>
                              <div class="block-product-price margin-bottom-12">
                                   <span class="price new-price font-bold padding-right-8 font-size-26"></span>
                                   <del class="price old-price font-size-26"></del>
                              </div>

                              <div class="product-selector margin-top-16">
                                   <div class="margin-bottom-8">
                                        <div class="margin-bottom-8">
                                             <p class="font-size-14 font-bold margin-bottom-8">Chọn màu sắc</p>
                                        </div>
                                        <div class="color-options grid-col col-l-10 text-center col-m-12 col-s-12 no-gutter flex">
                                             
                                        </div>
                                   </div>

                                   <div>
                                        <div class="margin-bottom-8">
                                             <p class="font-size-14 font-bold margin-bottom-8">Chọn dung lượng</p>
                                        </div>
                                        <div class="storage-options grid-col col-l-6 col-m-12 col-s-12 no-gutter margin-bottom-12 margin-top-12 flex gap-12 text-center">
                                        </div>
                                   </div>

                                   

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
                                        <div class="add-to-cart button margin-bottom-8">thêm vào giỏ hàng</div>
                                   </div>
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

                    <div class="grid-col col-l-4">
                         <h3 class="">Thông số kỹ thuật</h3>
                         <div id="some-product-detail">
                              
                         </div>
                         <button onclick="showDetails()"
                              class="product-more-detail button bg-main-color text-white padding-12 margin-top-12">Hiển thị
                              chi tiết</button>
                    </div>

                    <!-- Overlay container -->
                    <div id="overlay-more-detail" class="overlay">

                    </div>
                    <script>
                         function showDetails() {
                              document.getElementById('overlay-more-detail').style.display = 'flex';
                              document.getElementById('overlay-more-detail').style.justifyContent = 'center';
                              document.getElementById('overlay-more-detail').style.alignItems = 'center';
                         }

                         function closeOverlay() {
                              document.getElementById('overlay-more-detail').style.display = 'none';
                         }
                    </script>
               </div>
          </section>
          <!-- show product same tags -->
          <section id="product-like-container" class="container grid-col col-l-12 col-m-12 col-s-12 no-gutter">
               <div class="category-tab">
                    <div class="list-title margin-bottom-12 padding-top-12 padding-bottom-12">
                         <strong class="font-size-20 padding-left-12">sản phẩm liên quan</strong>
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