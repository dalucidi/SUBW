<?php

session_start(); //Create/use session Variable
require_once 'dbconn.php'; //Useful DB functions
db_connect() ; //Connect to DB
include_once "functions.php";//Useful functions

if(isset($_POST['email']))//If email is entered
  {
    $email = $_POST['email'];//grab users email
    if(forgotPass($email))//resets the users password to something random and eemails them with a reset link
    {
      loginPage("The password for ".$email." has been reset. Please check your email.");//prints this to web page if successful
    }
    else
    {
      forgotPassPage("The password for ".$email." could not be reset. Please check your spelling.") ;//prints this to web page if reset failed
    }
    
  }
  else
  {
    forgotPassPage();//loads the actual page
  }

  ?>