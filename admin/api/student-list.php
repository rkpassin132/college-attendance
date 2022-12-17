<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'student-search':
        $query = "SELECT sd.id, concat(sd.first_name, ' ', sd.last_name)as name, u.email, sd.roll_no, sd.address, u.status as action from student_detail sd INNER JOIN user u on sd.user_id=u.id INNER JOIN student_classes sc on sc.student_id=sd.id INNER JOIN branch b on b.id=sc.branch_id";
        $where = "";

        if (isset($_POST['department']) && !empty($_POST['department'] && is_numeric($_POST['department']))) {
            $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
            $department = query_getData1($conn, "SELECT id from department where id=$department_key");
            if ($department != null) $where .= " and b.department_id=" . $department['id'];

            if (isset($_POST['course-type']) && !empty($_POST['course-type'] && is_numeric($_POST['course-type']))) {
                $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
                $course = query_getData1($conn, "SELECT id from course_type where id=$course_key");
                if ($course != null) $where .= " and b.course_type_id=" . $course['id'];

                if (isset($_POST['session']) && !empty($_POST['session'] && is_numeric($_POST['session']))) {
                    $session = sql_prevent($conn, xss_prevent($_POST['session']));
                    $where .= " and sc.session=" . $session;
                }
            }
        }

        if(empty($where)){
            $query = "SELECT sd.id, concat(sd.first_name, ' ', sd.last_name)as name, u.email, sd.roll_no, sd.address, u.status as action from student_detail sd INNER JOIN user u on sd.user_id=u.id LEFT JOIN student_classes sc on sc.student_id=sd.id where sc.student_id is NULL";
        }else{
            $query .= " where sc.status=1 ".$where." ORDER BY b.course_type_id, b.department_id, sc.session ASC";
        }

        $students =  query_getData($conn,  $query);
        sendData(true, $students);
        break;

    default:
        sendData(false, "Method not found");
        break;
}
