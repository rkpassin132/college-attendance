<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'student-change-password':
        $error = array();
        if (!isset($_POST['student']) || empty($_POST['student'])) sendData(false, "Not able to found student");
        if (!isset($_POST['password']) || empty($_POST['password'])) $error['password'] = "Password should not be empty";
        else if (valid_password($password) != 1) $error['password'] = valid_password($password);
        if (!isset($_POST['confirm_password']) || empty($_POST['confirm_password'])) $error['confirm_password'] = "Confirm should not be empty";
        else if(strcasecmp($password, $confirm_password)) $error['confirm_password'] = "Password not matching";
        if (sizeof($error) > 0) sendData(false, $error);
        
        $student_id = sql_prevent($conn, xss_prevent($_POST['student']));
        $password = sql_prevent($conn, xss_prevent($_POST['password']));
        $confirm_password = sql_prevent($conn, xss_prevent($_POST['confirm_password']));
        
        if (empty($student_id) || !is_numeric($student_id)) sendData(false, "Not able to found student");
        if (empty($password)) $error['password'] = "Password should not be empty";
        else if (valid_password($password) != 1) $error['password'] = valid_password($password);
        if (empty($confirm_password)) $error['confirm_password'] = "Confirm should not be empty";
        else if (strcasecmp($password, $confirm_password)) $error['confirm_password'] = "Password not matching";
        if (sizeof($error) > 0) sendData(false, $error);

        $user = query_getData1($conn, "SELECT user_id from student_detail where id=$student_id");
        if($user == null) sendData(false, "Not able to found student");
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

    case 'student-change-personal':
        $error = array();
        if (!isset($_POST['student']) || empty($_POST['student']) || !is_numeric($_POST['student'])) sendData(false, "Not able to found student");
        if (!isset($_POST['first_name']) || empty($_POST['first_name'])) $error['first_name'] = "First name should not be empty";
        else if (valid_name($_POST['first_name']) != 1) $error['first_name'] = valid_name($_POST['first_name']);
        if(isset($_POST['last-name']) && !empty($_POST['last-name'])){
            if (valid_name($_POST['last-name']) != 1) $error['last-name'] = valid_name($_POST['last-name']);
        }
        if (!isset($_POST['roll-no']) || empty($_POST['roll-no'])) $error['roll-no'] = "Roll no should not be empty";
        if (isset($_POST['phone']) && !empty($_POST['phone'])) {
            if (valid_phone($_POST['phone']) != 1) $error['phone'] = valid_phone($_POST['phone']);
        }
        if (sizeof($error) > 0) sendData(false, $error);

        $student_id = sql_prevent($conn, xss_prevent($_POST['student']));
        $first_name = sql_prevent($conn, xss_prevent($_POST['first_name']));
        $last_name = sql_prevent($conn, xss_prevent($_POST['last_name']));
        $phone = sql_prevent($conn, xss_prevent($_POST['phone']));
        $roll_no = sql_prevent($conn, xss_prevent($_POST['roll-no']));
        $address = sql_prevent($conn, xss_prevent($_POST['address']));

        $user = query_getData1($conn, "SELECT id from student_detail where id=$student_id");
        if($user == null) sendData(false, "Not able to found student");

        $user = query_getData1($conn, "SELECT id from student_detail where roll_no=$roll_no and id not in ($student_id)");
        if($user != null) sendData(false, "Roll number exist");

        query_update($conn, "UPDATE user u INNER JOIN student_detail td on td.user_id=u.id set u.name='$first_name $last_name' where td.id=$student_id");
        $query = "UPDATE student_detail SET first_name='$first_name', last_name='$last_name', roll_no='$roll_no', phone='$phone', address='$address' WHERE id=$student_id";
        if (query_update($conn, $query)) sendData(true, "Detail updated");
        else sendData(true, "Detail not able to submit");
        break;

    case 'student-class':
        if (!isset($_POST['student']) || empty($_POST['student']) || !is_numeric($_POST['student'])) sendData(false, "Not able to found student");
        $student_id = sql_prevent($conn, xss_prevent($_POST['student']));
        $user = query_getData1($conn, "SELECT id from student_detail where id=$student_id");
        if($user == null) sendData(false, "Not able to found student");

        $query = "SELECT ct.name as course, d.short_name as branch, sc.session, b.session_type from student_classes sc
        LEFT JOIN branch b on b.id=sc.branch_id
        LEFT JOIN course_type ct on ct.id=b.course_type_id
        LEFT JOIN department d on d.id=b.department_id
        where sc.student_id=$student_id and sc.status=1";
        $class = query_getData1($conn, $query);
        $class['session_type'] = SESSION_TYPE[$class['session_type']]['name'];
        sendData(true, $class);
        break;

    case 'student-schedule-day':
        if (!isset($_POST['student']) || empty($_POST['student']) || !is_numeric($_POST['student'])) sendData(false, "Not able to found student");
        if (!isset($_POST['day']) || empty($_POST['day']) || !isset(WEEK_DAYS[$_POST['day']])) sendData(false, "Please select correct week day");
        $day = WEEK_DAYS[sql_prevent($conn, xss_prevent($_POST['day']))];

        $student_id = sql_prevent($conn, xss_prevent($_POST['student']));
        $user = query_getData1($conn, "SELECT id from student_detail where id=$student_id");
        if($user == null) sendData(false, "Not able to found student");

        $query = "SELECT concat(td.first_name, ' ' , td.last_name) as teacher, s.name as subject, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time from student_classes sc 
        LEFT JOIN branch b on b.id=sc.branch_id 
        LEFT JOIN branch_subject bs on bs.branch_id=b.id 
        LEFT JOIN subject s on s.id=bs.subject_id 
        LEFT JOIN teacher_classes tc on tc.branch_subject_id=bs.id 
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id 
        where sc.status=1 and tc.days=$day and sc.student_id=$student_id
        ORDER BY tc.start_time ASC;";

        $schedule =  query_getData($conn,  $query);
        sendData(true, $schedule);
        break;
        
    case 'student-activate':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $query = mysqli_query($conn,"SELECT id FROM student_detail WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if(query_delete($conn,"UPDATE user u INNER JOIN student_detail sd on sd.user_id=u.id SET u.status=1 WHERE sd.id=$id")) sendData(true,"User activated successfully!");
            else sendData(false,"Data cannot be activated");
        }
        else sendData(false,"Data not found!");
        
        break;

    case 'student-deactivate':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $query = mysqli_query($conn,"SELECT id FROM student_detail WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if(query_delete($conn,"UPDATE user u INNER JOIN student_detail sd on sd.user_id=u.id SET u.status=0 WHERE sd.id=$id")) sendData(true,"User deactivated successfully!");
            else sendData(false,"Data cannot be deactivated");
        }
        else sendData(false,"Data not found!");
        
        break;
    
    case 'student-delete':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $query = mysqli_query($conn,"SELECT id FROM student_detail WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            query_delete($conn,"DELETE FROM user u INNER JOIN student_detail sd on sd.user_id=u.id WHERE sd.id=$id");
            query_delete($conn,"DELETE FROM student_detail WHERE id=$id");
            query_delete($conn,"DELETE FROM student_classes WHERE student_id=$id");
            query_delete($conn,"DELETE FROM student_attendance WHERE student_id=$id");
            sendData(true, "Data deleted successfully!");
        }
        else sendData(false,"Data not found!");
        
        break;
    default:
        sendData(false, "Method not found");
        break;
}