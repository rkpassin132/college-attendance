<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['teacher']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));

switch ($submit) {

    case 'attendance-list':
        $attendance = query_getData($conn, "SELECT id, name from attendance_type");
        sendData(true, $attendance);
        break;

    case 'attendance-class':
        if (!isset($_POST['time']) || empty($_POST["time"])) sendData(false, "Not able to found students");
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) sendData(false, "Not able to found students");
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $day = WEEK_DAYS[strtolower(date('l'))];
        $user_id = get_userId();

        $query = "SELECT ct.name as course, d.short_name as branch, b.session_type, bs.session, s.name as subject from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id
        INNER JOIN subject s on s.id=bs.subject_id
        INNER JOIN branch b on b.id=bs.branch_id
        INNER JOIN course_type ct on ct.id=b.course_type_id
        INNER JOIN department d on d.id=b.department_id
        where td.user_id=$user_id and tc.days=$day and '$curr_time' >= tc.start_time and '$curr_time' <= tc.end_time";
        $teacher = query_getData1($conn, $query);
        if ($teacher == null) sendData(false, "Sorry, There is not any class to attend at this time");
        $teacher['session_type'] = SESSION_TYPE[$teacher['session_type']]['name'];
        sendData(true, $teacher);

        break;

    case 'attendance-student-list':
        if (!isset($_POST['time']) || empty($_POST["time"])) sendData(false, "Not able to found students");
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) sendData(false, "Not able to found students");
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $day = WEEK_DAYS[strtolower(date('l'))];
        $user_id = get_userId();

        $query = "SELECT distinct(sd.id), bs.id as branch_subject_id, concat(sd.first_name, ' ', sd.last_name) as name, sd.roll_no, sd.id as action from teacher_classes tc 
        INNER JOIN teacher_detail td on td.id=tc.teacher_id 
        INNER JOIN branch_subject bs on bs.id=tc.branch_subject_id 
        INNER JOIN branch b on b.id=bs.branch_id 
        INNER JOIN student_classes sc on sc.branch_id=b.id
        INNER JOIN student_detail sd on sd.id=sc.student_id
        where sc.session=bs.session and sc.status=1 and td.user_id=$user_id and tc.days=$day and '$curr_time' >= tc.start_time and '$curr_time' <= tc.end_time";
        // echo $query; die;
        $student = query_getData($conn, $query);
        // print_r($student); die;
        $student_check_id = "(";
        foreach ($student as $value) {
            $student_check_id .= $value['id'].",";
        }
        $student_check_id = substr($student_check_id, 0, -1);
        $student_check_id .= ")";
        $branch_subject_id = $student[0]['branch_subject_id'];

        $query = "SELECT sa.student_id, sa.attendance_type_id from student_attendance sa 
        INNER JOIN teacher_detail td on td.id= sa.teacher_id
         where sa.student_id in $student_check_id and Date(sa.created_at) = CURDATE() and td.user_id=$user_id and sa.branch_subject_id=$branch_subject_id";
        $student_attendace = query_getData($conn, $query);
        $students_data = array();
        foreach ($student as $value) {
            $action = array();
            $action['id'] = $value['action'];
            $action['attendance'] = '';
            $value['action'] = $action;
            $students_data[$value['id']] = $value;
        }
        foreach ($student_attendace as $value) {
            $students_data[$value['student_id']]['action']['attendance'] = $value['attendance_type_id'];
        }
        $sendData = array();
        foreach($students_data as $value) array_push($sendData, $value);
        sendData(true, $sendData);
        break;

    case 'attendance-take':
        $error = false;
        if (!isset($_POST['key']) || empty($_POST['key']) || !is_numeric($_POST['key'])) $error = true;
        if (!isset($_POST['attendance']) || empty($_POST['attendance']) || !is_numeric($_POST['attendance'])) $error = true;
        if (!isset($_POST['time']) || empty($_POST["time"])) $error = true;
        else if (!valid_time(TIME_FORMATE, $_POST["time"])) $error = true;
        if ($error) sendData(false, "Not able to set set attendance");

        $attendance_id = sql_prevent($conn, xss_prevent($_POST['attendance']));
        $student_id = sql_prevent($conn, xss_prevent($_POST['key']));
        $curr_time = sql_prevent($conn, xss_prevent($_POST['time']));
        $day = WEEK_DAYS[strtolower(date('l'))];
        $user_id = get_userId();

        $attendace_type = query_getData1($conn, "SELECT id from attendance_type where id=$attendance_id");
        if ($attendace_type == null) sendData(false, "Please select correct attendance");

        $query = "SELECT bs.id, td.id as teacher_id from teacher_classes tc 
        LEFT JOIN teacher_detail td on td.id=tc.teacher_id
        LEFT JOIN branch_subject bs on bs.id=tc.branch_subject_id
        LEFT JOIN branch b on b.id=bs.branch_id
        LEFT JOIN student_classes sc on sc.branch_id=bs.branch_id
        where sc.student_id=$student_id and sc.session=bs.session and sc.status=1 and 
        td.user_id=$user_id and tc.days=$day and '$curr_time' >= tc.start_time and '$curr_time' <= tc.end_time";
        $branch_subject = query_getData($conn, $query);
        if(count($branch_subject) <= 0) sendData(false, "Student is not valid");
        else $branch_subject = $branch_subject[0];
        $teacher_id = $branch_subject['teacher_id'];
        $branch_subject_id = $branch_subject['id'];

        // update if already exist
        $query = "SELECT * from student_attendance where teacher_id=$teacher_id and student_id=$student_id and branch_subject_id=$branch_subject_id and DATE(created_at) = DATE(now())";
        if(count(query_getData($conn, $query)) > 0){
            $query = "UPDATE student_attendance SET attendance_type_id=$attendance_id where teacher_id=$teacher_id and student_id=$student_id and branch_subject_id=$branch_subject_id and DATE(created_at) = DATE(now())";
            if(query_update($conn, $query)) sendData(true, "Attendance set");
            else sendData(false, "Not able to set attendance");
        }else{
            $query = " INSERT into student_attendance (teacher_id, student_id, attendance_type_id, branch_subject_id, created_at) value ($teacher_id, $student_id, $attendance_id, $branch_subject_id, current_timestamp())";
            if(query_create($conn, $query)) sendData(true, "Attendance set");
            else sendData(false, "Not able to set attendance");
        }
        break;

    default:
        sendData(false, "Method not found");
        break;
}
