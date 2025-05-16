import { getProductPhones, renderProducts } from './product.js';
import { hiddenException, resizeImages } from './interfaces.js';
import { formatPrices } from "./interfaces.js";
import { $, $$ } from './bridges.js';

// show more product here
function handleCategoryNavigation() {
    const categoryButtons = $$('.category-btn');
    const navCategories = $$(".nav-categories .nav-item");

    eventForCategoryButton(categoryButtons);
    eventForSubHeader(navCategories);
}

// show more buttons
function eventForCategoryButton(categoryButtons) {
    categoryButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const parentSection = button.closest('section');
            if (parentSection && parentSection.id) {
                const categoryId = parentSection.id;
                window.location.href = `index.php?category=${categoryId}`;
            }
        });
    });
}

function eventForSubHeader(navCategories) {
    // sub header navigations
    navCategories.forEach(item =>
        item.addEventListener("click", ((event) => {
            event.preventDefault();
            const categoryType = item.querySelector("a.flex.full-width");
            let type = categoryType ? categoryType.getAttribute("title") : "";
            window.location.href = `index.php?category=${type}`;
        })
        ));    
}


// page.js: Xử lý hiển thị phần nội dung dựa trên URL
const ITEMS_PER_PAGE = 10; // Số sản phẩm trên mỗi trang
/**
 * Hàm khởi tạo trang
 */

//! NEED TO CHANGE HERE (GET DATA FROM DB AND RETURN ARRAY FOR FILTER CATEGORY AND RENDER)
async function initializePage() {
    // Lấy tham số từ URL
    const categoryId = getCategoryFromURL();
    if (categoryId) {
        // Ẩn tất cả các section
        hideAllSections();
        // Hiển thị section mục tiêu
        showTargetSection(categoryId);
        //! Lấy danh sách sản phẩm từ localStorage (CHANGE TO DB)
        const allProducts = await getProductPhones();
        // Lọc sản phẩm theo danh mục
        const filteredProducts = filterProductsByCategory(allProducts, categoryId);
        // Tích hợp phân trang
        setupPagination(filteredProducts, categoryId);
    }
}
/**
 * Lọc sản phẩm theo danh mục
 * @param {Array} products Danh sách sản phẩm
 * @param {string} categoryId ID của danh mục
 * @returns {Array} Danh sách sản phẩm đã lọc
 */
function filterProductsByCategory(products, categoryId) {
    if (!products || !categoryId) return [];
    switch (categoryId) {
        case 'fs-container':
            return products.sort((a, b) => b.sale - a.sale);
        case 'new-phones-container':
            return products.slice(-5);
        case 'samsung-phone-container':
        case "samsung":
            return products.filter((product) => product.thuonghieu.toLowerCase().includes('samsung'));
        case 'ip-container':
        case "iphone":
            return products.filter((product) => product.thuonghieu.toLowerCase().includes('iphone'));
        case 'other-phones-container':
            return products.sort((a, b) => a.releaseDate - b.releaseDate);
        case "xiaomi":
            return products.filter((product) => product.thuonghieu.toLowerCase().includes('xiaomi'));
        case "iqoo":
            return products.filter((product) => product.thuonghieu.toLowerCase().includes('vivo'));
        default:
            return products;
    }
}

/**
 * Tích hợp phân trang
 * @param {Array} products Danh sách sản phẩm đã lọc
 * @param {string} categoryId ID của danh mục
 */
async function setupPagination(products, categoryId) {
    if (!products || products.length === 0) {
        console.error("Không có sản phẩm để phân trang.");
        return;
    }
    let container;
    let totalPages;
    let targetSection = document.getElementById(categoryId);
    if (!targetSection) {
        const checkTarget = setInterval(() => {
            targetSection = document.getElementById(`${categoryId}-container`);
            if (targetSection) {
                container = targetSection.querySelector('.product-container');
                totalPages = Math.ceil(products.length / ITEMS_PER_PAGE);
                displayPage(products, container, 1);
                createPaginationControls(targetSection, products, container, totalPages);
                clearInterval(checkTarget);
            }
        })
    }
    else {
        container = targetSection.querySelector('.product-container');
        if (!container) {
            console.error("Không tìm thấy container sản phẩm.");
            return;
        }
        totalPages = Math.ceil(products.length / ITEMS_PER_PAGE);
        // console.log(`Tổng số trang: ${totalPages}, Tổng sản phẩm: ${products.length}`);
        displayPage(products, container, 1);
        createPaginationControls(targetSection, products, container, totalPages);
    }
}


