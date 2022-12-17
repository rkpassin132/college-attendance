<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'student-list':
        $query = "SELECT sd.id, concat(sd.first_name, ' ', sd.last_name)as name, u.email, sd.phone, sd.address, '' as action from student_detail sd INNER JOIN user u on sd.user_id=u.id";
        $students =  query_getData($conn,  $query);
        sendData(true, $students);
        break;

    case 'student-create':
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
        if (!isset($_POST['roll-no']) || empty($_POST['roll-no'])) $error['roll-no'] = "Roll no should not be empty";
        if (isset($_POST['phone']) && !empty($_POST['phone'])) {
            if (valid_phone($_POST['phone']) != 1) $error['phone'] = valid_phone($_POST['phone']);
        }
        if (sizeof($error) > 0) sendData(false, $error);

        $email = sql_prevent($conn, xss_prevent($_POST['email']));
        $password = sql_prevent($conn, xss_prevent($_POST['password']));
        $first_name = sql_prevent($conn, xss_prevent($_POST['first-name']));
        $last_name = sql_prevent($conn, xss_prevent($_POST['last-name']));
        $roll_no = sql_prevent($conn, xss_prevent($_POST['roll-no']));
        $phone = sql_prevent($conn, xss_prevent($_POST['phone']));
        $address = sql_prevent($conn, xss_prevent($_POST['address']));
        $iv = openssl_random_pseudo_bytes(16);
        $new_iv = bin2hex($iv);
        $hash = encryption($password, $iv);

        $user = query_getData1($conn, "select id from user where email='$email'");
        if($user != null) sendData(false, array("email"=>"Email already exist"));

        $student = query_getData1($conn, "select id from student_detail where roll_no='$roll_no'");
        if($student != null) sendData(false, array("roll-no"=>"Roll no already exist"));

        $create_user_query = "INSERT into user (role, name, email, password, iv, status, created_at) value (".USER_ROLE['student'].", '$first_name $last_name', '$email', '$hash', '$new_iv', 1, current_timestamp())";
        if(query_create($conn, $create_user_query)){
            $user = query_getData1($conn, "select id from user where email='$email'");
            $create_student_detail = "INSERT into student_detail (user_id, first_name, last_name, roll_no, phone, address, created_at) value (".$user['id'].",'$first_name', '$last_name', '$roll_no', '$phone', '$address', current_timestamp())";
            if(query_create($conn, $create_student_detail)) sendData(true, "Student created successfully");
            else sendData(false, "Student not able to create");
        }
        else sendData(false, "Student not able to create");

        break;

    case 'student-excel-create':
        if (!isset($_FILES['student-file'])) sendData(false, ['student-file' => "File is required"]);
        $valid_file = valid_file($_FILES['student-file'], ["excel"]);
        if ($valid_file != 1) sendData(false, ['student-file' => $valid_file]);

        require('../../api/ImportExcel.php');
        $valid_col = [ 
            "email" => ["required", "email"],
            "password" => ["required", "password"],
            "first name" => ["required", "name"],
            "last name" => ["name"],
            "roll no" => ["required","number"],
            "phone" => ["phone"],
            "address" => [],
        ];
        try {
            $excel = new ImportExcel($conn, $_FILES['student-file']['tmp_name'], $valid_col);
        } catch (Exception $e) {
            sendData(false, "Some error occur in file");
        }
        if ($excel->hasErrors()) sendData(false, ["file_error" => $excel->getErrors()]);

        // validate data
        $data = $excel->getData();
        $errors = array();
        $emails = array();
        $check_email = "(";
        $roll_no = array();
        $check_roll_no = "(";
        foreach ($data as $user) {
            if(!isset($emails[$user['email']])) $emails[$user['email']] = 1;
            else $emails[$user['email']]++;
            if($emails[$user['email']] > 1 && !isset($errors[$user['email']])) array_push($errors, "Email <b>".$user['email']."</b> is duplicate");
            $check_email .= "'".$user['email']."',";
            
            if(!isset($roll_no[$user['roll_no']])) $roll_no[$user['roll_no']] = 1;
            else $roll_no[$user['roll_no']]++;
            if($roll_no[$user['roll_no']] > 1 && !isset($errors[$user['roll_no']])) array_push($errors, "Roll no <b>".$user['roll_no']."</b> is duplicate");
            $check_roll_no .= "'".$user['roll_no']."',";
        }
        $check_email = substr($check_email, 0, -1);
        $check_email .= ")";
        $check_roll_no = substr($check_roll_no, 0, -1);
        $check_roll_no .= ")";
        if(count($errors) > 0) sendData(false, ['file_error' => $errors]);
        
        $users = query_getData($conn, "select id, email from user where email in $check_email");
        if (count($users) > 0){
            foreach ($users as $key => $user) array_push($errors, "Email <b>".$user['email']."</b> is already exist");
        }
        if(count($errors) > 0) sendData(false, ['file_error' => $errors]);

        $student = query_getData($conn, "select id, roll_no from student_detail where roll_no in $check_roll_no");
        if (count($student) > 0){
            foreach ($student as $key => $user) array_push($errors, "Roll no <b>".$user['roll_no']."</b> is already exist");
        }
        if(count($errors) > 0) sendData(false, ['file_error' => $errors]);

        // create user, teacher_detail
        $create_user_query = "INSERT into user (role, name, email, password, iv, status, created_at) value ";
        $users_data = array();
        foreach ($data as $user) {
            $users_data[$user['email']] = $user;
            $iv = openssl_random_pseudo_bytes(16);
            $new_iv = bin2hex($iv);
            $hash = encryption($user['password'], $iv);
            $create_user_query .= "(" . USER_ROLE['student'] . ", '".$user['first_name']." ".$user['last_name']."', '".$user['email']."', '$hash', '$new_iv', 1, current_timestamp()),";
        }
        $create_user_query = substr($create_user_query, 0, -1);
        if (query_create($conn, $create_user_query)) {
            $users = query_getData($conn, "select id, email from user where email in $check_email");
            $create_student_detail = "INSERT into student_detail (user_id, first_name, last_name, roll_no, phone, address, created_at) value ";
            foreach ($users as $user) {
                $create_student_detail .= "(" . $user['id'] . ",'".$users_data[$user['email']]['first_name']."', '".$users_data[$user['email']]['last_name']."', '".$users_data[$user['email']]['roll_no']."', '".$users_data[$user['email']]['phone']."', '".$users_data[$user['email']]['address']."', current_timestamp()),";
            }
            $create_student_detail = substr($create_student_detail, 0, -1);
            if (query_create($conn, $create_student_detail)) sendData(true, "Student created successfully");
        }
        sendData(false, "Student not able to create");
        break;
    
    default:
        sendData(false, "Method not found");
        break;
}
