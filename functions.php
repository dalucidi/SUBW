<?php
require_once ('dbconn.php');//Useful DB functions
db_connect() ;//Connect to DB


/* Function Name: CreateUser
 * Description: insert user information into the database
 * Parameters:
 *             email - the user's email
 *             password - the user's password
 * Return Value: TRUE if the information was successfully inserted,
 *               FALSE if information insertion was a failure
 */
function CreateUser($email, $password, $fname, $lname) 
{
  global $connection ; //Bring in the connection variable into local scope
  try {
    $hash = password_hash($password, PASSWORD_DEFAULT); //Hash the password
    $stmt = $connection->prepare("INSERT INTO User (Email, Password, FirstName, LastName) VALUES (?, ?, ?, ?)") ;
    $stmt->bind_param('ssss', $email, $hash, $fname, $lname) ;
    if($stmt->execute())//If failed to execute statement to DB server
    {
      return TRUE;
    }
    else 
    {
      return FALSE ;
    }           
  }

  catch (Exception $e) {
    print "<p>$e</p>";
    print_r('Exception caught! ') ; //DELETE THIS LATER, FOR DEBUGGING ONLY
    return FALSE;
  }
}










/* Function Name: verifyUser
 * Description: Authenticate user
 * Parameters: email - KU Email
 *             Password - User's password          
 * Return Value: UserID if the user is verified, else FALSE ;
 */
function verifyUser($email, $password) 
{
  global $connection ;//Bring connection into local scope

  $numCat = 0 ;
  $cquery = "SELECT * FROM User WHERE Email= '$email'" ; //Query for DB
  $cresult = mysqli_query($connection, $cquery) ;
  
  if($cresult)//If there are results from DB
  {
      $numCat = mysqli_num_rows($cresult) ;
  }

  if($numCat)//If there are results from DB
  {
    for ($i=0; $i < $numCat ; $i++) 
    { 
      $hash = get_mysqli_result($cresult, $i, "Password" ) ;
      $user = get_mysqli_result($cresult, $i, "UserID" ) ;
      $verified = get_mysqli_result($cresult, $i, "Verified" ) ;

      if(password_verify($password, $hash)) //If password entered matches
      {
        if(!$verified)//If email is not verififed
        {
          verifyEmail($email) ;
          return 2 ;
        }
        else
        {
          return $user ; //Return user ID
        }
      }
    }
    
  }
  return 0 ;
}










/* Function Name: verifyEmail
 * Description: Sends verification email to user
 * Parameters: email - KU Email        
 * Return Value: TRUE if email sent, else FALSE ;
 */
function verifyEmail($email)
{
  $user = getUser($email) ; //Get user Info
  $to = $email.'@kutztown.edu'; //Who to send email to

    // Subject
    $subject = 'Verify Your Email';

    // Message
    $message = '
    <html>
  <head>
    <title>Email Verification</title>
  </head>
  <body>
    <h2>Hey '.$user['FirstName'].' we\'re really glad you decided to get your money\'s worth out of your textbooks!</h2>
    <p>Please click this <a href= \'subw.hanlecofire.org/Staging/newAccount.php?verify=TRUE&email='.$email.'\'>link</a> to verifiy your email!</p>
    <br>
    <br>
    <br>
    <img alt=\'The main man Leanord\' src=\'http://subw.hanlecofire.org/Staging/images/worm.png\' style=\'width:225px;height:300px;\' >
    <p> Respectfully, <p>
    <p>Slightly Used Bookworm Team</p>
  </body>
  </html>
  ';//CHANGE THE LIVE ENVIROMENT WHEN WE PUSH THIS ELSEWHERE

  // To send HTML mail, the Content-type header must be set
  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=iso-8859-1';

  // Additional headers
  $headers[] = 'From: Slightly Used Bookworm <noreply@subw.hanlecofire.org>';

  // Mail it
  mail($to, $subject, $message, implode("\r\n", $headers));
}








/* Function Name: emailVerified
 * Description: set user's verification as true
 * Parameters:
 *             email - the user's email
 * Return Value: TRUE if the information was successfully inserted,
 *               FALSE if information insertion was a failure
 */
