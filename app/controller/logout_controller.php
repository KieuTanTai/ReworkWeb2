<?php
session_start();
require_once '../model/account.php';

// Xử lý đăng xuất
logoutUser();

// Chuyển hướng về trang chủ
unset($_SESSION['login_info']);
unset($_SESSION['set_client_session']); // xoá cờ luôn khi logout
logoutUser();
header("Location: ../../public/index.php");
exit();
?>