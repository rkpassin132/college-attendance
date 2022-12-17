<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'branch-list':
        $query = "SELECT b.id, d.name as department, ct.short_name as course_type, if(b.session_type=0, 'semester', 'year') as session_type, '' as action from branch b LEFT JOIN department d on d.id=b.department_id LEFT JOIN course_type ct on ct.id=b.course_type_id";

        $data = query_getData($conn, $query);
        sendData(true, $data);
        break;

    case 'branch-create':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Branch not valid";
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['session-type']) || empty($_POST['session-type']) || !is_numeric($_POST['session-type'])) $error['session-type'] = "Session type not valid";
        else if (!isset(SESSION_TYPE[$_POST['session-type']])) sendData(false, ['session-type' => "Session type not valid"]);

        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $session_type = sql_prevent($conn, xss_prevent($_POST['session-type']));
        
        $department = query_getData1($conn, "SELECT id from department where id=$department_key");
        if ($department == null) sendData(false, ['department' => "Department not valid"]);

        $course = query_getData1($conn, "SELECT id from course_type where id=$course_key");
        if ($course == null) sendData(false, ['course-type' => "Course not valid"]);

        $data_query = "SELECT id from branch where course_type_id=$course_key and department_id=$department_key and session_type=$session_type";
        $data = query_getData($conn, $data_query);
        if (count($data) > 0) sendData(false, "Branch is already exist, Please check your selection");

        $insert_query = "INSERT INTO branch (department_id, course_type_id, session_type, created_at) value ($department_key, $course_key, $session_type, current_timestamp())";
        if (query_create($conn, $insert_query)) sendData(true, "Branch created successfully");
        else sendData(false, "Branch not able to create");
        break;

    case 'branch-delete':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
        
        $query = mysqli_query($conn,"SELECT id FROM branch WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if(query_delete($conn,"DELETE FROM branch WHERE id=$id")) sendData(true,"Branch deleted successfully!");
            else sendData(false,"Branch cannot be deleted");
        }
        else sendData(false,"Branch not found!");
        
        break;

    default:
        sendData(false, "Method not found");
        break;
}