function emailVerified($email) 
{
  global $connection ;//Bring variable into local scope
  $yes = 1 ;
  //return true if successful insert, false otherwise
    $stmt = $connection->prepare("UPDATE User SET Verified = (?) WHERE Email= '".$email."'") ;
    if(!$stmt)//If prepared statement failed || WE NEED TO DO SOME OTHER SHIT IN FINAL VERSION
    {
      print_r($connection) ;
      return FALSE ;
    }
    $stmt->bind_param('i', $yes) ;
    
    //$stmt->bind_param('i', $yes) ;
    if($stmt->execute())//If failed to execute statement to DB server
    {
      return TRUE;
    }
    else 
    {
      return FALSE ;
    }           
}








//Chooses random background
function RandBack()
{

  $randNum = rand(1,5);

  if($randNum == 1){
    echo "class = CP";
  }else if($randNum == 2){
    echo "class = LB";
  }else if($randNum == 3){
    echo "class = LP";
  }else if($randNum == 4){
    echo "class = DB";
  }else{
    echo "class = DP";
  }
}//randBack









/* Name: createAccountPage
     * Purpose: Loads the createAccountPage to whatever page it's called on
     * Parameters: 
                  $message = Message to be printed under the Create account header on the page
     * Return value: None              */
function createAccountPage($message="")
{
  require_once('header.php');//Bring in header
  echo "<!DOCTYPE html>

    <html lang='en'>
        <head>
          <title>Create Account</title>
       <link rel='stylesheet' href='style.css'>
        </head>
        <br>
        <br>
       <body class=\"bg-base\">

        <form action=\"create_account.php\" method=\"post\" id=\"createform\" class=\"container\">
          <h1 class=\"text-center\">Create an account</h1>
          <h2 style='color: black ;'>".$message."</h2>
          
          <div class=\"form-row align-items-center\">
	       <div class=\"col text-right\">
	       	    <label id=\"bgrfnt\">Email:</label>
	       </div> 
	       <div class=\"col\">
	       	    <input class=\"form-control\" maxlength=\"8\" name=\"email\" id=\"email\" required autofocus type=\"text\">
	       </div>
	       <div class=\"col text-left\">
	       	    <label class=\"font-weight-bold\" id=\"bgrfnt\">@live.kutztown.edu</label>
	       </div>
	  </div>

          <br>
	  <div class=\"form-row align-items-center\">
	       <div class=\"col text-right\">
	       	    <label id=\"bgrfnt\">First Name:</label>
	       </div>
	       <div class=\"col\">
 	       	    <input class=\"form-control\" type=\"text\" name=\"fname\" id=\"fname\" required>
	       </div>
	       <div class=\"col text-left\">
               </div>
          </div>

          <br>
	  <div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
               	    <label id=\"bgrfnt\">Last Name:</label>
	       </div>
	       <div class=\"col\">
	       	     <input class=\"form-control\" type=\"text\" name=\"lname\" id=\"lname\" required>
	       </div>
	       <div class=\"col text-left\">
               </div>
          </div>
          <br>
	  <div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
               	    <label id=\"bgrfnt\">Password:</label>
	       </div>
	       <div class=\"col\">
	       	     <input class=\"form-control\" type=\"password\" name=\"password1\" id=\"pass\" required>
	       </div>
	       <div class=\"col text-left\">
               </div>
          </div>
          <br>
	  <div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
               	    <label id=\"bgrfnt\">Retype Password:</label>
	  </div>
               <div class=\"col\">
	       	     <input class=\"form-control\" type=\"password\" name=\"password2\" required>
	  </div>
               <div class=\"col text-left\">
               </div>
          </div>
          <div class=\"form-row align-items-center\">
               <div class=\"col\">
          </div>
               <div class=\"col\">
	             <input class=\"form-control btn\" type=\"submit\" value=\"Create Account\" class=\"btn\">
          </div>
               <div class=\"col text-left\">
               </div>
          </div>

        </form>

    <div id=\"ferror\"></div>

    </body>
    </html> ";

}









