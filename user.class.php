<?php
//Import the file connectionDB.class.php, similar to "import" from Java.
include('connectiondb/connectionDB.class.php');
//User is the class responsible for access the database and validate the username and password.
class User
{
  //Declare the user's attribute.
  private $db;
  //Method that construct (start) the class
  function __construct()
  {
    //Set a new connection object into the attribute.
    $this->db = ConnectionDB::getInstance();
  }
  //login method is responsible for validate the username and password.
  public function login($uname,$upass,$newupass)
  {
    try
    {
      //Search for a user that has the username and password passed.
      $stmt = $this->db->prepare("SELECT * FROM users WHERE user_username=:uname AND user_pass=:upass LIMIT 1");
      $stmt->bindValue(":uname", $uname);
      $stmt->bindValue(":upass", md5($upass));
      $stmt->execute();
      $userRow  = $stmt->fetch(PDO::FETCH_ASSOC);
      //Check if the user exist.
      if($stmt->rowCount() > 0)
      {
        //Check if it's the first user access.
        if ($userRow['user_first_access'] == 'Y') {
          if (trim($newupass) == '') {
            //Return the form to fill with the new password.
            $return = $this->firstAccessForm();
            return $return;
          }else{
            //Save the new password.
            $return = $this->saveNewPassword($uname,$upass,$newupass);
            if (is_array($return)) {
              return $return;
              $userRow['user_first_access'] = '';
            } 
          }    
        }elseif ($userRow['user_first_access'] == 'N') {
          //Log into the system and set the id and username in the sessions.
          session_start();
          $_SESSION['user_id']              = $userRow['user_id'];
          $_SESSION['user_username']        = $userRow['user_username'];
          return array('result' => 'true');
        } 
      }else
      {
        return array('result' =>  "Login and/or password incorrect!");
      }
    }
    catch(PDOException $e)
    {
      return array('result' =>  $e->getMessage());
    }
  }
  //Method that will return the form to fill with the new password and confirm new password.
  public function firstAccessForm(){
    return array('new_pass' => 'true','result' => '
                      <div class="form-group has-feedback">
                        <label for="senha">New password</label>
                        <input type="password" name="NOVA_SENHA" class="form-control" id="NOVA_SENHA" placeholder="Provide your new password" maxlength="12" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback" ></span>
                      </div>
                      <div class="form-group has-feedback">
                        <label for="senha">Confirm new password</label>
                        <input type="password" name="CONFIRMA_SENHA" class="form-control" id="CONFIRMA_SENHA" placeholder="Provide the confirmation of your new password" maxlength="12" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback" ></span>
                      </div>');
  }
  //Method that will save the new password.
  public function saveNewPassword($uname,$upass,$newupass)
  {
    $sql = "UPDATE users set
            user_pass           = :newupass,
            user_first_access   = :firstaccess
            WHERE user_username = :uname AND user_pass = :upass LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $newupass = md5($newupass);
    $stmt->bindValue(":newupass", $newupass);
    $stmt->bindValue(":firstaccess", 'N');
    $stmt->bindValue(":uname", $uname);
    $upass = md5($upass);
    $stmt->bindValue(":upass", $upass);
    $stmt->execute();

    if($stmt->rowCount() > 0)
    {
      return array('result' => "Success! Your Password has been changed!");
    }
  }
  //Method that will check if the user is logged.
  public function is_loggedin()
  {
    session_start();
    if(isset($_SESSION['user_id']))
    {
      return array('result' => "true", 'id' => $_SESSION['user_id'], 'username' => $_SESSION['user_username']);
    }else{
      return array('result' => "false".$_SESSION['user_id']);
    }
  }
  //Method that will disconnect the user.
  public function logout()
  {
    session_destroy();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    return array('result' => "true");
  }
}
?>