<?php
include_once 'constant.php';
session_start();
session_destroy();
header('location:' . BASE_URL);