/* Name: email_verified
     * Purpose: Loads the email has been verified page to whatever page it's called on
     * Parameters: 
                  $email - email of user who's account is being verified
     * Return value: None              */
function email_verified($email)
{
  $user = getUser($email);//Get user info

  echo"
  <link rel='stylesheet' href='style.css'>
    <body>

    <h1>Thank you, ".$user['FirstName'].", your email has been verified!</h1><h1> Happy Shopping!</h1>
    </body> " ;
}










/* Name: loginPage
     * Purpose: Loads login page to whatever page it's called on
     * Parameters: 
                  $message - Message to be displayed under the main header of the page              */
function loginPage($message="")
{
  require('header.php');//Bring in header
  echo"
  <!DOCTYPE html>
    <html lang='en'>
    <head>
      <title>Sign in</title>
      <link rel='stylesheet' href='style.css'>
    </head>
   <body class=\"bg-base\" ";


    echo " id='back'>
    <br>
    <br>
        <form action=\"sign_in.php\" method=\"post\"  class=\"container\">
          <h1 class=\"text-center\">Log In</h1>
          <center><h2 style='color: black ;'>".$message."</h2></center>

          <div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
                    <label id=\"bgrfnt\">Email:</label>
               </div>
               <div class=\"col\">
                    <input class=\"form-control\" maxlength=\"8\" name=\"email\" id=\"email\" required autofocus type=\"text\">
               </div>
               <div class=\"col text-left\">
                    <label class=\"font-weight-bold\" id=\"bgrfnt\">@live.kutztown.edu</label>
               </div>
          </div>

          <br>
          <div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
                    <label id=\"bgrfnt\">Password:</label>
               </div>
               <div class=\"col\">
                    <input class=\"form-control\" type=\"password\" name=\"password\" id=\"password\" required>
               </div>
               <div class=\"col text-left\">
               </div>
          </div>


	  <div class=\"form-row align-items-center\">
               <div class=\"col\">
          </div>
               <div class=\"col\">
                     <input class=\"form-control btn\" type=\"submit\" value=\"Log In\" class=\"btn\">
          </div>
               <div class=\"col text-left\">
               </div>
          </div>
<center class=\"pt-3\">
		<a href=\"forgotPasswordPage.php\" id=\"bgrfnt\" class=\"text-primary text-right noDisp h-25\">Forgot Password?</a>
</center>
</form>

        </body>
        </html>

        <script type='text/javascript'>
          
          
          function validate(e)
          {
            var email = document.getElementById('email').value ;
            var pass = document.getElementById('pass').value ;

            if(email == '')
              {
                alert('Email Address must be entered')
                document.getElementById('email').focus() ;
                e.preventDefault() ;
                return false ;
              }//End of if

            if(pass == '')
              {
                alert('Password must be entered') ;
                document.getElementById('pass').focus() ;
                e.preventDefault() ;
                return false ;
              }//End of if

            if(email.length < 6)
              {
                alert('email must be at least 8 characters long!') ;
                document.getElementById('user').focus() ;
                e.preventDefault() ;
                return false ;
              }//End of if

            if(goodPass(e))
            {
              return  false ;
            }
          }//End of validate function

            function reset(e) 
            {
              var resetForm = window.confirm('Are you sure you want to reset the form?');
                    if (resetForm == true)
                        document.getElementById('myForm').reset() ;
                    else
                      e.preventDefault() ;
                      return false;
            }//End of reset function

            function sendIt()
              {
                  document.getElementById('myButton').onclick = function () {
                    location.href = 'createaccount.php';
            };
              }

            //Make sure password is 6 characters long or more and has at least one lowercase and one uppercase alphabetical character or has at least one lowercase and one numeric character or has at least one uppercase and one numeric character
            function goodPass(e)
            {
              var mediumRegex = new RegExp('^(((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])))(?=.{6,})');
              var pass = document.getElementById('pass').value ;

              if(!mediumRegex.test(pass))
              {
                alert('Password must be six characters long, have one lowercase letter, one uppercase letter, and one number') ;
                document.getElementById('pass').focus() ;
                e.preventDefault() ;
                return false;
              }
              return true ;
            }

          window.addEventListener('submit', validate, false) ;

        </script> " ;
}









