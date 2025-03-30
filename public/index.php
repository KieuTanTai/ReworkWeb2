<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <meta name="description" content="Shop Mobile " />
     <link rel="shortcut icon" href="./assets/images/icons/web_logo/main-logo.ico" type="image/x-icon" />
     <link rel="stylesheet" href="./assets/fonts/fontawesome-6.6.0/css/all.min.css" />
     <link rel="stylesheet" href="./assets/css/index.css" />
     <link rel="stylesheet" href="./assets/css/responsive.css" />
</head>

<body>
     <div class="web-content">
          <header id="header-container">
               <?php
               require "../app/views/header-footer.php";
               renderHeader();
               ?>
          </header>

          <main id="main-container">
               <?php
               renderSubHeader();
               ?>
               <!-- html for sub header -->
               <!-- breadcrumb -->
               <section class="breadcrumb-list disable">
                    <div class="grid wide">
                         <div class="grid-row">
                              <div class="grid-col col-l-12">
                                   <ol class="capitalize font-size-14">
                                        <li>
                                             <a href="">
                                                  <i class="fa-solid fa-house" style="color: var(--main-color)"></i>
                                                  <span class="font-bold font-size-14"
                                                       style="color: var(--main-color)">Trang chủ</span>
                                             </a>
                                        </li>

                                        <li>
                                             <span>Hello</span>
                                        </li>
                                   </ol>
                              </div>
                         </div>
                    </div>
               </section>
               <div id="main-content">
                    <article id="scroll-top" class="s-m-hidden">
                         <img src="./assets/images/icons/web_logo/manga-icon-backtotop_550x700_1.png"
                              alt="back to top" />
                    </article>

                    <!-- main items -->
                    <section class="grid wide">
                         <div class="grid-row">
                              <!-- homepage -->
                              <div id="index-content" class="grid-col col-l-12 col-m-12 col-s-12">
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
                                                  <img src="./assets/images/icons/services/icon-sv1.jpg"
                                                       alt="Thanh toán" />
                                                  <div class="flex-direction-y padding-left-8">
                                                       <h5 class="font-bold uppercase font-size-13">Thanh toán</h5>
                                                       <p class="font-size-13 font-light capitalize">khi nhận hàng</p>
                                                  </div>
                                             </div>

                                             <div class="service-content grid-col col-l-3 col-m-3 col-s-6">
                                                  <img src="./assets/images/icons/services/icon-sv2.jpg"
                                                       alt="Quà tặng" />
                                                  <div class="flex-direction-y padding-left-8">
                                                       <h5 class="font-bold uppercase font-size-13">Quà tặng</h5>
                                                       <p class="font-size-13 font-light capitalize">Miễn phí</p>
                                                  </div>
                                             </div>

                                             <div class="service-content grid-col col-l-3 col-m-3 col-s-6">
                                                  <img src="./assets/images/icons/services/icon-sv3.jpg"
                                                       alt="Bảo mật" />
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
                                        class="container flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
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

                                   <!-- FOR ADMIN CONTENT!!!! -->
                                   <section class="grid-col col-l-12 no-gutter margin-y-16 full-width"></section>
                              </div>
                    </section>
               </div>
          </main>
          <footer id="footer-container">
               <?php
               renderFooter();
               ?>
          </footer>
     </div>
</body>

</html>