<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['student']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'schedule-day':
        if (!isset($_POST['day']) || empty($_POST['day']) || !isset(WEEK_DAYS[$_POST['day']])) sendData(false, "Please select correct week day");
        $day = WEEK_DAYS[sql_prevent($conn, xss_prevent($_POST['day']))];
        $user_id = get_userId();

        $query = "SELECT concat(td.first_name, ' ' , td.last_name) as teacher, s.name as subject, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time  from student_classes sc
        LEFT JOIN student_detail sd on sd.id=sc.student_id
        INNER JOIN branch b on b.id=sc.branch_id
        LEFT JOIN branch_subject bs on bs.branch_id=b.id
        LEFT JOIN subject s on s.id=bs.subject_id
        LEFT JOIN teacher_classes tc on tc.branch_subject_id=bs.id
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id
        where bs.session=sc.session and sc.status=1 and tc.days=$day and sd.user_id=$user_id
        ORDER BY tc.start_time ASC";

        $schedule =  query_getData($conn,  $query);
        sendData(true, $schedule);
        break;

    case 'schedule-today-remain':
        if (!isset($_POST['time']) || empty($_POST["time"])) sendData(false, "Not able to found schedule");
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) sendData(false, "Not able to found schedule");
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $user_id = get_userId();
        $day = WEEK_DAYS[strtolower(date('l'))];

        $query = "SELECT concat(td.first_name, ' ' , td.last_name) as teacher, s.name as subject, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time  from student_classes sc
        LEFT JOIN student_detail sd on sd.id=sc.student_id
        INNER JOIN branch b on b.id=sc.branch_id
        LEFT JOIN branch_subject bs on bs.branch_id=b.id
        LEFT JOIN subject s on s.id=bs.subject_id
        LEFT JOIN teacher_classes tc on tc.branch_subject_id=bs.id
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id
        where bs.session=sc.session and sc.status=1 and tc.days=$day and (tc.start_time >= '$curr_time' or tc.end_time >= '$curr_time') and sd.user_id=$user_id
        ORDER BY tc.start_time ASC";
        // echo $query; die;
        $schedule =  query_getData($conn,  $query);
        sendData(true, $schedule);
        break;

    default:
        sendData(false, "Method not found");
        break;
}

?>