/* Name: resetPass
     * Purpose: sends a reset email to user and resets their passwords randomly until they reset their password through the email
     * Parameters: 
                  $email - email of password reset account 
    *Return: Resets email if successful, else returns false             */
function resetPass($email)
{
    
  global $connection ;//bring variable into local scope
  
  try {
    $tempPass = generate_string() ;
    console_log("functions>> Randomized Password: ".$tempPass) ;
    $hash = password_hash($tempPass, PASSWORD_DEFAULT);//Create random hashed password
    console_log('functions>> Hashed Pass: '.$hash) ;
    $stmt = $connection->prepare("UPDATE User SET Password = (?) WHERE Email= '".$email."'") ;
    $stmt->bind_param('s', $hash) ;
    if(true)//$stmt->execute())//If failed to execute statement to DB server
    {
      console_log('functions>> Password Reset successful. Sending email.') ;
      resetPassEmail($email, $tempPass) ;//Send email to user to have them reset their password
      return ;
    }
    else 
    {
      return FALSE ;
    }           
  }

  catch (Exception $e) {
    print "<p>$e</p>";
    print_r('Exception caught! ') ; //DELETE THIS LATER, FOR DEBUGGING ONLY
    return FALSE;
  }
}







/* Name: forgotPass
     * Purpose: sends a reset email to user and resets their passwords randomly until they reset their password through the email
     * Parameters: 
                  $email - email of password reset account               */
function forgotPass($email)
{
  global $connection ;//bring variable into local scope
  
  try {
    $tempPass = generate_string() ;
    console_log("functions>> Randomized Password: ".$tempPass) ;
    $hash = password_hash($tempPass, PASSWORD_DEFAULT);//create random hashed password
    $stmt = $connection->prepare("UPDATE User SET Password = (?) WHERE Email= '".$email."'") ;
    $stmt->bind_param('s', $hash) ;
    if($stmt->execute())//If failed to execute statement to DB server
    {
      forgotPassEmail($email, $tempPass) ;//Send email to user to have them reset their password
      return TRUE ;
    }
    else 
    {
      return FALSE ;
    }           
  }

  catch (Exception $e) {
    print "<p>$e</p>";
    print_r('Exception caught! ') ; //DELETE THIS LATER, FOR DEBUGGING ONLY
    return FALSE;
  }
}






/* Name: resetPassEmail
     * Purpose: sends a reset email to user and resets their passwords randomly until they reset their password through the email
     * Parameters:
                  $tempPass - new Temporary Password  
                  $email - email of password reset account               */
function resetPassEmail($email, $tempPass)
{
  $user = getUser($email) ;//Get user info
  console_log("functions>> The user whose getting this email is".$user) ;
  $to = $email.'@kutztown.edu'; 

    // Subject
    $subject = 'Reset Your Password!';

    // Message
    $message = '
    <html>
  <head>
    <title>Password Reset</title>
  </head>
  <body class=\"bg-base\">
    <h2>Hey '.$user['FirstName'].' we hate to do this to ya, but we need you to reset your account password</h2>
    <h2>We noticed someone attempted to log into your account a few times and thought it\'d be in your best interest</h2>
    <p>Please log into your account using this super random temporary password we made cause we care about you and your well being.</p>
    <p>Temporary Password: '.$tempPass.'</p>

    <p>After you log in, change your password by going to the seller\'s page.</p>
    <br>
    <br>
    <br>
    <img alt=\'The main man Leanord\' src=\'http://subw.hanlecofire.org/Staging/images/worm.png\' style=\'width:225px;height:300px;\' >
    <p> Respectfully, <p>
    <p>Slightly Used Bookworm Team</p>
  </body>
  </html>
  ';//CHANGE THE LIVE ENVIROMENT WHEN WE PUSH THIS ELSEWHERE

  // To send HTML mail, the Content-type header must be set
  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=iso-8859-1';

  // Additional headers
  $headers[] = 'From: Slightly Used Bookworm <noreply@subw.hanlecofire.org>';

  // Mail it
  mail($to, $subject, $message, implode("\r\n", $headers));
}







