<?php

$conn = new mysqli('localhost', 'root', '', 'sprayloc-inventaire');

if($conn->connect_errno){
die('Could not connect: '.mysqli_connect_error());  
}else{
    // $conn->set_charset("utf8");
}