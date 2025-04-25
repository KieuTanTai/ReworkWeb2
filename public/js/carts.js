"use strict";
import * as Bridge from "./bridges.js";
import { validateRegister } from "./registers.js";
import { getDetailPhones, getProductPhones } from "./product.js";
import { formatPrices, hiddenException } from "./interfaces.js";
import { sleep } from "./navigates.js";
import { userDetail } from "./action.js";

// cart navigation
async function handleCartNavigation() {
  const cartButtons = Bridge.$$(".cart-btn");
  // const categoryButton = Bridge.default().getCartContent();

  cartButtons.forEach((button) => {
    button.addEventListener("click", async (event) => {
      event.preventDefault();
      hiddenException("cart-content");
      const currentURL = window.location.origin + window.location.pathname;
      history.pushState({}, "", currentURL + "?type=cart");
      callCartFunctions();
      Bridge.default().getMainContainer().scrollIntoView({ behavior: "smooth", block: "start", inline: "start" });
    });
  });
}

function callCartFunctions() {
  let elementsObj = Bridge.default();
  let lastPath = location.href;
  lastPath = lastPath.slice(lastPath.lastIndexOf("/") + 1, lastPath.length);
  if (lastPath.includes("type=cart")) {
    displayCartItems(elementsObj);
    updateCartTotal(elementsObj);
    handleQuantityChange(elementsObj);
    handleCheckboxChange(elementsObj);
    handleSelectAllCheckbox(elementsObj);
    handleRemoveItem(elementsObj);
    handleOrderPlacement(elementsObj);
  }
}

function updateCartTotal(elementsObj) {
  const cartItems = elementsObj.getCartItems();
  let total = 0;
  let shippingFee = 0;
  let shippingDiscount = 0;
  let voucherDiscount = 0;
  let Prices = 0;
  cartItems.forEach((item) => {
    const checkbox = item.querySelector('input[type="checkbox"]');
    const priceElement = item.querySelector(".price");
    const quantityElement = item.querySelector(".quantity-cart");
    if (!checkbox || !checkbox.checked) return;

    const rawPrice = priceElement
      ? priceElement.innerText.replace(/\D/g, "")
      : "0";
    const price = parseFloat(rawPrice) || 0;
    const quantity = quantityElement ? parseInt(quantityElement.value, 10) : 1;

    shippingFee = 10000;
    shippingDiscount = 5250;
    voucherDiscount += 3000;
    Prices += price * quantity;
  });
  total = Prices + shippingFee - shippingDiscount - voucherDiscount;

  if (Prices === 0) {
    shippingFee = 0;
    shippingDiscount = 0;
    voucherDiscount = 0;
    total = 0;
  }
  const formatCurrency = (value) =>
    value.toLocaleString("vi-VN", { style: "currency", currency: "VND" });
  const PricesPriceElement = document.querySelector(".prices");
  const totalPriceElement = document.querySelector(".total-price");
  const shippingFeeElement = document.querySelector(".shipping-fee");
  const shippingDiscountElement = document.querySelector(".shipping-discount");
  const voucherDiscountElement = document.querySelector(".voucher-discount");
  if (PricesPriceElement) PricesPriceElement.innerText = formatCurrency(Prices);
  if (totalPriceElement) totalPriceElement.innerText = formatCurrency(total);
  if (shippingFeeElement)
    shippingFeeElement.innerText = formatCurrency(shippingFee);
  if (shippingDiscountElement)
    shippingDiscountElement.innerText = formatCurrency(shippingDiscount);
  if (voucherDiscountElement)
    voucherDiscountElement.innerText = formatCurrency(voucherDiscount);

  console.log(
    `Tổng tiền: ${total}, Giá sản phẩm: ${Prices}, Phí vận chuyển: ${shippingFee}, Giảm giá vận chuyển: ${shippingDiscount}, Giảm giá voucher: ${voucherDiscount}`
  );
}

function handleQuantityChange(elementsObj) {
  const quantityInputs = elementsObj.getQuantityInputs();

  quantityInputs.forEach((input, index) => {
    input.addEventListener("change", () => {
      const cartItems = elementsObj.getCartItems();
      const item = cartItems[index - 1];
      const pricePerItemElement = item.querySelector(".price-per-item");
      const priceElement = item.querySelector(".price");

      const rawPrice = priceElement.innerText.replace(/\D/g, "");
      const price = parseFloat(rawPrice);

      const quantity = parseInt(input.value, 10);

      if (isNaN(price) || isNaN(quantity) || price < 0) {
        console.error("Giá hoặc số lượng không hợp lệ:", { price, quantity });
        return;
      }

      const newPricePerItem = price * quantity;
      pricePerItemElement.innerText = newPricePerItem;

      formatPrices({ getElementPrices: () => [pricePerItemElement] });
      updateCartTotal(elementsObj);
    });
  });
}

