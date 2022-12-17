<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'department-list-active':
        $query = "SELECT DISTINCT(b.department_id) as id, d.name, d.short_name from branch b INNER JOIN department d on d.id=b.department_id";
        $department =  query_getData($conn,  $query);
        sendData(true, $department);
        break;

    case 'department-course-type-list-active':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) sendData(false, "Not able to get course type");
        $department = sql_prevent($conn, xss_prevent($_POST['department']));

        $query = "SELECT DISTINCT(b.course_type_id) as id, ct.short_name as name from branch b INNER JOIN course_type ct on ct.id=b.course_type_id where b.department_id=$department";
        $course =  query_getData($conn,  $query);
        sendData(true, $course);
        break;

    case 'department-course-session-list-active':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) sendData(false, "Not able to get course type");
        if (!isset($_POST['course']) || empty($_POST['course']) || !is_numeric($_POST['course'])) sendData(false, "Not able to get course type");
        $department = sql_prevent($conn, xss_prevent($_POST['department']));
        $course = sql_prevent($conn, xss_prevent($_POST['course']));

        $query = "SELECT DISTINCT(b.session_type) from branch b where b.department_id=$department and b.course_type_id=$course";
        $session =  query_getData1($conn,  $query);
        if ($session == null)  sendData(true, []);
        $session = SESSION_TYPE[$session['session_type']]['data'];
        sendData(true, $session);
        break;

    case 'department-course-session-subject-list-active':
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) sendData(false, "Not able to get course type");
        if (!isset($_POST['course']) || empty($_POST['course']) || !is_numeric($_POST['course'])) sendData(false, "Not able to get course type");
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) sendData(false, "Not able to get course type");
        $department = sql_prevent($conn, xss_prevent($_POST['department']));
        $course = sql_prevent($conn, xss_prevent($_POST['course']));
        $session = sql_prevent($conn, xss_prevent($_POST['session']));

        $query = "SELECT s.id, s.name from branch_subject bs 
        LEFT JOIN branch b on b.id=bs.branch_id 
        LEFT JOIN subject s on s.id=bs.subject_id
        where b.department_id=$department and b.course_type_id=$course and bs.session=$session";
        $session =  query_getData($conn,  $query);
        sendData(true, $session);
        break;

    case 'create-branch-subject':
        $error = array();
        if (!isset($_POST['department']) || empty($_POST['department']) || !is_numeric($_POST['department'])) $error['department'] = "Department not valid";
        if (!isset($_POST['course-type']) || empty($_POST['course-type']) || !is_numeric($_POST['course-type'])) $error['course-type'] = "Course not valid";
        if (!isset($_POST['subjects']) || !is_array($_POST['subjects']) || count($_POST['subjects']) <= 0) $error['subjects[]'] = "Subject not valid";
        else {
            $check = true;
            $check_id = "(";
            foreach ($_POST['subjects'] as $key => $subject) {
                $_POST['subjects'][$key] = sql_prevent($conn, xss_prevent($_POST['subjects'][$key]));
                $check_id .= $_POST['subjects'][$key] . ",";
                if (!is_numeric($subject)) $check = false;
            }
            $check_id = substr($check_id, 0, -1);
            $check_id .= ")";
            if (!$check) $error['subjects[]'] = "Subject not valid";
        }
        if (!isset($_POST['session']) || empty($_POST['session']) || !is_numeric($_POST['session'])) $error['session'] = "Session not valid";
        if (sizeof($error) > 0) sendData(false, $error);

        $department_key = sql_prevent($conn, xss_prevent($_POST['department']));
        $course_key = sql_prevent($conn, xss_prevent($_POST['course-type']));
        $subjects = $_POST['subjects'];
        $session = sql_prevent($conn, xss_prevent($_POST['session']));

        $query = "SELECT id, session_type from branch where department_id=$department_key and course_type_id=$course_key";
        $branch = query_getData1($conn, $query);
        if ($branch == null) sendData(true, "Please select correct branch");
        else if (!in_array($session, SESSION_TYPE[$branch['session_type']]['data'])) sendData(false, ['session' => "Session not valid"]);
        $branch_key = $branch['id'];

        $subjects_data = query_getData($conn, "SELECT id from subject where id in $check_id");
        if (count($subjects_data) != count($subjects)) sendData(false, ['subject' => "Subject not valid"]);

        $data_query = "SELECT id from branch_subject where branch_id=$branch_key and subject_id in $check_id and session=$session";
        $data = query_getData($conn, $data_query);
        if (count($data) > 0) sendData(false, "Data is already exist, Please check your subject selection");

        $insert_query = "INSERT INTO branch_subject (branch_id, subject_id, session, created_at) value ";
        foreach ($subjects as $subject) $insert_query .= "($branch_key, $subject, $session, current_timestamp()),";
        $insert_query = substr($insert_query, 0, -1);
        if (query_create($conn, $insert_query)) sendData(true, "Data created successfully");
        else sendData(false, "Data not able to create");

        break;

    case 'subject-search':
        $query = "SELECT bs.id, s.name, d.name as department_name, d.short_name as department_short_name, b.session_type, ct.short_name as course_name, bs.session, '' as action from branch_subject bs INNER JOIN subject s on s.id=bs.subject_id INNER JOIN branch b on b.id=bs.branch_id INNER JOIN course_type ct on ct.id=b.course_type_id INNER JOIN department d on d.id=b.department_id ";
        $where = " where 1=1";

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
                    $where .= " and bs.session=" . $session;
                }
            }
        }

        $query .= $where . " ORDER BY b.department_id, b.course_type_id, bs.session ASC";

        $subjects =  query_getData($conn,  $query);
        sendData(true, $subjects);
        break;

    case 'subject-branch-delete':
        if (!isset($_POST['key']) || empty($_POST["key"]) || !is_numeric($_POST['key'])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $query = mysqli_query($conn, "SELECT id FROM branch_subject WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if (query_delete($conn, "DELETE FROM branch_subject WHERE id=$id")) sendData(true, "Subject deleted successfully!");
            else sendData(false, "Subject cannot be deleted");
        } else sendData(false, "Subject not found!");

        break;

    default:
        sendData(false, "Method not found");
        break;
}
