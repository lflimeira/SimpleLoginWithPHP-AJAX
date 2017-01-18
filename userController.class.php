<?php
//Import the file user.class.php, similar to "import" from Java.
include('user.class.php');
//UserController is the class responsible for the page "index.html".
class UserController
{
	//Declare the UserController's attributes.
   	private $username;
	private $password;
	private $newPassword;
	private $confirmPassword;
	private $captcha;
	//Method that construct (start) the class
	function __construct()
	{
		//Set values into the attributes.
		$this->username 		= trim(preg_replace('/[^[:alpha:]_]/', '',$_POST['LOGIN']));
		$this->password 		= trim($_POST['SENHA']);
		$this->newPassword 		= trim($_POST['NOVA_SENHA']);
		$this->confirmPassword 	= trim($_POST['CONFIRMA_SENHA']);
		$this->captcha 			= trim($_POST['captcha']);
		//Check if username and password isn't empty.
		if ($this->username != '' && $this->password != '') {
			session_start();
			//Check if the security code didn't expired.
			if (strtoupper(str_replace(" ","",$_SESSION["captcha"])) == strtoupper($this->captcha) 
				&& date('Y-m-d H:i:s') <= $_SESSION['captcha_expira'])
			{
				//Create an object User.
				$userClass = new User(); 
				//Call the method  login() from class User, and it will verify if the User exist or not.
				$return = $userClass->login($this->username,$this->password,$this->newPassword);
			}else
			{
				$return = array('result' => "Invalid security code or limit exceeded.",
								'error_type' => "captchaError");
			}
		} elseif ($this->username == '') {
			$return = array('result' =>  "Please enter your Username!");
		} elseif ($this->password == '') {
			$return = array('result' =>  "Please enter your Password!");
		}
		//Clear the session variable of the security code.
		unset($_SESSION['captcha']);
		unset($_SESSION['captcha_expira']);
		//Show the result in Json format.
		echo json_encode($return);
	}
}
//Create an object UserController to initialize the class.
$user = new UserController();
?>