<?php
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

<body>
 <body style="background-color:#C4C4C4;">
 <div class="col-md-12">
 <div class="row">
  <div class="col-md-4" style="background-color: green;">
    <div class="thumbnail">
    <a href=""> 
      
	<!--<a href="Query.php?SearchBar=Data">-->
        <img src="images/green.jpg" alt="Lights" style="width:100%">
    
        <div class="caption">
        </div>
      </a>
    </div>
  </div>
  <div class="col-md-4" style="background-color: #C96C4A;">
    <div class="thumbnail">
    <a href="">
       <img src="images/burntOrange.jpg" alt="Lights" style="width:100%">
        <div class="caption">
        </div>
      </a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="thumbnail">
    <a href="">
    <img src="images/grey.jpg" alt="Lights" style="width:100%">	
        <div class="caption">
        </div>
      </a>
    </div>
  </div>
 </div>
 </div>
</body>

</body>
</html>
