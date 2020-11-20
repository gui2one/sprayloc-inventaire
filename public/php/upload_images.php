<?php




include("db_connect.php");

if ($conn->connect_errno) {
    echo "Echec lors de la connexion à MySQL : (" .mysql_error();
}else{
	//echo "ok";

	echo "FILES length ---> ".count($_FILES)."<br />\n";
	for ($i=0; $i < count($_FILES); $i++) { 

		

		//echo $_FILES["image".$i]["name"]."<br />\n";
		$sourcePath = $_FILES['file_'.$i]['tmp_name'];       // Storing source path of the file in a variable
		// echo "-----> ".$_FILES['image'.$i]['name'] ."\n";
		if($sourcePath){
			$targetPath = "../uploads/".$_FILES['file_'.$i]['name']; // Target path where file is to be stored

			echo "new Name -> ".uniqueNameV2($targetPath)."\n";
			$targetPath = uniqueNameV2($targetPath);
			// echo "\ntargetPath -------->>>>>>".$targetPath."\n";
			move_uploaded_file($sourcePath,$targetPath) ;    // Moving Uploaded file	
			//echo "\n SOURCE PATH -->".$sourcePath;
		}else{
			echo "\n unvalid source path";
			return false;
		}	

		$image_src = $targetPath;
		$info = pathinfo($image_src);
		$dimensions = getimagesize($image_src);

		$width = $dimensions[0];
		$height = $dimensions[1];
		echo "\nDimensions --> width : ". $width." -- height : ".$height."\n";
		$baseName = basename($image_src, ".".$info['extension']);


		

		$image_dest = "../uploads/".$baseName."_thumbnail.".$info['extension'];
		

		$thumb = imagethumb($image_src, $image_dest,500);

		//echo "THUMB --> ".$thumb."\n";

		// echo basename($image_dest);
		// end create thumbnail
		if($thumb){

			$title = str_replace("_"," ",basename($targetPath,".".$info['extension']));
			//echo "\nTitle -->".$title;

			$query = mysql_query("INSERT INTO gallery_items  (title,url, thumbnail, width, height) VALUES ('".$title."','".$_FILES['image'.$i]['name']."', '".basename($image_dest)."',".$width.",".$height.")");
			
		}
	
	if($query){
		
		echo "\nok --> add gellery item";
	}else{
		echo " pas cool......:".mysql_error();
	}
}

}
//////////
//// create thumbnail
////////
function imagethumb( $image_src , $image_dest = NULL , $max_size = 500, $expand = FALSE, $square = FALSE )
{
	if( !file_exists($image_src) ) return FALSE;

	// Récupère les infos de l'image
	$fileinfo = getimagesize($image_src);

	echo "file infos :".$fileinfo;
	
	if( !$fileinfo ) return FALSE;

	$width     = $fileinfo[0];
	$height    = $fileinfo[1];


	$type_mime = $fileinfo['mime'];


	$type      = str_replace('image/', '', $type_mime);

	//echo "\ntype_mime --> ".$type;

	if( !$expand && max($width, $height)<=$max_size && (!$square || ($square && $width==$height) ) )
	{
		// L'image est plus petite que max_size
		if($image_dest)
		{
			return copy($image_src, $image_dest);
		}
		else
		{
			header('Content-Type: '. $type_mime);
			return (boolean) readfile($image_src);
		}
	}

	// Calcule les nouvelles dimensions
	$ratio = $width / $height;

	if( $square )
	{
		$new_width = $new_height = $max_size;

		if( $ratio > 1 )
		{
			// Paysage
			$src_y = 0;
			$src_x = round( ($width - $height) / 2 );

			$src_w = $src_h = $height;
		}
		else
		{
			// Portrait
			$src_x = 0;
			$src_y = round( ($height - $width) / 2 );

			$src_w = $src_h = $width;
		}
	}
	else
	{
		$src_x = $src_y = 0;
		$src_w = $width;
		$src_h = $height;

		if ( $ratio > 1 )
		{
			// Paysage
			$new_width  = $max_size;
			$new_height = round( $max_size / $ratio );
		}
		else
		{
			// Portrait
			$new_height = $max_size;
			$new_width  = round( $max_size * $ratio );
		}
	}

	// Ouvre l'image originale
	$func = 'imagecreatefrom' . $type;
	if( !function_exists($func) ) return FALSE;

	$image_src = $func($image_src);
	$new_image = imagecreatetruecolor($new_width,$new_height);

	// Gestion de la transparence pour les png
	if( $type=='png' )
	{
		imagealphablending($new_image,false);
		if( function_exists('imagesavealpha') )
			imagesavealpha($new_image,true);
	}

	// Gestion de la transparence pour les gif
	elseif( $type=='gif' && imagecolortransparent($image_src)>=0 )
	{
		$transparent_index = imagecolortransparent($image_src);
		$transparent_color = imagecolorsforindex($image_src, $transparent_index);
		$transparent_index = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
		imagefill($new_image, 0, 0, $transparent_index);
		imagecolortransparent($new_image, $transparent_index);
	}

	// Redimensionnement de l'image
	imagecopyresampled(
		$new_image, $image_src,
		0, 0, $src_x, $src_y,
		$new_width, $new_height, $src_w, $src_h
	);

	// Enregistrement de l'image
	$func = 'image'. $type;
	if($image_dest)
	{
		$func($new_image, $image_dest);
	}
	else
	{
		header('Content-Type: '. $type_mime);
		$func($new_image);
	}

	// Libération de la mémoire
	imagedestroy($new_image); 

	return TRUE;
}


function uniqueNameV2($target_file){
	$try = 1;
	while(file_exists($target_file)) {
	    // $target_file = preg_replace('/(\(\\d+\))*(\.[^\\(\\)]+)$/',"({$try})\\2", $target_file);

		$inf = pathinfo($target_file);
		$ext = ".".$inf["extension"];

	    $target_file = str_replace(basename($target_file,$ext), basename($target_file,$ext)."(".$try.")", $target_file);
	    $try++;
	}

	return $target_file;

}


?>