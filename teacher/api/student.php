<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['teacher']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'student-search':
        $error = array();
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";
        if (sizeof($error) > 0) sendData(false, $error);

        $department_key = sql_prevent($conn, xss_prevent($_POST['department'])); 
        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type'])); 
        $session = sql_prevent($conn, xss_prevent($_POST['session'])); 
        $user_id = get_userId();

        $query = "SELECT DISTINCT(b.id) from teacher_classes tc
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id
        INNER JOIN branch b on b.id=bs.branch_id
        where b.department_id=$department_key and b.course_type_id=$course_key and bs.session=$session and td.user_id=$user_id";
        $teacher_branch = query_getData($conn, $query);
        if(count($teacher_branch) != 1) sendData(false, "Your have not permission to access this class");
        $branch_id = $teacher_branch[0]['id'];

        $query = "SELECT sd.id, concat(sd.first_name, ' ', sd.last_name)as name, u.email, sd.phone, sd.address from student_detail sd 
        INNER JOIN user u on sd.user_id=u.id 
        INNER JOIN student_classes sc on sc.student_id=sd.id
        where sc.branch_id=$branch_id and sc.session=$session
        ORDER BY sd.first_name ASC";

        $students =  query_getData($conn,  $query);
        sendData(true, $students);
        break;

    default:
        sendData(false, "Method not found");
        break;
}