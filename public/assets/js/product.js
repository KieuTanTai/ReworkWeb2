"use strict";
import { setQuantityBox } from "./action.js";
import * as Bridge from "./bridges.js";
import { fakeOverlay, formatPrices, hiddenException, isEmpty, resizeImages, scrollView } from "./interfaces.js";
import * as Navigate from "./navigates.js";
import { GetDetailPRoducts, GetProducts } from "./getdata.js";
import { attachAddToCartEvents, attachAddToCartInDetails } from "./carts.js";
//! get / set products (NEED TO CHANGE)
function getProducPhones() {
  return GetProducts();
}

function getDetailPhones(id) {
  return GetDetailPRoducts();
}

function setProductBooks(product) {
  localStorage.setItem("products", JSON.stringify(product));
}

function getValueQuery(request) {
  let newURL = new URLSearchParams(window.location.search);
  let query = newURL.get(request);
  return query === "undefined" ? undefined : query;
}

//! detail product
async function dynamicDetail(product) {
  // ?product detail (NEED TO CHANGE HERE)
  if (!product) return;
  let container = Bridge.default().getMainContent();
  const elementsObj = Bridge.default();
  let currentTitle = Bridge.$("title");
  let quantity = 1 //product.quantity ;
  let productSale = 0.29, productPrice = 10000000;
  let srcImage = "assets/images/Phone/RedMagics/vn-11134207-7ras8-m2nn2bl6q4922e.jpg" //product.hinhanh;
  let productName = product.tensp, id = product.masp;
  // let productCategories = product.category;

  // !Container for detail
  let saleLabel = container.querySelector(".detail-block .sale-label");
  let imageContainer = container.querySelector(".detail-block .product-image img");
  let productTitle = container.querySelector(".product-title h1");
  let productID = container.querySelector(".product-title .product-id");
  let price = container.querySelector(".block-product-price");
  let quantityBox = container.querySelector(".quantity-box");
  let buttons = container.querySelectorAll(".button");
  //!for selections
  let listOptions = container.querySelector(".product-selector");
  let selectOptions = Array.from(listOptions?.children);
  let productLike = container.querySelector("#product-like-container");

  // fakeOverlay(container);
  // !set data (CONTINUE) GET FIELDS ON OBJ -> ADD DATA TO DOM 
  currentTitle.innerText = productName;
  // img
  imageContainer.setAttribute("src", srcImage);
  imageContainer.setAttribute("alt", productName);
  imageContainer.style.width = 80 + "%";
  // other details
  saleLabel.innerText = Math.round(productSale * 100) + "%";
  productTitle.innerText = productName;
  productID.innerText = id;

  await Navigate.sleep(50);
  fakeOverlay(container, 150);

  // price
  (Array.from(price.children)).forEach(() => {
    let oldPrice = price.querySelector(".old-price");
    let newPrice = price.querySelector(".new-price");
    if (oldPrice)
      oldPrice.innerText = productPrice;
    if (newPrice)
      newPrice.innerText = Math.round(productPrice * (1 - productSale));
  });
  // remove not exist selections
  // if (!productCategories)
  //   selectOptions.remove();
  // if (!productCategories.includes("normal"))
  //   (selectOptions.find((child) => child.value === "normal"))?.remove();
  // if (!productCategories.includes("special"))
  //   (selectOptions.find((child) => child.value === "special"))?.remove();
  // if (productCategories.includes("collectible"))
  //   listOptions.innerHTML = "<option value=\"collectible\">bản sưu tập</option>"

  // execute buttons when quantity > 0 or not 
  if (quantity <= 0) {
    buttons.forEach((button) => button.classList.add("disable"));
    quantityBox.classList.add("disable")
  }

  setQuantityBox(Bridge.default());

  // call other functions
  // productContainers(list, sameAuthor);
  productContainers(Array.of(await getProducPhones()), productLike);
  callFuncsAgain(elementsObj);
}

async function callFuncsAgain(elementsObj) {
  resizeImages(elementsObj);
  formatPrices(elementsObj);
}

