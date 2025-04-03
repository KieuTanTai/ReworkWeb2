<?php
function renderHeader()
{
     ?>
     <section class="grid wide">
          <section class="grid-row flex justify-center">
               <div class="s-m-header s-m-content flex justify-center align-center grid-col col-m-12 col-s-12 no-gutter">
                    <div class="header-items full-height s-m-content s-m-nav-btn">
                         <i class="fa-solid fa-list fa-xl" style="color: var(--main-color)"></i>
                         <div class="overlay">
                              <div class="s-m-nav-content s-m-content">
                                   <div class="s-m-nav-btn flex align-center margin-y-12">
                                        <button type="button" class="lnw-btn js-login active">Đăng
                                             nhập</button>
                                        <button type="button" class="lnw-btn js-register margin-left-16 active">Đăng
                                             ký</button>
                                        <button type="button" class="lnw-btn js-signout margin-left-16">Đăng
                                             xuất</button>
                                   </div>
                                   <ul class="s-m-nav-list">
                                        <li class="web-logo">
                                             <div>Trang chủ</div>
                                        </li>
                                        <li class="history-order-link">
                                             <div>Lịch sử mua hàng</div>
                                        </li>
                                        <li class="books-store">
                                             <div>Tủ sách thương hiệu</div>
                                        </li>
                                        <li class="news-nav">
                                             <div>Tin tức & Sự kiện</div>
                                        </li>
                                        <li class="services">
                                             <div>Liên hệ</div>
                                        </li>
                                   </ul>
                              </div>
                         </div>
                    </div>
                    <div class="web-logo full-height">
                         <div>
                              <img src="./assets/images/icons/web_logo/light-novel-world.png" alt="Mobile Phone Store" />

                         </div>
                    </div>
                    <div class="header-items full-height">
                         <div class="cart-btn full-height">
                              <div class="flex align-center full-height">
                                   <div class="cart-count item-count">0</div>
                                   <i class="fa-solid fa-cart-shopping fa-xl" style="color: var(--main-color)"></i>
                                   <span class="padding-left-8 font-size-14 s-m-hidden">Giỏ hàng</span>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="grid-col col-l-2 col-m-8 col-s-8 web-logo s-m-hidden">
                    <div>
                         <img src="./assets/images/icons/web_logo/light-novel-world.png" alt="Mobile Phone Store" />

                    </div>
               </div>
               <section class="search-container grid-col col-l-5 col-m-12 col-s-12">
                    <div class="search-form-container flex justify-center align-center full-width">
                         <form method="get" autocomplete="on" class="full-width">
                              <label for="search-input">
                                   <input type="text" name="query" id="search-input" class="no-outline"
                                        placeholder="Tìm kiếm sản phẩm...." />
                              </label>
                              <button type="button" class="search-btn button flex justify-center align-center">
                                   <i class="fa-solid fa-magnifying-glass fa-gl" style="color: var(--primary-white)"></i>
                              </button>
                         </form>
                    </div>
               </section>
               <section class="header-items grid-col col-l-5 col-m-2 col-s-2 s-m-hidden font-size-14">
                    <div class="hotline flex justify-center align-center full-height">
                         <i class="fa-solid fa-phone-volume fa-xl" style="color: var(--main-color)"></i>
                         <div class="flex-direction-y padding-left-8">
                              <span>Hotline</span>
                              <span class="font-bold" style="color: var(--main-color)">032838xxxx</span>
                         </div>
                    </div>
                    <div class="order-tracking flex justify-center align-center full-height">
                         <i class="fa-solid fa-truck-fast fa-xl" style="color: var(--main-color)"></i>
                         <div class="flex-direction-y">
                              <span class="padding-left-8">Tra cứu</span>
                              <span class="padding-left-8">đơn hàng</span>
                         </div>
                    </div>
                    <div class="cart-btn full-height">
                         <div class="flex align-center full-height">
                              <div class="cart-count item-count">0</div>
                              <i class="fa-solid fa-cart-shopping fa-xl" style="color: var(--main-color)"></i>
                              <span class="padding-left-8">Giỏ hàng</span>
                         </div>
                    </div>
                    <div id="user-account" class="account full-height">
                         <div class="flex align-center full-height">
                              <div id="no-sign-in" class="flex justify-center align-center">
                                   <i class="fa-regular fa-circle-user fa-xl" style="color: var(--main-color)"></i>
                                   <p class="padding-left-8">Tài khoản</p>
                              </div>

                              <div class="header-user-info flex justify-center align-center disable">
                                   <div class="user-img flex justify-center">
                                        <img src="./assets/images/icons/web_logo/favicon.ico" width="20%" />
                                   </div>
                                   <div class="user-name font-bold font-size-14">Thiên Lang Vô Tri</div>
                              </div>

                              <!-- account -->
                              <nav class="nav-account flex justify-center align-center flex-direction-y">
                                   <button type="button" title="Đăng nhập" class="lnw-btn active js-login margin-bottom-16">
                                        <div class="font-bold uppercase">Đăng nhập</div>
                                   </button>

                                   <button type="button" title="Đăng ký" class="lnw-btn js-register">
                                        <div class="font-bold uppercase">Đăng ký</div>
                                   </button>

                                   <button type="button" title="Đăng xuất" class="lnw-btn js-signout disable">
                                        <div class="font-bold uppercase">Đăng xuất</div>
                                   </button>
                              </nav>
                         </div>
                    </div>
               </section>
          </section>
     </section>
     <div id="admin-header-container" class="disable">

     </div>
     <?php
}
?>

