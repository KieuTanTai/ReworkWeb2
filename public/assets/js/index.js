"use strict";
import * as Products from "./product.js";
import * as Interface from "./interfaces.js";
import * as Bridge from "./bridges.js";
import * as Actions from "./action.js";
import * as FlashSales from "./flashsale.js";
import * as Navigate from "./navigates.js";
import * as Search from "./search.js";
import * as Login from "./login.js";
import * as Register from "./registers.js";
import * as Cart from "./carts.js";
import * as Pages from "./pages.js";

document.addEventListener("DOMContentLoaded", () => {
  let elementsObj = Bridge.default();
  let lastPath = location.href;
  lastPath = lastPath.slice(lastPath.lastIndexOf("/") + 1, lastPath.length);

  // DOM ON action.js
  Navigate.forbiddenDOM();
  // Interface.addDOMHeaderFooter(elementsObj);
  const checkDOM = setInterval(() => {
    if (
      elementsObj.getHeader() &&
      elementsObj.getSubHeader() &&
      elementsObj.getFooter()
    ) {
      // call funcs
      Interface.resizeSmNav(elementsObj);
      Interface.headerUserInfo(elementsObj);
      Cart.increaseCartCount();
      Cart.updateCartCount(elementsObj);
      Cart.handleCartNavigation();
      Cart.handlePaymentOptionChange();
      Cart.handleDefaultAddressCheckbox();
      Actions.accountEvents(elementsObj);
      Actions.staticContents(elementsObj);
      Actions.historyNavigate(elementsObj);
      Actions.returnHomepage(elementsObj);
      Actions.trackingNavigate(elementsObj);
      Actions.smNavigationMenu(elementsObj);
      Search.searchBtn();
      // show more product here
      Pages.handleCategoryNavigation(); 
      // remove Interval
      clearInterval(checkDOM);
    }
  }, 200);

  // navigate.js
  Navigate.execQueryHandler();
  Navigate.popStateHandler();
  Navigate.forbiddenDOM();
  // login
  Login.validateAccount();
  Register.validateRegister();
  if (lastPath.includes("cart")) {
    Cart.displayCartItems(elementsObj);
    Cart.updateCartTotal(elementsObj);
    Cart.handleQuantityChange(elementsObj);
    Cart.handleCheckboxChange(elementsObj);
    Cart.handleSelectAllCheckbox(elementsObj);
    Cart.handleRemoveItem(elementsObj);
    Cart.handleOrderPlacement(elementsObj);
  }

  // others
  Pages.initializePage();
});

