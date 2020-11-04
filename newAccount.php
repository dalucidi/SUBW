<?php
session_start() ;//Create/use session variable
require_once 'header.php'; //Add header to top of page
require_once 'functions.php' ; //useful functions
require_once 'dbconn.php' ; //useful DB functions
db_connect() ;//Connect to DB


if(isset($_GET['verify']))//If sent to this page from email verification link
  {
    if($user = getUser($_GET['email'])) //If user exists in DB
    {
    	if(emailVerified($_GET['email'])) //If their email is successfully verified
    	  {
    	    email_verified($_GET['email']) ;//Inform user email has been verified
    	    exit() ;
    	  }
    	else //Unable to verify email
    	  {
    	    echo "<h1>Error Verifying your email :(</h1>";
    	    echo "<h2>Please try signing in to resend your verification email</h2>" ;
    	    exit();
    	  }
      }
    else//User does not exist in DB
      {
	echo "<h1>Error Verifying your email :(</h1>";
	echo "<h2>Please try signing in to resend your verification email</h2>" ;
	exit();
      }
  }

if(!isset($_SESSION['userID'])) //If they are not logged in
  {
    //header("Location: index.php"); || PAGE TO SEND TO THAT SAYS EMAIL NEEDS TO BE VERIFIED
    exit() ;
  }
else if(isset($_SESSION['userID']) && $_SESSION['userID'] == FALSE && getUser($_GET['email']))// If they have an account, but are not verified
  {
    $_SESSION['tries'] = 0 ;
    echo "A verification email has been sent! Please verify your email to be able to sign in.";
  }
else//Account already verified
  {
    echo "You're account is already verified!" ;
  }
?>
