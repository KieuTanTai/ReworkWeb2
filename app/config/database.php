<?php

$conn = mysqli_connect("localhost", "root", "", "qlchdienthoai_simple");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

?>