<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'teacher-create':
        $error = array();
        if (!isset($_POST['email']) || empty($_POST['email'])) $error['email'] = "Email should not be empty";
        else if (valid_email($_POST['email']) != 1) $error['email'] = valid_email($_POST['email']);
        if (!isset($_POST['password']) || empty($_POST['password'])) $error['password'] = "Password should not be empty";
        else if (valid_password($_POST['password']) != 1) $error['password'] = valid_password($_POST['password']);
        if (!isset($_POST['first-name']) || empty($_POST['first-name'])) $error['first-name'] = "First name should not be empty";
        else if (valid_name($_POST['first-name']) != 1) $error['first-name'] = valid_name($_POST['first-name']);
        if(isset($_POST['last-name']) && !empty($_POST['last-name'])){
            if (valid_name($_POST['last-name']) != 1) $error['last-name'] = valid_name($_POST['last-name']);
        }
        if (isset($_POST['phone']) && !empty($_POST['phone'])) {
            if (valid_phone($_POST['phone']) != 1) $error['phone'] = valid_phone($_POST['phone']);
        }
        if (sizeof($error) > 0) sendData(false, $error);

        $email = sql_prevent($conn, xss_prevent($_POST['email']));
        $password = sql_prevent($conn, xss_prevent($_POST['password']));
        $first_name = sql_prevent($conn, xss_prevent($_POST['first-name']));
        $last_name = sql_prevent($conn, xss_prevent($_POST['last-name']));
        $phone = sql_prevent($conn, xss_prevent($_POST['phone']));
        $address = sql_prevent($conn, xss_prevent($_POST['address']));
        $iv = openssl_random_pseudo_bytes(16);
        $new_iv = bin2hex($iv);
        $hash = encryption($password, $iv);

        $user = query_getData1($conn, "select id from user where email='$email'");
        if ($user != null) sendData(false, array("email" => "Email already exist"));

        $create_user_query = "INSERT into user (role, name, email, password, iv, status, created_at) value (" . USER_ROLE['teacher'] . ", '$first_name $last_name', '$email', '$hash', '$new_iv', 1, current_timestamp())";
        if (query_create($conn, $create_user_query)) {
            $user = query_getData1($conn, "select id from user where email='$email'");
            $create_teacher_detail = "INSERT into teacher_detail (user_id, first_name, last_name, phone, address, created_at) value (" . $user['id'] . ",'$first_name', '$last_name', '$phone', '$address', current_timestamp())";
            if (query_create($conn, $create_teacher_detail)) sendData(true, "Teacher created successfully");
            else sendData(false, "Teacher not able to create");
        } else sendData(false, "Teacher not able to create");

        break;

    case 'teacher-excel-create':
        if (!isset($_FILES['teacher-file'])) sendData(false, ['teacher-file' => "File is required"]);
        $valid_file = valid_file($_FILES['teacher-file'], ["excel"]);
        if ($valid_file != 1) sendData(false, ['teacher-file' => $valid_file]);

        require('../../api/ImportExcel.php');
        $valid_col = [ 
            "email" => ["required", "email"],
            "password" => ["required", "password"],
            "first name" => ["required", "name"],
            "last name" => ["name"],
            "phone" => ["phone"],
            "address" => [],
        ];
        try {
            $excel = new ImportExcel($conn, $_FILES['teacher-file']['tmp_name'], $valid_col);
        } catch (Exception $e) {
            sendData(false, "Some error occur in file");
        }
        if ($excel->hasErrors()) sendData(false, ["file_error" => $excel->getErrors()]);

        // validate data
        $data = $excel->getData();
        $errors = array();
        $emails = array();
        $check_email = "(";
        foreach ($data as $user) {
            if(!isset($emails[$user['email']])) $emails[$user['email']] = 1;
            else $emails[$user['email']]++;
            if($emails[$user['email']] > 1 && !isset($errors[$user['email']])) array_push($errors, "Email <b>".$user['email']."</b> is duplicate email");
            $check_email .= "'".$user['email']."',";
        }
        $check_email = substr($check_email, 0, -1);
        $check_email .= ")";
        if(count($errors) > 0) sendData(false, ['file_error' => $errors]);
        
        $users = query_getData($conn, "select id, email from user where email in $check_email");
        if (count($users) > 0){
            foreach ($users as $key => $user) array_push($errors, "Email <b>".$user['email']."</b> is already exist");
            sendData(false, ['file_error' => $errors]);
        }

        // create user, teacher_detail
        $create_user_query = "INSERT into user (role, name, email, password, iv, status, created_at) value ";
        $users_data = array();
        foreach ($data as $user) {
            $users_data[$user['email']] = $user;
            $iv = openssl_random_pseudo_bytes(16);
            $new_iv = bin2hex($iv);
            $hash = encryption($user['password'], $iv);
            $create_user_query .= "(" . USER_ROLE['teacher'] . ", '".$user['first_name']." ".$user['last_name']."', '".$user['email']."', '$hash', '$new_iv', 1, current_timestamp()),";
        }
        $create_user_query = substr($create_user_query, 0, -1);
        if (query_create($conn, $create_user_query)) {
            $users = query_getData($conn, "select id, email from user where email in $check_email");
            $create_teacher_detail = "INSERT into teacher_detail (user_id, first_name, last_name, phone, address, created_at) value ";
            foreach ($users as $user) {
                $create_teacher_detail .= "(" . $user['id'] . ",'".$users_data[$user['email']]['first_name']."', '".$users_data[$user['email']]['last_name']."', '".$users_data[$user['email']]['phone']."', '".$users_data[$user['email']]['address']."', current_timestamp()),";
            }
            $create_teacher_detail = substr($create_teacher_detail, 0, -1);
            if (query_create($conn, $create_teacher_detail)) sendData(true, "Teacher created successfully");
        }
        sendData(false, "Teacher not able to create");
        break;

    default:
        sendData(false, "Method not found");
        break;
}
