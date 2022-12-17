<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));

switch ($submit) {

    case 'dashboard-count':
        $query ="SELECT 
        (SELECT count(sd.id) from student_detail sd JOIN user u on u.id=sd.user_id where u.status=1) as student_active,
        (SELECT count(sd.id) from student_detail sd LEFT JOIN user u on u.id=sd.user_id where u.status=0 or u.id is null) as student_inactive,
        (SELECT count(td.id) from teacher_detail td JOIN user u on u.id=td.user_id where u.status=1) as teacher_active,
        (SELECT count(td.id) from teacher_detail td LEFT JOIN user u on u.id=td.user_id where u.status=0 or u.id is null) as teacher_inactive,
        (SELECT count(DISTINCT(ct.id)) from branch b LEFT JOIN course_type ct on b.course_type_id=ct.id) as course_active,
        (SELECT count(DISTINCT(ct.id)) from branch b LEFT JOIN course_type ct on b.course_type_id=ct.id where b.course_type_id is null) as course_inactive,
        (SELECT count(DISTINCT(b.id)) from branch b LEFT JOIN department d on d.id=b.department_id) as branch_active,
        (SELECT count(DISTINCT(b.id)) from branch b LEFT JOIN department d on d.id=b.department_id where b.department_id is null) as branch_inactive;";
        $data = query_getData1($conn, $query);
        sendData(true, $data);
        break;
    
    case 'dashboard-branch-student':
        $query = "SELECT b.course_type_id as course_id, ct.name as course_name, b.department_id, d.short_name as department_name, count(student_id) as student from student_classes sc 
        LEFT JOIN branch b on b.id=sc.branch_id 
        LEFT JOIN course_type ct on ct.id=b.course_type_id 
        LEFT JOIN department d on d.id=b.department_id 
        where sc.status=1 GROUP BY b.course_type_id, b.department_id 
        ORDER BY b.department_id, b.course_type_id ASC;";
        $data = query_getData($conn, $query);
        $arr = array();
        foreach ($data as $key => $value) {
            $arr[$value['course_id']]['course_id']  = $value['course_id'];
            $arr[$value['course_id']]['course_name']  = $value['course_name'];
            $departments = array("department_id"=> $value["department_id"], "department_name"=> $value["department_name"], "student"=> $value["student"]);
            if(isset($arr[$value['course_id']]['department'])) array_push($arr[$value['course_id']]['department'], $departments);
            else $arr[$value['course_id']]['department'] = array($departments);
            $index = count($arr[$value['course_id']]['department'])-1;
            $arr[$value['course_id']]['department'][$index]['color'] =  COLOR_SCHEMA[$index];
        }
        sendData(true, $arr);
        break;

    case 'dashboard-teacher-student-bar-graph':
        $query = "SELECT 
        (SELECT count(u2.id) as s from user u2 JOIN student_detail sd on sd.user_id=u2.id where u2.role=3 and YEAR(u2.created_at)=YEAR(u1.created_at)) as student,
        (SELECT count(u3.id) as s from user u3 JOIN teacher_detail td on td.user_id=u3.id where u3.role=2 and YEAR(u3.created_at)=YEAR(u1.created_at)) as teacher, 
        YEAR(u1.created_at) as year from user u1 
        GROUP BY YEAR(u1.created_at) ORDER BY YEAR(u1.created_at) ASC;";
        $data = query_getData($conn, $query);
        
        $send['color'] = [COLOR_SCHEMA[0], COLOR_SCHEMA[1]];
        $send['year'] = array();
        $send['student'] = array();
        $send['teacher'] = array();
        foreach ($data as $value) array_push($send['year'], $value['year']);
        foreach ($data as $value) array_push($send['student'], $value['student']);
        foreach ($data as $value) array_push($send['teacher'], $value['teacher']);

        sendData(true, $send);
        break;

    default:
        sendData(false, "Method not found");
        break;
}