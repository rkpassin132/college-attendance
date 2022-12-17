<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['teacher']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'department-list-active':
        $user_id = get_userId();
        $query = "SELECT DISTINCT(d.id), d.name from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id
        INNER JOIN branch b on b.id=bs.branch_id
        INNER JOIN department d on d.id=b.department_id
        where td.user_id=$user_id";
        $department =  query_getData($conn,  $query);
        sendData(true, $department);
        break;

    case 'department-course-type-list-active':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) sendData(false, "Not able to get course type");
        $department = sql_prevent($conn, xss_prevent($_POST['department']));
        $user_id = get_userId();
        $query = "SELECT DISTINCT(ct.id), ct.name, ct.short_name from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id
        INNER JOIN branch b on b.id=bs.branch_id
        INNER JOIN course_type ct on ct.id=b.course_type_id
        where td.user_id=$user_id and b.department_id=$department";
        $course =  query_getData($conn,  $query);
        sendData(true, $course);
        break;

    case 'department-course-session-list-active':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) sendData(false, "Not able to get course type");
        if (!isset($_POST['course']) || empty($_POST['course']) || !is_numeric($_POST['course'])) sendData(false, "Not able to get course type");
        $department = sql_prevent($conn, xss_prevent($_POST['department']));
        $course = sql_prevent($conn, xss_prevent($_POST['course']));
        $user_id = get_userId();

        $query = "SELECT DISTINCT(bs.session) from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id
        INNER JOIN branch b on b.id=bs.branch_id
        INNER JOIN course_type ct on ct.id=b.course_type_id
        where td.user_id=$user_id and b.department_id=$department and b.course_type_id=$course";
        $session =  query_getData($conn,  $query);
        sendData(true, $session);
        break;

    case 'department-course-session-subject-list-active':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) sendData(false, "Not able to get course type");
        if (!isset($_POST['course']) || empty($_POST['course']) || !is_numeric($_POST['course'])) sendData(false, "Not able to get course type");
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) sendData(false, "Not able to get course type");
        $department = sql_prevent($conn, xss_prevent($_POST['department']));
        $course = sql_prevent($conn, xss_prevent($_POST['course']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));

        $query = "SELECT DISTINCT(s.id), s.name from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id
        INNER JOIN subject s on s.id=bs.subject_id
        INNER JOIN branch b on b.id=bs.branch_id
        where td.user_id=$user_id and b.department_id=$department and b.course_type_id=$course";
        $session =  query_getData($conn,  $query);
        sendData(true, $session);
        break;

    default:
        sendData(false, "Method not found");
        break;
}
