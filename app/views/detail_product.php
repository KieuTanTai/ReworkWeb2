<?php
function renderDetailProduct()
{
     ?>
     <!-- detail product -->
     <div id="detail-content" class="root-session-content grid-col col-l-12 col-m-12 col-s-12 no-gutter">
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
                              <!-- <div class="product-info grid-col col-l-12 col-m-12 col-s-12 no-gutter flex margin-bottom-12">
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
                              </div> -->

                              <div class="product-selector margin-top-16">
                                   <div class="margin-bottom-8">
                                        <div class="margin-bottom-8">
                                             <p class="font-size-14 font-bold margin-bottom-8">Chọn màu sắc</p>
                                        </div>
                                        <div
                                             class="color-options grid-col col-l-6 text-center col-m-12 col-s-12 no-gutter flex">
                                             <div class="color-option col-l-3 margin-right-8 padding-bottom-4 padding-top-4 border-1-solid-black text-white"
                                                  data-value="black" style="background-color: var(--primary-dark)">Black
                                             </div>
                                             <div class="color-option col-l-3 margin-inline-8 padding-bottom-4 padding-top-4 border-1-solid-black"
                                                  data-value="white" style="background-color: var(--primary-white)">White
                                             </div>
                                             <div class="color-option col-l-3 margin-inline-8 padding-bottom-4 padding-top-4 border-1-solid-black text-white"
                                                  data-value="blue" style="background-color: var(--main-color);">Blue
                                             </div>
                                        </div>
                                   </div>

                                   <div>
                                        <div class="margin-bottom-8">
                                             <p class="font-size-14 font-bold margin-bottom-8">Chọn dung lượng</p>
                                        </div>
                                        <div
                                             class="storage-options grid-col col-l-6 col-m-12 col-s-12 no-gutter margin-bottom-12 margin-top-12 flex gap-12 text-center">
                                             <div class="storage-option padding-bottom-4 padding-top-4 col-l-3 border-1-solid-black button bg-dark "
                                                  data-value="64GB">64GB</div>
                                             <div class="storage-option padding-bottom-4 padding-top-4 col-l-3 border-1-solid-black button bg-dark "
                                                  data-value="128GB">128GB</div>
                                             <div class="storage-option padding-bottom-4 padding-top-4 col-l-3 border-1-solid-black button bg-dark "
                                                  data-value="256GB">256GB</div>
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
                                        <div class="add-to-cart button margin-bottom-8">thêm
                                             vào giỏ hàng</div>
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
                         <ul>
                              <li class="product-monitor"><strong>Màn hình:</strong> Liquid Retina IPS LCD, 6.1 inches, HD+
                              </li>
                              <li class="product-os"><strong>Hệ điều hành:</strong> iOS 13 (gốc), được lên iOS 18</li>
                              <li class="product-rear-camera"><strong>Camera sau:</strong> 12 MP + 12 MP (siêu rộng)</li>
                              <li class="product-font-camera"><strong>Camera trước:</strong> 12 MP, HDR</li>
                              <li class="product-cpu"><strong>CPU:</strong> Apple A13 Bionic (7 nm+)</li>
                              <li class="product-ram"><strong>RAM:</strong> 4GB</li>
                              <li class="product-rom"><strong>Bộ nhớ trong:</strong> 64/128/256GB</li>
                              <li class="product-battery"><strong>Dung lượng pin:</strong> 3110 mAh, sạc nhanh 18W</li>
                         </ul>
                         <button onclick="showDetails()"
                              class="product-more-detail button bg-main-color text-white padding-12 margin-top-12">Hiển thị
                              chi tiết</button>
                    </div>

                    <!-- Overlay container -->
                    <div id="overlay-more-detail" class="overlay">
                         <div class="grid wide">
                              <div class="grid-row">
                                   <div class="overlay-content bg-white grid-col col-l-12 bg-white padding-12 border-1-solid-black">
                                        <h2>Thông tin chi tiết</h2>
                                        <ul id="specs-list">
                                             <li><strong>Thông tin chung</strong></li>
                                             <li class="product-os">Hệ điều hành: iOS 13 (gốc). Được lên iOS 18</li>
                                             <li class="product-language">Ngôn ngữ: Tiếng Việt, đa ngôn ngữ</li>
                                             <li><strong>Màn hình</strong></li>
                                             <li class="product-monitor">
                                                  Loại màn hình: Liquid Retina IPS LCD
                                                  Màu màn hình: 16 Triệu màu
                                                  Chuẩn màn hình: Liquid Retina IPS LCD, 625 nits (typ)
                                                  6.1 inches, HD+ (828 x 1792 pixels), tỷ lệ 19.5:9
                                                  Kính chống xước</li>
                                             <li class="product-resolution">Độ phân giải: 828 x 1792 pixels</li>
                                             <li><strong>Camera</strong></li>
                                             <li class="product-rear-camera">Camera sau: 12 MP + 12 MP (siêu rộng)
                                                  Quay phim: 4K 60fps, 1080p 240fps
                                                  Đèn Flash: True Tone Flash
                                             </li>
                                             <li class="product-font-camera">Camera trước: 12 MP, HDR</li>
                                             <li><strong>CPU & RAM</strong></li>
                                             <li class="product-cpu">Chipset: Apple A13 Bionic (7 nm+)
                                                  Số nhân: 6 nhân (2x2.65 GHz Lightning + 4x1.8 GHz Thunder)</li>
                                             <li class="product-ram">RAM: 4GB</li>
                                             <li><strong>Bộ nhớ & Lưu trữ</strong></li>
                                             <li class="product-rom">Bộ nhớ trong: 64/128/256GB</li>
                                             <li><strong>Pin & Sạc</strong></li>
                                             <li class="product-battery">Dung lượng pin: 3110 mAh
                                                  Loại pin: Li-Ion</li>
                                             <li class="product-battery-tech">Công nghệ pin: Sạc nhanh 18W, sạc không dây
                                             </li>
                                             <li class="product-charging-port">Cổng sạc: Lightning</li>
                                        </ul>
                                        <button onclick="closeOverlay()"
                                             class="more-detail-form-close button bg-main-color text-white padding-12 margin-top-12">Đóng</button>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- <style>
                         .overlay {
                              display: none;
                              position: fixed;
                              top: 0;
                              left: 0;
                              width: 100%;
                              height: 100%;
                              background: rgba(0, 0, 0, 0.8);
                              justify-content: center;
                              align-items: center;
                         }

                         .overlay-content {
                              background: white;
                              padding: 20px;
                              width: 80%;
                              max-height: 80vh;
                              overflow-y: auto;
                         }
                    </style> -->

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