<?php

	ini_set('display_errors', 1);

	// echo nl2br($_SERVER["HTTP_HOST"]);

	$rootDir = $_SERVER["DOCUMENT_ROOT"];
	$httpHost = $_SERVER["HTTP_HOST"];
	$imgDirPath = $rootDir.'/uploads/';
	// echo nl2br("$imgDirPath\n");
	$handle = opendir($imgDirPath);

	$thumbFiles = array();
	$imgFiles = array();
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
		
    	if (strpos($entry, '_thumbnail') !== false) {

	    		// echo $entry."\n";
	    	$pathInfo = pathInfo($entry);
	    	if( strtoupper($pathInfo["extension"]) == "JPG" || strtoupper($pathInfo["extension"]) == "JPEG" || strtoupper($pathInfo["extension"]) == "PNG" || strtoupper($pathInfo["extension"]) == "GIF" )
	    	{

	        	// echo nl2br("$entry\n");
				$thumbFullPath = "https://".$httpHost."/uploads/".$entry;
				$thumbLocalPath = $rootDir."/uploads/".$entry;
				// echo nl2br($thumbFullPath."\n");
	        	array_push($thumbFiles, array( 'url' => $thumbFullPath, 'date' => filectime($thumbLocalPath)));
	        	// echo nl2br(str_replace("_thumbnail", "",$entry)."\n");
	        	array_push($imgFiles, str_replace("_thumbnail", "",$thumbFullPath));
	    		
	    	}

		}	
    }

    for ($i=0; $i < count($thumbFiles); $i++) { 

    	//echo nl2br("&nbsp;&nbsp;&nbsp;&nbsp;".$imgFiles[$i]."\n");

    }
	print_r(json_encode($thumbFiles));



?>