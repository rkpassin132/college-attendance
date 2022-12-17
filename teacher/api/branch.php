<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['teacher']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'branch-list':
        $user_id = get_userId();
        $query = "SELECT distinct(d.id), d.name, d.short_name, ct.name as course_name, ct.short_name as course_short_name from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id 
        INNER JOIN branch b on b.id=bs.branch_id
        INNER JOIN department d on d.id=b.department_id
        INNER JOIN course_type ct on ct.id=b.course_type_id
        where td.user_id=$user_id";
        $branches =  query_getData($conn,  $query);
        sendData(true, $branches);
        break;

    default:
        sendData(false, "Method not found");
        break;
}

?>
