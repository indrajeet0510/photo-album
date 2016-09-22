<?php

/**
 * Description of FileUploader
 *
 * @author XGeek
 */
 
require_once '../../image_lib/demo/helpers/common.php';
require_once '../../image_lib/lib/WideImage.php';
require_once '../../db_config.php';
class FileHandler {
    
    private $fileObj;
    
    private $extension;
    
    private $hashFileName;

    private $fileName;

    private $timeStamp;

    private $fileSize;

    private $fieldName;

    private $lowResName;

    private $highResName;

    public $imagePath = '../../images/';

    private $allowedExt = array("jpeg", "jpg", "png");
    
    public $maxFileSize = 5242880; //Size in Bytes
    
    public $msg = array(
        "status"=>0,
        "msg"=>"An Error Occured"
    ); 
    
    public function returnFullPath()
    {
        return $this->filePath.$this->hashFileName;
    }
    
    public function returnHashVal()
    {
        return $this->hashVal;
    }
    
    public function returnFileName()
    {
        return $this->fileName.".".$this->extension;
    }
    
    public function returnExtension()
    {
        return $this->extension;
    }

    public function __construct($fileObj,$fieldName,$filePath=NULL) {
        $this->fileObj= $fileObj;
        $this->fieldName = $fieldName;
        $this->extension = $this->getExtension($fileObj,$fieldName);
        $this->fileName = $this->getFileName($fileObj,$fieldName);
        $this->timeStamp = time();
        $this->hashFileName = $this->getHashFileName();
		$this->lowResName = $this->getLowResName();
		$this->highResName = $this->getHighResName();
        $this->filePath = $filePath;
        $this->fileSize = $this->getFileSize($fileObj, $fieldName);
    }
    
    
    public function getExtension($fileObj,$fieldName)
    {
        $x = pathinfo($fileObj[$fieldName]['name']);
        return $x['extension'];
    }
    
    public function getFileName($fileObj,$fieldName)
    {
        $x = pathinfo($fileObj[$fieldName]['name']);
        return $x['filename'];
    }
    
    public function getFileSize($fileObj,$fieldName)
    {
        return ($fileObj[$fieldName]['size'])/1048576;
    }


    public function getHashFileName()
    {
        return sha1($this->timeStamp.$this->fileName).".".$this->extension;
    }
	public function getLowResName()
	{
		return sha1($this->timeStamp.$this->fileName)."_lowres.".$this->extension;
	}
	public function getHighResName()
	{
		return sha1($this->timeStamp.$this->fileName)."_highres.".$this->extension;
	}
    public function uploadFile()
    {
        if(is_uploaded_file($this->fileObj[$this->fieldName]['tmp_name']))
        {
            if($this->fileObj[$this->fieldName]['size'] >= $this->maxFileSize)
            {
                $this->msg = array(
                    "status"=>0,
                    "msg"=>"Maximum file size exceeds"
                );
                return false;
            }
            else 
            {
				//echo $this->filePath.$this->hashFileName;
                $result = move_uploaded_file($this->fileObj[$this->fieldName]['tmp_name'],$this->filePath.$this->hashFileName);
                if ($result == 1)
                {
                    
                    $this->msg = array(
                        "status"=>1,
                        "msg"=>"Upload Complete"
                    );
                    return true;
                }
                else return false;;
            }
        }
        else
        {
            return false;
        }
    }
    
	function createSecondaryImages()
	{
		$imageLocation = $this->imagePath.$this->hashFileName;
		try
		{
		  $image = WideImage::load($imageLocation);
		}
		catch (Exception $e)
		{
		  echo "Image isn't valid";
		}
		$image = $image->resize(800, 600);
	
		//$image->crop('center', 'center', 300, 300)->resize(100, 100)->saveToFile($this->imagePath.$this->lowResName);
		$image->resize(400, 300)->saveToFile($this->imagePath.$this->lowResName);
		$large = $image->unsharp(80, 0.5, 3);
		
		$large->saveToFile($this->imagePath.$this->highResName);
	}
	
    function saveUploadInDB($eventId,$eventTypeId)
    {
        $Query = "INSERT INTO image(event_id,event_type_id,name,small_size,large_size,original_file,timestamp) VALUES(?,?,?,?,?,?,?)";
        $mysqli = new MySQLi(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Connection Error");
        $stmt = $mysqli->prepare($Query);
        $x = $this->fileName;
        $y = $this->lowResName;
        $z = $this->highResName;
		$original = $this->hashFileName;
		$timeStamp = $this->timeStamp;
        $stmt->bind_param('iissssi', $eventId,$eventTypeId, $x,$y,$z,$original,$timeStamp);
        if($stmt->execute())
        {
            $stmt->close();
            $this->msg = array(
                        "status"=>1,
                        "msg"=>"Upload Successful"
                    );
            return true;
        }
        else return false;
    }
	
	function createEventThumbnail($eventId)
	{
		$Query = "UPDATE event SET thumbnail=? WHERE id=?";
		$mysqli = new MySQLi(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Connection Error");
        $stmt = $mysqli->prepare($Query);
        $y = $this->lowResName;
		$stmt->bind_param('si',$y,$eventId);
        if($stmt->execute())
        {
            $stmt->close();
            $this->msg = array(
                        "status"=>1,
                        "msg"=>"Upload Successful"
                    );
            return true;
        }
        else return false; 
	}
   
}

?>
