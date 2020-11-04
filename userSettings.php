<?php

//
// Filename:	userSettings.php
// Purpose:	Handles user settings. Current functionality is only to display books
//		user has listed and remove their listings
//

session_start(); //Create/use session Variable
require_once 'dbconn.php'; //Useful DB functions
db_connect() ; //Connect to DB
include_once "functions.php";//Useful functions

//Call userSettingsPage from functions.php
userSettingsPage();

//Remove book functionality
if(isset($_POST['book']))
{
    removeBook($_POST['book']);
    unset($_POST['book']);
}

if(isset($_SESSION['userID']) && $_SESSION['userID'] != FALSE)// If user is logged in
  {
    $user = getUserById($_SESSION['userID']) ;//Get user information from DB
    $userEmail = $user['Email'];

    echo("<a href='changePass.php' style='color: blue;'>Change Password</a>");

    //Start of user book selling list
    echo("<h1>Books you are selling:</h1>\n\n");
    echo("<br>");
    echo "<br>";
    if($sellerbooks = getSellerBooks($userEmail))//array of book id's
    {
        echo"<form action='userSettings.php' method='post'>";
	
        $bookCount = count($sellerbooks); //Number of books user has
        for($i=0; $i < $bookCount; $i++) //Grab book information and show book for every book user has listed
          {
		//Get all the book information 
        	$currentBookId = $sellerbooks[$i];
        	$currentBook = getBook($currentBookId);
        	$bookName = $currentBook['name'];
        	$bookISBN10 = $currentBook['ISBN10'];
        	$bookISBN13 = $currentBook['ISBN13'];
        	$bookPrice = $currentBook['price'];
        	$bookCondition = $currentBook['condition'];
        	$bookClass = $currentBook['class'];
        	$bookProf = $currentBook['prof'];
        	$bookPic = $currentBook['pic'];

		//Print out ALL of the book information
            if(file_exists("images/BKimages/".$bookPic) && $bookPic != null)//If there is an image for the book, display image
            {
                echo("<a href='Book.php?bookID=$currentBookId'><image src='https://subw.hanlecofire.org/Dev/images/BKimages/$bookPic' alt='Picture of book: $Name' > <br> Book Name: " . $bookName . " <br> ISBN10: " . $bookISBN10 . "<br>" . "ISBN13: " . $bookISBN13 . "<br>" . "Price: $" . $bookPrice . "<br>" . "Condition: " . $bookCondition . "<br>" . "Class Used For: " . $bookClass . "<br>" . "Professor: " . $bookProf . "<br></a>\t<button name='book' id='$currentBookId' value='$currentBookId' type='submit'>Remove</button><br><hr><br>");
            }
            else//Display just text
            {
                echo("<a href='Book.php?bookID=$currentBookId'>Book Name: " . $bookName . " <br> ISBN10: " . $bookISBN10 . "<br>" . "ISBN13: " . $bookISBN13 . "<br>" . "Price: $" . $bookPrice . "<br>" . "Condition: " . $bookCondition . "<br>" . "Class Used For: " . $bookClass . "<br>" . "Professor: " . $bookProf . "<br>\t</a><button name='book' id='$currentBookId' value='$currentBookId' type='submit'>Remove</button><br><hr><br>");
            }
        	
          }
        echo "</form>";
    }
    //In the event user has no books listed, give a friendly output message to say so
    else
        echo "<h2 style='text-align: center;'>You do not have any books listed for sale</h2>" ;
    }


 ?>


