<?php
function renderDynamicScript()
{
     if (isset($_GET["type"]) && $_GET["type"] === "cart") {
          renderCartScript();
     } else if (isset($_GET["query"])) {
          if ($_GET["query"] === "") {
               renderEmptySearchScript();
          } else {
               renderSearchScript($_GET["query"]);
          }
     } else if (isset($_GET["name"])) {
          renderProductDetailScript($_GET["name"]);
     }
}

function renderCartScript()
{
     ?>
     <script type="module">
          import { Interface, Cart, Bridge } from './assets/js/index.js';

          document.addEventListener('DOMContentLoaded', function () {
               Interface.hiddenException("cart-content");
               Cart.callCartFunctions();
               Cart.increaseCartCount();
               Cart.updateCartCount(Bridge.default());
               Cart.handleCartNavigation();
               Cart.handlePaymentOptionChange();
               Cart.handleDefaultAddressCheckbox();
          });
     </script>
     <?php
}

function renderSearchScript($query)
{
     ?>
     <script type="module">
          import { Interface, Cart, Bridge, Search } from './assets/js/index.js';

          let query = <?php echo json_encode($query); ?>;
          Search.renderSearchDOM(query);
          Interface.hiddenException("search-content");
     </script>
     <?php
}

function renderEmptySearchScript()
{
     ?>
     <script type="module">
          import { Interface, Cart, Bridge, Search } from './assets/js/index.js';

          let searchInput = Bridge.$("#search-input");
          const bookName = searchInput?.value.trim();
          Search.renderSearchDOM(bookName);
          Interface.hiddenException("search-content");
     </script>
     <?php
}

function renderProductDetailScript($productName)
{
     ?>
     <script type="module">
          import { Interface, Cart, Bridge, Search, Products } from './assets/js/index.js';

          async function renderDetail() {
               console.log(<?php echo json_encode($productName); ?>);
               let product = (await Products.getProductPhones()).find((item) =>
                    (item.tensp).replaceAll("&", "").replaceAll("!", "").replaceAll(" ", "-")
                    === <?php echo json_encode($productName); ?>);
               if (Bridge.$("#detail-content").classList.contains("disable"))
                    Interface.hiddenException("detail-content");
               Bridge.default().getProductLikeContainer().style.display = "flex";
               Products.dynamicDetail(product);
          }

          renderDetail();
     </script>
     <?php
}

?>