/* Name: forgotPassEmail
     * Purpose: sends a forgot pass email to user and resets their passwords randomly until they reset their password through the email
     * Parameters:
                  $tempPass - Temporary account password 
                  $email - email of password reset account               */
function forgotPassEmail($email, $tempPass)
{
  $user = getUser($email) ;//Get user info
  $to = $email.'@kutztown.edu'; 

    // Subject
    $subject = 'Reset Your Password!';

    // Message
    $message = "
    <html>
  <head>
    <title>Password Reset</title>
  </head>
  <body class=\"bg-base\">
    <h2>Hey ".$user['FirstName']." we saw you forgot your password and we would like to say, that is just classic you</h2>
    
    <p>Please log into your account using this super random temporary password we made cause we care about you and your well being.</p>
    <p>Temporary Password: ".$tempPass."</p>
    <br>
    <br>
    <br>
    <img alt='The main man Leanord' src='http://subw.hanlecofire.org/Staging/images/worm.png' style='width:225px;height:300px;' >
    <p> Respectfully, <p>
    <p>Slightly Used Bookworm Team</p>
  </body>
  </html>
  ";//CHANGE THE LIVE ENVIROMENT WHEN WE PUSH THIS ELSEWHERE

  // To send HTML mail, the Content-type header must be set
  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=iso-8859-1';

  // Additional headers
  $headers[] = 'From: Slightly Used Bookworm <noreply@subw.hanlecofire.org>';

  // Mail it
  mail($to, $subject, $message, implode("\r\n", $headers));
}









/* Name: createNewBookListingPage
 * Purpose: the page when a user wants to upload a new book listing
 * Parameters:
                $message - Message to display on page
                             */
function createNewBookListingPage($message="")
{
  require('header.php');//Bring in header
  echo"
  <!DOCTYPE html>
    <html lang='en'>
    <head>
      <title>New Book Listing</title>
      <link rel='stylesheet' href='style.css'>
    </head>
  <body class=\"bg-base\" ";
  
  echo " id='back'>

  
  <form action=\"newBookListing.php\" method=\"post\" enctype=\"multipart/form-data\" class=\"container\">
  	<h1 class=\"text-center\">New Book Listing</h1>
	<center><h2 style='color: black ;'>".$message."</h2></center>

	<div class=\"form-row align-items-center\">
	  <div class=\"col text-right\">
	    <label id=\"bgrfnt\">*Book Name:</label>
	  </div>
	  <div class=\"col\">
	    <input class=\"form-control\" maxlength=\"150\" name=\"bookname\" id=\"bookname\" required autofocus type=\"text\">
	  </div>
	  <div class=\"col text-left\">
	  </div>
	</div>

	<br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">ISBN-10:</label>
          </div>
          <div class=\"col\">
            <input class=\"form-control\"  name=\"isbn10\" id=\"isbn10\" type=\"text\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">ISBN-13:</label>
          </div>
          <div class=\"col\">
            <input class=\"form-control\"  name=\"isbn13\" id=\"isbn13\" type=\"text\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>
	
	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">*Price($):</label>
          </div>
          <div class=\"col\">
            <input class=\"form-control\"  name=\"price\" id=\"price\" required type=\"text\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">*Condition:</label>
          </div>
          <div class=\"col\">
            <select class=\"form-control\" name=\"condition\" id=\"condition\" required>
	      <option value=\"Brand New\">Brand New</option>
	      <option value=\"Near Mint\">Near Mint</option>
	      <option value=\"Fair\">Fair</option>
	      <option value=\"Poor\">Poor</option>
	      <option value=\"Very Poor\">Very Poor</option>
	    </select>
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">Course# Used For:</label>
          </div>
          <div class=\"col\">
            <input class=\"form-control\"  name=\"class\" id=\"class\" type=\"text\" placeholder=\"CSC135\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">Professor:</label>
          </div>
          <div class=\"col\">
            <input class=\"form-control\" name=\"prof\" id=\"prof\" type=\"text\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\">Picture:</label>
          </div>
          <div class=\"col\">
            <input class=\"form-control\" name=\"pic\" id=\"pic\" type=\"file\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>

	<div class=\"form-row align-items-center\">
          <div class=\"col text-right\">
            <label id=\"bgrfnt\"></label>
          </div>
          <div class=\"col\">
            <input class=\"form-control btn\" type=\"submit\" value=\"List Book\">
          </div>
          <div class=\"col text-left\">
          </div>
        </div>

        <br>


  </form>
    </body>
    </html>

    <script type='text/javascript'>
    function sendIt()
              {
                  document.getElementById('myButton').onclick = function () {
                    location.href = 'newBookListing.php';
            };
              }

    //Make sure course number is three letters followed by numbers
            function goodCourse(e)
            {
              var mediumRegex = new RegExp('^[a-zA-Z]{3}[0-9]{2,3}');
              var class = document.getElementById('class').value ;
            console.log(mediumRegex.test(class));
              if(!mediumRegex.test(class))
              {
                alert('Course must be three letters followed by three numbers') ;
                document.getElementById('class').focus() ;
                e.preventDefault() ;
                return false;
              }
              sendIt() ;
            }

          window.addEventListener('submit', goodCourse, false) ;

        </script> " ;
}










