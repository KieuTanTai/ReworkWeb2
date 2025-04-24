"use strict";
import { setQuantityBox } from "./action.js";
import * as Bridge from "./bridges.js";
import { fakeOverlay, formatPrices, hiddenException, isEmpty, resizeImages, scrollView } from "./interfaces.js";
import * as Navigate from "./navigates.js";
import { GetColor, GetDetailPRoducts, GetProducts, GetRam, GetRom } from "./getdata.js";
import { attachAddToCartEvents, attachAddToCartInDetails } from "./carts.js";
//! get / set products (NEED TO CHANGE)
function getProducPhones() {
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
  if (quantity <= 0) {
    buttons.forEach((button) => button.classList.add("disable"));
    quantityBox.classList.add("disable")
  }

  setQuantityBox(Bridge.default());

  // call other functions
  // productContainers(list, sameAuthor);
  displayDefaultSpect(product);
  displayProductDetails(product);
  productContainers(await getProducPhones(), productLike);
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
            <img src="assets/images/Phone/RedMagics/vn-11134207-7ras8-m2nn2bl6q4922e.jpg" alt="${product.tensp}">
          </span>
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

async function displayProductDetails(product) {
  // Tạo cấu trúc HTML cho overlay
  const overlayContent = `
    <div class="grid wide">
      <div class="grid-row">
        <div class="overlay-content bg-white grid-col col-l-12 bg-white padding-12 border-1-solid-black">
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
              ${product.kichthuocman ? `<br>${product.kichthuocman}` : ''}
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
            <li class="product-ram">RAM: ${(await GetDetailPRoducts(product.masp)).ram || 'Không có thông tin'}</li>
            
            <li><strong>Bộ nhớ & Lưu trữ</strong></li>
            <li class="product-rom">Bộ nhớ trong: ${(await GetDetailPRoducts(product.masp)).rom || 'Không có thông tin'}</li>
            
            <li><strong>Pin & Sạc</strong></li>
            <li class="product-battery">Dung lượng pin: ${product.dungluongpin || 'Không có thông tin'}
              ${product.batteryType ? `<br>Loại pin: ${product.batteryType}` : ''}
            </li>
            <li class="product-battery-tech">Công nghệ pin: ${product.batteryTech || 'Không có thông tin'}</li>
            <li class="product-charging-port">Cổng sạc: ${product.chargingPort || 'Không có thông tin'}</li>
          </ul>
          <button onclick="closeOverlay()" 
            class="more-detail-form-close button bg-main-color text-white padding-12 margin-top-12">Đóng</button>
        </div>
      </div>
    </div>
  `;

  const overlay = document.getElementById('overlay-more-detail');
  overlay.innerHTML = overlayContent;
}

async function displayDefaultSpect(product) {
  const detail = `
                <ul>
                    <li class="product-monitor"><strong>Màn hình:</strong> ${product.kichthuocman}
                    </li>
                    <li class="product-os"><strong>Hệ điều hành:</strong> ${product.hedieuhanh}</li>
                    <li class="product-rear-camera"><strong>Camera sau:</strong>${product.camerasau}</li>
                    <li class="product-font-camera"><strong>Camera trước:</strong>${product.cameratruoc}</li>
                    <li class="product-cpu"><strong>CPU:</strong> <span>${product.chipxuly}</span></li>
                    <li class="product-ram"><strong>RAM:</strong> ${(await GetDetailPRoducts(product.masp)).ram}</li>
                    <li class="product-rom"><strong>Bộ nhớ trong:</strong> ${(await GetDetailPRoducts(product.masp)).rom}</li>
                    <li class="product-battery"><strong>Dung lượng pin:</strong> ${product.dungluongpin}</li>
                </ul>
  `
  const overlay = document.getElementById('some-product-detail');
  overlay.innerHTML = detail;
}

async function renderColorOptions(product) {
  let listProductColor = (await GetDetailPRoducts()).filter(detail => detail.masp == product.masp);
  let colors = await getColors();

  // Lọc duy nhất theo mã màu
  const uniqueColorsMap = new Map();
  listProductColor.forEach(element => {
    if (!uniqueColorsMap.has(element.mausac)) {
      uniqueColorsMap.set(element.mausac, element);
    }
  });

  let scriptHtml = "";
  for (let [, element] of uniqueColorsMap) {
    let color = colors.find(c => c.mamau == element.mausac);
    if (color) {
      scriptHtml += `
        <div class="color-option col-l-3 margin-right-8 padding-bottom-4 padding-top-4 border-1-solid-black text-white"
             data-value="${color.tenmau.toLowerCase()}" >
          ${color.tenmau.toLowerCase()}
        </div>
      `;
    }
  }

  const overlay = document.querySelector('.color-options');
  overlay.innerHTML = scriptHtml;
  const colorOptions = overlay.querySelectorAll('.color-option');
  colorOptions.forEach(option => {
    option.addEventListener('click', () => {
      colorOptions.forEach(opt => opt.classList.remove('selected')); // bỏ selected cũ
      option.classList.add('selected'); // thêm selected mới
    });
  });
}

async function renderStorageOptions(product) {
  const details = (await GetDetailPRoducts()).filter(p => p.masp === product.masp);
  const rams = await getRams();
  const roms = await getRoms();

  const container = document.querySelector(".storage-options");
  let html = "";
  const uniqueCombo = new Set();

  details.forEach(detail => {
      const comboKey = `${detail.ram}-${detail.rom}`;
      if (!uniqueCombo.has(comboKey)) {
          uniqueCombo.add(comboKey);
          const ram = rams.find(r => r.madlram == detail.ram);
          const rom = roms.find(r => r.madlrom == detail.rom);

          html += `
          <div class="storage-option padding-bottom-4 padding-top-4 font-size-14 col-l-6 border-1-solid-black button bg-dark"
               data-ram="${detail.ram}" data-rom="${detail.rom}" 
               data-price="${detail.giaban}" data-oldprice="${detail.giagoc}">
               ${rom.kichthuocrom}GB + ${ram.kichthuocram}GB
          </div>`;
      }
  });

  container.innerHTML = html;

  // Gán sự kiện click để cập nhật giá
  container.querySelectorAll('.storage-option').forEach(option => {
      option.addEventListener('click', function () {
          container.querySelectorAll('.storage-option').forEach(el => el.classList.remove("selected"));
          this.classList.add("selected");

          const price = parseInt(Math.round(this.dataset.price * (1 - 0.29))).toLocaleString('vi-VN');
          const oldPrice = parseInt(Math.round(this.dataset.price)).toLocaleString('vi-VN');

          document.querySelector(".new-price").innerHTML = `${price}&nbsp;₫`;
          document.querySelector(".old-price").innerHTML = `${oldPrice}&nbsp;₫`;
          console.log(`💰 Giá mới: ${price} - Giá gốc: ${oldPrice}`);
      });
  });
}




export { getProducPhones, setProductBooks, productContainers, getValueQuery, renderProductDetails, renderProducts, dynamicDetail, callFuncsAgain };
