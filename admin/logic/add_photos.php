<?php
	$eventId = $_POST['eventId'];
	$eventTypeId = $_POST['eventTypeId'];
	
	require_once('functions.php');
	require_once('lib/FileHandler.class.php');
	require_once('lib/Database.class.php');
	//var_dump($_FILES);
	try{
		
		$x = sizeof($_FILES['file']['name']);
		for($i=0;$i<$x;$i++)
		{
			$file[$i]['file'] = array('name'=>$_FILES['file']['name'][$i],'type'=>$_FILES['file']['type'][$i],'tmp_name'=>$_FILES['file']['tmp_name'][$i],'error'=>$_FILES['file']['error'][$i],'size'=>$_FILES['file']['size'][$i]);
			$Image = new FileHandler($file[$i],'file','../../images/');
			if($Image->uploadFile())
			{
				$Image->createSecondaryImages();
				$Image->saveUploadInDB($eventId,$eventTypeId);
				if($i==0)
				{
					$Image->createEventThumbnail($eventId);
				}
			}
		}

	}
	
	catch (Exception $e)
	{
		echo "Error encountered while processing";
	}
	require_once('../../header.php');
	echo '<h2>Files Uploaded Successfully</h2><a href="/" class="back-button big page-back"></a><div class="clearfix padding80"></div>';
	require_once('../../footer.php');
	
	
	/*foreach($_FILES as $file)
	{
		$Image = new FileHandler($file,'file','../../images/');
		if($Image->uploadFile())
		{
			$Image->createSecondaryImages();
			$Image->saveUploadInDB();
		}
	}
	*/