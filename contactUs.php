<?php

  //File Name: contactUs.php
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


<?php
  print("<p>Contact Us!</p>");
  print("<p>Jonathan Fernezan:  jfern683@live.kutztown.edu<p>");
  print("<p>Craig Long:  clong579@live.kutztown.edu<p>");
  print("<p>Gregory Klein:  gklei613@live.kutztown.edu<p>");
  print("<p>Daniel Lucidi:  dluci029@live.kutztown.edu");
?>
</body>
</html>
