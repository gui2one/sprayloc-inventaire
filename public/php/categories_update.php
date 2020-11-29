<?php

require_once "./db_connect.php";

$id = "null";
// if (isset($_POST["id"]) && isset($_POST["json_data"])) {
//     $id = $_POST["id"];
//     $json_data = $_POST["json_data"];

//     echo "item id : ".$id . "\n";
//     echo "JSON : ".$json_data . "\n";
//     $query = "UPDATE categories SET json_data='$json_data' WHERE id=$id";

//     $result = $conn->query($query);

//     if ($result) {
//         echo "category updated successfully";
//     }else{
//         echo "problem...";
//     }
// }

if(isset($_POST["num_cats"])){
    // echo "received all categories :";

    $num_categories = $_POST["num_cats"];

    // print_r($num_categories);

    for($i=0; $i < $num_categories; $i++){
        $id = $_POST["cat_id_".$i];
        $json_data = $_POST["cat_json_data_".$i];

        print_r($json_data);
        $query = "UPDATE categories SET json_data='$json_data' WHERE id=$id";

        $result = $conn->query($query);


        if ($result) {
            echo "category ".$i." updated successfully";
        }else{
            echo "problem...";
        }
    }
    // $return_array[] = $categories;
    // echo json_encode($return_array, true);


    // foreach( $categories as $cat)
    // {
   
    //     $return_array[] = $cat->{"json_data"};

    //     // $id = $cat->{"id"};
    //     // $json_data = json_encode($cat->{"json_data"});
    //     // $query = "UPDATE categories SET json_data='$json_data' WHERE id=$id";

    //     // $result = $conn->query($query);


    //     // if ($result) {
    //     //     echo "category updated successfully";
    //     // }else{
    //     //     echo "problem...";
    //     // }
    // }


}
?>