/* Name: forgotPassPage
     * Purpose: Loads forgotPass page to whatever page it's called on
     * Parameters: 
                  $message - Message to be displayed under the main header of the page              */
function forgotPassPage($message="")
{
  require('header.php');//Bring in header
  echo"
  <!DOCTYPE html>
    <html lang='en'>
    <head>
      <title>Forgot Password</title>
      <link rel='stylesheet' href='style.css'>
    </head>
  <body class=\"bg-base\" id='back'>

    

    <form action='forgotPasswordPage.php' method='post' class='container'>
        <h1 class=\"text-center\">Forgot Password</h1>
        <h2 style = 'color:red;'>".$message."</h2>

	<div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
               	    <label id=\"bgrfnt\">Email:</label>
	       </div>
	       <div class=\"col\">
	       	    <input class=\"form-control\" type=\"text\" maxlength=\"8\" name=\"email\" id=\"email\" required autofocus>
	       </div>
	       <div class=\"col text-left\">
	       	    <label id=\"bgrfnt\">@live.kutztown.edu</label>
	       </div>
	</div>        
        <br>
	<div class=\"form-row align-items-center\">
               <div class=\"col\">
               </div>
               <div class=\"col\">
               	    <input type='submit' value='Reset' class='btn'>
	       </div>
               <div class=\"col text-left\">
               </div>
        </div>

";

}




/* Name: userSettingsPage
     * Purpose: Loads userSettings page to whatever page it's called on
     * Parameters: 
                          */
function userSettingsPage()
{
  require('header.php');//Bring in header
  echo"
  <!DOCTYPE html>
    <html lang='en'>
    <head>
      <title>User Settings</title>
      <link rel='stylesheet' href='style.css'>
    </head>
  <body class=\"bg-base\" ";
  echo " id='back'>"; 

}







/* Name: console_log
     * Purpose: write data to web browser's console
     * Parameters: 
                  $data - data to display on console
     * Return value: true              */
function console_log($data){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
  return true ;
}








/* Name: generate_string
     * Purpose: create a random string of 16 alphanumeric characters
     * Parameters: None
     * Return value: String of 16 alphanumeric characters           */