function handleCheckboxChange(elementsObj) {
  const cartItems = elementsObj.getCartItems();
  const qrCodeMomo = document.querySelector("#qr-code-momo");
  const qrCodeATM = document.querySelector("#qr-code-atm");
  const paymentOptions = document.querySelectorAll('input[name="payment-option"]');

  cartItems.forEach((item) => {
    const checkbox = item.querySelector('input[type="checkbox"]');
    checkbox.addEventListener("change", () => {
      const isAnyProductSelected = Array.from(cartItems).some((item) => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        return checkbox && checkbox.checked;
      });

      if (!isAnyProductSelected) {
        if (qrCodeMomo) qrCodeMomo.style.display = "none";
        if (qrCodeATM) qrCodeATM.style.display = "none";
      } else {

        const selectedPaymentOption = document.querySelector(
          'input[name="payment-option"]:checked'
        );
        if (selectedPaymentOption) {
          if (selectedPaymentOption.id === "payment-option-2") {
            qrCodeMomo.style.display = "block";
            qrCodeATM.style.display = "none";
          } else if (selectedPaymentOption.id === "payment-option-3") {
            qrCodeATM.style.display = "block";
            qrCodeMomo.style.display = "none";
          }
        }
      }

      updateCartTotal(elementsObj);
    });
  });
}


function handleSelectAllCheckbox(elementsObj) {
  const selectAllCheckbox = elementsObj.getSelectAllCheckbox();
  const cartItems = elementsObj.getCartItems();
  const qrCodeMomo = document.querySelector("#qr-code-momo");
  const qrCodeATM = document.querySelector("#qr-code-atm");
  const paymentOptions = document.querySelectorAll('input[name="payment-option"]');

  selectAllCheckbox.addEventListener("change", () => {
    const isChecked = selectAllCheckbox.checked;

    cartItems.forEach((item) => {
      const checkbox = item.querySelector('input[type="checkbox"]');
      if (checkbox) checkbox.checked = isChecked;
    });

    const isAnyProductSelected = isChecked;

    if (!isAnyProductSelected) {
      if (qrCodeMomo) qrCodeMomo.style.display = "none";
      if (qrCodeATM) qrCodeATM.style.display = "none";
    } else {

      const selectedPaymentOption = document.querySelector(
        'input[name="payment-option"]:checked'
      );
      if (selectedPaymentOption) {
        if (selectedPaymentOption.id === "payment-option-2") {
          qrCodeMomo.style.display = "block";
          qrCodeATM.style.display = "none";
        } else if (selectedPaymentOption.id === "payment-option-3") {
          qrCodeATM.style.display = "block";
          qrCodeMomo.style.display = "none";
        }
      }
    }

    updateCartTotal(elementsObj);
  });
}


function handleRemoveItem(elementsObj) {
  const cartContainer = document.querySelector(".list-carts");
  if (!cartContainer) {
    console.warn(
      "Không tìm thấy phần tử '.list-carts'. Kiểm tra HTML của bạn."
    );
    return;
  }

  cartContainer.addEventListener("click", (event) => {
    if (event.target.closest(".rm-cart-btn")) {
      const cart = JSON.parse(localStorage.getItem("cart")) || [];
      const productElement = event.target.closest(".block-product");
      if (!productElement) return;
      const itemName = productElement
        .querySelector(".info-product-cart p")
        .textContent.trim();
      const updatedCart = cart.filter((item) => item.name !== itemName);
      localStorage.setItem("cart", JSON.stringify(updatedCart));
      displayCartItems(elementsObj);
      updateCartCount(elementsObj);
      updateCartTotal(elementsObj);
      handleQuantityChange(elementsObj);
      handleCheckboxChange(elementsObj);
      handleSelectAllCheckbox(elementsObj);
    }
  });
}

function increaseCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const uniqueProductCount = cart.length;
  const cartCountElements = document.querySelectorAll(".cart-count");
  cartCountElements.forEach((el) => {
    el.textContent = uniqueProductCount;
  });
}

