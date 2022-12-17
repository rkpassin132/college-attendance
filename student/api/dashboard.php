<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['student']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'dashboard-count':
        $user_id = get_userId();
        $query = "SELECT 
        ( SELECT count(bs.subject_id) from student_classes sc 
        INNER JOIN branch b on b.id=sc.branch_id
        INNER JOIN branch_subject bs on bs.branch_id=b.id
        INNER JOIN student_detail sd on sd.id=sc.student_id
        where bs.session=sc.session and sc.status=1 and sd.user_id=$user_id) as subject,
        ( SELECT count(sa.id) from student_classes sc 
        INNER JOIN branch b on b.id=sc.branch_id
        INNER JOIN branch_subject bs on bs.branch_id=b.id
        INNER JOIN student_attendance sa on sa.branch_subject_id=bs.id
        INNER JOIN student_detail sd on sd.id=sc.student_id
        where bs.session=sc.session and sc.status=1 and sd.user_id=$user_id ) as attendance
        ";
        $count = query_getData1($conn, $query);
        sendData(true, $count);
        break;

    case 'dashboard-percent-classes':
        $user_id = get_userId();
        $query = "SELECT count(sa.id) as count, aty.name from student_classes sc
        INNER JOIN student_detail sd on sd.id=sc.student_id
        INNER JOIN branch b on b.id=sc.branch_id
        INNER JOIN branch_subject bs on bs.branch_id=b.id
        INNER JOIN student_attendance sa on sa.branch_subject_id=bs.id
        INNER JOIN attendance_type aty on aty.id=sa.attendance_type_id
        Where sc.session=bs.session and sd.user_id=$user_id
        group by aty.id";
        $data = query_getData($conn, $query);
        foreach ($data as $key => $value) $data[$key]['color']=COLOR_SCHEMA[$key];
        sendData(true, $data);
        break;

    default:
        sendData(false, "Method not found");
        break;
}
