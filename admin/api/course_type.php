<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));

switch ($submit) {

    case 'course-list':
        $courses =  query_getData($conn,  "SELECT id, name, short_name, '' as action FROM course_type");
        sendData(true, $courses);
        break;

    case 'course-create':
        if (!isset($_POST["name"]) || empty($_POST["name"])) sendData(false, ['name' => "Course name should not be empty"]);
        if (!isset($_POST["short_name"]) || empty($_POST["short_name"])) sendData(false, ['short_name' => "Short name should not be empty"]);
        $name = sql_prevent($conn, xss_prevent($_POST["name"]));
        $short_name = sql_prevent($conn, xss_prevent($_POST["short_name"]));

        $query = "INSERT INTO course_type (name, short_name, created_at) VALUES ('$name','$short_name',current_timestamp())";

        if($result = mysqli_query($conn,$query)) sendData(true, "created");
        else sendData(true, "something went wrong");
        break;

    case 'course-update':
        $error = array();
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        if (!isset($_POST["name"]) || empty($_POST["name"])) $error['name'] = "Course name should not be empty";
        if (!isset($_POST["short_name"]) || empty($_POST["short_name"])) $error['short_name'] = "Course name should not be empty";
        if (sizeof($error) > 0) sendData(false, $error);

        $id = sql_prevent($conn, xss_prevent($_POST["key"]));
        $name = sql_prevent($conn, xss_prevent($_POST["name"]));
        $short_name = sql_prevent($conn, xss_prevent($_POST["short_name"]));

        $query = mysqli_query($conn,"SELECT id FROM course_type WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            $query = "UPDATE `course_type` SET `name` = '$name', `short_name` = '$short_name' WHERE `course_type`.`id` = $id";
            if(query_update($conn,$query)) sendData(true,"Data Updated successfully!");
            else sendData(false,"Data cannot be updated");
        }
        else sendData(false,"Data not found!");
        break;

    case 'course-delete':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
       
        $query = mysqli_query($conn,"SELECT id FROM course_type WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if(query_delete($conn,"DELETE FROM course_type WHERE id=$id")) sendData(true,"Data deleted successfully!");
            else sendData(false,"Data cannot be deleted");
        }
        else sendData(false,"Data not found!");
        
        break;
        
    default:
        sendData(false, "Method not found");
        break;
}
