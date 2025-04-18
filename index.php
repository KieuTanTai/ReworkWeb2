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
     require "../app/views/header-footer.php";
     require "../app/views/detail_product.php";
     require "../app/views/cart.php";
     require "../app/views/homepage.php";
     require "../app/views/search_result.php"
     ?>
</head>

<body>
<?php 
     // Hiển thị thông báo sau khi đăng nhập/đăng xuất nếu có
     if (isset($_SESSION['auth_message'])) {
          echo '<div class="auth-message ' . $_SESSION['auth_message_type'] . '">';
          echo htmlspecialchars($_SESSION['auth_message']);
          echo '</div>';
          
          // Script để hiển thị và ẩn thông báo
          echo '<script>
               document.addEventListener("DOMContentLoaded", function() {
                    const message = document.querySelector(".auth-message");
                    if (message) {
                         message.style.display = "block";
                         setTimeout(function() {
                              message.style.display = "none";
                         }, 3000);
                    }
               });
          </script>';
          
          // Xóa thông báo sau khi hiển thị
          unset($_SESSION['auth_message']);
          unset($_SESSION['auth_message_type']);
     }
?>
     <?php loadDefaultHomepage();?>
     <?php if(isset($_GET["mode"])) {
          if($_GET["mode"] === "admin") {
               require "../app/controller/adminController.php";
               helloAdmin();
          }
          else {
               require "../app/controller/endUserController.php";
               helloUser();
          }
     } 
     var_dump($_GET);
     ?>
</body>

</html>