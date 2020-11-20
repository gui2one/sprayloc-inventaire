<?php
require_once "./db_connect.php";

if( $conn->connect_errno){
    die("could not connect");
}else{

    $query = "INSERT INTO inventaire (json_data) VALUES('{}')";

    $result = $conn->query($query);

    if($result){

        echo "ok";
    }
}

?>