<?php
function renderSubHeader()
{
     ?>
     <!-- html for sub header -->
     <section id="sub-header" class="s-m-hidden">
          <div class="grid wide">
               <div class="grid-row">
                    <!-- Items in sub header  -->
                    <div class="sub-menu min-height-40 grid-col col-l-12">
                         <div class="sub-menu-item menu-nav grid-col col-l-2-4 no-gutter">
                              <!-- Nav button -->
                              <div class="flex justify-center align-center list-btn min-height-40 full-width">
                                   <span><i class="fa-solid fa-list" style="color: var(--primary-white)"></i></span>
                                   <p class="padding-left-8 font-size-13 font-bold"> Danh mục sản phẩm </p>

                                   <!-- Nav content -->
                                   <nav class="nav-categories js-bg-white full-width">
                                        <div class="nav-item">
                                             <a href="" class="manga flex full-width" title="manga-container">
                                                  <img src="./assets/images/icons/Other_icons/Manga.webp" alt="manga"
                                                       width="20em" />
                                                  <p class="padding-left-8 font-size-14 font-bold"> Manga </p>
                                             </a>
                                        </div>

                                        <div class="nav-item">
                                             <a href="" class="light-novel flex full-width" title="light-novel-container">
                                                  <img src="./assets/images/icons/Other_icons/LN.webp" alt="light novel"
                                                       width="20em" />
                                                  <p class="padding-left-8 font-size-14 font-bold"> Light
                                                       Novel </p>
                                             </a>
                                        </div>

                                        <div class="nav-item">
                                             <a href="" class="literary flex full-width" title="literary">
                                                  <img src="./assets/images/icons/Other_icons/yang-icon-1.webp"
                                                       alt="literary" width="20em" />
                                                  <p class="padding-left-8 font-size-14 font-bold">Sách văn
                                                       học</p>
                                             </a>
                                        </div>

                                        <div class="nav-item">
                                             <a href="" class="education flex full-width" title="education">
                                                  <img src="./assets/images/icons/Other_icons/yang-icon-4.webp"
                                                       alt="education" width="20em" />
                                                  <p class="padding-left-8 font-size-14 font-bold">Sách giáo
                                                       dục</p>
                                             </a>
                                        </div>
                                   </nav>
                              </div>
                         </div>

                         <div class="sub-menu-item history-order-link grid-col col-l-2-4">
                              <div class="full-height full-width">
                                   <span>
                                        <i class="fa-solid fa-file-invoice fa-lg" style="color: var(--main-color)"></i>
                                   </span>
                                   <p class="padding-left-8 font-size-13 font-bold">Lịch sử mua hàng</p>
                              </div>
                         </div>

                         <div class="sub-menu-item grid-col col-l-2-4 news-nav">
                              <div class="full-width full-height">
                                   <span><i class="fa-regular fa-newspaper fa-lg"
                                             style="color: var(--main-color)"></i></span>
                                   <p class="padding-left-8 font-size-13 font-bold">Tin tức & Sự kiện</p>
                              </div>
                         </div>

                         <div class="sub-menu-item grid-col col-l-2-4 lnw-store light-novel-world-test">
                              <div class="full-height full-width">
                                   <span><i class="fa-brands fa-fantasy-flight-games fa-lg"
                                             style="color: var(--main-color)"></i></span>
                                   <p class="padding-left-8 font-size-13 font-bold">Mobile Phone Store</p>
                              </div>
                         </div>

                         <div class="sub-menu-item grid-col col-l-2-4 services">
                              <div class="full-width full-height">
                                   <span>
                                        <i class="fa-solid fa-headset fa-lg" style="color: var(--main-color)"></i>
                                   </span>
                                   <p class="padding-left-8 font-size-13 font-bold">Liên hệ</p>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>
     </main>
     <?php
}
?>

