<?php
require_once "./db_connect.php";

if( $conn->connect_errno){
    die("could not connect");
}else{
    $json_data = $_POST["json_data"];
    $query = "INSERT INTO inventaire (json_data) VALUES('$json_data')";

    $result = $conn->query($query);

    if($result){

        echo "ok";
    }
}

?>