<?php

	function verifyName($string)
	{
		$exp = "/^[a-zA-Z]$/";
		return preg_match($exp,$string);
	}
	
	
	
	function validate_upload_file(&$file)
	{
		$allowedExts = array("jpeg", "jpg", "png");
		$extension = get_file_extension($file['file']['name']);
		
		if((($file['file']['type']=='image/jpeg')||($file['file']['type']=='image/jpg')||($file['file']['type']=='image/pjpeg')||($file['file']['type']=='image/x-png')||($file['file']['type']=='image/png'))&&($file['file']['size']<5242880) && in_array($extension,$allowedExts))
		{
			if($file['file']['error']>0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		return false;
	}
		
	function move_upload_file(&$file)
	{
		$imageDirectory = '../images/';
		$name = $file['file']['name'];
		//$hash = 
		if(is_uploaded_file($file['file']['tmp_name']))
		{
			
		}
	}
  