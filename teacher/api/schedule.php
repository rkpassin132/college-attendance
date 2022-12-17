<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['teacher']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'schedule-day':
        if (!isset($_POST['day']) || empty($_POST['day']) || !isset(WEEK_DAYS[$_POST['day']])) sendData(false, "Please select correct week day");
        $day = WEEK_DAYS[sql_prevent($conn, xss_prevent($_POST['day']))];
        $user_id = get_userId();

        $query = "SELECT ct.name as course, d.short_name as department, bs.session, b.session_type, s.name as subject, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time  from teacher_classes tc 
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id
        LEFT JOIN branch_subject bs on bs.id=tc.branch_subject_id
        LEFT JOIN branch b on b.id=bs.branch_id
        LEFT JOIN course_type ct on ct.id=b.course_type_id
        LEFT JOIN department d on d.id=b.department_id
        LEFT JOIN subject s on s.id=bs.subject_id
        where tc.days=$day and td.user_id=$user_id";

        $schedule =  query_getData($conn,  $query);
        sendData(true, $schedule);
        break;

    case 'schedule-today-remain':
        if (!isset($_POST['time']) || empty($_POST["time"])) sendData(false, "Not able to found schedule");
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) sendData(false, "Not able to found schedule");
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $user_id = get_userId();
        $day = WEEK_DAYS[strtolower(date('l'))];

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

    default:
        sendData(false, "Method not found");
        break;
}

?>
