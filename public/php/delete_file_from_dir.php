<?php

    $url = $_POST["url"];
	ini_set('display_errors', 1);

	

	$rootDir = $_SERVER["DOCUMENT_ROOT"];
	$httpHost = $_SERVER["HTTP_HOST"];
	$imgDirPath = $rootDir.'/uploads/';
	
    // $handle = opendir($imgDirPath);
    
    echo $httpHost. "\n";
    $thumb = str_replace('https://'.$httpHost, $rootDir, $url);
    $img = str_replace("_thumbnail", '', $thumb );
    unlink($thumb);
    unlink($img);


?>