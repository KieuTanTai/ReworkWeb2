<?php
session_start();
require_once '../model/account.php';

// Xử lý đăng xuất
logoutUser();

// Chuyển hướng về trang chủ
header("Location: ../../../index.php");
exit();
?>