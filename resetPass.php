<?php

//
// Filename:	resetPass.php
// Purpose:	Reset the user's password
//

session_start();//Create/use session variable
require_once 'dbconn.php';//Useful DB functions
require_once "functions.php";//Useful functions

console_log("resetPass>> Is user logged in: ".(string)isset($_SESSION['userID'])) ;
$user = getUserById($_SESSION['userID']) ;//Get user information from DB

if(verifyUser($user['Email'], $_POST['currentPass']) && isset($_POST['newPass']) && isset($_POST['newPass2']))//If old and new Passwords are set
  {
    console_log("resetPass>> New and old Passwords are set!") ;
    if( $_POST['newPass'] === $_POST['newPass2']) //If new passwords match
    {
      console_log("resetPass>> New Passwords match!") ;
      

      console_log("resetPass>> User email is: ".$user['Email']) ;
      updatePassword($user['Email'], $_POST['newPass']) ;//update password in DB

      changePassPage("Your password has been updated!") ;//Inform user their password has been updated
      exit() ;
    }
    else if($userID)//If Passwords don't match and user logged in
    {
    console_log("resetPass>> New Passwords didn't match, sending user back") ;
     changePassPage("The new passwords did not match!"); //Inform user that passwords did not match
     exit();   
    }    
    else //If new passwords aren't set, Display login page
    {
      console_log("resetPass>> New passwords aren't set, Display login page (2)") ;
      changePassPage();
    }
  }
else //If new passwords aren't set, Display login page
  {
    console_log("resetPass>> New passwords aren't set, Display login page (1)") ;
    console_log("resetPass>> isCurrentPassSet: ".(string)isset($_POST['currentPass'])) ;
    console_log("resetPass>> isnewPassSet: ".(string)isset($_POST['newPass'])) ;
    console_log("resetPass>> isnewPass2Set: ".(string)isset($_POST['newPass2'])) ;
    changePassPage("Password not Update! Please try again!");
  }


?>


 
