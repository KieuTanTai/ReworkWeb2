<?php
// session_start(); // bắt buộc phải có dòng này
if (isset($_GET["cartQuantity"])) {
    $_SESSION["cartQuantity"] = intval($_GET["cartQuantity"]);
}

// Getter
function getCartQuantity() {
    return $_SESSION["cartQuantity"] ?? 0;
}

// Setter
function setCartQuantity($q) {
    $_SESSION["cartQuantity"] = $q;
    return true;
}
?>
