<?php

include '../../api/function.php';
check_user_api($conn, USER_ROLE['admin']);
check_method("POST");

if(!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {

    case 'subject-list':
        $subjects =  query_getData($conn,  "SELECT *,'' as action FROM subject");
        sendData(true, $subjects);
        break;

    case 'subject-create':

        if (!isset($_POST['subject-name']) || empty($_POST["subject-name"])) sendData(false, ['subject-name' => "Subject name should not be empty"]);
        $subject_name = sql_prevent($conn, xss_prevent($_POST["subject-name"]));
        $query = "INSERT INTO subject (name, created_at) VALUES ('$subject_name', current_timestamp())";

        if ($result = mysqli_query($conn, $query)) sendData(true, "Subject created successfully");
        else sendData(true, "something went wrong");
        break;

    case 'subject-excel-create':
        if (!isset($_FILES['subject-file'])) sendData(false, ['subject-file' => "File is required"]);
        $valid_file = valid_file($_FILES['subject-file'], ["excel"]);
        if ($valid_file != 1) sendData(false, ['subject-file' => $valid_file]);

        require('../../api/ImportExcel.php');
        $valid_col = [ "subject_name" => ["required", "name"] ];
        try {
            $excel = new ImportExcel($conn, $_FILES['subject-file']['tmp_name'], $valid_col);
        } catch (Exception $e) {
            sendData(false, "Some error occur in file");
        }
        if ($excel->hasErrors()) sendData(false, ["file_error" => $excel->getErrors()]);

        $data = $excel->getData();
        $query = "INSERT INTO subject (name, created_at) VALUES ";
        foreach ($data as $value) {
            $query .= "('".$value['subject_name']."', current_timestamp()),";
        }
        $query = substr($query, 0, -1);
        if(query_create($conn, $query)) sendData(true, "Subject created sccussfully");
        else sendData(false, "Not able to create subject");
        break;

    case 'subject-update':
        if (!isset($_POST['key']) || empty($_POST['key']) || !is_numeric($_POST['key'])) sendData(false, "Not able to found subject");
        if (!isset($_POST['subject-name']) || empty($_POST['subject-name'])) sendData(false, ['subject-name' => "Subject should not ne empty"]);

        $name = sql_prevent($conn, xss_prevent($_POST['subject-name']));
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $query = mysqli_query($conn, "SELECT id FROM subject WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            $query = "UPDATE subject SET name='" . $name . "' WHERE id=$id";
            if (query_update($conn, $query)) sendData(true, "Data Updated successfully!");
            else sendData(false, "Data cannot be updated");
        } else sendData(false, "Data not found!");
        break;

    case 'subject-delete':
        if (!isset($_POST['key']) || empty($_POST["key"]) || !is_numeric($_POST['key'])) sendData(false, "Data not found");
        $id = sql_prevent($conn, xss_prevent($_POST['key']));

        $query = mysqli_query($conn, "SELECT id FROM subject WHERE id=$id");
        if (mysqli_num_rows($query) > 0) {
            if (query_delete($conn, "DELETE FROM subject WHERE id=$id")) sendData(true, "Data deleted successfully!");
            else sendData(false, "Data cannot be deleted");
        } else sendData(false, "Data not found!");

        break;

    default:
        sendData(false, "Method not found");
        break;
}
