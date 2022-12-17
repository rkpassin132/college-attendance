<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'department-list':
        $query = "SELECT id, name, short_name,  '' as action FROM department";
        $departmentes =  query_getData($conn,  $query);
        sendData(true, $departmentes);
        break;

    case 'department-session':
        if (!isset($_POST['department-key']) || empty($_POST['department-key'])) sendData(false, "Not able to load session type");
        $key = sql_prevent($conn, xss_prevent($_POST['department-key']));
        $department =  query_getData1($conn,  "SELECT session_type FROM department where id=$key");

        if (SESSION_TYPE[$department['session_type']]) sendData(true, SESSION_TYPE[$department['session_type']]['data']);
        else sendData(false, "Not able to load session type");
        break;

    case 'course-department-list':
        $departmentes =  query_getData($conn,  "SELECT *, '' as action FROM department where course");
        sendData(true, $departmentes);
        break;

    case 'department-create':
        $error = array();
        if (!isset($_POST["fullname"]) || empty($_POST["fullname"])) $error['fullname'] = "full name should not be empty";
        if (!isset($_POST['shortname']) || empty($_POST["shortname"])) $error['shortname'] = "short name should not be empty";
        if (sizeof($error) > 0) sendData(false, $error);

        $fullname = sql_prevent($conn, xss_prevent($_POST["fullname"]));
        $shortname = sql_prevent($conn, xss_prevent($_POST["shortname"]));

        $query = "INSERT INTO department (name, short_name, created_at) VALUES ('$fullname', '$shortname', current_timestamp())";

        if ($result = mysqli_query($conn, $query)) sendData(true, "created");
        else sendData(true, "something went wrong");
        break;

    case 'department-excel-create':
        if (!isset($_FILES['department-file'])) sendData(false, ['department-file' => "File is required"]);
        $valid_file = valid_file($_FILES['department-file'], ["excel"]);
        if ($valid_file != 1) sendData(false, ['department-file' => $valid_file]);

        require('../../api/ImportExcel.php');
        $valid_col = [
            "full name" => ["required", "name"],
            "short name" => ["required", "name"],
        ];
        try {
            $excel = new ImportExcel($conn, $_FILES['department-file']['tmp_name'], $valid_col);
        } catch (Exception $e) {
            sendData(false, "Some error occur in file");
        }
        if ($excel->hasErrors()) sendData(false, ["file_error" => $excel->getErrors()]);

        $data = $excel->getData();
        $query = "INSERT INTO department (name, short_name, created_at) VALUES ";
        foreach ($data as $value) {
            $query .= "('".$value['full_name']."', '".$value['short_name']."', current_timestamp()),";
        }
        $query = substr($query, 0, -1);
        if(query_create($conn, $query)) sendData(true, "Departmentes created sccussfully");
        else sendData(false, "Not able to create departmentes");
        break;

    case 'department-update':
        $error = array();
        if (!isset($_POST["key"]) || empty($_POST["key"])) sendData(false, "Not able to found department");
        if (!isset($_POST["fullname"]) || empty($_POST["fullname"])) $error['fullname'] = "full name should not be empty";
        if (!isset($_POST['shortname']) || empty($_POST["shortname"])) $error['shortname'] = "short name should not be empty";
        if (sizeof($error) > 0) sendData(false, $error);

        $fullname = sql_prevent($conn, xss_prevent($_POST["fullname"]));
        $shortname = sql_prevent($conn, xss_prevent($_POST["shortname"]));
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $query = mysqli_query($conn, "SELECT id FROM department WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            $query = "UPDATE department SET name='" . $fullname . "',short_name='" . $shortname . "' WHERE id=$id";
            if (query_update($conn, $query)) sendData(true, "Data Updated successfully!");
            else sendData(false, "Data cannot be updated");
        } else sendData(false, "Data not found!");
        break;

    case 'department-delete':
        if (!isset($_POST['key']) || empty($_POST["key"])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $query = mysqli_query($conn, "SELECT id FROM department WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if (query_delete($conn, "DELETE FROM department WHERE id=$id")) sendData(true, "Data deleted successfully!");
            else sendData(false, "Data cannot be deleted");
        } else sendData(false, "Data not found!");

        break;

    default:
        sendData(false, "Method not found");
        break;
}
