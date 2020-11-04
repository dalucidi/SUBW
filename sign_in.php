<?php

//
// Filename:	sign_in.php
// Purpose:	Sign the user into a preexisting account to be able to access buyer/seller features
//

session_start();//Create/use session variable
require_once 'dbconn.php';//Useful DB functions
require_once "functions.php";//Useful functions

if(!isset($_SESSION['tries']))//If the session variable isn't set, set it to zero
{
  $_SESSION['tries'] = 0 ;
} 

if(isset($_POST['email']) && isset($_POST['password']))//If email and password are entered
{
  if(isset($_SESSION['tries']) && ($_SESSION['tries'] >= 3) && getUser($_POST['email']))//If their is more than three attempts and the account actually exists
  {
    resetPass($_POST['email']) ;
    $_SESSION['tries'] = 0 ;
    loginPage("This account has been locked and a reset password email has been sent!") ;
    exit() ;
  }
  $email = $_POST['email']; //Store the users email
  $password = $_POST['password']; //Store the users password
  $userID = verifyUser($email, $password) ; //Attempt to get userID
  if($userID == 2)//If user's email has not been verified
  {
    loginPage("You must first verify your email before you're allowed to log in!") ;
    $_SESSION['tries'] = 0 ;
  }
  else if ($userID)//If user is an account that has been verified
  {
   $_SESSION['userID'] = $userID ;
   header('Location: index.php');
   $_SESSION['tries'] = 0 ;
   exit();   
  }    
  else//Email pass combo is no bueno
  {  
    console_log($_SESSION['tries']) ;
    if(isset($_SESSION['tries']) && getUser($email))//If the user actually exists, increase incorrect login attempts
    {
      loginPage("Email and password combination are not valid!") ;
      $_SESSION['tries'] = $_SESSION['tries'] + 1 ;
    }
    else //Display login page
    {
      console_log($_SESSION['tries']) ;
      loginPage("An account with this email doesn't exist! Try signing up for an account!") ;
    }
  }
}
else //Display login page
{
  loginPage() ;
}

?>


 