function generate_string() 
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*()';
    $input_length = 16;
    $random_string = '';
    for($i = 0; $i < $input_length; $i++) 
    {
        $random_character = $permitted_chars[mt_rand(0, 70 - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}










/* Name: changePassPage
     * Purpose: Loads change password page to whatever page it's called on
     * Parameters: 
                  $message - Message to be displayed under the main header of the page              */
function changePassPage($message="")
{
  require('header.php');//Bring in header
  echo"
  <!DOCTYPE html>
    <html lang='en'>
    <head>
      <title>Change Password</title>
      <link rel='stylesheet' href='style.css'>
    </head>
  <body class=\"bg-base\">

        <form action='changePass.php' method='post' class='container'>
            <h1 class=\"text-center\">Change Password</h1>
            <center><h2 style='color: black ;'>".$message."</h2></center>

	    <div class=\"form-row align-items-center\">
               <div class=\"col text-right\">
               	    <label id='bgrfnt'>Current Password</label>
	       </div>
	       <div class=\"col\">
		    	    <input class=\"form-control\" type='password' name='currentPass' id='currentPass' placeholder='YourSuperSecretPassword' required autofocus>
	       </div>
	       <div class=\"col text-left\">
               </div>
	    </div>

            <br>
	    <div class=\"form-row align-items-center\">
            	 <div class=\"col text-right\">
            	      <label id='bgrfnt'>New Password</label>
	         </div>
 	    	 <div class=\"col\">
		      <input class=\"form-control\" type='password' placeholder='YourSuperSecretPassword' id='newPass' name='newPass' required>
		 </div>
		 <div class=\"col text-left\">
                 </div>
            </div>
	    <br>
	    <div class=\"form-row align-items-center\">
                 <div class=\"col text-right\">
            	      <label id='bgrfnt'> Confirm New Password</label>
		 </div>
		 <div class=\"col\">
		       <input class=\"form-control\" type='password' placeholder='YourSuperSecretPassword' id='newPass2' name='newPass2' required>
		 </div>
		 <div class=\"col text-left\">
                 </div>
            </div>
            <br>
            <div class=\"form-row align-items-center\">
                 <div class=\"col\">
            	 </div>
                 <div class=\"col\">
	       	      <input class=\"form-control btn\" type='submit' value='Change Password'>
	         </div>
		 <div class=\"col text-left\">
                 </div>
            </div>	          
        </form>
        

        </body>
        </html>

        <script type='text/javascript'>
          
          
          function validate(e)
          {
            var email = document.getElementById('currentPass').value ;
            var pass = document.getElementById('newPass').value ;
            var confirmPass = document.getElementById('newPass2').value ;

            if(email == '')
              {
                alert('Email Address must be entered')
                document.getElementById('email').focus() ;
                e.preventDefault() ;
                return false ;
              }//End of if

            if(pass == '')
              {
                alert('Password must be entered') ;
                document.getElementById('newPass').focus() ;
                e.preventDefault() ;
                return false ;
              }//End of if

              if(confirmPass == '')
              {
                alert('Password must be entered') ;
                document.getElementById('newPass2').focus() ;
                e.preventDefault() ;
                return false ;
              }//End of if

            if(goodPass(e))
            {
              return  false ;
            }

          }//End of validate function

            function reset(e) 
            {
              var resetForm = window.confirm('Are you sure you want to reset the form?');
                    if (resetForm == true)
                        document.getElementById('myForm').reset() ;
                    else
                      e.preventDefault() ;
                      return false;
            }//End of reset function

            function sendIt()
              {
                  document.getElementById('myButton').onclick = function () {
                    location.href = 'createaccount.php';
            };
              }

            //Make sure password is 6 characters long or more and has at least one lowercase and one uppercase alphabetical character or has at least one lowercase and one numeric character or has at least one uppercase and one numeric character
            function goodPass(e)
            {
              var mediumRegex = new RegExp('^(((?=.*[a-z])(?=.*[A-Z]))(?=.*[0-9])))(?=.{6,})');
              var pass = document.getElementById('newPass').value ;
              var confirmPass = document.getElementById('newPass2').value ;

              if(!mediumRegex.test(pass) || !mediumRegex.test(confirmPass))
              {
                alert('Password must be six characters long, have one lowercase letter, one uppercase letter, and one number') ;
                document.getElementById('pass').focus() ;
                e.preventDefault() ;
                return false;
              }
              return true ;
            }

          window.addEventListener('submit', validate, false) ;

        </script> " ;
}



/* Name: clean
     * Purpose: Remove special characters from string
     * Parameters: 
                  $string - a string
     * Return value: String without special characters             */
function clean($string) 
{return preg_replace('/[^A-Za-z0-9\-:,+=*^%$#@!]/', '', $string);} // Removes special chars
?>
