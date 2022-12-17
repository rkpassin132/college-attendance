<?php 

function query_getData($conn, $query)
{
    $query_execute = mysqli_query($conn, $query);
    if (mysqli_num_rows($query_execute) > 0) {
        $data = array();
        while ($result = mysqli_fetch_array($query_execute, MYSQLI_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }
    return [];
}

function query_getData1($conn, $query)
{
    $query_execute = mysqli_query($conn, $query);
    if (mysqli_num_rows($query_execute) > 0) {
        return mysqli_fetch_array($query_execute, MYSQLI_ASSOC);
    }
    return null;
}

function query_delete($conn, $query)
{
    $query_execute = mysqli_query($conn, $query);
    if ($query_execute) return true;
    else false;
}

function query_update($conn, $query)
{
    $query_execute = mysqli_query($conn, $query);
    if ($query_execute) return true;
    else false;
}

function query_create($conn, $query)
{
    return $conn->query($query);
}