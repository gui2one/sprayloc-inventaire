<?php

require_once "./db_connect.php";

$id = "null";
if (isset($_POST["id"]) && isset($_POST["json_data"])) {
    $id = $_POST["id"];
    $json_data = $_POST["json_data"];

    echo "item id : ".$id . "\n";
    echo "JSON : ".$json_data . "\n";
    $query = "UPDATE inventaire SET json_data='$json_data' WHERE id=$id";

    $result = $conn->query($query);

    if ($result) {
        echo "item updated successfully";
    }else{
        echo "problem...";
    }
}
?>