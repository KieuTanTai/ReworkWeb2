"use strict";
import * as Bridge from "./bridges.js";
import * as FlashSale from "./flashsale.js";
import { sleep } from "./navigates.js";
import * as RenderProducts from "./product.js";
import * as Get from "./getdata.js";
import { attachAddToCartEvents, attachAddToCartInDetails, updateCartCount } from "./carts.js";

function scrollView() {
  let webContent = Bridge.default().getWebContent();
  // webContent.scrollIntoView({ behavior: "auto", block: "start", inline: "nearest" });
  window.scrollTo(0, 0);
}

// change header info when user has been login
// change header info when user has been login
function headerUserInfo(elementsObj) {
  let container = elementsObj.getHeaderUserInfo();
  let noSignIn = elementsObj.getNoSignIn();
  let loginBtn = elementsObj.getJsLoginBtn();
  let registerBtn = elementsObj.getJsRegisterBtn();
  let logoutBtn = elementsObj.getJsSignoutBtn();
  let userName = container.querySelector(".user-name");
  
  // Sử dụng session từ PHP (đã được chuyển sang sessionStorage)
  const isLoggedIn = sessionStorage.getItem("login") === 'true';
  let userAccount = null;
  
  try {
      userAccount = JSON.parse(sessionStorage.getItem("loginAccount"));
  } catch (e) {
      console.error("Error parsing loginAccount:", e);
  }
  
  if (isLoggedIn && userAccount) {
      noSignIn.classList.add("disable");
      // disable login - register btn
      loginBtn?.forEach((btn) => btn.classList.add("disable"));
      registerBtn?.forEach((btn) => btn.classList.add("disable"));
      logoutBtn?.forEach((btn) => btn.classList.remove("disable"));
      container.classList.remove("disable");
      
      // Hiển thị tên người dùng từ session
      userName.innerHTML = userAccount.tenkhachhang || userAccount.user_name || "Người dùng";
  }
  else {
      noSignIn.classList.remove("disable");
      loginBtn?.forEach((btn) => btn.classList.remove("disable"));
      registerBtn?.forEach((btn) => btn.classList.remove("disable"));
      logoutBtn?.forEach((btn) => btn.classList.add("disable"));
      container.classList.add("disable");
      userName.innerHTML = "";
  }
}

// show and hide specific form
function hiddenException(exception) {
  exception = !exception ? "index-content" : exception;
  if(exception === "index-content")
    location.href = `${location.origin}/public`;
  let getHandler = Bridge.default();
  let container = getHandler.getMainContent()?.children;
  // let newsContainer = getHandler.getNewsBlogs();
  container = Array.of(...container);

  container.forEach((element) => {
    if (element.getAttribute("id") !== exception)
      element.classList.add("disable");
    else {
      element.classList.remove("disable");
      element.style.display = "block";
    }
  });
}

// funcs event
function disableSiblingContainer(container) {
  if (!container) return;
  Array.of(...container.children).forEach((child) => {
    child.classList.contains("active") ? child.classList.remove("active") : child;
    child.offsetWidth > 0 ? child.classList.add("disable") : child;
  });
}
//! disable for show more product of specific container
function disableForShowMore(container, target) {
  if (!container || !target)
    return;
  Array.of(...container.children).forEach((child) => {
    if (child.getAttribute("id") != target) {
      child.classList.add("disable");
    }
  });
}

async function fakeOverlay(container, time) {
  const overlay = document.createElement('div');
  overlay.className = 'overlay';
  overlay.innerHTML = 'Loading... :3';
  overlay.style.display = 'flex';
  overlay.style.alignItems = 'center';
  overlay.style.justifyContent = 'center';
  overlay.style.fontSize = 3 + "em";
  overlay.style.color = "var(--primary-white)";
  document.body.appendChild(overlay);
  await sleep(time);
  scrollView();
  // scroll before show DOM
  await sleep(time); // fake loading
  document.body.removeChild(overlay);
  container.removeAttribute("style");
  container.classList.remove("hidden");
}

