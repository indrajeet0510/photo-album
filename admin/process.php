<?php
	
	$option = @$_POST['option'];
	if($option==NULL)
	{
		$option = 0;
	}
	echo $option;
	switch($option)
	{
		case 1 : 
			require_once('logic/authenticate.php');
			break;
		case 2 :
			require_once('logic/create_album.php');
			break;
		case 3 :
			require_once('logic/add_photos.php');
		default :
			break;
	}
?>