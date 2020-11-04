<?php
//
// Filename:	newBookListing.php
// Purpose:	Functionality to list a new book and store it to the database for
//		users to later query for.
//

session_start(); //Createuse session Variable
require_once "dbconn.php"; //Useful DB functions
db_connect() ; //Connect to DB
include_once "functions.php";//Useful functions

//console_log(isset($_POST["bookname"])) ;
if(isset($_POST["bookname"]))
{
  $imgName = null ; // Container for book image, starts null
  $user = getUserById($_SESSION['userID']) ; // Container for the username posting the book
   
  if($_FILES['pic']['name'] != "")//If there is a file name in the FILES array
  {
    console_log("newBookListing>> Filesize is: ".filesize($_FILES['pic']['tmp_name'])." Bytes") ;

    if(filesize($_FILES['pic']['tmp_name']) > 40000)//If filesize is greater than 40,000 Bytes
    {
      console_log("newBookListing>> File is too big to be uploaded to server") ;
      createNewBookListingPage("Book not listed for sale! File size must be less than 40MB!") ;
      exit() ;
    }

    // get the file extension
    $extension = explode("/", $_FILES['pic']['type'])[1];

    console_log("newBookListing>> File extension is: ".$extension) ;//If the file extension is anything other than .png or .jpg or .jpeg then don't allow it
    
    if($extension != "png" and $extension != "jpeg" and $extension != "jpg")//If file isn't a picture
    {   
      console_log("newBookListing>> File is not an image") ;
      createNewBookListingPage("Book not listed for sale! File must be a png or jpg!") ;
      exit() ;
    }

    // read in the file contents
    $contents = file_get_contents($_FILES['pic']['tmp_name']);
    
    //I WANNA CHECK THIS USERS BOOKS THAT ARE LISTED, SEE IF THERE ARE ANY OF THE SAME FILE NAME, AND MAKE DECISIONS ACCORDIGLY
    $books = getSellerBooks($user['Email']) ;

    for($i = 0; $i < count($books); $i++)
    {
      $book = getBook($books[$i]) ;
      if($book['name'] == $_POST["bookname"])//If the new book has the same name as a book the seller is already selling
      {
        $uniqueName = $_POST["bookname"].generate_string() ;
        console_log("newBookListing>> New Image FileName is: ".$uniqueName) ;
      }
    }

    if(isset($uniqueName))//If a new filename had to be created, use that filename of the image
    {
      $imgName = clean($uniqueName) ;
      $imgName = str_replace(" ", "", $imgName) . $user["Email"] . "." . $extension ;
    }
    else
    {
      //create a, hopefuly, unique name for the image, removing any spaces too
      $imgName = clean($_POST["bookname"]) ;
      $imgName = str_replace(" ", "", $imgName) . $user["Email"] . "." . $extension ;
    }
    
    console_log("newBookListing>> Image filename: ".$imgName);
    console_log("newBookListing>> File Contents: ".$contents) ;
  }

  //If the book info has been filled out
  if(insertBook($_POST["bookname"], $user['Email'], $_POST["price"], $_POST["condition"], $_POST["class"], $_POST["prof"], $_POST["isbn10"], $_POST["isbn13"], $imgName))
    {
      // write the image file to the BKimages directory
      console_log("\$_FILES['pic']['name']: ".$_FILES['pic']['name']) ;
      if($_FILES['pic']['name'])
      {
        console_log("newBookListing>> How many bytes were uploaded to server: ".file_put_contents('./images/BKimages/'.$imgName, $contents));
        //file_put_contents('./images/BKimages/'.$imgName, $contents);
      }
      else//if image name was blank
      {
      console_log("newBookListing>> No image to upload to server");
      }
      createNewBookListingPage("Book was added for sale!") ;
    }
    else //If book info was not properly filled out and couldn't be listed
    {
      createNewBookListingPage("Book was unable to be added to DB!") ;
    }
}
else
{
  createNewBookListingPage();
}
?>
