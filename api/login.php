<?php

include 'function.php';

if(check_user($conn)) sendData(false, "User already logged in");
check_method("POST");

if (isset($_POST['submit'])) {
    $error = array();
    if (!isset($_POST['email']) || empty($_POST['email'])) $error['email'] = "email should not be empty";
    else if (valid_email($_POST['email']) != 1) $error['email'] = "email should  be valid";
    if (!isset($_POST['password']) || empty($_POST['password'])) $error['password'] = "password should not be empty";
    if (sizeof($error) > 0) sendData(false, $error);

    $email = sql_prevent($conn, xss_prevent($_POST['email']));
    $password = sql_prevent($conn, xss_prevent($_POST['password']));
    
    $user = query_getData($conn, "select * from user where email='$email' and status=1");
    if(count($user) != 1) sendData(false, ["email" => "Invalid email"]);

    $user = $user[0];
    $password_decode = decryption($user['password'], hex2bin($user['iv']));
    if (strcasecmp($password_decode, $password) != 0) sendData(false, ["password" => "Invalid password"]);

    $iv = openssl_random_pseudo_bytes(16);
    $id = encryption($user['id'], $iv);
    $_SESSION['user']['check_user'] = $id;
    $_SESSION['user']['check_iv'] = bin2hex($iv);
    $_SESSION['user']['name'] = $user['name'];
    $_SESSION['user']['role'] = $user['role'];

    $redirect = "";
    if($user['role'] == USER_ROLE['admin']) $redirect = BASE_URL."/admin";
    else if($user['role'] == USER_ROLE['teacher']) $redirect = BASE_URL."/teacher";
    else if($user['role'] == USER_ROLE['student']) $redirect = BASE_URL."/student";
    echo json_encode(array("success" => true, "redirect"=>$redirect, "message" => "Login successful"));
    die;

}
