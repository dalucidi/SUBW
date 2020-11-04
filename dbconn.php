<?php

require_once "functions.php";//Useful functions

$connection; //Global variable for DB connection

// connect to the Bookstore DB
function db_connect($DB_USER= "", $DB_PASS= "") 
{
   $DB_NAME = "hanlecofire_SUBW";
   $DB_HOST = "mysql.server284.com";

   // Uncomment and set these for your project!!!
    $DB_USER = "database";
    $DB_PASS = "0ne}!r*M;o1)";

   // global keyword required to make variable have global scope 
   global $connection;
      
   $connection = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME)
      or die("<h2>Cannot connect to <i>&#39;$DB_HOST&#39;</i> "
        . "as <i>&#39;$DB_USER&#39;</i></h2>");
}  // end function db_connect



// close connection to bookstore DB
function db_close() {
   global $connection;
   mysqli_close($connection);
}  // end function db_close

// replacement for mysql_result
function get_mysqli_result($result, $number, $field) {
   mysqli_data_seek($result, $number);
   $row = mysqli_fetch_assoc($result);
   return $row[$field];
}

/* Name: queryBooks
     * Purpose: Print the books that match the string arguement $search.
                Will seach book table for matches in its Name, ISBN, and professor
     * Parameters: 
                  $connection - database connection
                  $cquery - sql statment to be sent to server
     * Return value: 1 if results are found, else 0              */
function queryBooks($cquery, &$tracker)
{
  global $connection ;//bring variable into local scope
  $numCat = 0 ;
  $cresult = mysqli_query($connection, $cquery) ;//Query DB
  
  if($cresult)//If there are any results
  {
      $numCat = mysqli_num_rows($cresult) ;
  }

  if($numCat)//If there any results
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $bookID = get_mysqli_result($cresult, $i, "Book_Id" ) ;
      $Name = get_mysqli_result($cresult, $i, "Name" ) ;
      $ISBN1 = get_mysqli_result($cresult, $i, "ISBN_10" ) ;
      $ISBN3 = get_mysqli_result($cresult, $i, "ISBN_13" ) ;
      $Price = get_mysqli_result($cresult, $i, "Price" ) ;
      $Condition = get_mysqli_result($cresult, $i, "Condition" ) ;
      $Class = get_mysqli_result($cresult, $i, "Class" ) ;
      $Prof = get_mysqli_result($cresult, $i, "Professor" ) ;
      $image = get_mysqli_result($cresult, $i, "Picture" ) ;

      if(!(in_array($bookID, $tracker)))//IF the book hasn't been displayed yet
      {
        array_push($tracker, $bookID) ;//Add book ID to the list of books being displayed
        //IF THE FILE EXISTS INSTEAD OF IF IMAGE
        console_log("dbconn>> An image exists for this file: ".file_exists("images/BKimages/".$image)) ;
        console_log("dbconn>> Image FileName is: ".$image) ;
        if(file_exists("images/BKimages/".$image) && $image != null)//If there is an image to display, display it
        {
          echo "<a href='Book.php?bookID=".$bookID."'> <image src='https://subw.hanlecofire.org/Dev/images/BKimages/$image' alt='Picture of book: $Name' > <br> $Name | Price: $${Price} | Condition: $Condition</a> <br> <hr style=\"height:2px;border-width:0;color:black;background-color:black\">";
        }
        else//If there is no image to display
        {
          echo "<a href='Book.php?bookID=".$bookID."'> $Name | Price: $${Price} | Condition: $Condition</a> <br> <hr style=\"height:2px;border-width:0;color:black;background-color:black\">";
        }
      }
    }
    return 1 ;
  }
  return 0 ;
}

/* Name: queryUsers
     * Purpose: Print the users that match the string arguement $search.
                Will seach User table for matches in its Name and KU email
     * Parameters: 
                  $connection - database connection
                  $cquery - sql statment to be sent to server
     * Return value: 1 if results are found, else 0              */
