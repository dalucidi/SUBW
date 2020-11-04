<?php

  //File Name: aboutSUBW.php
  //Purpose:   Displays contact information about the devlopers

session_start();// Create/use session variable
require_once('header.php');//Add header to the page
include_once "functions.php"; //Useful functions
$_SESSION['tries'] = 0 ;
?>

<!DOCTYPE html>
<html lang="en">
 <head>
    <link rel='icon' type='image/png' sizes='16x16' href='images/favicon2.png'>
   <title>Slightly Used Bookworm</title>
 </head>

 <body class=bg-base>
    <blockquote class="blockquote text-center">
	<h1>About SUBW!</h1>
	<p class="mb-0">Slightly Used Bookworm (SUBW), is a book match-making service for students at Kutztown University.<br/>
	With SUBW, users can perform two main functions. Students looking to purchase a book may search for one that is being sold,<br/>
	and students looking to sell a book may post a book that they have for sale.</p>
   </div>
</body>
</html>
