"use strict";
import { getCurrentUser } from "./auth.js";
import * as Bridge from "./bridges.js";
import { GetDetailPRoducts, GetOrderDetail, GetOrderDetails, GetOrdersByCustomer, GetProducts, GetUserById, UpdateOrder } from "./getdata.js";
import { disableSiblingContainer, formatPrices, headerUserInfo, hiddenException, scrollView } from "./interfaces.js";
import { getDetailPhones, getProductPhones } from "./product.js";

function returnHomepage(elementsObj) {
     let testURL = location.pathname;
     const webLogo = elementsObj.getWebLogo();
     let homepageBtns = elementsObj.getHomepageBtn();
     testURL = testURL.slice(testURL.lastIndexOf("/") + 1, testURL.indexOf("?") + 1);

     homepageBtns?.forEach((btn) =>
          btn.addEventListener("click", () => Bridge.navigateRootURL())
     );
     webLogo?.forEach((element) =>
          element.addEventListener("click", () => Bridge.navigateRootURL()));
}

// header navigation button on mobile
function smNavigationMenu(elementsObj) {
     let navigateBtn = elementsObj.getMobileNavigate();
     navigateBtn.addEventListener("click", Bridge.debounce((event) => {
          let overlay = navigateBtn.querySelector(".overlay");
          overlay.classList.add("active");
          overlay.classList.remove("overflowText");
          overlay.classList.add("menu");
          if (event.target.classList.contains("overlay")) {
               setTimeout(() => overlay.classList.remove("active"), 200);
               overlay.classList.remove("menu");
               overlay.classList.add("overflowText");
          }
     }), 200, "mobileHeaderNavigate");
}

function cancelButtons(elementsObj) {
     let loginForm = Bridge.$("#login");
     let forgotForm = Bridge.$("#forgot-password");
     const cancelBtn = elementsObj.getJsCancelBtn();
     if (cancelBtn)
          cancelBtn.forEach((btn) =>
               btn.addEventListener("click", () => {
                    loginForm?.classList.add("active");
                    forgotForm?.classList.contains("active") ? forgotForm.classList.remove("active") : forgotForm;
               })
          );
}

//! navigate to order-tracking
async function trackingNavigate(elementsObj) {
     // navigate to index.html if not have any container
     const buttons = elementsObj.getOrderTrackingBtn();
     if (!buttons) return;
     const trackers = await GetOrdersByCustomer(JSON.parse(sessionStorage.getItem("loginAccount"))["user_id"]);
     buttons.forEach((btn) =>
          btn.addEventListener("click", Bridge.throttle(() => showTracking(trackers), 200, "statusNav")));
     if (trackers) orderInfo(trackers);
}

function showTracking(trackers) {
     const elementsObj = Bridge.default();
     const container = elementsObj.getStatusContainer();
     const blankOrder = container?.querySelector("#blank-order");
     const customerOrder = container?.querySelector("#customer-order");
     let isLoggedIn = sessionStorage.getItem("login");
     // navigate to index.html if not have any container
     if (!container) {
          sessionStorage.setItem("retryTracking", "true");
          Bridge.navigateRootURL();
     }

     hiddenException("order-content");
     disableSiblingContainer(elementsObj.getOrderContent());
     elementsObj.getStatusContainer()?.classList.remove("disable");
     const hasOrders = Array.isArray(trackers) && trackers.length > 0;

     if (!hasOrders || !isLoggedIn) {
          blankOrder.classList.add("active");
          customerOrder.classList.remove("active");
     } else {
          customerOrder.classList.add("active");
          blankOrder.classList.remove("active");
     }
}

