"use strict";

import * as Bridge from "./bridges.js";
import {
  formatPrices,
  hiddenException,
  resizeImages,
  scrollView,
} from "./interfaces.js";
import { execQueryHandler } from "./navigates.js";
import { getDetailPhones, getProductPhones, renderProducts } from "./product.js";

function searchBtn() {
  let searchBtn = Bridge.$("button[type=button].search-btn");
  let searchInput = Bridge.$("#search-input");

  searchBtn.addEventListener("click", () => {
    const bookName = searchInput?.value.trim();
    renderSearchDOM(bookName);
    hiddenException("search-content");
  });

  searchInput?.addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      const bookName = searchInput.value.trim();
      renderSearchDOM(bookName);
    }
  });
}

async function renderSearchDOM(bookName) {
  let searchContent = Bridge.default().getSearchContent();
  hiddenException("search-content");
  searchContent.innerHTML = searchDOM(); // Tạo giao diện tìm kiếm
  scrollView();
  let newURL = `${location.href.slice(
    0,
    location.href.lastIndexOf("/") + 1
  )}?query=${bookName.toLowerCase()}`;
  window.history.pushState({}, "", newURL); // Cập nhật URL
  initSearchFilters(); // Hiển thị sản phẩm theo từ khóa
}

function searchDOM() {
  return `
        <section id="filters">
          <label for="category-filter">Thể loại:</label>
          <select id="category-filter">
                <option value="">Tất cả</option>
                <option value="samsung">Samsung</option>
                <option value="iphone">Iphone</option>
                <option value="vivo">Vivo</option>
                <option value="iqoo">IQOO</option>
          </select>

          <label for="price-filter" class="padding-left-12">Khoảng giá:</label>
          <select id="price-filter">
                <option value="">Tất cả</option>
                <option value="0-4000000">0đ - 4,000,000đ</option>
                <option value="4000000-7000000">4,000,000đ - 7,000,000đ</option>
                <option value="7000000-10000000">7,000,000đ - 10,000,000đ</option>
                <option value="10000000-">10,000,000đ trở lên</option>
          </select>
      </section>

      <!-- Vùng hiển thị sản phẩm -->
      <section id="search-results-container" class="container flex grid-col col-l-12 col-m-12 col-s-12 no-gutter">
          <div class="category-tab">
                <div class="heading">
                    <div id="search-book-label" class="heading-label"></div>
                    <div class="uppercase font-bold font-size-20 padding-left-8"> Kết quả tìm kiếm: </div>
                </div>

                <!-- container for products -->
                <div class="product-container" style="flex-flow: row wrap"></div>
                <div id="pagination-controls" class="pagination"></div> <!-- Điều khiển phân trang -->
          </div>
      </section>
  `;
}

async function applyFilters(productList, searchQuery, elementsObj) {
  if (!elementsObj) elementsObj = Bridge.default();
  
  // Lấy dữ liệu chi tiết trước
  const detailPhones = await getDetailPhones();
  
  const categoryFilter = elementsObj.getCategoryFilter();
  const priceFilter = elementsObj.getPriceFilter();
  const category = categoryFilter?.value;
  const priceRange = priceFilter?.value;
  
  // Sau đó lọc
  return productList.filter(product => {
    const name = product.tensp?.toLowerCase();
    const brand = product.thuonghieu?.toLowerCase() || ""; 
    const queryMatch = searchQuery ? name.includes(searchQuery) : true;

    // Lọc theo thể loại
    const categoryMatch = category ? (product.thuonghieu.toLowerCase()).includes(category) : true;

    // Lọc theo khoảng giá
    const phoneDetail = detailPhones.find(item => item.masp == product.masp);
    const price = phoneDetail?.giaban * (1 - 0.29); // Giá sau giảm giá
    
    let priceMatch = true;
    if (priceRange && price) {
      const [min, max] = priceRange.split("-").map(val => val ? Number(val) : null);
      priceMatch = max ? price >= min && price <= max : price >= min;
    }
    
    return queryMatch && categoryMatch && priceMatch;
  });
}


async function displayProducts(productList, searchQuery, elementsObj, currentPage = 1, itemsPerPage = 15) {
  if (!elementsObj) elementsObj = Bridge.default();
  const filteredProducts = await applyFilters(productList, searchQuery);
  const productContainer = elementsObj
    .getResultContainer()
    ?.querySelector(".product-container");
  const paginationContainer = elementsObj
    .getResultContainer()
    ?.querySelector("#pagination-controls");

  if (!productContainer || !paginationContainer) return;

  const totalItems = filteredProducts.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage);
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const productsToShow = filteredProducts.slice(startIndex, endIndex);

  if (productsToShow.length > 0) {
    renderProducts(productsToShow, productContainer);
    resizeImages(elementsObj);
    formatPrices(elementsObj);
  } else
    productContainer.innerHTML =
      '<div class="font-size-13 font-bold">Không tìm thấy sản phẩm nào phù hợp</div>';
  renderPaginationControls(
    paginationContainer,
    currentPage,
    totalPages,
    (page) =>
      displayProducts(productList, searchQuery, elementsObj, page, itemsPerPage)
  );
}

function renderPaginationControls(container, currentPage, totalPages, onPageChange) {
  container.innerHTML = "";
  if (totalPages <= 1) return;

  for (let i = 1; i <= totalPages; i++) {
    const pageButton = document.createElement("button");
    pageButton.textContent = i;
    pageButton.className = `pagination-btn ${
      i === currentPage ? "active" : ""
    }`;
    pageButton.addEventListener("click", () => onPageChange(i));
    container.appendChild(pageButton);
  }
}

async function initSearchFilters() {
  let elementsObj = Bridge.default();
  const query = execQueryHandler("query");
  let productList = await getProductPhones();
  displayProducts(productList, query, elementsObj, 1, 15);
  changeByFilter([elementsObj.getCategoryFilter(), elementsObj.getPriceFilter()], query, productList);
}

function changeByFilter(elements, query, productList) {
  elements.forEach((filter) =>
    filter?.addEventListener("change", () => {
      displayProducts(productList, query, null, 1);
      formatPrices(Bridge.default());
    }
    )
  );
}

export { searchBtn, renderSearchDOM };
