<?php
function loadDefaultHomepage()
{ ?>
     <div class="web-content">
          <header id="header-container">
               <?php renderHeader();?>
          </header>

          <main id="main-container">
               <?php
               renderSubHeader();
               ?>
               <!-- html for sub header -->

               <div id="main-content">
                    <article id="scroll-top" class="s-m-hidden">
                         <img src="./assets/images/icons/web_logo/manga-icon-backtotop_550x700_1.png" alt="back to top" />
                    </article>

                    <!-- main items -->
                    <section class="grid wide">
                         <div class="grid-row">
                              <!-- homepage -->
                              <div id="index-content" class="root-session-content grid-col col-l-12 col-m-12 col-s-12">
                                   <!-- banner -->
                                   <section class="banner-container flex justify-center align-center">
                                        <div class="homepage grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                             <img src="./assets/images/backgrounds/siteheader_weakestTamerLN_864x300.webp"
                                                  alt="LIGHT NOVEL WORLD" />
                                        </div>

                                        <div class="ad-banner grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                             <div class="banner-item">
                                                  <a href=" ">
                                                       <img src="./assets/images/ads/demo5.webp" alt="Demo 1" />
                                                  </a>
                                             </div>

                                             <div class="banner-item">
                                                  <a href=" ">
                                                       <img src="./assets/images/ads/demo6.webp" alt="Demo 2" />
                                                  </a>
                                             </div>

                                             <div class="banner-item">
                                                  <a href=" ">
                                                       <img src="./assets/images/ads/demo3.webp" alt="Demo 3" />
                                                  </a>
                                             </div>

                                             <div class="banner-item">
                                                  <a href=" ">
                                                       <img src="./assets/images/ads/demo4.webp" alt="Demo 4" />
                                                  </a>
                                             </div>
                                        </div>
                                   </section>

                                   <!-- service -->
                                   <section class="grid-col col-l-12 no-gutter margin-y-16 full-width">
                                        <div class="services-container flex justify-center align-center">
                                             <div class="service-content grid-col col-l-3 col-m-3 col-s-6">
                                                  <img src="./assets/images/icons/services/icon-sv1.jpg" alt="Thanh toán" />
                                                  <div class="flex-direction-y padding-left-8">
                                                       <h5 class="font-bold uppercase font-size-13">Thanh toán</h5>
                                                       <p class="font-size-13 font-light capitalize">khi nhận hàng</p>
                                                  </div>
                                             </div>

                                             <div class="service-content grid-col col-l-3 col-m-3 col-s-6">
                                                  <img src="./assets/images/icons/services/icon-sv2.jpg" alt="Quà tặng" />
                                                  <div class="flex-direction-y padding-left-8">
                                                       <h5 class="font-bold uppercase font-size-13">Quà tặng</h5>
                                                       <p class="font-size-13 font-light capitalize">Miễn phí</p>
                                                  </div>
                                             </div>

                                             <div class="service-content grid-col col-l-3 col-m-3 col-s-6">
                                                  <img src="./assets/images/icons/services/icon-sv3.jpg" alt="Bảo mật" />
                                                  <div class="flex-direction-y padding-left-8">
                                                       <h5 class="font-bold uppercase font-size-13">Bảo mật</h5>
                                                       <p class="font-size-13 font-light capitalize">Thanh toán trực
                                                            tuyến</p>
                                                  </div>
                                             </div>

                                             <div class="service-content grid-col col-l-3 col-m-3 col-s-6">
                                                  <img src="./assets/images/icons/services/icon-sv4.jpg" alt="Hỗ trợ" />
                                                  <div class="flex-direction-y padding-left-8">
                                                       <h5 class="font-bold uppercase font-size-13">Hỗ trợ</h5>
                                                       <p class="font-size-13 font-light capitalize">24/7</p>
                                                  </div>
                                             </div>
                                        </div>
                                   </section>

                                   <!-- flash-sale -->
                                   <section id="fs-container"
                                        class="container root-session-content flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                        <div class="category-tab">
                                             <div class="heading">
                                                  <span id="fs-label" class="heading-label"></span>
                                                  <span
                                                       class="fs-countdown flex justify-center align-center padding-left-8 font-bold">
                                                       <p class="s-m-hidden padding-right-8">kết thúc trong:</p>
                                                       <div class="fs-time">
                                                            <span class="fs-number">01</span>
                                                            <span>:</span>
                                                            <span class="fs-number">59</span>
                                                            <span>:</span>
                                                            <span class="fs-number">59</span>
                                                       </div>
                                                  </span>
                                             </div>

                                             <!-- container for products -->
                                             <div class="product-container"></div>

                                             <div class="nav-btn margin-inline-8 disable">
                                                  <div class="prev-btn font-size-13">
                                                       <i class="fa-solid fa-angle-left fa-lg"
                                                            style="color: var(--primary-white);"></i>
                                                  </div>
                                                  <div class="next-btn font-size-13">
                                                       <i class="fa-solid fa-angle-right fa-lg"
                                                            style="color: var(--primary-white);"></i>
                                                  </div>
                                             </div>

                                             <div
                                                  class="flex justify-center align-center font-bold capitalize margin-bottom-16">
                                                  <a href="#" target="_blank" class="category-btn button">Xem thêm</a>
                                             </div>
                                        </div>
                                   </section>

                                   <!-- RENAME ID IN HERE AND IN FILE CSS -->
                                   <!-- new phone -->
                                   <section id="new-phones-container"
                                        class="container root-session-content flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                        <div class="category-tab">
                                             <div class="heading">
                                                  <div id="new-phone-label" class="heading-label"></div>
                                                  <div class="uppercase font-bold font-size-20 padding-left-8">Mới ra mắt
                                                  </div>
                                             </div>

                                             <!-- container for products -->
                                             <div class="product-container"></div>

                                             <div
                                                  class="flex justify-center align-center font-bold capitalize margin-bottom-16">
                                                  <a href="#" class="category-btn button">Xem thêm</a>
                                             </div>
                                        </div>
                                   </section>

                                   <!-- SamSung fan   -->
                                   <section id="samsung-phone-container"
                                        class="container root-session-content flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                        <div class="category-tab">
                                             <div class="heading">
                                                  <div class="heading-label samsung-phone-label"></div>
                                                  <div class="uppercase font-bold font-size-20 padding-left-8">SamSung</div>
                                             </div>

                                             <!-- container for products -->
                                             <div class="product-container"></div>

                                             <div
                                                  class="flex justify-center align-center font-bold capitalize margin-bottom-16">
                                                  <a href="#" class="category-btn button">Xem thêm</a>
                                             </div>
                                        </div>
                                   </section>

                                   <!-- Iphone -->
                                   <section id="ip-container"
                                        class="container root-session-content flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                        <div class="category-tab">
                                             <div class="heading">
                                                  <div class="heading-label iphone-label"></div>
                                                  <div class="uppercase font-bold font-size-20 padding-left-8">Iphone
                                                  </div>
                                             </div>

                                             <!-- container for products -->
                                             <div class="product-container"></div>

                                             <div
                                                  class="flex justify-center align-center font-bold capitalize margin-bottom-16">
                                                  <a href="#" class="category-btn button">Xem thêm</a>
                                             </div>
                                        </div>
                                   </section>

                                   <!-- Other -->
                                   <section id="other-phones-container"
                                        class="container root-session-content flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                                        <div class="category-tab">
                                             <div class="heading">
                                                  <div id="other-phone-label" class="heading-label"></div>
                                                  <div class="uppercase font-bold font-size-20 padding-left-8">Các hãng khác
                                                  </div>
                                             </div>

                                             <div class="product-container"></div>

                                             <div
                                                  class="flex justify-center align-center font-bold capitalize margin-bottom-16">
                                                  <a href="#" class="category-btn button">Xem thêm</a>
                                             </div>
                                        </div>
                                   </section>

                                   
                                   <!-- FOR ADMIN CONTENT!!!! -->
                                   <section class="grid-col col-l-12 no-gutter full-width"></section>
                              </div>
                              <!-- TEST FORM -->
                              <?php renderDetailProduct(); ?>
                              
                              <!-- Thông tin cá nhân -->
                              <?php renderProfile(); ?>
                              <!-- cart content -->
                              <?php renderSearch(); ?>
                              <?php renderCart(); ?>
                    </section>
               </div>
          </main>
          <footer id="footer-container">
               <?php
               renderFooter();
               ?>
          </footer>
     </div>
<?php }?>