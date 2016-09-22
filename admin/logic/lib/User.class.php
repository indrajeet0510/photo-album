<?php
	class User{
		
		private $UserId;
		protected $Email;
		protected $FirstName;
		protected $LastName;
		protected $Status;
		
		public function getUserId()
		{
			return $this->UserId;
		}
		public function getEmail()
		{
			return $this->Email;
		}
		
		public function getFirstName()
		{
			return $this->FirstName;
		}
		
		public function getLastName()
		{
			return $this->LastName;
		}
		
		public $errArr = array(
		
			"fname" => "",
			"lname" => "",
			"email" => "",
			"password" => "",
			"repassword" => "",
			"registered" => "",
			"status" => 0,
			"msg" => ""
			);

		public function makeAdmin($userId)
		{
			$one = 1;
			$conn = Database::getConnection();
			$Query = "UPDATE user SET adminField=? WHERE userId=?";
			$stmt = $conn->prepare($Query);
			$stmt->bind_param('ii',$one,$userId);
			if($stmt->execute())
			{
				$stmt->close();
				return true;
			}
			else return false;
		}
		
		public function isDeveloper()
		{
			if($this->Status==0)
			{
				return true;
			}
			else return false;
		}
		
		public function checkUser($email)
		{
			$query = "SELECT firstName FROM user WHERE userEmail=?";
			
			$conn = Database::getConnection();
			
			$stmt = $conn->prepare($query);
			
			$stmt->bind_param('s',$email);
			if($stmt->execute())
			{
				$stmt->bind_result($first);
				$stmt->fetch();
				if(strlen($first))
				{
					$stmt->close();
					$this->errArr["registered"] = true;
					$this->errArr["msg"]="Email Already registered !";
					return false;
				}
				else return true;
			}
			
			else 
			{
				$this->errArr["registered"] = "true";
				$this->errArr["msg"]="Email Already registered !";
				return false;
			}
		}
		/* 
		Registers the user with basic details
		 */
		public function registerUser($email,$firstName, $lastName, $password)
		{
			$query = "INSERT INTO user(firstName,lastName,userEmail,userSignature) VALUES(?,?,?,?)";
			
			$conn = Database::getConnection();
			
			$stmt = $conn->prepare($query);
			
			$hash = crypt($password,$email);
			
			$stmt->bind_param('ssss',$firstName,$lastName,$email,$hash);
			if($stmt->execute())
			{
				$this->errArr['msg'] = "Your email ".$email." registered successfully. Please Login to Continue";
				$this->errArr['status']=1;
				$stmt->close();
				return true;
			}
			else 
			{
				$this->errArr['status']=0;
				return false;
			}
		}
		
		/* 
		Authenticates the user with email and password
		 */
		public function authUser($email,$password)
		{
			$conn = Database::getConnection();
			$passHash = "hello";
			$hash = crypt($password,$email);
			$stmt = $conn->prepare("SELECT userId,adminField, userSignature FROM user WHERE userEmail=?");
			$stmt->bind_param('s',$email);
			if($stmt->execute())
			{
				$stmt->bind_result($userId,$adminField,$passHash);
				$stmt->fetch();
				if($passHash==$hash)
				{
					session_start();
					$_SESSION['userEmail'] = $email;
					$_SESSION['userId'] = $userId;
					if($adminField!=0)
					{
						$_SESSION['admin'] = $email;
					}
					session_write_close();
					$this->errArr['success'] = "Login successful";
					$this->errArr['status']=1;
					$this->errArr['msg'] = "prosys.php";
					return true;
				}
				else
				{
					$this->errArr['msg'] = "UserName or Password Invalid";
					$this->errArr['status']=0;
					return false;
				}
				$stmt->close();
			}
		}
		/* 
			
		 */
		public function changePassword($oldpass,$newpass,$email)
		{
			$conn = Database::getConnection();
			$passHash = "hello";
			$oldhash = crypt($oldpass,$email);
			$newhash = crypt($newpass,$email);
			$stmt = $conn->prepare("UPDATE user SET userSignature=? WHERE userEmail=? AND userSignature=?");
			$stmt->bind_param('sss',$newhash,$email,$oldhash);
			if($stmt->execute())
			{
				$stmt->close();
                                $this->errArr['msg']="Password changed successfully";
				return true;
			}
			else
                        {
                            $this->errArr['msg']="Old Password doesn't match with the new one";
                            return false;
                        }
		}
		 
		public function verifyData($fname,$lname,$email,$password,$repassword)
		{
			$ret = true;
			if(!(is_string($fname) && strlen($fname)>1))
			{
				$this->errArr["fname"]= "Error in First Name";
				$ret *= false;
			}
			if(!(is_string($lname) && strlen($lname)>1))
			{
				$this->errArr["lname"]= "Error in First Name";
				$ret *= false;
			}
			if(!(is_email($email) && strlen($email)>1))
			{
				$this->errArr["email"]= "Error in Email";
				$ret *= false;
			}
			if(!(is_string($password) && strlen($password)>2))
			{
				$this->errArr["password"]= "Error in Password";
				$ret *= false;
			}
			if(!(is_string($password) && strlen($password)>2))
			{
				$this->errArr["repassword"]= "Password doesn't match";
				$ret *= false;
			}
			return $ret;
		}
		
		
		
		public function printMsg()
		{
			foreach($this->errArr as $key=>$value)
			{
				echo $value;
			}
		}
		
		public function getUserDetails($userId)
		{
			$conn = Database::getConnection();
			$stmt = $conn->prepare("SELECT userId, firstName, lastName, adminField, userEmail FROM user WHERE userId=?");
			$stmt->bind_param('i',$userId);
			if($stmt->execute())
			{
				$stmt->bind_result($UserId, $FirstName, $LastName, $Status, $Email);
				//var_dump($this);
				$stmt->fetch();
				$this->UserId = $UserId;
				$this->FirstName = $FirstName;
				$this->LastName = $LastName;
				$this->Status = $Status;
				$this->Email = $Email;
				$stmt->close();
			}
		}
		
		function getAllDevelopers()
		{
			$zero = 0;
			$_userList = array();
			$Query = "SELECT userId,firstName,lastName,userEmail FROM user WHERE adminField=?";
			$conn = Database::getConnection();
			$stmt = $conn->prepare($Query);
			$stmt->bind_param('i',$zero);
			
			if($stmt->execute())
			{
				$stmt->bind_result($userId,$firstName,$lastName,$userEmail);
				while($stmt->fetch())
				{
					$_user["userId"] = $userId;
					$_user["firstName"] = $firstName;
					$_user["lastName"] = $lastName;
					$_user["userEmail"] = $userEmail;
					
					array_push($_userList,$_user);
				}
				$stmt->close();
				return $_userList;
			}
			else return NULL;
		}
		
		public function LogOut()
		{
			unset($_SESSION);
			session_destroy();
		}
		
		
		
		public function uploadImage()
		{
			
		}
		
		
	}
	
	/*$user = new User;
	var_dump($user->verifyData("a","hell","indrajeet@gmail.com","123","123"));
	$user->printMsg();
	*/
?>