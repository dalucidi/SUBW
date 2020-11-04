<?php

//
// Filename:	changePass.php
// Purpose:	Reset the user's password
//

session_start();//Create/use session variable
require_once 'dbconn.php';//Useful DB functions
require_once "functions.php";//Useful functions

console_log("changePass>> Is user logged in: ".(string)isset($_SESSION['userID'])) ;
$user = getUserById($_SESSION['userID']) ;//Get user information from DB

if(isset($_POST['currentPass']) && verifyUser($user['Email'], $_POST['currentPass']) && isset($_POST['newPass']) && isset($_POST['newPass2']))//If old and new Passwords are set
  {
    console_log("changePass>> New and old Passwords are set!") ;
    if( $_POST['newPass'] === $_POST['newPass2']) //If new passwords match
    {
      console_log("changePass>> New Passwords match!") ;
      

      console_log("changePass>> User email is: ".$user['Email']) ;
      updatePassword($user['Email'], $_POST['newPass']) ;//update password in DB

      changePassPage("Your password has been updated!") ;//Inform user their password has been updated
      exit() ;
    }
    elseif($user)//If Passwords don't match and user logged in
    {
    console_log("changePass>> New Passwords didn't match, sending user back") ;
     changePassPage("The new passwords did not match!"); //Inform user that passwords did not match
     exit();   
    }    
    else //If new passwords aren't set, Display login page
    {
      console_log("changePass>> New passwords aren't set, Display login page (2)") ;
      changePassPage();
    }
  }
elseif (isset($_POST['currentPass']) && !verifyUser($user['Email'], $_POST['currentPass']))//If the user account credentials don't match up 
{
    console_log("changePass>> New passwords aren't set, Display login page (3)") ;
    console_log("changePass>> isCurrentPassSet: ".(string)isset($_POST['currentPass'])) ;
    console_log("changePass>> isnewPassSet: ".(string)isset($_POST['newPass'])) ;
    console_log("changePass>> isnewPass2Set: ".(string)isset($_POST['newPass2'])) ;
    changePassPage("Wrong current password entered! Please try again!");
}
else //If new passwords aren't set, Display login page
  {
    console_log("changePass>> New passwords aren't set, Display login page (1)") ;
    console_log("changePass>> isCurrentPassSet: ".(string)isset($_POST['currentPass'])) ;
    console_log("changePass>> isnewPassSet: ".(string)isset($_POST['newPass'])) ;
    console_log("changePass>> isnewPass2Set: ".(string)isset($_POST['newPass2'])) ;
    changePassPage();
  }


?>


 
