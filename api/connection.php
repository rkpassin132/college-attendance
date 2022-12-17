<?php
include 'constant.php';

$conn = mysqli_connect(constant("HOST_NAME"), constant("DB_USER"), constant("DB_PASSWORD"), constant("DB_NAME"));

if ($conn->connect_error) {
    die("connection failed");
}