<?php
function renderFooter()
{
     ?>
     <section class="grid wide">
          <div class="grid-row">
               <div class="footer-col-container grid-col col-l-4 col-m-4 col-s-12">
                    <div class="footer-logo">
                         <a href="#">
                              <img src="./assets/images/icons/web_logo/light-novel-world.png" alt="Mobile Phone Store" />
                         </a>
                    </div>
                    <div class="contact-info">
                         <div class="margin-bottom-16">
                              <i class="fa-solid fa-map-location-dot fa-xl" style="color: var(--main-color)"></i>
                              <div class="line-height-1-6 font-size-14 padding-left-16">
                                   <p>
                                        Số 110 Nguyễn Ngọc Nại, Khương Mai, Thanh Xuân, Hà Nội
                                   </p>
                              </div>
                         </div>
                         <div class="margin-bottom-16">
                              <i class="fa-solid fa-phone fa-xl" style="color: var(--main-color)"></i>
                              <p class="padding-left-16">Hotline: 03 2838 xxxx</p>
                         </div>
                         <div class="margin-bottom-16">
                              <i class="fa-regular fa-envelope fa-xl" style="color: var(--main-color)"></i>
                              <p class="padding-left-16">tanthi***la**@gmail.com</p>
                         </div>
                         <div>
                              <i class="fa-solid fa-shield fa-xl" style="color: var(--main-color)"></i>
                              <p class="padding-left-16">
                                   Giấy phép DKKD số xxxxxx, cấp lần thứ x năm 2024
                              </p>
                         </div>
                    </div>
               </div>

               <div class="footer-col-container grid-col col-l-5 col-m-8 col-s-12">
                    <div class="block-footer margin-bottom-12">
                         <div class="flex-direction-y">
                              <h4 class="uppercase margin-bottom-12">Hỗ trợ khách hàng</h4>
                              <a href="">Câu hỏi thường gặp</a>
                              <a href="">Điều khoản dịch vụ</a>
                         </div>

                         <div class="flex-direction-y">
                              <h4 class="uppercase margin-bottom-12">Chính sách</h4>
                              <a href="">Chính sách bảo mật</a>
                              <a href="">Chính sách thanh toán</a>
                              <a href="">Chính sách vận chuyển</a>
                              <a href="">Chính sách đổi trả</a>
                         </div>
                    </div>
                    <div>
                         <h4 class="uppercase margin-y-12">đăng ký nhận tin</h4>
                         <p class="font-size-13 margin-y-12" style="max-width: 60%">
                              Hãy nhập email của bạn vào ô dưới đây để có thể nhận được tất
                              cả tin tức mới nhất của LNW
                         </p>
                         <div class="footer-input">
                              <form method="post" class="full-width margin-right-16">
                                   <input type="email" name="user-email" id="user-email" placeholder="đăng ký email"
                                        autocomplete="on" required class="padding-16" />
                                   <button type="submit" class="subscribe-btn uppercase" name="subscribe" id="subscribe">
                                        đăng ký
                                   </button>
                              </form>
                              <img src="./assets/images/icons/web_logo/bct_img.jpg" alt="bo cong thuong" />
                         </div>
                    </div>
               </div>

               <div class="footer-col-container grid-col col-l-3 col-m-12 col-s-12">
                    <h4 class="uppercase margin-bottom-16">kết nối với chúng tôi</h4>
                    <div class="footer-icon">
                         <div>
                              <a href="https://www.facebook.com/profile.php?id=100079218978533" target="_blank">
                                   <img src="./assets/images/icons/Social_media/facebook.svg" alt="facebook" />
                              </a>
                         </div>
                         <!-- <div class="padding-left-8">
                                        <a href="https://www.youtube.com/@KT-ThienLang" target="_blank">
                                             <img src="./assets/images/icons/Social_media/icons8-youtube.svg" alt="youtube">
                                        </a>
                                   </div> -->
                         <div class="padding-left-8">
                              <a href="https://www.tiktok.com/@tlangvotri" target="_blank">
                                   <img src="./assets/images/icons/Social_media/icons8-tiktok.svg" alt="tiktok" />
                              </a>
                         </div>
                         <div class="padding-left-8">
                              <a href="https://twitter.com/thien8224" target="_blank">
                                   <img src="./assets/images/icons/Social_media/icons8-twitterx.svg" alt="Twitter/X" />
                              </a>
                         </div>
                         <div class="padding-left-8">
                              <a href="https://chat.zalo.me/" target="_blank">
                                   <img src="./assets/images/icons/Social_media/icons8-zalo.svg" alt="zalo" />
                              </a>
                         </div>
                    </div>
                    <h4 class="uppercase margin-bottom-16">
                         các phương thức thanh toán
                    </h4>
                    <div class="footer-image-container">
                         <div class="footer-image-item">
                              <img src="./assets/images/icons/payment_methods/internet_banking.jpg" alt="internet banking" />
                         </div>
                         <div class="footer-image-item">
                              <img src="./assets/images/icons/payment_methods/pay_7.jpg" alt="visa" />
                         </div>
                         <div class="footer-image-item">
                              <img src="./assets/images/icons/payment_methods/mastercard.jpg" alt="master card" />
                         </div>
                         <div class="footer-image-item">
                              <img src="./assets/images/icons/payment_methods/pay_5.jpg" alt="tiền mặt" />
                         </div>
                         <div class="footer-image-item">
                              <img src="./assets/images/icons/payment_methods/pay_4.jpg" alt="zalo pay" />
                         </div>
                         <div class="footer-image-item">
                              <img src="./assets/images/icons/payment_methods/pay_8.jpg" alt="Momo" />
                         </div>
                    </div>
               </div>
          </div>
     </section>
     </footer>
     <?php
}
?>