// check container is empty or not
function isEmpty(container) {
  let children = container?.children;
  if (!children) return true;
  if (children.length === 0) return true;
  for (let child of children)
    if (child.classList.contains("empty-mess")) return true;
  return false;
}

// format prices from default to vi-VN format
function formatPrices(elementsObj) {
  const pricesContainer = elementsObj.getElementPrices();
  if (pricesContainer) {
    const formatPricesHandler = new Intl.NumberFormat("vi-VN", {
      style: "currency",
      currency: "VND",
      minimumSignificantDigits: "3",
    });
    pricesContainer.forEach((element) => {
      if (!element.innerHTML.includes("₫"))
        element.innerText = formatPricesHandler.format(element.innerText);
    });
  }
}

//change DOM on categories if it not have any product inside
function categoryIsEmpty() {
  Bridge.default().getCategories().forEach((category) => {
    const container = category.querySelector(".product-container");
    if (isEmpty(container)) {
      container.innerHTML = '<div class="empty-mess font-size-20 font-bold">Không có sản phẩm trong phần này</div>';
      container.classList.add("flex", "full-height", "align-center", "justify-center");
      container.querySelector(".nav-btn")?.classList.add("disable");
      category.querySelector(".category-btn")?.classList.add("disable");
    }
  });
}

// fix bug interface func
// resize image
function resizeImages(elementsObj) {
  const ratio = 333.5 / 216;
  const productImages = elementsObj.getImages();

  if (!productImages) {
    alert("not found any image!");
    return false;
  }
  productImages.forEach((image) => {
    image.addEventListener("load", () => {
      let imageWidth = image.offsetWidth;
      image.style.height = ratio * imageWidth + "px";
    });

    if (image && !image.getAttribute("style")) {
      let imageWidth = image.offsetWidth;
      image.style.height = ratio * imageWidth + "px";
    }
  });
  window.addEventListener("resize", () => {
    productImages.forEach((image) => {
      let imageWidth = image.offsetWidth;
      image.style.height = ratio * imageWidth + "px";
    });
  });
}

// resize width of nav
function resizeSmNav(elementsObj) {
  const subMenuNav = elementsObj.getSubMenuNav();

  if (!subMenuNav) {
    alert("not found nav!");
    return false;
  }

  const childInner = subMenuNav.firstElementChild;
  let parentWidth = subMenuNav.offsetWidth;
  childInner.style.width = parentWidth / 16 + "em";

  window.addEventListener("resize", function () {
    parentWidth = subMenuNav.offsetWidth;
    if (!childInner.width === "unset") childInner.style.width = "unset";
    else childInner.style.width = parentWidth / 16 + "em";
  });
}

async function getInitProducts(elementsObj) {
  try {
    // const storage = await fetch("assets/js/storage.js");
    // const jsonArray = await storage.json();
    // const productsList = Array.from(jsonArray);
    // localStorage.setItem("products", JSON.stringify(productsList));
    let getProducts =Array.from(await Get.GetProducts());

    // render init products
    await RenderProducts.productContainers(getProducts);
    FlashSale.setTimeFS(elementsObj);
    attachAddToCartEvents();
    await attachAddToCartInDetails();
    formatPrices(elementsObj);
    resizeImages(elementsObj);
    categoryIsEmpty();
    // console.log(await Get.GetOrdersByCustomer(JSON.parse(sessionStorage.getItem("loginAccount"))["makh"]));
    // console.log(await Get.GetOrders());
  } catch (error) {
    console.error(error);
  }
}

export { formatPrices, isEmpty, categoryIsEmpty, getInitProducts, hiddenException, scrollView, fakeOverlay, disableSiblingContainer };
export { resizeSmNav, resizeImages, headerUserInfo };
