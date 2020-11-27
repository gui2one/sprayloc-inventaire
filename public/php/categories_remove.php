<?php

require_once "./db_connect.php";

$id = "null";
if (isset($_POST["id"])) {
    $id = $_POST["id"];


    echo $id;

    $query = "DELETE FROM categories WHERE id=$id";

    $result = $conn->query($query);

    if ($result) {
        echo "category deleted";
    }
}
?>