async function orderInfo(trackers) {
     // if (!sessionStorage.getItem("hasLogin")) return;
     // let ordersList = JSON.parse(localStorage.getItem("donhang"));
     // let loginAccount = JSON.parse(sessionStorage.getItem("hasLoginAccount"));
     if (!trackers) return;
     let customer = await GetUserById(trackers[0].makh);
     console.log(customer);
     let container = Bridge.$$(".order-info .block-order-info span");
     container.forEach((block) => {
          if (!customer) return;
          if (block.classList.contains("order-code")) block.innerHTML = customer.makh;
          if (block.classList.contains("order-time")) block.innerHTML = trackers[0].thoigian;
          if(block.classList.contains("tracking-code")) block.innerHTML = trackers[0].madonhang + customer.makh;
          if (block.classList.contains("expected-delivery-date"))
               block.innerHTML = "3 ngày sau xác nhận đơn";
          if (block.classList.contains("Consignee")) block.innerHTML = customer.tenkhachhang;
          if (block.classList.contains("Consignee-phone"))
               block.innerHTML = customer.sdt;
          if (block.classList.contains("Consignee-address"))
               block.innerHTML = customer.diachi;
     });
}


// navigate to history tracking
function historyNavigate(elementsObj) {
     let historyBtn = elementsObj.getHistoryBtn();
     historyBtn.forEach((btn) => btn.addEventListener("click", showOrderContent));
}

// ! NEED CHANGE HERE
async function showOrderContent() {
     let elementsObj = Bridge.default();
     let historyContainer = elementsObj.getHistoryOrder();
     let lists = await GetOrdersByCustomer(JSON.parse(sessionStorage.getItem("loginAccount"))["user_id"]);
     let orderContainer = elementsObj.getOrderContent();
     hiddenException("order-content");
     disableSiblingContainer(orderContainer);
     const hasOrders = Array.isArray(lists) && lists.length > 0;
     console.log(lists);
     console.log(hasOrders);
     if (!hasOrders || !JSON.parse(sessionStorage.getItem("login"))) {
          blankOrder(elementsObj);
          return;
     }
     // navigate to index.html if not have any container
     if (!orderContainer) {
          sessionStorage.setItem("retryShowOrder", "true");
          Bridge.navigateRootURL();
     }

     elementsObj.getHistoryContainer()?.classList.remove("disable");
     if (historyContainer.classList.contains("active")) return;
     historyContainer.classList.add("active");
     renderOrder(elementsObj);
}

