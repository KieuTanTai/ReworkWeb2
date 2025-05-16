<?php
require_once 'productController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $product_id = intval($_POST['id']);

    $controller = new ProductController();
    $deleted = $controller->delete($product_id);

    if ($deleted) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
