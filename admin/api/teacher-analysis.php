<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'schedule-today-remain':
        if (!isset($_POST['time']) || empty($_POST["time"])) sendData(false, "Not able to found schedule");
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) sendData(false, "Not able to found schedule");
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $user_id = get_userId();
        $day = WEEK_DAYS[strtolower(date('l'))];
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN teacher_detail td on td.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");

        $query = "SELECT ct.name as course, d.short_name as department, bs.session, b.session_type, s.name as subject, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time  from teacher_classes tc 
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id
        LEFT JOIN branch_subject bs on bs.id=tc.branch_subject_id
        LEFT JOIN branch b on b.id=bs.branch_id
        LEFT JOIN course_type ct on ct.id=b.course_type_id
        LEFT JOIN department d on d.id=b.department_id
        LEFT JOIN subject s on s.id=bs.subject_id
        where tc.days=$day and td.user_id=$user_id and (tc.start_time >= '$curr_time' or tc.end_time >= '$curr_time')";
       
        $schedule =  query_getData($conn,  $query);
        sendData(true, $schedule);
        break;

    case 'dashboard-count':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN teacher_detail td on td.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");

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
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN teacher_detail td on td.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");

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
