<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));

switch ($submit) {

    case 'profile-change-password':
        $error = array();
        if (!isset($_POST['password']) || empty($_POST['password'])) $error['password'] = "Password should not be empty";
        else if (valid_password($_POST['password']) != 1) $error['password'] = valid_password($_POST['password']);
        if (!isset($_POST['confirm_password']) || empty($_POST['confirm_password'])) $error['confirm_password'] = "Confirm should not be empty";
        else if(strcasecmp($_POST['password'], $_POST['confirm_password'])) $error['confirm_password'] = "Password not matching";
        if (sizeof($error) > 0) sendData(false, $error);
        
        $password = sql_prevent($conn, xss_prevent($_POST['password']));
        $confirm_password = sql_prevent($conn, xss_prevent($_POST['confirm_password']));

        $id = get_userId();
        $check = query_getData1($conn, "SELECT iv, password from user where id=$id");
        if(strcasecmp(decryption($check['password'], hex2bin($check['iv'])), $password) == 0) sendData(false, "Password is similar to previous password.");

        $iv = openssl_random_pseudo_bytes(16);
        $new_iv = bin2hex($iv);
        $hash = encryption($password, $iv);

        $query = "UPDATE user SET iv='$new_iv', password='$hash' where id=$id";
        if(query_update($conn, $query)) sendData(true, "Password change successfully.");
        else sendData(false, "Password on able to update.");

        break;

    default:
        sendData(false, "Method not found");
        break;
}