// ! NEED TO FIX 
async function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartCount = cart.length;

  document.querySelectorAll(".cart-count").forEach((el) => {
    el.textContent = cartCount;
  });

}

// !NEED TO CHANGE HERE
function displayCartItems(elementsObj) {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartContainer = document.querySelector(".list-carts");
  if (!cartContainer) return;
  if (cart.length === 0) {
    cartContainer.innerHTML = "<p>Giỏ hàng trống</p>";
    return;
  }
  cartContainer.innerHTML = "";
  cart.forEach((item, index) => {
    let discountPrice = Math.round(item.price * 0.71);
    const totalPricePerItem = discountPrice * item.quantity || 0;
    cartContainer.innerHTML += `
            <div class="block-product block-cart">
                <input type="checkbox" name="select-block-product" id="block-product-${index}" class="grid-col col-l-1 col-m-1 col-s-1"/>
                <div class="product-cart grid-col col-l-1 col-m-1 col-s-1 no-gutter full-width">
                    <img class="mini-image" src="${'assets/images/Phone/RedMagics/red-magic-supernova_1_2_2_2.webp'}" alt="${item.name}" />
                </div>
                <div class="grid-col col-l-10 col-m-10 col-s-10 no-gutter flex align-center">
                    <div class="info-product-cart padding-left-8 grid-col col-l-6 col-m-12 col-s-12">
                        <p class="font-bold capitalize margin-bottom-16">${item.name}</p>
                        <div class="block-product-price">
                            <span class="new-price font-bold padding-right-8 price">${discountPrice}</span>
                            <del class="price old-price">${Math.round(item.price)}</del>
                        </div>
                    </div>
                    <div class="number-product-cart grid-col col-l-2 col-m-10 col-s-10 no-gutter">
                        <input type="number" name="quantity-cart" id="update_${index}" value="${item.quantity}" min="1" max="99" class="quantity-cart"/>
                    </div>
                    <div class="price-per-item price font-bold grid-col col-l-3 s-m-hidden no-gutter text-center">
                        ${totalPricePerItem}
                    </div>
                    <div class="rm-cart-btn col-l col-l-1 col-m-2 col-s-2 flex justify-center">
                        <div>
                            <i class="fa-solid fa-trash fa-lg" style="color: var(--primary-dark)"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
  });
  setTimeout(() => {
    formatPrices({
      getElementPrices: () => cartContainer.querySelectorAll(".price"),
    });
  }, 0);
  updateCartTotal(elementsObj);
  console.log("Giỏ hàng đã được hiển thị:", cart);
}

// !NEED TO CHANGE HERE
async function addToCart(productName, realQuantity, productColor, ram, rom, price) {
  const products = await getProductPhones();
  const product = products.find((item) => (item.tensp).toLowerCase().trim() === productName.toLowerCase().trim());

  if (!product) {
    console.error(`Sản phẩm "${productName}" không tìm thấy!`);
    return;
  }

  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const existingProductIndex = cart.findIndex(
    (item) => item.name === product.tensp && item.ram === ram && item.rom === rom && item.price === price
  );

  if (existingProductIndex !== -1) {
    let temp = parseInt(cart[existingProductIndex].quantity, 10);
    if (realQuantity)
      temp += parseInt(realQuantity, 10);
    else
      temp += 1;
    cart[existingProductIndex].quantity = temp;
  } else {
    cart.push({
      name: product.tensp,
      color: productColor,
      ram: ram,
      rom: rom,
      price: price,
      image: product.hinhanh,
      quantity: realQuantity ? realQuantity : 1,
    });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

async function attachAddToCartEvents() {
  const mainContainer = document.querySelector("#index-content");

  if (!mainContainer) {
    console.error("Không tìm thấy #main-container!");
    return;
  }
  const productItems = mainContainer.querySelectorAll(".product-item");
  productItems.forEach((productItem) => {
    const addToCartButton = productItem.querySelector(".add-to-cart .button");
    const buyNowButton = productItem.querySelector(".buy-btn .button");
    const detailWrapper = productItem.querySelector(".product-detail-hidden.selected");
    const ram = detailWrapper.querySelector(".ram")?.dataset.ram;
    const rom = detailWrapper.querySelector(".rom")?.dataset.rom;
    const mausac = detailWrapper.querySelector(".mausac")?.dataset.mausac;
    const price = detailWrapper.querySelector(".price")?.dataset.price;


    if (!addToCartButton || !buyNowButton) {
      console.warn("Không tìm thấy nút 'Thêm vào giỏ hàng' hoặc 'Mua ngay' cho sản phẩm:", productItem);
      return;
    }

    addToCartButton.replaceWith(addToCartButton.cloneNode(true));
    const newAddToCartButton = productItem.querySelector(
      ".add-to-cart .button"
    );

    const productName = productItem.querySelector("h4")?.textContent.trim();
    if (!productName) {
      console.warn("Không tìm thấy tên sản phẩm trong .product-item:", productItem);
      return;
    }

    newAddToCartButton.addEventListener("click", () => {
      console.log(`Đang thêm sản phẩm: ${productName}`);
      addToCart(productName, 1, mausac, ram, rom, price);
      increaseCartCount();
    });

    buyNowButton.replaceWith(buyNowButton.cloneNode(true));
    const newBuyNowButton = productItem.querySelector(".buy-btn .button");

    newBuyNowButton.addEventListener("click", () => {
      if (sessionStorage.getItem("login")) {
        console.log(`Đang thêm sản phẩm và chuyển đến giỏ hàng: ${productName}`);
        addToCart(productName, 1, mausac, ram, rom, price);
        increaseCartCount();
        // window.location.href = "cart.html";
      } else {
        alert("Phải đăng nhập trước");
      }
    });
  });
}

function handleCategoryNavigation() {
  const categoryButtons = document.querySelectorAll(".category-btn");

  categoryButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const parentSection = button.closest("section");
      if (parentSection && parentSection.id) {
        const categoryId = parentSection.id;
        // window.location.href = `index.html?category=${categoryId}`;
      }
    });
  });
}

function handleDefaultAddressCheckbox() {
  const checkbox = document.querySelector("#selection-address");
  const userAddressInput = document.querySelector("#user-address");

  if (!checkbox || !userAddressInput) {
    console.warn("Không tìm thấy checkbox hoặc input địa chỉ.");
    return;
  }

  checkbox.addEventListener("change", () => {
    const user = JSON.parse(sessionStorage.getItem("loginAccount"));

    if (checkbox.checked) {
      if (user && user.diachi) {
        userAddressInput.value = user.diachi;
      } else {
        alert("Không tìm thấy địa chỉ mặc định của người dùng. Vui lòng kiểm tra lại thông tin đăng nhập.");
        checkbox.checked = false;
      }
    } else {
      userAddressInput.value = "";
    }
  });
}



function createDonHang(orderId, userId, userAddress, totalOrderPrice, formattedDate, phone, userName, status) {
  return {
    id_donhang: orderId,
    id_khachhang: userId,
    date: formattedDate,
    ten_khach_hang: userName,
    dia_chi: userAddress,
    phonenumber: phone,
    tong: totalOrderPrice,
    trang_thai: status,
  };
}

function createChiTietDonHang(orderId, selectedItems, totalOrderPrice) {
  const products = getProductPhones();
  return selectedItems.map((item) => {
    const product = products.find((prod) => {
      const normalize = (str) => str.trim().toLowerCase();
      return normalize(prod.name) === normalize(item.name);
    });
    return {
      id_donhang: orderId,
      id_sanpham: product?.productID || "",
      sanpham: product?.name || item.name,
      don_gia: item.price.toString(),
      sl: item.quantity.toString(),
      tong: totalOrderPrice.toString(),
    };
  });
}

function handleOrderPlacement(elementsObj) {
  const checkoutButton = document.querySelector(".checkout-btn");
  let accountLogin = JSON.parse(sessionStorage.getItem("loginAccount"));
  if (!checkoutButton) {
    console.warn("Không tìm thấy nút 'checkout-btn'.");
    return;
  }

  checkoutButton.addEventListener("click", () => {
    const cartItems = document.querySelectorAll(".block-product");
    if (cartItems.length === 0) {
      alert("Giỏ hàng của bạn đang trống. Hãy thêm sản phẩm trước khi đặt hàng!");
      return;
    }

    if (!sessionStorage.getItem("login")) {
      alert("Vui lòng đăng nhập");
      return;
    }

    const selectedItems = Array.from(cartItems)
      .filter((item) => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        return checkbox && checkbox.checked;
      })
      .map((item) => {
        const priceElement = item.querySelector(".price");
        const quantityElement = item.querySelector(".quantity-cart");
        const rawPrice = priceElement
          ? priceElement.innerText.replace(/\D/g, "")
          : "0";
        const price = parseFloat(rawPrice) || 0;
        const quantity = quantityElement
          ? parseInt(quantityElement.value, 10)
          : 1;
        const name = item
          .querySelector(".info-product-cart p")
          .innerText.trim();
        const productID = item.dataset.productId; // Assume productID is stored as a data attribute
        const image = item.querySelector("img").src;
        return { name, price, quantity, image, productID };
      });

    if (selectedItems.length === 0) {
      alert("Bạn chưa chọn sản phẩm nào để đặt hàng.");
      return;
    }

    const paymentOption = document.querySelector(
      'input[name="payment-option"]:checked'
    );
    if (!paymentOption) {
      alert("Hãy chọn một phương thức thanh toán.");
      return;
    }

    const userAddress = document.querySelector("#user-address").value.trim();
    const userNote = document.querySelector("#user-note").value.trim();

    if (!userAddress && !accountLogin.diachi) {
      alert("Hãy nhập địa chỉ giao hàng.");
      return;
    }

    if (!accountLogin.sdt) {
      alert("chưa nhập số điện thoại liên lạc.");
      userDetail(Bridge.default());
    }

    const voucherCode = document.querySelector("#voucher-code").value.trim();

    const Prices = selectedItems.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
    const shippingFee = 10000;
    const shippingDiscount = 5250;
    const voucherDiscount = 3000;

    const totalOrderPrice =
      Prices + shippingFee - shippingDiscount - voucherDiscount;
    const currentDate = new Date();
    const formattedDate = `${String(currentDate.getMonth() + 1).padStart(2, "0")}/${String(
      currentDate.getDate()
    ).padStart(2, "0")}/${String(currentDate.getFullYear()).slice(-2)} ${currentDate.toLocaleTimeString("en-US")}`;
    const orderId = `DH${String(Date.now()).slice(-5)}`;
    const user = JSON.parse(sessionStorage.getItem("loginAccount"));
    const userName = user ? `${user.firstName} ${user.lastName}` : "Khách hàng";
    const status = userName === "Khách hàng" ? "2" : "1";
    let userId, phone;
    if (user?.userID) {
      userId = user.userID;
      phone = user.sdt;
    } else {
      userId = generateId(user);
      phone = generateId(user);
    }

    if (!phone || phone === "Unknown") {
      alert("Số điện thoại không hợp lệ. Vui lòng cập nhật thông tin của bạn trước khi đặt hàng.");
      return;
    }

    // !NEED TO CHANGE HERE
    // Create donhang and chitiet_donhang
    const donHang = createDonHang(orderId, userId, userAddress, totalOrderPrice, formattedDate, phone, userName, status);
    const chiTietDonHang = createChiTietDonHang(orderId, selectedItems, totalOrderPrice);

    // Save to localStorage
    let orders = JSON.parse(localStorage.getItem("donhang")) || [];
    orders.push(donHang);
    localStorage.setItem("donhang", JSON.stringify(orders));

    let orderDetails = JSON.parse(localStorage.getItem("chitiet_donhang")) || [];
    orderDetails = orderDetails.concat(chiTietDonHang);
    localStorage.setItem("chitiet_donhang", JSON.stringify(orderDetails));

    // Update cart
    const updatedCart = Array.from(cartItems)
      .filter((item) => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        return !checkbox || !checkbox.checked;
      })
      .map((item) => {
        const name = item.querySelector(".info-product-cart p").innerText.trim();
        const priceElement = item.querySelector(".price");
        const quantityElement = item.querySelector(".quantity-cart");
        const rawPrice = priceElement ? priceElement.innerText.replace(/\D/g, "") : "0";
        const price = parseFloat(rawPrice) || 0;
        const quantity = quantityElement ? parseInt(quantityElement.value, 10) : 1;
        const image = item.querySelector("img").src;
        return { name, price, quantity, image };
      });
    localStorage.setItem("cart", JSON.stringify(updatedCart));
    updateCartCount();
    displayCartItems(elementsObj);
    handleQuantityChange(elementsObj);
    handleCheckboxChange(elementsObj);
    handleSelectAllCheckbox(elementsObj);
    updateCartTotal(elementsObj);
    alert("Đặt hàng thành công!");
  });
}


async function attachAddToCartInDetails() {
  const addToCartButton = Bridge.$$(".add-to-cart.button");
  const buyNowButton = Bridge.$$(".buy-btn.button");
  let historyCart = Bridge.$$(".block-product .cart-content");

  if (!addToCartButton || !buyNowButton) {
    console.error("Không tìm thấy nút 'Thêm vào giỏ hàng' hoặc 'Mua ngay' trong Product Details.");
    return;
  }

  // add to cart
  addToCartButton.forEach((button) => {
    if (button.dataset.eventAttached)
      return;
    button.addEventListener("click", Bridge.throttle(() => {
      const productName = document.querySelector(".product-title h1")?.textContent.trim();
      const productPrice = parseFloat(document.querySelector(".new-price")?.textContent.replace(/\D/g, "")) || 0;
      const productImage = document.querySelector(".product-image img")?.src;
      const productQuantity = document.querySelector(".quantity-cart")?.value;
      const productColor = document.querySelector(".color-option.selected")?.getAttribute("data-value");
      const element = document.querySelector('.storage-option.selected');
      const ram = element?.getAttribute("data-ram");
      const rom = element?.getAttribute("data-rom");
      const price = element?.getAttribute("data-price");

      if (!productName || !productPrice || !productImage) {
        console.error("Không thể lấy thông tin sản phẩm từ Product Details.");
        return;
      }

      if (!ram || !rom) {
        alert("Vui lòng chọn phiên bản bộ nhớ (RAM/ROM)!");
        return;
      }
      
      if (!productColor) {
        alert("Vui lòng chọn màu sắc!");
        return;
      }

      console.log(ram, rom, price, element, productColor);
      addToCart(productName, productQuantity, productColor, ram, rom, price);
      increaseCartCount();
    }), 200, "add-to-cart");
    button.dataset.eventAttached = true;
  });

  //buy now
  buyNowButton.forEach((button) => button.addEventListener("click", Bridge.throttle(() => {
    if (!sessionStorage.getItem("login")) {
      alert("Bạn cần đăng nhập để mua ngay.");
      return;
    }

    const productName = document.querySelector(".product-title h1")?.textContent.trim();
    const productPrice = parseFloat(document.querySelector(".new-price")?.textContent.replace(/\D/g, "")) || 0;
    const productImage = document.querySelector(".product-image img")?.src;
    const productQuantity = document.querySelector(".quantity-cart")?.value;
    const element = document.querySelector('.storage-option.selected');
    const productColor = document.querySelector(".color-option.selected")?.value;
    const ram = element?.getAttribute("data-ram");
    const rom = element?.getAttribute("data-rom");
    const price = element?.getAttribute("data-price");

    if (!productName || !productPrice || !productImage) {
      console.error("Không thể lấy thông tin sản phẩm từ Product Details.");
      return;
    }

    if (!ram || !rom) {
      alert("Vui lòng chọn phiên bản bộ nhớ (RAM/ROM)!");
      return;
    }
    
    if (!productColor) {
      alert("Vui lòng chọn màu sắc!");
      return;
    }    

    addToCart(productName, productQuantity, productColor, ram, rom, price);
    increaseCartCount();
    // window.location.href = "cart.html";
  })), 200, "buy-now");
}


function handlePaymentOptionChange() {
  const qrCodeMomo = document.querySelector("#qr-code-momo");
  const qrCodeATM = document.querySelector("#qr-code-atm");
  const paymentOptions = document.querySelectorAll('input[name="payment-option"]');

  paymentOptions.forEach((option) => {
    option.addEventListener("change", () => {
      const cartItems = document.querySelectorAll(".block-product");
      const isAnyProductSelected = Array.from(cartItems).some((item) => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        return checkbox && checkbox.checked;
      });

      if (!isAnyProductSelected) {
        if (qrCodeMomo) qrCodeMomo.style.display = "none";
        if (qrCodeATM) qrCodeATM.style.display = "none";
        return;
      }

      if (option.id === "payment-option-2") {
        qrCodeMomo.style.display = "block";
        qrCodeATM.style.display = "none";
      } else if (option.id === "payment-option-3") {
        qrCodeATM.style.display = "block";
        qrCodeMomo.style.display = "none";
      } else {
        qrCodeMomo.style.display = "none";
        qrCodeATM.style.display = "none";
      }
    });
  });
}





export { addToCart, attachAddToCartEvents, increaseCartCount, displayCartItems, updateCartCount, updateCartTotal, handleOrderPlacement, attachAddToCartInDetails, handlePaymentOptionChange };
export { handleQuantityChange, handleCheckboxChange, handleSelectAllCheckbox, handleRemoveItem, handleCartNavigation, handleCategoryNavigation, handleDefaultAddressCheckbox, callCartFunctions };
