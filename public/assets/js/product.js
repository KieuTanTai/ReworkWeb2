"use strict";
import { setQuantityBox } from "./action.js";
import * as Bridge from "./bridges.js";
import { fakeOverlay, formatPrices, hiddenException, isEmpty, resizeImages, scrollView } from "./interfaces.js";
import * as Navigate from "./navigates.js";
import { GetColor, GetDetailPRoducts, GetProducts, GetRam, GetRom } from "./getdata.js";
import { attachAddToCartEvents, attachAddToCartInDetails } from "./carts.js";
//! get / set products (NEED TO CHANGE)
function getProductPhones() {
  return GetProducts();
}

function getDetailPhones(id) {
  return GetDetailPRoducts(id);
}

function getColors() {
  return GetColor();
}

function getRams() {
  return GetRam();
}

function getRoms() {
  return GetRom();
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
  let productSale = 0.29, productPrice = await GetDetailPRoducts(product.masp);
  productPrice = productPrice.giaban;
  let srcImage = `${'assets/images/' + product.hinhanh}`;
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
  imageContainer.onerror = function() {
    this.onerror = null; // Ngăn chặn lặp vô hạn nếu ảnh mặc định cũng lỗi
    this.src = 'assets/images/vn-11134207-7ras8-m2nn2bl6q4922e.jpg'; // Thay đổi đường dẫn ảnh mặc định tại đây
  };
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
  if (quantity <= 0) {
    buttons.forEach((button) => button.classList.add("disable"));
    quantityBox.classList.add("disable")
  }

  setQuantityBox(Bridge.default());

  // call other functions
  // productContainers(list, sameAuthor);
  displayDefaultSpect(product);
  displayProductDetails(product);
  productContainers(await getProductPhones(), productLike);
  callFuncsAgain(elementsObj);
  renderColorOptions(product);
  renderStorageOptions(product);
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
        Bridge.default().getProductLikeContainer().style.display = "flex";
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

    let productDetails = await GetDetailPRoducts(product.masp);
    let discountedPrice = Math.round(productDetails.giaban * (1 - 0.29));
html += `
      <div class="product-item grid-col col-l-2-4 col-m-3 col-s-6">
        <div class="block-product product-resize">
          <span class="product-image js-item">
            <img src="${'assets/images/' + product.hinhanh}" alt="${product.tensp}" onerror="this.onerror=null; this.src='assets/images/vn-11134207-7ras8-m2nn2bl6q4922e.jpg';">
          </span>

          <div class="product-detail-hidden disable selected">
            <div class="detail-item ram" data-ram="${productDetails.ram}">${await getRamText(productDetails.ram)} GB RAM</div>
            <div class="detail-item rom" data-rom="${productDetails.rom}">${await getRomText(productDetails.rom)} GB ROM</div>
            <div class="detail-item mausac" data-mausac="${productDetails.mausac}">${await getColorText(productDetails.mausac)}</div>
            <div class="detail-item price" data-price="${productDetails.giaban * (1 - 0.29)}">${productDetails.giaban.toLocaleString('vi-VN')} ₫</div>
            <div class="detail-item oldprice" data-oldprice="${productDetails.giaban}">
              ${(productDetails.giaban).toLocaleString('vi-VN')} ₫
            </div>
          </div>

          <div class="sale-label">${Math.round(0.29 * 100)}%</div>
          <div class="sale-off font-bold capitalize ${product.trangthai > 0 ? "" : "active"}">hết hàng</div>
          <div class="info-inner flex justify-center align-center line-height-1-6">
            <h4 class="font-light capitalize" title="${product.tensp}">${product.tensp}</h4>
            <div class="margin-y-4">
              <span class="price font-bold">${discountedPrice}</span>
              <del class="price old-price padding-left-8 font-size-14">${productDetails.giaban}</del>
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
  formatPrices(Bridge.default());
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
        list = productsList.sort((a, b) => a.hedieuhanh - b.hedieuhanh).toSpliced(5);
      else {
        list = productsList.sort((a, b) => b.chipxuly - a.chipxuly).toSpliced(5);
      }

      await renderProducts(list, wrapper); // chờ render xong
    }
  } else {
    let wrapper = container.querySelector(".product-container");
    let containerID = container.getAttribute("id");
    let list;

    if (wrapper && containerID === "product-like-container")
      list = productsList.filter((product) => (product.tensp)?.includes(Bridge.$(".product-tags div:first-child p")?.innerHTML)).toSpliced(5);

    await renderProducts(list, wrapper); // chờ render xong
  }

  if (isEmpty(container)) return;
}
//! NEED TO CHANGE HERE
async function displayProductDetails(product) {
  const detail = await GetDetailPRoducts(product.masp);

  const ram = await getRamSize(detail?.ram);
  const rom = await getRomSize(detail?.rom);

  const html = `
    <div class="grid wide">
      <div class="grid-row justify-center">
        <div class="overlay-content bg-white grid-col col-l-4 bg-white padding-12 border-1-solid-black">
          <h2>Thông tin chi tiết</h2>
          <ul id="specs-list">
            <li><strong>Thông tin chung</strong></li>
            <li class="product-os">Hệ điều hành: <div>${product.os || 'Không có thông tin'}</div></li>
            <li class="product-language">Ngôn ngữ: ${product.language || 'Tiếng Việt, đa ngôn ngữ'}</li>
            
            <li><strong>Màn hình</strong></li>
            <li class="product-monitor">
              Loại màn hình: ${product.monitorType || 'Không có thông tin'}
              ${product.monitorColor ? `<br>Màu màn hình: ${product.monitorColor}` : ''}
              ${product.monitorStandard ? `<br>Chuẩn màn hình: ${product.monitorStandard}` : ''}
              ${product.kichthuocman ? `<br>${product.kichthuocman}"` : ''}
              ${product.screenGlass ? `<br>${product.screenGlass}` : ''}
            </li>
            <li class="product-resolution">Độ phân giải: ${product.resolution || 'Không có thông tin'}</li>
            
            <li><strong>Camera</strong></li>
            <li class="product-rear-camera">Camera sau: ${product.camerasau || 'Không có thông tin'}
              ${product.videoRecording ? `<br>Quay phim: ${product.videoRecording}` : ''}
              ${product.flash ? `<br>Đèn Flash: ${product.flash}` : ''}
            </li>
            <li class="product-font-camera">Camera trước: ${product.cameratruoc || 'Không có thông tin'}</li>
            
            <li><strong>CPU & RAM</strong></li>
            <li class="product-cpu">Chipset: ${product.chipxuly || 'Không có thông tin'}
              ${product.cpuCores ? `<br>Số nhân: ${product.cpuCores}` : ''}
            </li>
            <li class="product-ram">RAM: ${ram ? ram + " GB" : "Không có thông tin"}</li>
            
            <li><strong>Bộ nhớ & Lưu trữ</strong></li>
            <li class="product-rom">Bộ nhớ trong: ${rom ? rom + " GB" : "Không có thông tin"}</li>
            
            <li><strong>Pin & Sạc</strong></li>
            <li class="product-battery">Dung lượng pin: ${product.dungluongpin || 'Không có thông tin'} mAh
              ${product.batteryType ? `<br>Loại pin: ${product.batteryType}` : ''}
            </li>
            <li class="product-battery-tech">Công nghệ pin: ${product.batteryTech || 'Không có thông tin'}</li>
            <li class="product-charging-port">Cổng sạc: ${product.chargingPort || 'Không có thông tin'}</li>
          </ul>
          <div class="grid-col col-l-12 justify-end flex">          
              <button onclick="closeOverlay()" class="more-detail-form-close button bg-main-color text-white padding-12 margin-top-12">Đóng</button>
          </div>
        </div>
      </div>
    </div>
  `;

  document.getElementById('overlay-more-detail').innerHTML = html;
}

async function displayDefaultSpect(product) {
  const detailList = await GetDetailPRoducts(product.masp);
  const detail = Array.isArray(detailList) ? detailList[0] : detailList;
  if (!detail) return;

  const rom = `${await getRomSize(detail.rom)}GB`;
  const ram = `${await getRamSize(detail.ram)}GB`;
  const osName = product.hedieuhanh;

  const html = `
    <ul>
      <li class="product-monitor"><strong>Màn hình:</strong> ${product.kichthuocman}</li>
      <li class="product-os"><strong>Hệ điều hành:</strong> ${osName}</li>
      <li class="product-rear-camera"><strong>Camera sau:</strong> ${product.camerasau}</li>
      <li class="product-font-camera"><strong>Camera trước:</strong> ${product.cameratruoc}</li>
      <li class="product-cpu"><strong>CPU:</strong> ${product.chipxuly}</li>
      <li class="product-ram"><strong>RAM:</strong> ${ram}</li>
      <li class="product-rom"><strong>Bộ nhớ trong:</strong> ${rom}</li>
      <li class="product-battery"><strong>Dung lượng pin:</strong> ${product.dungluongpin} mAh</li>
    </ul>
  `;

  document.getElementById("some-product-detail").innerHTML = html;
}

async function renderColorOptions(product) {
  const details = (await GetDetailPRoducts()).filter(d => d.masp == product.masp);
  const colors = await getColors();
  const uniqueColors = new Set();
  let html = "";

  for (const detail of details) {
    if (uniqueColors.has(detail.mausac)) continue;
    uniqueColors.add(detail.mausac);

    const color = colors.find(c => c.mamau == detail.mausac);
    if (color) {
      html += `
        <div class="color-option col-l-3 margin-right-8 padding-bottom-4 padding-top-4 border-1-solid-black text-white"
             data-value="${detail.mausac}">
          ${color.tenmau.toLowerCase()}
        </div>
      `;
    }
  }

  const container = document.querySelector(".color-options");
  container.innerHTML = html;

  container.querySelectorAll(".color-option").forEach(option => {
    option.addEventListener("click", () => {
      container.querySelectorAll(".color-option").forEach(el => el.classList.remove("selected"));
      option.classList.add("selected");
    });
  });
}

async function renderStorageOptions(product) {
  const details = (await GetDetailPRoducts()).filter(p => p.masp === product.masp);
  const rams = await getRams();
  const roms = await getRoms();
  const container = document.querySelector(".storage-options");
  if (!container) return;

  let html = "";
  const uniqueCombo = new Set();

  details.forEach(detail => {
    const comboKey = `${detail.ram}-${detail.rom}`;
    if (uniqueCombo.has(comboKey)) return;
    uniqueCombo.add(comboKey);

    const ram = rams.find(r => r.madlram == detail.ram);
    const rom = roms.find(r => r.madlrom == detail.rom);
    const ramText = ram ? ram.kichthuocram + "GB" : "RAM?";
    const romText = rom ? rom.kichthuocrom + "GB" : "ROM?";
    const price = detail.giaban || 0;
    const oldPrice = detail.giagoc || price;

    html += `
      <div class="storage-option padding-bottom-4 padding-top-4 font-size-14 col-l-6 border-1-solid-black button bg-dark"
           data-ram="${detail.ram}" data-rom="${detail.rom}" 
           data-price="${price}" data-oldprice="${oldPrice}">
        ${romText} + ${ramText}
      </div>`;
  });

  container.innerHTML = html;

  // Gán sự kiện click để cập nhật giá
  container.querySelectorAll(".storage-option").forEach(option => {
    option.addEventListener("click", function () {
      container.querySelectorAll(".storage-option").forEach(el => el.classList.remove("selected"));
      this.classList.add("selected");

      const price = parseInt(this.dataset.price);
      const discountPrice = Math.round(price * (1 - 0.29));
      const formattedPrice = discountPrice.toLocaleString("vi-VN");
      const formattedOldPrice = price.toLocaleString("vi-VN");

      document.querySelector(".new-price").innerHTML = `${formattedPrice}&nbsp;₫`;
      document.querySelector(".old-price").innerHTML = `${formattedOldPrice}&nbsp;₫`;

      console.log(`💰 Giá mới: ${formattedPrice} - Giá gốc: ${formattedOldPrice}`);
    });
  });
}


async function getRamText(ramId) {
  const rams = await getRams();
  const ram = rams.find(r => r.madlram == ramId);
  return ram ? `${ram.kichthuocram}GB` : null;
}

async function getRomText(romId) {
  const roms = await getRoms();
  const rom = roms.find(r => r.madlrom == romId);
  return rom ? `${rom.kichthuocrom}GB` : null;
}

async function getColorText(colorId) {
  const colors = await getColors();
  const color = colors.find(c => c.mamau == colorId);
  return color ? color.tenmau : null;
}

function getFormattedPrice(price, discount = 0.29) {
  const discounted = Math.round(price * (1 - discount));
  return discounted.toLocaleString("vi-VN") + " ₫";
}

async function getRamSize(id) {
  const rams = await getRams();
  return rams.find(r => r.madlram == id)?.kichthuocram || "0";
}

async function getRomSize(id) {
  const roms = await getRoms();
  return roms.find(r => r.madlrom == id)?.kichthuocrom || "0";
}

export { getProductPhones, setProductBooks, productContainers, getValueQuery, renderProductDetails, renderProducts, dynamicDetail, callFuncsAgain };
export { getRoms, getRams, getColors, getDetailPhones }
