<?php

  //File Name: create_account.php
  //Purpose:   

session_start(); //Create/use session Variable
require_once 'dbconn.php'; //Useful DB functions
db_connect() ; //Connect to DB
include_once "functions.php";//Useful functions


 if(isset($_POST['password1']) && isset($_POST['password2']))//If there are passwords entered
  {
    if( $_POST['password1'] === $_POST['password2']) //If passwords match
    {
        $email = $_POST['email']; //get data from the form
        $password = $_POST['password1'];
	      $fname = $_POST['fname'];
	      $lname = $_POST['lname'];

        $emailRegEx = '/^[a-zA-z]{3,5}[0-9]{3}$/';

        if(preg_match($emailRegEx, $_POST['email']))//Regex for email
       { 
          $passRegEx = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/'; 
          if(preg_match($passRegEx, $_POST['password1']) && preg_match($passRegEx, $_POST['password2']))//Regex for passwords
            {
             if(createUser($email, $password, $fname, $lname)) //Attempt to create user - if successfully created
              {
               header("Location: email_verified.php"); //Redirect to this page
               $_SESSION['userID'] = FALSE ; //User has an account, but hasn't verified email
               verifyEmail($email) ; //Send email verification
               exit();
              }
              else //account creation falied
              {
                createAccountPage("Account Not Created: Account may already exist!") ; //this function is in functions.php
              }
            }
          else//Bad Password(s)
            {
              createAccountPage("Password must be six characters long, have one lowercase letter, one uppercase letter, and one number");
            }
       }
       else //Bad email
       {
        createAccountPage("Account Not Created: Email must be at least 3 characters followed by 3 numbers") ;
       }
    }
    else //passwords do not match
    {
      createAccountPage("Account Not Created: Passwords didn't match") ; //this function is in functions.php
      exit() ;
    }
  }
  else //If there are no passwords entered
  {
    createAccountPage() ; //Load createAccount page
  }

 ?>