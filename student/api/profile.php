<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['student']);
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
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
        if (strcasecmp(decryption($check['password'], hex2bin($check['iv'])), $password) == 0) sendData(false, "Password is similar to previous password.");

        $iv = openssl_random_pseudo_bytes(16);
        $new_iv = bin2hex($iv);
        $hash = encryption($password, $iv);

        $query = "UPDATE user SET iv='$new_iv', password='$hash' where id=$id";
        if (query_update($conn, $query)) sendData(true, "Password change successfully.");
        else sendData(false, "Password on able to update.");

        break;

    case 'profile-change-personal':
        $error = array();
        if (!isset($_POST['first_name']) || empty($_POST['first_name'])) $error['first_name'] = "First name should not be empty";
        else if (valid_name($_POST['first_name']) != 1) $error['first_name'] = valid_name($_POST['first_name']);
        if (isset($_POST['last-name'])) {
            if (!isset($_POST['last-name']) || empty($_POST['last_name'])) $error['last_name'] = "Last name should not be empty";
            else if (valid_name($_POST['last_name']) != 1) $error['last_name'] = valid_name($_POST['last_name']);
        }
        if (!isset($_POST['phone']) || empty($_POST['phone'])) $error['phone'] = "Phone should not be empty";
        else if (valid_phone($_POST['phone']) != 1) $error['phone'] = valid_phone($_POST['phone']);
        if (!isset($_POST['address']) || empty($_POST['address'])) $error['address'] = "Address should not be empty";
        if (sizeof($error) > 0) sendData(false, $error);

        $user_id = get_userId();
        $first_name = sql_prevent($conn, xss_prevent($_POST['first_name']));
        $last_name = sql_prevent($conn, xss_prevent($_POST['last_name']));
        $phone = sql_prevent($conn, xss_prevent($_POST['phone']));
        $address = sql_prevent($conn, xss_prevent($_POST['address']));

        query_update($conn, "UPDATE user set name='$first_name $last_name' where id=$user_id");
        $query = "UPDATE student_detail SET first_name='$first_name', last_name='$last_name', phone='$phone', address='$address' WHERE user_id=$user_id";
        if (query_update($conn, $query)) sendData(true, "Detail updated");
        else sendData(true, "Detail not able to submit");
        break;

    case 'student-current-class':
        $user_id = get_userId();
        $query = "SELECT ct.name as course, ct.short_name as course_short, d.short_name as branch, sc.session, b.session_type from student_classes sc
        LEFT JOIN student_detail sd on sd.id=sc.student_id
        LEFT JOIN branch b on b.id=sc.branch_id
        LEFT JOIN course_type ct on ct.id=b.course_type_id
        LEFT JOIN department d on d.id=b.department_id
        where sc.status=1 and sd.user_id=$user_id";
        $data = query_getData1($conn, $query);
        if($data != null) $data['session_type'] = SESSION_TYPE[$data['session_type']]['name'];
        sendData(true, $data);
        break;

    default:
        sendData(false, "Method not found");
        break;
}
