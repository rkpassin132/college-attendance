<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'teacher-change-password':
        $error = array();
        if (!isset($_POST['teacher']) || empty($_POST['teacher'])) sendData(false, "Not able to found teacher");
        if (!isset($_POST['password']) || empty($_POST['password'])) $error['password'] = "Password should not be empty";
        else if (valid_password($password) != 1) $error['password'] = valid_password($password);
        if (!isset($_POST['confirm_password']) || empty($_POST['confirm_password'])) $error['confirm_password'] = "Confirm should not be empty";
        else if(strcasecmp($password, $confirm_password)) $error['confirm_password'] = "Password not matching";
        if (sizeof($error) > 0) sendData(false, $error);
        
        $teacher_id = sql_prevent($conn, xss_prevent($_POST['teacher']));
        $password = sql_prevent($conn, xss_prevent($_POST['password']));
        $confirm_password = sql_prevent($conn, xss_prevent($_POST['confirm_password']));
        
        if (empty($teacher_id) || !is_numeric($teacher_id)) sendData(false, "Not able to found teacher");
        if (empty($password)) $error['password'] = "Password should not be empty";
        else if (valid_password($password) != 1) $error['password'] = valid_password($password);
        if (empty($confirm_password)) $error['confirm_password'] = "Confirm should not be empty";
        else if (strcasecmp($password, $confirm_password)) $error['confirm_password'] = "Password not matching";
        if (sizeof($error) > 0) sendData(false, $error);

        $user = query_getData1($conn, "SELECT user_id from teacher_detail where id=$teacher_id");
        if($user == null) sendData(false, "Not able to found teacher");
        $id = $user['user_id'];

        $check = query_getData1($conn, "SELECT iv, password from user where id=$id");
        if (strcasecmp(decryption($check['password'], hex2bin($check['iv'])), $password) == 0) sendData(false, "Password is similar to previous password.");

        $iv = openssl_random_pseudo_bytes(16);
        $new_iv = bin2hex($iv);
        $hash = encryption($password, $iv);

        $query = "UPDATE user SET iv='$new_iv', password='$hash' where id=$id";
        if (query_update($conn, $query)) sendData(true, "Password change successfully.");
        else sendData(false, "Password on able to update.");
        break;

    case 'teacher-change-personal':
        $error = array();
        if (!isset($_POST['teacher']) || empty($_POST['teacher']) || !is_numeric($_POST['teacher'])) sendData(false, "Not able to found teacher");
        if (!isset($_POST['first_name']) || empty($_POST['first_name'])) $error['first_name'] = "First name should not be empty";
        else if (valid_name($_POST['first_name']) != 1) $error['first_name'] = valid_name($_POST['first_name']);
        if(isset($_POST['last-name']) && !empty($_POST['last-name'])){
            if (valid_name($_POST['last-name']) != 1) $error['last-name'] = valid_name($_POST['last-name']);
        }
        if (isset($_POST['phone']) && !empty($_POST['phone'])) {
            if (valid_phone($_POST['phone']) != 1) $error['phone'] = valid_phone($_POST['phone']);
        }
        if (sizeof($error) > 0) sendData(false, $error);

        $teacher_id = sql_prevent($conn, xss_prevent($_POST['teacher']));
        $first_name = sql_prevent($conn, xss_prevent($_POST['first_name']));
        $last_name = sql_prevent($conn, xss_prevent($_POST['last_name']));
        $phone = sql_prevent($conn, xss_prevent($_POST['phone']));
        $address = sql_prevent($conn, xss_prevent($_POST['address']));

        $user = query_getData1($conn, "SELECT id from teacher_detail where id=$teacher_id");
        if($user == null) sendData(false, "Not able to found teacher");

        query_update($conn, "UPDATE user u INNER JOIN teacher_detail td on td.user_id=u.id set u.name='$first_name $last_name' where td.id=$teacher_id");
        $query = "UPDATE teacher_detail SET first_name='$first_name', last_name='$last_name', phone='$phone', address='$address' WHERE id=$teacher_id";
        if (query_update($conn, $query)) sendData(true, "Detail updated");
        else sendData(true, "Detail not able to submit");
        break;

    case 'teacher-schedule-day':
        if (!isset($_POST['teacher']) || empty($_POST['teacher']) || !is_numeric($_POST['teacher'])) sendData(false, "Not able to found teacher");
        if (!isset($_POST['day']) || empty($_POST['day']) || !isset(WEEK_DAYS[$_POST['day']])) sendData(false, "Please select correct week day");
        $day = WEEK_DAYS[sql_prevent($conn, xss_prevent($_POST['day']))];

        $teacher_id = sql_prevent($conn, xss_prevent($_POST['teacher']));
        $user = query_getData1($conn, "SELECT id from teacher_detail where id=$teacher_id");
        if($user == null) sendData(false, "Not able to found teacher");

        $query = "SELECT ct.name as course, d.short_name as department, bs.session, b.session_type, s.name as subject, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time  from teacher_classes tc 
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id
        LEFT JOIN branch_subject bs on bs.id=tc.branch_subject_id
        LEFT JOIN branch b on b.id=bs.branch_id
        LEFT JOIN course_type ct on ct.id=b.course_type_id
        LEFT JOIN department d on d.id=b.department_id
        LEFT JOIN subject s on s.id=bs.subject_id
        where tc.days=$day and td.id=$teacher_id"; 

        $schedule =  query_getData($conn,  $query);
        sendData(true, $schedule);
        break;
        
    case 'teacher-activate':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $query = mysqli_query($conn,"SELECT id FROM teacher_detail WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if(query_delete($conn,"UPDATE user u INNER JOIN teacher_detail td on td.user_id=u.id SET u.status=1 WHERE td.id=$id")) sendData(true,"User activated successfully!");
            else sendData(false,"Data cannot be activated");
        }
        else sendData(false,"Data not found!");
        
        break;

    case 'teacher-deactivate':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $query = mysqli_query($conn,"SELECT id FROM teacher_detail WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if(query_delete($conn,"UPDATE user u INNER JOIN teacher_detail td on td.user_id=u.id SET u.status=0 WHERE td.id=$id")) sendData(true,"User deactivated successfully!");
            else sendData(false,"Data cannot be deactivated");
        }
        else sendData(false,"Data not found!");
        
        break;

    case 'teacher-delete':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $data = query_getData1($conn, "SELECT id FROM teacher_detail WHERE id=$id");
        if($data == null) sendData(false, "Data not found!");
        query_delete($conn,"DELETE FROM user u INNER JOIN teacher_detail td on td.user_id=u.id WHERE td.id=$id");
        query_delete($conn,"DELETE FROM teacher_detail WHERE id=$id");
        query_delete($conn,"DELETE FROM teacher_classes WHERE teacher_id=$id");
        sendData(true, "Data deleted successfully!");
        break;
    default:
        sendData(false, "Method not found");
        break;
}