/**
 * Hiển thị sản phẩm của một trang
 * @param {Array} products Danh sách sản phẩm
 * @param {HTMLElement} container Container hiển thị sản phẩm
 * @param {number} pageNumber Số trang hiện tại
 */
function displayPage(products, container, pageNumber) {
    container.innerHTML = ""; // Làm trống container

    const startIndex = (pageNumber - 1) * ITEMS_PER_PAGE;
    const endIndex = startIndex + ITEMS_PER_PAGE;
    const pageProducts = products.slice(startIndex, endIndex);

    // Hiển thị sản phẩm
    renderProducts(pageProducts, container);
    // Chỉ định dạng giá khi đã chắc chắn sản phẩm đã được render
    setTimeout(() => {
        formatPrices({ getElementPrices: () => container.querySelectorAll('.price') });
    }, 0);

    // console.log(`Hiển thị trang ${pageNumber}:`, pageProducts);
    const elementsObj = {
        getImages: () => container.querySelectorAll('img'),
    };

    // Thay đổi kích thước hình ảnh
    resizeImages(elementsObj);

}


/**
 * Tạo nút điều khiển phân trang
 * @param {HTMLElement} section Phần tử chứa phân trang
 * @param {Array} products Danh sách sản phẩm
 * @param {HTMLElement} container Container hiển thị sản phẩm
 * @param {number} totalPages Tổng số trang
 */
function createPaginationControls(section, products, container, totalPages) {
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination-controls';
    paginationContainer.style.textAlign = 'center';
    paginationContainer.style.marginTop = '1em';
    paginationContainer.style.marginBottom = '1em';

    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.className = 'page-button';
        pageButton.style.margin = '0 5px';
        pageButton.style.padding = '5px 10px';
        pageButton.style.cursor = 'pointer';
        pageButton.addEventListener('click', () => displayPage(products, container, i));

        paginationContainer.appendChild(pageButton);
    }

    section.appendChild(paginationContainer);
}
/**
 * Lấy ID của category từ URL
 * @returns {string | null} ID của category hoặc null nếu không có
 */
function getCategoryFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('category');
}

/**
 * Ẩn tất cả các section trên trang
 */
function hideAllSections() {
    const allSections = $$('section.container');
    const allCategoryButtons = $$('.category-btn'); // Lấy tất cả các nút "Xem thêm"

    // Ẩn tất cả các section
    allSections.forEach(section => {
        section.style.display = 'none';
    });

    // Ẩn tất cả các nút "Xem thêm"
    allCategoryButtons.forEach(button => {
        if (!button.classList.contains("return-homepage"))
            button.style.display = 'none';
    });
}

/**
 * Hiển thị section mục tiêu theo ID
 * @param {string} categoryId ID của section cần hiển thị
 */
function showTargetSection(categoryId) {
    const targetSection = document.getElementById(categoryId);
    if (targetSection) {
        targetSection.style.display = 'flex';
        targetSection.style.justifyContent = 'center';
        return;
    }
    else {
        renderContainer(categoryId);
        return;
    }
}

function renderContainer(name) {
    console.log(name);
    let script = `
        <div class="category-tab margin-0">
                <div class="heading">
                    <div class="heading-label ${name}-label"></div>
                    <div class="uppercase font-bold font-size-20 padding-left-8">${name.replace("-", " ")}</div>
                </div>

                <!-- container for products -->
                <div class="product-container"></div>
        </div>
    `
    let element = document.createElement("section");
    element.setAttribute("id", `${name}-container`);
    element.classList.add("flex");
    element.classList.add("justify-center");
    element.classList.add("grid-col");
    element.classList.add("col-l-12");
    element.classList.add("col-m-12");
    element.classList.add("col-s-12");
    element.classList.add("no-gutter");
    element.innerHTML = script;

    $("#index-content")?.insertAdjacentElement("afterEnd", element);
}

export { initializePage, handleCategoryNavigation }
