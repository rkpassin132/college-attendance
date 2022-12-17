<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'teacher-list-select':
        $query = "SELECT td.id, concat(td.first_name, ' ', td.last_name)as name, u.email from teacher_detail td INNER JOIN user u on td.user_id=u.id where u.status=1";
        $teachers =  query_getData($conn,  $query);
        sendData(true, $teachers);
        break;
    case 'teacher-add-shedule':
        $error = array();
        if (!isset($_POST['teacher']) || empty($_POST["teacher"]) || !is_numeric($_POST['teacher'])) $error['teacher'] = "Teacher should not be empty";
        if (!isset($_POST['course-type']) || empty($_POST["course-type"]) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course should not be empty";
        if (!isset($_POST['department']) || empty($_POST["department"]) || !is_numeric($_POST['department'])) $error['department'] = "Branch should not be empty";
        if (!isset($_POST['session']) || empty($_POST["session"]) || !is_numeric($_POST['session'])) $error['session'] = "Session should not be empty";
        if (!isset($_POST['subject']) || empty($_POST["subject"]) || !is_numeric($_POST['subject'])) $error['subject'] = "Subject should not be empty";
        if (!isset($_POST['day']) || empty($_POST["day"])) $error['day'] = "Day should not be empty";
        else if(!isset(WEEK_DAYS[$_POST["day"]])) $error['day'] = "Day not valid";
        if (!isset($_POST['start-time']) || empty($_POST["start-time"])) $error['start-time'] = "Start time should not be empty";
        else if(!valid_time(TIME_FORMATE, $_POST["start-time"])) $error['start-time'] = "Start time should in format";
        if (!isset($_POST['end-time']) || empty($_POST["end-time"])) $error['end-time'] = "End time should not be empty";
        else if(!valid_time(TIME_FORMATE, $_POST["end-time"])) $error['end-time'] = "End time should in format";
        else if(strtotime($_POST["end-time"]) <= strtotime($_POST["start-time"])) $error['end-time'] = "Greater than start time";
        if (sizeof($error) > 0) sendData(false, $error);

        $teacher_key = sql_prevent($conn, xss_prevent($_POST['teacher']));
        $course_type_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session_key = sql_prevent($conn, xss_prevent($_POST['session']));
        $subject_key = sql_prevent($conn, xss_prevent($_POST['subject']));
        $day_key = sql_prevent($conn, xss_prevent($_POST['day']));
        $start_time = sql_prevent($conn, xss_prevent($_POST['start-time']));
        $end_time = sql_prevent($conn, xss_prevent($_POST['end-time']));
        
        $teacher = query_getData1($conn, "SELECT td.id from teacher_detail td INNER JOIN user u on u.id=td.user_id where u.status=1 and td.id=$teacher_key");
        if($teacher == null) sendData(false, "Teacher is not valid");
        
        $branch_subject = query_getData1($conn, "SELECT bs.id from branch_subject bs LEFT JOIN branch b on b.id=bs.branch_id where b.course_type_id=$course_type_key and b.department_id=$department_key and bs.subject_id=$subject_key and bs.session=$session_key");
        if($branch_subject == null) sendData(false, "Please select correct branch");

        $query_check = "SELECT tc.id, tc.start_time, tc.end_time from teacher_classes tc 
        JOIN branch_subject bs on bs.id=tc.branch_subject_id  
        JOIN branch b on b.id=bs.branch_id  
        where b.course_type_id=$course_type_key and b.department_id=$department_key and bs.session=$session_key and tc.days=" . WEEK_DAYS[$day_key] . "  and  ( ((tc.start_time >= '$start_time' and tc.start_time <= '$end_time') or (tc.end_time >= '$start_time' and tc.end_time <= '$end_time')) or (('$start_time' >= tc.start_time and '$start_time' <= tc.end_time) or ('$end_time' >= tc.start_time and '$end_time' <= tc.end_time)) )";
        
        $data = query_getData1($conn, $query_check);
        if($data != null) sendData(false, "Shedule already exist at this day from ".get_time($data['start_time'])." to ".get_time($data['end_time']));

        $query = "INSERT into teacher_classes (teacher_id, branch_subject_id, days, start_time, end_time, created_at) value ($teacher_key, ".$branch_subject['id'].", ".WEEK_DAYS[$day_key].", '$start_time', '$end_time', current_timestamp())";
        if(query_create($conn, $query)) sendData(true, "Teacher shedule inserted");
        else sendData(false, "Teacher shedule not able to inserted");
        break;

    case 'teacher-search-shedule':
        $error = array();
        if (!isset($_POST['course-type']) || empty($_POST["course-type"]) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course should not be empty";
        if (!isset($_POST['department']) || empty($_POST["department"]) || !is_numeric($_POST['department'])) $error['department'] = "Branch should not be empty";
        if (!isset($_POST['session']) || empty($_POST["session"]) || !is_numeric($_POST['session'])) $error['session'] = "Session should not be empty";
        if (sizeof($error) > 0) sendData(false, $error);

        $course_type_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session_key = sql_prevent($conn, xss_prevent($_POST['session']));

        $day = "CASE";
        foreach (WEEK_DAYS as $key => $value) {
            $day .= " When tc.days = ".$value." THEN '".$key."'";
        }
        $day .= "ELSE '' END as days";
        $query = "SELECT tc.id, $day, concat(td.first_name, ' ', td.last_name) as teacher, s.name as subject, '' as action, time_format(tc.start_time, '%h:%i %p') as start_time, time_format(tc.end_time, '%h:%i %p') as end_time from teacher_classes tc 
        JOIN branch_subject bs on bs.id=tc.branch_subject_id 
        JOIN branch b on b.id=bs.branch_id 
        JOIN teacher_detail td on td.id=tc.teacher_id 
        JOIN subject s on s.id=bs.subject_id
        WHERE b.course_type_id=$course_type_key and b.department_id=$department_key and bs.session=$session_key
        ORDER BY tc.days, tc.start_time";

        $data = query_getData($conn, $query);
        sendData(true, $data);
        break;

    case 'teacher-class-delete':
        if (!isset($_POST['key']) || empty($_POST["key"]) || !is_numeric($_POST['key'])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $data = query_getData1($conn, "SELECT id FROM teacher_classes WHERE id=$id");
        if($data == null) sendData(false, "Data not found!");
        if(query_delete($conn, "DELETE FROM teacher_classes WHERE id=$id")) sendData(true, "Data deleted successfully!");
        else sendData(false, "Data cannot be deleted");
        break;

    default:
        sendData(false, "Method not found");
        break;
}
    
    