function queryUsers($cquery, &$tracker)
{
  global $connection ;//Bring variable into local scope
  $numCat = 0 ;
  $cresult = mysqli_query($connection, $cquery) ;//Query DB

  console_log("dbconn/queryUsers>> query: ". $cquery) ;
  
  if($cresult)//If there are query results
  {
      $numCat = mysqli_num_rows($cresult) ;
  }

  if($numCat)//If there are query results
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $userID = get_mysqli_result($cresult, $i, "UserID" ) ;
      $FirstName = get_mysqli_result($cresult, $i, "FirstName" ) ;
      $LastName = get_mysqli_result($cresult, $i, "LastName" ) ;
      $Email = get_mysqli_result($cresult, $i, "Email" ) ;

      if(!(in_array($userID, $tracker)))//IF the book hasn't been displayed yet 
      {
        array_push($tracker, $userID) ;//Add book ID to the list of books being displayed
        echo "<p>$FirstName $LastName | Email: ${Email}@kutztown.edu</p> ";
	
	if($sellerbooks = getSellerBooks($Email))
	{
		$bookCount = count($sellerbooks);
		for($i=0; $i < $bookCount; $i++)
		{
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
			
		if(file_exists("images/BKimages/".$bookPic) && $bookPic != null)//If there is an image to display, display it
        {
          echo "<a href='Book.php?bookID=".$currentBookId."'> <image src='https://subw.hanlecofire.org/Dev/images/BKimages/$bookPic' alt='Picture of book: $bookName' > <br> $bookName | Price: $${bookPrice} | Condition: $bookCondition</a\
> <br> <hr style=\"height:2px;border-width:0;color:black;background-color:black\">";
        }
        else//If there is no image to display
        {
          echo "<a href='Book.php?bookID=".$currentBookId."'> $bookName | Price: $${bookPrice} | Condition: $bookCondition</a> <br> <hr style=\"height:2px;border-width:0;color:black;background-color:black\">";
        }


		}
	}

      }
    }
    return 1 ;
  }
  return 0 ;
}

 /* Function Name: getUser
  * Description: get user information from the database
  * Parameters: email - the user's username
  * Return Value: The user's record if it exists, otherwise False
  */
function getUser($email)
{
  global $connection ;//Bring variable into local scope

  $userArray = array() ;//Create array for user information

  $sql = "SELECT * FROM User WHERE Email = '$email'";//SQL statement for getting user info
  $numCat = 0 ;
  $cresult = mysqli_query($connection, $sql) ;//Send Query to DB
  
  if($cresult)//If the DB has results
  {
      $numCat = mysqli_num_rows($cresult) ;
  }

  if($numCat)//If the DB has results
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $userID = get_mysqli_result($cresult, $i, "UserID" ) ;
      $FirstName = get_mysqli_result($cresult, $i, "FirstName" ) ;
      $LastName = get_mysqli_result($cresult, $i, "LastName" ) ;
      $Email = get_mysqli_result($cresult, $i, "Email" ) ;
      $pMethod = get_mysqli_result($cresult, $i, "P_ContactMethod" ) ;
      
    }

    $userArray = array("userID" => $userID, "FirstName" => $FirstName, "LastName" => $LastName, "Email" => $Email, "p_method" => $pMethod) ;//Populate array with user information
    return $userArray ;
  }
  return 0 ;
}

/* Function Name: getUserById
  * Description: get user information from the database
  * Parameters: userID - the user's ID number
  * Return Value: The user's record if it exists, otherwise False
  */
function getUserById($userID)
{
  global $connection ;//Bring variable into local scope

  $userArray = array() ;//create array for user information



  $sql = "SELECT * FROM User WHERE UserID = '$userID'";//SQL statement for DB
  $numCat = 0 ;
  $cresult = mysqli_query($connection, $sql) ;//Send Query to DB
  
  if($cresult)//If DB returns results
  {
      $numCat = mysqli_num_rows($cresult) ;
  }

  if($numCat)//If DB returns results
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $userID = get_mysqli_result($cresult, $i, "UserID" ) ;
      $FirstName = get_mysqli_result($cresult, $i, "FirstName" ) ;
      $LastName = get_mysqli_result($cresult, $i, "LastName" ) ;
      $Email = get_mysqli_result($cresult, $i, "Email" ) ;
      $pMethod = get_mysqli_result($cresult, $i, "P_ContactMethod" ) ;
      
    }

    $userArray = array("userID" => $userID, "FirstName" => $FirstName, "LastName" => $LastName, "Email" => $Email, "p_method" => $pMethod) ;//Populate user array
    return $userArray ;
  }
  return 0 ;
}

