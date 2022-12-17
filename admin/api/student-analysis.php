<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'student-current-class':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN student_detail sd on sd.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");
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

    case 'schedule-today-remain':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        if (!isset($_POST['time']) || empty($_POST["time"])) sendData(false, "Not able to found schedule");
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) sendData(false, "Not able to found schedule");
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $day = WEEK_DAYS[strtolower(date('l'))];
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN student_detail sd on sd.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");

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

    case 'dashboard-count':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN student_detail sd on sd.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");
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
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $user_id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $user = query_getData1($conn,"SELECT u.id FROM user u INNER JOIN student_detail sd on sd.user_id=u.id WHERE u.id=$user_id");
        if($user == null) sendData(false, "Data not found");

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
