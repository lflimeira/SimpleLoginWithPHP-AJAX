<?php
//Import the file user.class.php, similar to "import" from Java.
include('user.class.php');
//PrincipalController is the class responsible for the page "principal.html".
class PrincipalController
{
	//Method that construct (start) the class
	function __construct()
	{
		//Create an object User.
		$principalClass = new User(); 
		//Call the method  is_loggedin() from class User, and it will verify if the User is logged.
		$result = $principalClass->is_loggedin();
		//Show the result in Json format.
		echo json_encode($result);
	}
}
//Create an object PrincipalController to initialize the class.
$principal = new PrincipalController();
?>