<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'teacher-list':
        $query = "SELECT td.id, concat(td.first_name, ' ', td.last_name)as name, u.email, td.phone, td.address, u.status as action from teacher_detail td INNER JOIN user u on td.user_id=u.id";
        $teachers =  query_getData($conn,  $query);
        sendData(true, $teachers);
        break;
        
    default:
        sendData(false, "Method not found");
        break;
}
    