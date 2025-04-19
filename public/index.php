<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <meta name="description" content="Shop Mobile" />
     <link rel="shortcut icon" href="./assets/images/icons/web_logo/main-logo.ico" type="image/x-icon" />
     <link rel="stylesheet" href="./assets/fonts/fontawesome-6.6.0/css/all.min.css" />
     <link rel="stylesheet" href="./assets/css/index.css" />
     <link rel="stylesheet" href="./assets/css/responsive.css" />
     <script type="module" src="./assets/js/index.js"></script>
     <title>Light Novel World</title>
     <?php
     require __DIR__ . '/../app/views/header-footer.php';
     require __DIR__ . '/../app/views/detail_product.php';
     require __DIR__ . '/../app/views/cart.php';
     require __DIR__ . '/../app/views/homepage.php';
     require __DIR__ . '/../app/views/search_result.php';

     ?>
</head>

<body>
     <?php loadDefaultHomepage(); ?>
     <?php if (isset($_GET["mode"])) {
          if ($_GET["mode"] === "admin") {
               require "../app/controller/adminController.php";
               helloAdmin();
          } else {
               require "../app/controller/endUserController.php";
               helloUser();
          }
     }
     var_dump($_GET);
     ?>
</body>

</html>