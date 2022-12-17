<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['teacher']);
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'dashboard-count':
        $user_id = get_userId();
        $query = "SELECT
        ( SELECT count(tc.id) from teacher_classes tc Left JOIN teacher_detail td on td.id=tc.teacher_id where td.user_id=$user_id ) as week_classes,
        ( SELECT count(distinct(bs.subject_id)) from teacher_classes tc Left JOIN branch_subject bs on bs.id=tc.branch_subject_id Left JOIN teacher_detail td on td.id=tc.teacher_id where td.user_id=$user_id) as subjects";
        $count = query_getData1($conn, $query);
        $query = "SELECT count(distinct(bs.session)) as classes from teacher_classes tc Left JOIN branch_subject bs on bs.id=tc.branch_subject_id Left JOIN teacher_detail td on td.id=tc.teacher_id where td.user_id=$user_id GROUP by bs.branch_id";
        $classes = query_getData($conn, $query);
        $count['classes'] = 0;
        foreach ($classes as $value) $count['classes']+=(int)$value['classes'];
        sendData(true, $count);
        break;

    case 'dashboard--graph-classes':
        $user_id = get_userId();
        $query = "SELECT DATE_FORMAT(sa.created_at, '%Y-%m-%d') as weeks, COUNT(sa.id) AS classes FROM student_attendance sa 
        LEFT JOIN teacher_detail td on td.id=sa.teacher_id 
        Where td.user_id=$user_id 
        GROUP BY YEAR(sa.created_at), DATE_FORMAT(sa.created_at, '%b %e');";
        // echo $query; die;
        $data = query_getData($conn, $query);
        $weekly = array("weeks" => array(), "classes" => array());
        foreach ($data as $week) {
            array_push($weekly["weeks"], $week["weeks"]);
            array_push($weekly["classes"], $week["classes"]);
        }

        $query = "SELECT DATE_FORMAT(sa.created_at, '%Y') as years, COUNT(sa.id) AS classes FROM student_attendance sa 
        LEFT JOIN teacher_detail td on td.id=sa.teacher_id 
        Where td.user_id=$user_id 
        GROUP BY YEAR(sa.created_at);";
        $data = query_getData($conn, $query);
        $yearly = array("years" => array(), "classes" => array());
        foreach ($data as $year) {
            array_push($yearly["years"], $year["years"]);
            array_push($yearly["classes"], $year["classes"]);
        }
        sendData(true, ["weekly" => $weekly, "yearly" => $yearly, "colors" => array(COLOR_SCHEMA[0], COLOR_SCHEMA[1])]);
        break;

    default:
        sendData(false, "Method not found");
        break;
}