/* Function Name: insertBook
  * Description: Add new book for sale in DB
  * Parameters: $bookName - Name of book
                $ISBN1 - ISBN10 of book
                $ISNB3 - ISBN13 of book
                $Price - Price of book
                $Condition - Condition of book
                $Class - Course# used for
                $Professor - Name of professor who uses this book for their class
                $userID - userID of user selling the book
  * Return Value: True if book is added, otherwise False
  */
function insertBook($bookname, $email,  $price, $condition, $class=null, $prof=null, $ISBN1=null, $ISBN3=null, $imgName=null)
{
    $regISBN10 = '/^([0-9]{10})$/m' ;    
    $regISBN13 = '/^([0-9]{13})$|^([0-9]{3}-[0-9]{10})$/m' ;
    $regPrice = '/^[0-9]{0,4}\.[0-9]{2}$|^[0-9]{1,4}$/m' ;
    $regName = '/^.{1,256}$/m' ;
    $regClass = '/^[a-zA-Z]{2,3}\-[0-9]{1,3}$/m' ;
    $regProf = '/^.{1,256}$/m' ;
/*
    $validISBN10 = preg_match($regISBN10, $ISBN1);
    $validISBN13 = preg_match($regISBN13, $ISBN3);
    $validPrice = preg_match($regPrice, $price);
    $validName = preg_match($regName, $bookname);
    $validClass = preg_match($regClass, $class);
    $validProf = preg_match($regProf, $prof);
*/

//    if((($ISBN1==null) and ($ISBN3==null)) or (((preg_match($regISBN10, $ISBN1)) == 1) or ((preg_match($regISBN13, $ISBN3)) == 1)))


    if(((preg_match($regISBN10, $ISBN1)) or $ISBN1==null) and ((preg_match($regISBN13, $ISBN3)) or $ISBN3==null) and (preg_match($regPrice, $price)) and (preg_match($regName, $bookname)) and ((preg_match($regClass, $class)) or $class==null) and ((preg_match($regProf, $prof)) or $prof==null))
    {
	    global $connection ;//Bring variable into local scope
	    console_log("dbconn>> attempting to upload: ".$imgName) ;
	    try 
      {
  	    $stmt = $connection->prepare("INSERT INTO Book (`ISBN_10`, `ISBN_13`, `Price`, `Picture`, `Condition`, `Class`, `Professor`, `Name`, `UserID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)") ;//SQL statement to send to DB
  	    $stmt->bind_param('sssssssss', $ISBN1, $ISBN3, $price, $imgName, $condition, $class, $prof, $bookname, $email) ;
  	    if($stmt->execute())//If failed to execute statement to DB server
  	    {
  	      console_log("dbconn>> Book added to DB!") ;
  	      return TRUE;
  	    }
  	    else 
  	    {     
  	      console_log('Book was not added to DB') ;
  	      console_log("dbconn>> Error that occured: ".$stmt);
  	      return FALSE ;
  	    }
	    }

	  catch (Exception $e) 
    {
	    console_log("dbconn>> $e");
	    console_log('Exception caught in insertBook function! ') ;
	    return FALSE;
	  }
  }
  else
  {
  	console_log('Book was not added. ISBN issue') ;
  	return FALSE;
  }
}

/* Name: getBook
     * Purpose: return that book that corresponds to the bookID passed as arguement
     * Parameters: 
                  $bookID - bookID of book information to get
     * Return value: Array with Book Information, else 0              */