//! for show detail products
async function renderProductDetails(list, wrapper) {
  try {
    // first param on fields requestRest when renderDOM is now object item
    if (!list || !wrapper) return;
    let arrayChild = Array.from(wrapper.children);
    arrayChild?.forEach((child, index) => {
      child.querySelector(".block-product")?.addEventListener("click", () => {
        let bookName = (list[index].tensp).replaceAll("&", "").replaceAll("!", "").replaceAll(" ", "-");
        // change path with path request
        let newURL = `${location.href.slice(0, location.href.lastIndexOf("/") + 1)}?name=${bookName}`;
        window.history.pushState({}, "", newURL);
        hiddenException("detail-content");
        dynamicDetail(list[index]);
      });
    });
  } catch (error) {
    console.error(error);
  }
}

//! render products
async function renderProducts(list, wrapper) {
  if (!list || !wrapper) return;

  let html = "";
  for (let product of list) {
    html += `
      <div class="product-item grid-col col-l-2-4 col-m-3 col-s-6">
        <div class="block-product product-resize">
          <span class="product-image js-item">
            <img src="assets/images/Phone/RedMagics/vn-11134207-7ras8-m2nn2bl6q4922e.jpg" alt="${product.tensp}">
          </span>
          <div class="sale-label">${Math.round(0.29 * 100)}%</div>
          <div class="sale-off font-bold capitalize ${product.trangthai > 0 ? "" : "active"}">hết hàng</div>
          <div class="info-inner flex justify-center align-center line-height-1-6">
            <h4 class="font-light capitalize" title="${product.tensp}">${product.tensp}</h4>
            <div class="margin-y-4">
              <span class="price font-bold">${Math.round(10000000 * (1 - 0.29))}</span>
              <del class="price old-price padding-left-8 font-size-14">${10000000}</del>
            </div>
          </div>
        </div>
        <div class="action ${product.trangthai > 0 ? "" : "disable"}">
          <div class="buy-btn">
            <div title="mua ngay" class="button">
              <i class="fa-solid fa-bag-shopping fa-lg" style="color: var(--primary-white);"></i>
            </div>
          </div>
          <div class="add-to-cart">
            <div title="thêm vào giỏ hàng" class="button">
              <i class="fa-solid fa-basket-shopping fa-lg" style="color: var(--primary-white);"></i>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  wrapper.innerHTML = html;
  // Chờ những thao tác DOM sau khi render hoàn thành
  renderProductDetails(list, wrapper);
  // attachAddToCartEvents();
  // await attachAddToCartInDetails();
}


//! get container for product and call render products
async function productContainers(productsList, container) {
  if (!productsList) return;

  let listLength = productsList.length;

  if (!container) {
    let containers = Bridge.$$(".container");
    if (!containers) return;

    for (let container of containers) {
      let list;
      let containerID = container.getAttribute("id");
      let wrapper = container.querySelector(".product-container");
      if (!wrapper || !isEmpty(wrapper)) continue;

      if (containerID === "fs-container")
        list = productsList.sort((a, b) => b.dungluongpin - a.dungluongpin).toSpliced(5);
      else if (containerID === "new-phones-container")
        list = productsList.toSpliced(0, listLength - 5);
      else if (containerID === "samsung-phone-container")
        list = productsList.filter((product) => (product.thuonghieu.toLowerCase()).includes("samsung")).toSpliced(5);
      else if (containerID === "ip-container")
        list = productsList.filter((product) => (product.thuonghieu.toLowerCase()).includes("iphone")).toSpliced(5);
      else if (containerID === "other-phones-container")
        list = productsList.sort((a, b) => a.releaseDate - b.releaseDate).toSpliced(5);
      else
        list = productsList.sort((a, b) => a.author - b.author).toSpliced(5);

      await renderProducts(list, wrapper); // chờ render xong
    }
  } else {
    let wrapper = container.querySelector(".product-container");
    let containerID = container.getAttribute("id");
    let list;

    if (wrapper && containerID === "product-like-container")
      list = productsList.filter((product) =>
        (product.tensp)?.includes(Bridge.$(".product-tags div:first-child p")?.innerHTML)
      ).toSpliced(5);

    await renderProducts(list, wrapper); // chờ render xong
  }

  if (isEmpty(container)) return;
}


export { getProducPhones, setProductBooks, productContainers, getValueQuery, renderProductDetails, renderProducts, dynamicDetail, callFuncsAgain };
