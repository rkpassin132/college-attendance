<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {
    case 'student-list-add-class':
        $query = "SELECT sd.id, sd.first_name as name, sd.roll_no, u.email from student_detail sd INNER JOIN user u on sd.user_id=u.id LEFT JOIN student_classes sc on sc.student_id=sd.id where sc.student_id is NULL and u.status=1";
        $students =  query_getData($conn,  $query);
        sendData(true, $students);
        break;

    case 'student-class-list':
        $error = array();
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";

        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));

        $query = "SELECT sd.id, sd.first_name as name, sd.roll_no from student_classes sc 
        INNER JOIN branch b on b.id=sc.branch_id 
        INNER JOIN student_detail sd on sd.id=sc.student_id 
        where b.course_type_id=$course_key and b.department_id=$department_key and sc.session=$session and sc.status=1";
        $students = query_getData($conn, $query);
        sendData(true, $students);
        break;
    case 'student-add-class':
        $error = array();
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";
        if (!isset($_POST['student']) || !is_array($_POST['student']) || count($_POST['student']) <= 0) $error['student'] = "Student not valid";
        else{
            $check = true;
            $check_id = "(";
            foreach ($_POST['student'] as $key => $subject) {
                $_POST['student'][$key] = sql_prevent($conn, xss_prevent($_POST['student'][$key]));
                $check_id .= $_POST['student'][$key].",";
                if(!is_numeric($subject)) $check = false;
            }
            $check_id = substr($check_id, 0, -1);
            $check_id .= ")";
            if(!$check) $error['student[]'] = "Subject not valid";
        }
        if (sizeof($error) > 0) sendData(false, $error);

        $students = $_POST['student'];
        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));

        $students_data = query_getData($conn, "SELECT id from student_detail where id in $check_id");
        if(count($students_data) != count($students)) sendData(false, ['student'=>"Data is already exist, Please check your selection"]);
        
        $query = "SELECT id, session_type from branch where course_type_id=$course_key and department_id=$department_key";
        $branch = query_getData1($conn, $query);
        if($branch == null) sendData(false, "Invalid branch selection");
        else if(!in_array($session, SESSION_TYPE[$branch['session_type']]['data'])) sendData(false, ['session'=>"Session not valid"]);

        // check student classes
        $data_query = "SELECT concat(sd.first_name,' ', sd.last_name) as student_name, ct.name as course_name, d.name as department_name, d.short_name as department_short_name, b.session_type, sc.session from student_classes sc 
        INNER JOIN student_detail sd on sd.id=sc.student_id 
        INNER JOIN branch b on b.id=sc.branch_id 
        INNER JOIN course_type ct on ct.id=b.course_type_id 
        INNER JOIN department d on d.id=b.department_id 
        where sc.student_id in $check_id and sc.status=1";

        $data = query_getData($conn, $data_query);
        if(count($data) > 0) {
            $msg = "Student already study\n\n";
            foreach ($data as $user) $msg .= $user['student_name']." in ".$user['session']." ".SESSION_TYPE[$user['session_type']]['name']." of (".$user['department_short_name'].") ".$user['department_name']." department of ". $user['course_name']." course.\n";
            $msg .= "\n If you want to change promote student class please check student promote section.";
            sendData(false, $msg);
        }

        // add new class
        $branch_key =$branch['id'];
        $insert_query = "INSERT INTO student_classes (student_id, branch_id, session, status, created_at) value ";
        foreach ($students as $student) $insert_query .= "($student,$branch_key, $session, 1, current_timestamp()),";
        $insert_query = substr($insert_query, 0, -1);
        if(query_create($conn, $insert_query)) sendData(true, "Student added to new class");
        else sendData(false, "Student not able to add in new class");
        break;
    
    case 'student-excel-add-class':
        $error = array();
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";
        if (!isset($_FILES['student-file'])) sendData(false, ['student-file' => "File is required"]);
        $valid_file = valid_file($_FILES['student-file'], ["excel"]);
        if ($valid_file != 1) sendData(false, ['student-file' => $valid_file]);

        require('../../api/ImportExcel.php');
        $valid_col = [ "roll no" => ["number"] ];
        try {
            $excel = new ImportExcel($conn, $_FILES['student-file']['tmp_name'], $valid_col);
        } catch (Exception $e) {
            sendData(false, "Some error occur in file");
        }
        if ($excel->hasErrors()) sendData(false, ["file_error" => $excel->getErrors()]);

        // validate data student roll no
        $data = $excel->getData();
        $errors = array();
        $roll_no = array();
        $check_roll_no = "(";
        $check_roll_no_2 = "";
        foreach ($data as $user) {
            if(!isset($roll_no[$user['roll_no']])) $roll_no[$user['roll_no']] = 1;
            else $roll_no[$user['roll_no']]++;
            if($roll_no[$user['roll_no']] > 1 && !isset($errors[$user['roll_no']])) array_push($errors, "Roll no <b>".$user['roll_no']."</b> is duplicate");
            $check_roll_no .= "'".$user['roll_no']."',";
            $check_roll_no .= "('".$user['roll_no']."'),";
        }
        $check_roll_no = substr($check_roll_no, 0, -1);
        $check_roll_no_2 = substr($check_roll_no_2, 0, -1);
        $check_roll_no .= ")";
        if(count($errors) > 0) sendData(false, ['file_error' => $errors]);

        $student = query_getData($conn, "select id from student_detail where roll_no in $check_roll_no");
        if (count($student) != count($data)) sendData(false, ['file_error' => ['Please check your roll no, some of them are not exist']]);

        // validate branch and session
        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));
        
        $query = "SELECT id, session_type from branch where course_type_id=$course_key and department_id=$department_key";
        $branch = query_getData1($conn, $query);
        if($branch == null) sendData(false, "Invalid branch selection");
        else if(!in_array($session, SESSION_TYPE[$branch['session_type']]['data'])) sendData(false, ['session'=>"Session not valid"]);

        // check student classes
        $data_query = "SELECT sd.id, sd.roll_no, concat(sd.first_name,' ', sd.last_name) as student_name, ct.name as course_name, d.name as department_name, d.short_name as department_short_name, b.session_type, sc.session from student_classes sc 
        INNER JOIN student_detail sd on sd.id=sc.student_id 
        INNER JOIN branch b on b.id=sc.branch_id 
        INNER JOIN course_type ct on ct.id=b.course_type_id 
        INNER JOIN department d on d.id=b.department_id 
        where sd.roll_no in $check_roll_no and sc.status=1";

        $data = query_getData($conn, $data_query);
        if(count($data) > 0) {
            $msg = "Student already study\n\n";
            foreach ($data as $user) $msg .= $user['student_name']." roll no (".$user['roll_no'].") is in ".$user['session']." ".SESSION_TYPE[$user['session_type']]['name']." of (".$user['department_short_name'].") ".$user['department_name']." department of ". $user['course_name']." course.\n";
            $msg .= "\n If you want to change promote student class please check student promote section.";
            sendData(false, $msg);
        }

        // add new class
        $branch_key =$branch['id'];
        $insert_query = "INSERT INTO student_classes (student_id, branch_id, session, status, created_at) value ";
        foreach ($student as $data) $insert_query .= "(".$data['id'].",$branch_key, $session, 1, current_timestamp()),";
        $insert_query = substr($insert_query, 0, -1);
        if(query_create($conn, $insert_query)) sendData(true, "Student added to new class");
        else sendData(false, "Student not able to add in new class");
        break;
        
    case 'student-promote-class':
        $error = array();
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";
        if (!isset($_POST['student']) || empty($_POST['student']) || !is_numeric($_POST['student'])) $error['student'] = "Student not valid";

        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));
        $update_session = (int)$session+1;
        $student_key = sql_prevent($conn, xss_prevent($_POST['student']));

        $check_query = "SELECT sc.branch_id from student_classes sc 
        INNER JOIN branch b on b.id=sc.branch_id 
        where b.course_type_id=$course_key and b.department_id=$department_key and sc.session=$session and sc.student_id=$student_key and sc.status=1";
        $student_class = query_getData1($conn, $check_query);
        if($student_class == null) sendData(false, "Student not exist in this class");
        $branch_key = $student_class['branch_id'];

        if(query_update($conn, "UPDATE student_classes set status=0 where status=1 and student_id=$student_key")){
            $insert_query = "INSERT into student_classes (student_id, branch_id, session, status, created_at) values ($student_key, $branch_key, $update_session, 1, current_timestamp())";
            if(query_create($conn, $insert_query)) sendData(true, "Student promoted");
        }
        sendData(false, "Student is not able to promote");
        break;

    case 'promote-class-student':
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";

        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));
        $update_session = (int)$session+1;

        $query = "SELECT sc.student_id, b.id as branch_id from student_classes sc INNER JOIN branch b on b.id=sc.branch_id where b.course_type_id=$course_key and b.department_id=$department_key and sc.session=$session and sc.status=1";
        $students = query_getData($conn, $query);
        if(count($students) <= 0) sendData(false, "Students not exist in this class");
        $students_id="(";
        $insert_query="";
        foreach ($students as $student) {
            $students_id .= $student['student_id'].",";
            $insert_query .= "( ".$student['student_id'].", ".$student['branch_id'].", ".$update_session.", 1, current_timestamp() ),"; 
        }
        $students_id = substr($students_id, 0, -1);
        $insert_query = substr($insert_query, 0, -1);
        $students_id .= ")";

        $update_query = "UPDATE student_classes SET status=0 where student_id in $students_id";
        if(query_update($conn, $update_query)){
            $insert_query = "INSERT into student_classes (student_id, branch_id, session, status, created_at) values $insert_query";
            if(query_create($conn, $insert_query)) sendData(true, count($students)." Student of this class are updated");
        }

        sendData(false, "Class not able to promote");
        break;
    default:
        sendData(false, "Method not found");
        break;
}
        