<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));

switch ($submit) {

    case 'attendance-list':
        $attendance =  query_getData($conn,  "SELECT *, '' as action FROM attendance_type");
        sendData(true, $attendance);
        break;

    case 'attendance-create':
        if (!isset($_POST['attendance-name']) || empty($_POST['attendance-name'])) sendData(false, ['attendance-name' => "Attendance name should not be empty"]);
        $name = sql_prevent($conn, xss_prevent($_POST['attendance-name']));

        $query = "INSERT INTO attendance_type (name,created_at) VALUES ('$name',current_timestamp())";
        if($result = mysqli_query($conn,$query)) sendData(true, "Attendance created successfully");
        else sendData(true, "Not able to create attendance");
        break;

    case 'attendance-update':
        if (!isset($_POST['key']) || empty($_POST["key"]) || !is_numeric($_POST['key'])) sendData(false, "Data not found");
        if (!isset($_POST['attendance-name']) || empty($_POST["attendance-name"])) sendData(false, ['attendance-name' => "Attendance name should not be empty"]);

        $name = sql_prevent($conn, xss_prevent($_POST["attendance-name"]));
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $data = query_getData1($conn,"SELECT id FROM attendance_type WHERE id=$id");
        if($data == null) sendData(false,"Data not found!");

        $query = "UPDATE attendance_type SET name='$name' WHERE id=$id";
        if(query_update($conn,$query)) sendData(true,"Attendance Updated successfully!");
        else sendData(false,"Attendance not able to update");
        break;

    case 'attendance-delete':
        if (!isset($_POST['key']) || empty($_POST["key"]) || !is_numeric($_POST['key'])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));
       
        $data = query_getData1($conn,"SELECT id FROM attendance_type WHERE id=$id");
        if($data == null) sendData(false,"Data not found!");

        if(query_delete($conn,"DELETE FROM attendance_type WHERE id=$id")) sendData(true,"Attendance deleted successfully!");
        else sendData(false,"Attendance not able to deleted");
        
        break;
        
    default:
        sendData(false, "Method not found");
        break;
}
