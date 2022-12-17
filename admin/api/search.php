<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));

switch ($submit) {
    case 'search':
        if (!isset($_POST['search']) || empty($_POST['search'])) sendData(false, "Search not found");
        $text = sql_prevent($conn, xss_prevent($_POST['search']));

        $query = "SELECT name from department where name like '%".$text."%' ORDER BY name limit 5";
        $query_data = query_getData($conn, $query);
        if(count($query_data) > 0) $data['department'] = $query_data;

        $student = USER_ROLE['student'];
        $teacher = USER_ROLE['teacher'];
        $query = "SELECT name, email, status from user where role=$teacher AND name like '%$text%' ORDER BY name ASC limit 5";
        $query_data = query_getData($conn, $query);
        if(count($query_data) > 0) $data['teacher'] = $query_data;

        $query = "SELECT name, email, status from user where role=$student AND name like '%$text%' ORDER BY name ASC limit 5";
        $query_data = query_getData($conn, $query);
        if(count($query_data) > 0) $data['student'] = $query_data;

        sendData(true, $data);
    break;

    default:
        sendData(false, "Method not found");
        break;
}