async function scriptOrder(order) {
     let productsList = await getProductPhones();
     let details = await GetOrderDetails(order.madonhang);
     let idProduct = (await getDetailPhones()).find((detail) => detail.maphienbansp == details[0].maphienbansp).masp;
     let product = productsList.find((product) => product.masp === idProduct);
     let status;

     // get status of this order (cập nhật trạng thái cho đơn hàng)
     if (order.trangthai === 1) status = "chờ xử lý";
     else if (order.trangthai === 2) status = "đã xác nhận";
     else if (order.trangthai === 3) status = "đã giao";
     else if (order.trangthai === 4) {
          status = "đã hủy";
          return;
     }
     // else if (order.trangthai === 4) status = "đã giao hàng";


     // get script html and append it (render đơn hàng)
     let script = `
<div class="block-product">
              <div class="cart-content">
                  <div class="completed-order-info margin-bottom-8">
                        <img src="${`src="${'assets/images/' + product.hinhanh}" alt="${product.tensp}" onerror="this.onerror=null; this.src='assets/images/vn-11134207-7ras8-m2nn2bl6q4922e.jpg'`}">
                        <div class="full-width padding-left-12">
                            <p class="capitalize padding-bottom-8">${product.tensp}</p>
                            <div class="block-product-price text-end">
                                  <div class="quantity-cart">x${details.length}</div>
                                  <div class="new-price price">${order.tongtien}</div>
                            </div>
                        </div>
                  </div>
                  <div
                        class="flex justify-space-between padding-bottom-8 padding-top-8">
                        <div class="total-item opacity-0-6">${details.length} item</div>
                        <div class="price total-price font-bold text-end">${order.tongtien}</div>
                  </div>
                  <div class="order-status flex justify-space-between padding-top-8 padding-bottom-8">
                        <span class="opacity-0-8 font-size-13 ${status === "đã giao" ? "success-color" : "waiting-color"}">${status ? status : "chờ xử lý"}</span>
                        <div><i class="fa-solid fa-chevron-right fa-xs" style="color: var(--main-color);"></i></div></div>
                  <div class="flex align-center justify-space-between padding-top-8">
                        <span class="delivered-day flex opacity-0-8">
                            <div>${status}</div>
                        </span>

                        <div class="flex">
                          <span class="remove-btn button ${(order.trangthai == 1) ? "" : "disable"}">
                                <div class="capitalize"> Hủy Đơn</div>
                          </span>
                        </div>
                  </div>
              </div>
        </div>
  `;
     return new DOMParser().parseFromString(script, "text/html").body.firstChild;
}

async function renderOrder(elementsObj) {
     let container = elementsObj.getHistoryOrderTable();
     let orders = await GetOrdersByCustomer(JSON.parse(sessionStorage.getItem("loginAccount"))["user_id"]);
     let details = [];

     for (let item of orders) {
          const orderDetails = await GetOrderDetails(item.madonhang);
          details.push(orderDetails);
     }

     if (orders && container) {
          // Render mỗi đơn hàng từ orders
          orders.forEach(async (order) => {
               let script = await scriptOrder(order);  // Gọi scriptOrder với đơn hàng tổng quát
               let removeBtn = script.querySelector(".remove-btn");

               // Thêm sự kiện xóa đơn hàng
               removeBtn.addEventListener("click", () => {
                    container.removeChild(removeBtn.offsetParent);

                    // Cập nhật lại danh sách đơn hàng sau khi xóa
                    orders = orders.find((o) => o.madonhang === order.madonhang);
                    orders.trangthai = 4;
                    UpdateOrder(orders);
                    // Thay đổi container khi không còn sản phẩm
                    if (container.childNodes.length === 0) blankOrder(elementsObj);
               });

               container.appendChild(script);
               formatPrices(elementsObj);
          });

     }

     if (container.childNodes.length === 0) blankOrder(elementsObj);
}


function blankOrder(elementsObj) {
     let orderContainer = elementsObj.getOrderContent();
     hiddenException("order-content");
     disableSiblingContainer(orderContainer);
     // navigate to index.html if not have any container
     let statusContainer = orderContainer.querySelector(".order-status-container");
     disableSiblingContainer(statusContainer);
     orderContainer?.classList.remove("disable");
     statusContainer?.classList.remove("disable");
     elementsObj.getBlankOrder().classList.add("active");
     return;
}

// update date deliveried
function addDaysToDate(dateString, daysToAdd) {
     const date = new Date(dateString); // Chuyển chuỗi thành đối tượng Date
     if (isNaN(date)) {
          throw new Error("Invalid date format");
     }

     date.setDate(date.getDate() + daysToAdd); // Thêm 3 ngày vào ngày hiện tại
     return date; // Trả về đối tượng Date mới
}


//! set quantity box on detail product
async function setQuantityBox(elementsObj) {
     const box = elementsObj.getQuantityBox();
     const reduceBtn = box.querySelector("input[type=button].reduce");
     const increaseBtn = box.querySelector("input[type=button].increase");
     const quantity = box.querySelector("input[type=text]#quantity");
     const productID = Bridge.$(".product-id")?.innerHTML;
     const product = (await GetProducts()).find((product) => product.masp === productID);
     const realQuantity = (await GetDetailPRoducts(product?.masp)).soluongton;

     reduceBtn.addEventListener("click", () => {
          const value = parseInt(quantity.value) || 1;
          quantity.value = Math.max(1, value - 1);
     });

     increaseBtn.addEventListener("click", () => {
          const value = parseInt(quantity.value) || 1;
          quantity.value = Math.min(realQuantity, value + 1);
     });

     quantity.addEventListener("change", () => {
          let value = parseInt(quantity.value);
          if (isNaN(value) || value < 1) value = 1;
          if (value > realQuantity) value = realQuantity;
          quantity.value = value;
     });
}


// handle scrolls
function scrollToHandler(nameStaticPage) {
     let staticPage;
     const elementsObj = Bridge.default();

     if (nameStaticPage === "news") {
          staticPage = elementsObj.getNewsBlogs();
          hiddenException();
     }

     if (nameStaticPage === "services") staticPage = elementsObj.getFooter();
     else if (!staticPage && nameStaticPage === "services") {
          alert("not found services!");
          return false;
     }

     // check if action is scroll to top or not
     if (nameStaticPage === "top")
          window.scroll({ top: 0, left: 0, behavior: "smooth" });
     else if (staticPage)
          window.scroll({
               top: staticPage.offsetTop + 3 * 16,
               left: 0,
               behavior: "smooth",
          });

     // check if action is scroll to top or not
     if (nameStaticPage === "top")
          window.scroll({ top: 0, left: 0, behavior: "smooth" });
     else if (staticPage)
          window.scroll({
               top: staticPage.offsetTop + 3 * 16,
               left: 0,
               behavior: "smooth",
          });
}

// func for click nav btn on sub header or click to scroll top btn
function staticContents(elementsObj) {
     // const newsButtons = elementsObj.getNewsBtn();
     const scrollTopButtons = elementsObj.getScrollTop();
     const servicesButtons = elementsObj.getServicesBtn();

     // add event listener
     // if (newsButtons) {
     //      newsButtons.forEach((btn) => {
     //           btn.addEventListener(
     //                "click",
     //                Bridge.throttle(() => scrollToHandler("news"), 200, "newsBtn")
     //           );
     //      });
     // }

     if (servicesButtons) {
          servicesButtons.forEach((btn) => {
               btn.addEventListener(
                    "click",
                    Bridge.throttle(() => scrollToHandler("services"), 200, "servicesBtn")
               );
          });
     }

     if (scrollTopButtons) {
          scrollTopButtons.addEventListener(
               "click",
               Bridge.throttle(() => scrollToHandler("top"), 200, "ScrollTopBtn")
          );
     }
}

// DOM navigate handler (SPAs)
// func account's events handle
function accountEvents(elementsObj) {
     const loginButtons = elementsObj.getJsLoginBtn();
     let loginForm = Bridge.$("#login");
     const registerButtons = elementsObj.getJsRegisterBtn();
     let registForm = Bridge.$("#register");
     const forgotButtons = elementsObj.getJsForgotBtn();
     let forgotForm = Bridge.$("#forgot-password");
     const signoutButtons = elementsObj.getJsSignoutBtn();

     loginButtons?.forEach((btn) =>
          btn.addEventListener("click", Bridge.throttle(() => showLogin(loginForm, registForm, forgotForm), 200, "login")));

     registerButtons?.forEach((btn) =>
          btn.addEventListener("click", Bridge.throttle(() => showRegister(loginForm, registForm, forgotForm), 200, "register")));

     forgotButtons?.forEach((btn) =>
          btn.addEventListener("click", Bridge.throttle(() => showForgotPassword(loginForm, registForm, forgotForm), 200, "forgotPassword")));

     signoutButtons?.forEach((btn) => btn.addEventListener("click", Bridge.throttle(() => singoutAccount(elementsObj), 200, "signout")));
}

// for login
function showLogin(loginForm, registForm, forgotForm) {
     hiddenException("account-content");
     if (!loginForm) {
          sessionStorage.setItem("login", true);
          Bridge.navigateRootURL();
     }
     loginForm?.classList.add("active");
     registForm?.classList.contains("active") ? registForm.classList.remove("active") : registForm;
     forgotForm?.classList.contains("active") ? forgotForm.classList.remove("active") : forgotForm;
     scrollView();
}

// for register
function showRegister(loginForm, registForm, forgotForm) {
     hiddenException("account-content");
     if (!registForm) {
          sessionStorage.setItem("register", true);
          Bridge.navigateRootURL();
     }
     registForm?.classList.add("active");
     loginForm?.classList.contains("active") ? loginForm.classList.remove("active") : loginForm;
     forgotForm?.classList.contains("active") ? forgotForm.classList.remove("active") : forgotForm;
     scrollView();
}

// for show forgot password
function showForgotPassword(loginForm, registForm, forgotForm) {
     if (!forgotForm) {
          sessionStorage.setItem("forgotPassword", true);
          Bridge.navigateRootURL();
     }
     hiddenException("account-content");
     forgotForm?.classList.add("active");
     loginForm?.classList.contains("active") ? loginForm.classList.remove("active") : loginForm;
     registForm?.classList.contains("active") ? registForm.classList.remove("active") : registForm;
     scrollView();
}

// for signout account
function singoutAccount(elementsObj) {
     sessionStorage.removeItem("hasLogin");
     sessionStorage.removeItem("hasLoginAccount");
     headerUserInfo(elementsObj);
     Bridge.navigateRootURL();
}

function changeInfoUser(elementsObj) {
     let editInfoBtn = elementsObj.getJsEditBtn();
     let submitInfoBtn = elementsObj.getJsSubmitBtn();
     let userCard = elementsObj.getUserCard();
     let accountLogin = JSON.parse(sessionStorage.getItem("hasLoginAccount"));
     let inputFields = userCard.querySelectorAll("input");
     // change default value to user value
     inputFields.forEach((fields) => {
          if (fields.getAttribute("id") === "user-password") {
               fields.style.border = "none";
               fields.style.outline = "none";
          }
          fields.style.borderLeft = "1px solid var(--main-color)";
          if (fields.getAttribute("id") === "user-last-name")
               fields.value = accountLogin.lastName;
          if (fields.getAttribute("id") === "user-first-name")
               fields.value = accountLogin.firstName;
          if (fields.getAttribute("id") === "user-email")
               fields.value = accountLogin.email;
          if (fields.getAttribute("id") === "user-password")
               fields.value = accountLogin.password;
          if (fields.getAttribute("id") === "user-phone")
               fields.value = accountLogin.phone ? accountLogin.phone : "";
          if (fields.getAttribute("id") === "user-address")
               fields.value = accountLogin.address ? accountLogin.address : "";
     });

     // new info object
     let newInfo = {
          userID: accountLogin.userID,
          password: accountLogin.password,
     };
     // edit btn 
     editInfoBtn.addEventListener("click", Bridge.throttle(() => {
          inputFields.forEach((fields) => fields.removeAttribute("disabled"));
          sessionStorage.setItem("editing", true);
     }, 200, "editInfo"));
     // submit btn
     submitInfoBtn.addEventListener("click", Bridge.throttle(() => {
          if (JSON.parse(sessionStorage.getItem("editing"))) {
               // disable edit mode
               inputFields.forEach((fields) => {
                    // get new info
                    if (fields.getAttribute("id") === "user-last-name")
                         newInfo.lastName = fields.value;
                    if (fields.getAttribute("id") === "user-first-name")
                         newInfo.firstName = fields.value;
                    if (fields.getAttribute("id") === "user-email")
                         newInfo.email = fields.value;
                    if (fields.getAttribute("id") === "user-password")
                         newInfo.password = fields.value;
                    if (fields.getAttribute("id") === "user-phone")
                         newInfo.phone = fields.value;
                    if (fields.getAttribute("id") === "user-address")
                         newInfo.address = fields.value;
                    fields.setAttribute("disabled", "");
               });
               // change account handler
               updateListAccount(newInfo);
               sessionStorage.setItem("editing", false);
          }
     }, 200, "submitNewInfo"));
}

function updateListAccount(newInfo) {
     let listAccount = JSON.parse(localStorage.getItem("users"));
     sessionStorage.setItem("hasLoginAccount", JSON.stringify(newInfo));
     console.log(listAccount);
     listAccount.forEach((account) => {
          if (account.userID === newInfo.userID) {
               account.password = newInfo.password;
               account.email = newInfo.email;
               account.lastName = newInfo.lastName;
               account.firstName = newInfo.firstName;
               account.phone = newInfo.phone;
               account.address = newInfo.address;
          }
     });
     console.log(listAccount);
     localStorage.setItem("users", JSON.stringify(listAccount));
}

export { cancelButtons, accountEvents, staticContents, historyNavigate, setQuantityBox, returnHomepage, trackingNavigate, smNavigationMenu };
export { showOrderContent, showTracking, showLogin, showRegister, showForgotPassword };
