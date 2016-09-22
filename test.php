<?php
	//var_dump(gd_info());
	require_once 'image_lib/demo/helpers/common.php';
	require_once 'image_lib/lib/WideImage.php';
	
	try
	{
	  $image = WideImage::load('images/15102010033.jpg');
	}
	catch (Exception $e)
	{
	  echo "Image isn't valid";
	}
	
	$image = $image->resize(400, 300);
	
	$image->crop('center', 'center', 300, 300)->resize(100, 100)->saveToFile('image-thumg2.png');
	$large = $image->unsharp(80, 0.5, 3);
	
	echo $large->getWidth() . 'x' . $large->getHeight();
	$large->saveToFile('image-large1.png');
?>