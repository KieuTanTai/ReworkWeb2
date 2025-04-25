<?php
function renderTracking()
{
     ?>
     <!-- order tracking, history order -->
     <div id="order-content" class="grid-col col-l-12 col-m-12 col-s-12 disable">
          <!-- status -->
          <div class="order-status-container margin-y-24 grid-col col-l-12 col-m-12 col-s-12">
               <section id="blank-order" class="margin-y-16">
                    <img src="https://m.media-amazon.com/images/G/31/cart/empty/kettle-desaturated._CB424694257_.svg">
                    <span class="font-size-16 padding-bottom-16 margin-y-12">Không có đơn đặt hàng nào.</span>
                    <section class="flex justify-center align-center font-bold capitalize margin-y-12">
                         <a href="./index.html" target="_self" class="return-homepage category-btn button">trang chủ</a>
                    </section>
               </section>

               <section id="customer-order" class="">
                    <div class="order-status-header grid-col col-l-12 col-m-12 col-s-12 padding-bottom-8 margin-bottom-8">
                         <div class="uppercase font-bold text-center font-size-20">đơn hàng của
                              bạn</div>
                    </div>

                    <!--block order info and tracking bar -->
                    <div class="order-info flex flex-direction-y margin-y-24">
                         <div class="flex ">
                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">mã đơn hàng</h4>
                                   <span class="order-code font-size-20 opacity-0-8">123451</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">thời gian đặt hàng</h4>
                                   <span class="order-time font-size-20 opacity-0-8">21/09/2024</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">mã vận đơn</h4>
                                   <span class="tracking-code font-size-20 opacity-0-8">432434xgf14142</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">vận chuyển bởi</h4>
                                   <span class="shipped-with font-size-20 opacity-0-8">TLANG
                                        SHIP</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">ngày nhận hàng dự kiến</h4>
                                   <span class="expected-delivery-date font-size-20 opacity-0-8">22/9/2024</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">tên người nhận</h4>
                                   <span class="Consignee font-size-20 opacity-0-8">K
                                        Tài</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">số điện thoại người nhận</h4>
                                   <span class="Consignee-phone font-size-20 opacity-0-8">(+84)843
                                        *** ***</span>
                              </div>

                              <div class="block-order-info grid-col col-l-3 col-m-6 col-s-6">
                                   <h4 class="capitalize">địa chỉ nhận hàng</h4>
                                   <span class="shipping-to font-size-20 opacity-0-8">
                                        <span class="Consignee-address">449/76 K4 HL BD BT TP.HCM</span>
                                   </span>
                              </div>
                         </div>

                         <!-- tracking bar -->
                         <div class="tracking-bar padding-top-8">
                              <div class="shipped-dot grid-col col-l-4 col-m-4 col-s-4">
                                   <div class="dot-status">
                                        <div class="dot-active">
                                             <div class="border-arrow-top"></div>
                                             <i class="fa-solid fa-circle fa-lg" style="color: var(--main-color);"></i>
                                             <p class="font-size-14">
                                                  <span class="flex">đơn hàng đã được giao cho
                                                       đơn vị vận chuyển</span>
                                             </p>
                                        </div>
                                        <div class="dot-disable">
                                             <div class="border-arrow-top opacity-0-6"></div>
                                             <i class="fa-regular fa-circle fa-lg" style="color: var(--main-color);"></i>
                                             <p class="font-size-14 text-end">
                                                  <span class="text-center flex opacity-0-6">chờ
                                                       lấy hàng</span>
                                             </p>

                                        </div>
                                   </div>
                              </div>

                              <div class="in-transit-dot grid-col col-l-4 com-m-4 col-s-4 no-gutter text-center">
                                   <div class="dot-status">
                                        <div class="dot-active">
                                             <div class="border-arrow-top"></div>
                                             <i class="fa-solid fa-circle fa-lg" style="color: var(--main-color);"></i>
                                             <p class="font-size-14">
                                                  <span class="flex justify-center">đơn hàng đang được vận chuyển đến
                                                       bạn</span>
                                             </p>
                                        </div>
                                        <div class="dot-disable">
                                             <div class="border-arrow-top opacity-0-6"></div>
                                             <i class="fa-regular fa-circle fa-lg" style="color: var(--main-color);"></i>
                                             <p class="font-size-14">
                                                  <span class="justify-center flex opacity-0-6">chờ giao hàng</span>
                                             </p>
                                        </div>
                                   </div>
                              </div>

                              <div class="delivered-dot grid-col col-l-4 col-m-4 col-s-4 text-end">
                                   <div class="dot-status">
                                        <div class="dot-active">
                                             <div class="border-arrow-top"></div>
                                             <i class="fa-solid fa-circle fa-lg" style="color: var(--main-color);"></i>
                                             <p class="font-size-14 text-end">
                                                  <span class="text-end flex">giao hàng thành công</span>
                                             </p>
                                        </div>
                                        <div class="dot-disable">
                                             <div class="border-arrow-top opacity-0-6"></div>
                                             <i class="fa-regular fa-circle fa-lg" style="color: var(--main-color);"></i>
                                             <p class="font-size-14 text-end">
                                                  <span class="flex justify-end opacity-0-6">chờ
                                                       nhận hàng</span>
                                             </p>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- tracking table -->
                         <!-- <div class="tracking-table-container grid-col col-l-12 col-m-12 col-s-12 margin-top-16 no-gutter">
                                                       <div class="tracking-table flex">
                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>18:05</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <div class="font-bold">Chờ xác nhận</div>
                                                                      <span class="opacity-0-8">Đặt hàng thành
                                                                           công</span>
                                                                 </div>
                                                            </div>

                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>19:05</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <div class="font-bold">Chuẩn bị hàng</div>
                                                                      <span class="opacity-0-8">Shop đang chuẩn bị
                                                                           hàng</span>
                                                                 </div>
                                                            </div>

                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>20:05</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <span class="opacity-0-8">Đơn vị vận chuyển lấy
                                                                           hàng thành công</span>
                                                                 </div>
                                                            </div>

                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>21:05</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <span class="opacity-0-8">Đơn hàng đã đến kho phân
                                                                           loại</span>
                                                                 </div>
                                                            </div>

                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>21:55</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <span class="opacity-0-8">Đơn hàng đã rời kho phân
                                                                           loại</span>
                                                                 </div>
                                                            </div>

                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>22:35</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <div class="font-bold">Chờ giao hàng</div>
                                                                      <span class="opacity-0-8">Đơn hàng đang trên đường
                                                                           giao đến bạn.</span>
                                                                 </div>
                                                            </div>

                                                            <div
                                                                 class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                                                 <div
                                                                      class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                                                      <div>12/09</div>
                                                                      <div>22:55</div>
                                                                 </div>

                                                                 <div class="dot-status flex align-center">
                                                                      <div>
                                                                           <i class="fa-regular fa-circle-dot fa-2xs"
                                                                                style="color: var(--main-color);"></i>
                                                                      </div>
                                                                 </div>

                                                                 <div class="delivery-state padding-left-12">
                                                                      <div class="font-bold">Đã giao hàng</div>
                                                                      <span class="opacity-0-8">Giao hàng thành
                                                                           công</span>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div> -->
                    </div>

                    <article class="history-order-link button flex justify-center align-center">
                         <div class="history-order-btn font-bold capitalize">xem lịch sử mua
                              hàng</div>
                    </article>
               </section>
          </div>

          <!-- history -->
          <div class="history-tracking-container margin-y-24 grid-col col-l-12 col-m-12 col-s-12">
               <section id="history-order-container" class="">
                    <div class="order-status-header padding-bottom-8 margin-bottom-16">
                         <p class="uppercase font-bold text-center font-size-20">lịch sử mua hàng</p>
                    </div>

                    <div
                         class="history-order-table grid-col col-l-12 col-m-12 col-s-12 no-gutter flex justify-space-between">
                    </div>
               </section>
          </div>

          <div class="overlay">
               <div class="status-container grid-col col-l-6 col-m-6 col-s-6 flex justify-center align-center full-height">
                    <!-- tracking table -->
                    <div class="tracking-table-container grid-col col-l-12 col-m-12 col-s-12 no-gutter">
                         <div class="tracking-table flex align-center">
                              <div class="flex justify-space-between full-width margin-bottom-12">
                                   <h4 class="uppercase">mã vận đơn</h4>
                                   <span class="tracking-code font-size-20 opacity-0-6">432434xgf14142</span>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>18:05</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <div class="font-bold">Chờ xác nhận</div>
                                        <span class="opacity-0-8">Đặt hàng thành công</span>
                                   </div>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>19:05</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <div class="font-bold">Chuẩn bị hàng</div>
                                        <span class="opacity-0-8">Shop đang chuẩn bị hàng</span>
                                   </div>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>20:05</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <span class="opacity-0-8">Đơn vị vận chuyển lấy hàng thành công</span>
                                   </div>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>21:05</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <span class="opacity-0-8">Đơn hàng đã đến kho phân loại</span>
                                   </div>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>21:55</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <span class="opacity-0-8">Đơn hàng đã rời kho phân
                                             loại</span>
                                   </div>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>22:35</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <div class="font-bold">Chờ giao hàng</div>
                                        <span class="opacity-0-8">Đơn hàng đang trên đường giao
                                             đến bạn.</span>
                                   </div>
                              </div>

                              <div class="tracking-block flex padding-top-8 padding-bottom-8 align-center full-width">
                                   <div class="tracking-time padding-right-8 font-size-14 opacity-0-6">
                                        <div>12/09</div>
                                        <div>22:55</div>
                                   </div>

                                   <div class="dot-status flex align-center">
                                        <div>
                                             <i class="fa-regular fa-circle-dot fa-2xs"
                                                  style="color: var(--main-color);"></i>
                                        </div>
                                   </div>

                                   <div class="delivery-state padding-left-12">
                                        <div class="font-bold">Đã giao hàng</div>
                                        <span class="opacity-0-8">Giao hàng thành công</span>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>


     <?php
}

?>