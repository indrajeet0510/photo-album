<?php
	require_once('../../db_config.php');
	$json = array(
		'error'=>array(
			'eventName'=>'',
			'eventType'=>'',
			'eventYear'=>''
		),
		'status'=>0,
		'msg'=>'An error occured',
		'eventId'=>NULL
	);
	
	$errorBit = 0;
	
	if(!isset($_POST['eventName']) && !is_string($_POST['eventName']))
	{
		$json['error']['eventName']= 'Error in event name';
		$errorBit = 1;
	}
	if(!isset($_POST['eventType']) && !is_int($_POST['eventType']))
	{
		$json['error']['eventType']= 'Error in event name';
		$errorBit = 1;
	}
	if(!isset($_POST['eventYear']) && !is_int($_POST['eventYear']))
	{
		$json['error']['eventYear']= 'Error in event name';
		$errorBit = 1;
	}
	if($errorBit!=0)
	{
		//echo json_encode($json);
		header('location:/admin/index.php');
		exit();
	}
	else
	{
		$eventName = $_POST['eventName'];
		$eventTypeId = $_POST['eventType'];
		$eventYear = $_POST['eventYear'];
		$time = time();
		$mysqli = new MySQLi(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('An error occured');
		$Query = "INSERT INTO event(name,year,type,timestamp) VALUES(?,?,?,?)";
		$stmt = $mysqli->prepare($Query);
		$stmt->bind_param('siii',$eventName,$eventYear,$eventTypeId,$time);
		
		
		
		if($stmt->execute())
		{
			$json['eventId'] = $stmt->insert_id;
			$json['status'] = 1;
			$json['msg'] = 'Album created successfully';
			$stmt->close();
			//echo json_encode($json);
			require_once('../uploader.php');
		}
		else header('location:/admin/index.php');
	}
?>