function getBook($bookID)
{
  global $connection ;//Variable to bring into local scope

  $bookArray = array() ;//Array to hold book info

  $sql = "SELECT * FROM Book WHERE Book_Id = '$bookID'";//SQL Command for DB
  $numCat = 0 ;
  $cresult = mysqli_query($connection, $sql) ;//Send query to DB
  
  if($cresult)//If there are results from the query
  {
      $numCat = mysqli_num_rows($cresult) ;
  }


  if($numCat)//IF there are results from the query
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $bookID = get_mysqli_result($cresult, $i, "Book_Id" ) ;
      $Name = get_mysqli_result($cresult, $i, "Name" ) ;
      $ISBN1 = get_mysqli_result($cresult, $i, "ISBN_10" ) ;
      $ISBN3 = get_mysqli_result($cresult, $i, "ISBN_13" ) ;
      $Price = get_mysqli_result($cresult, $i, "Price" ) ;
      $Condition = get_mysqli_result($cresult, $i, "Condition" ) ;
      $Class = get_mysqli_result($cresult, $i, "Class" ) ;
      $Prof = get_mysqli_result($cresult, $i, "Professor" ) ;
      $Picture = get_mysqli_result($cresult, $i, "Picture" ) ;
      $userID = get_mysqli_result($cresult, $i, "UserID" ) ;


      $userArray = array("bookID" => $bookID, "name" => $Name, "ISBN10" => $ISBN1, "ISBN13" => $ISBN3, "price" => $Price, "condition" => $Condition, "class" => $Class, "prof" => $Prof, "pic" => $Picture, "seller" => $userID) ;//Add book information to array
      return $userArray ;
    }
  }
  return FALSE ;
}

/* Name: getSellerBooks
     * Purpose: return that an array of bookIDs that a user is selling
     * Parameters: 
                  $email - seller's email
     * Return value: Array with Books a user is selling, else 0              */
function getSellerBooks($email)
{
  global $connection ;//Bring variable into local scope

  $bookArray = array() ;//Array for bookIDs

  $sql = "SELECT Book_Id FROM Book WHERE UserID = '$email'";//SQL Command for DB
  $numCat = 0 ;
  $cresult = mysqli_query($connection, $sql) ;//Send Command to DB
  
  if($cresult)//If there are results from DB
  {
      $numCat = mysqli_num_rows($cresult) ;
  }


  if($numCat)//IF there are results from DB
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $bookID = get_mysqli_result($cresult, $i, "Book_Id" ) ;

      array_push($bookArray, $bookID) ;//Add bookID to array
    }
    return $bookArray ;
  }
  return FALSE ;
}

/* Name: removeBook
     * Purpose: return that an array of bookIDs that a user is selling
     * Parameters: 
                  $bookID - BookID of book to be removed
     * Return value: True if book is removed from DB, else False              */
function removeBook($bookID)
{
  global $connection ;//Bring Variable into local scope

  $sql = "DELETE FROM Book WHERE Book_Id = '$bookID'";//SQL command for DB
  if($connection->query($sql))//if DB executed the command
  {
    return true ;
  }
  else
  {
    return FALSE ;
  }
}

/* Function Name: updatePassword
 * Description: update user password in the database
 * Parameters:
 *             email - the user's email
 *             password - password to be updated to
 * Return Value: TRUE if the information was successfully inserted,
 *               FALSE if information insertion was a failure
 */
function updatePassword($email, $password) 
{
  global $connection ; //Bring in the connection variable into local scope
  console_log("dbconn>> Attempting to update Password") ;
  //console_log("dbconn>> Email whose password is being updated: ".$email) ;
  //console_log("dbconn>> Is there a new Password: ".(string)isset($password)) ;
  try {
    $hash = password_hash($password, PASSWORD_DEFAULT); //Hash the password
    //console_log("dbconn>> Hashed Password: ".$hash) ;
    $stmt = $connection->prepare("UPDATE User SET Password=? WHERE Email=?") ;
    console_log("dbconn>> ".stmt) ;
    $stmt->bind_param('ss', $hash, $email) ;
    if($stmt->execute())//If failed to execute statement to DB server
    {
      console_log('Password updated!') ;
      return TRUE;
    }
    else 
    {
      console_log('Password Not updated!') ;
      return FALSE ;
    }           
  }

  catch (Exception $e) {
    console_log("dbconn>> $e");
    console_log('Exception caught in dbconn:updatePassword !') ;
    return FALSE;
  }
}
?>
