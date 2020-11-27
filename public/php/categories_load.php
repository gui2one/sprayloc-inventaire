<?php

require_once "./db_connect.php";
if( !$conn->connect_errno){


    $str_output = "";
    $sql = "create table IF NOT EXISTS categories(
    id INT AUTO_INCREMENT,json_data JSON NOT NULL,primary key (id))";  
    
    if(mysqli_query($conn, $sql)){  
    // echo "Table created successfully<br>";  
    } else {  
    // echo "Table is not created successfully<br> ";  
    }  
    // var_dump($conn);


    $sql = "select * FROM categories";
    $result = mysqli_query($conn, $sql);



    $array = [];
    if($result){
        while($item = $result->fetch_assoc()){
            $array[] = array("id" => $item["id"], "json_data" => json_decode($item["json_data"]));
        }

        echo json_encode($array);
    }else{
        echo "problem<br>";
    }
    mysqli_close($conn);  
}


?>