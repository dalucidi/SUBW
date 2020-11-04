<?php

  //File Name: Book.php
  //Purpose:   Displays book information after a user queries and selects a book

session_start();
require_once('header.php'); //header on top of each page
require_once 'dbconn.php'; //Useful database functions
require_once 'functions.php'; //other useful functions
db_connect() ;


$book = getBook($_GET['bookID']) ;
$user = getUser($book['seller']) ;
$price = $book['price'] ;
$bookimg = $book['pic'];

//HTML
echo"
  <!DOCTYPE html>
    <html lang='en'>
    <head>
      <title>".$book['name']."</title>
      <link rel='stylesheet' href='style.css'>
    </head>
  <body class=\"bg-base\" ";
  echo " id='back'>


  <form action=\"\" method=\"post\" enctype=\"multipart/form-data\" class=\"container\">
  <h2 class=\"text-center\">".$book['name']."</h2>
";

  
  if(isset($bookimg)) {
  echo"
  <!--CHECK TO SEE IF IMAGE EXISTS HERE-->
  <div class=\"container-fluid\">
  <div class=\"form-row align-items-left\">
   <div class=\"col-sm-3 text-left\">
      <image src='https://subw.hanlecofire.org/Dev/images/BKimages/$bookimg' alt='Picture of book: $Name'  > 	 
   </div> ";
  }
  else {
  echo"
  <div class=\"container-fluid\">
  <div class=\"form-row align-items-left\">
   <div class=\"col-sm-3 text-left\">
      <image src=\"images/noBookImg.png\" alt='Picture of book: $Name'>
   </div>";
}

echo"
   <div class=\"col-sm-9 text-right\">
    <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> Price: $</label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">$price</label>
     </div>
    </div>

    <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> Course Num: </label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">".$book['class']."</label>
     </div> 
    </div>

    <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> Condition: </label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">".$book['condition']."</label>
     </div>
    </div>

    <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> Professor: </label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">".$book['prof']."</label>
     </div>
    </div>

    <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> Seller: </label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">".$user['FirstName']." ".$user['LastName']." | ".$user['Email']."@live.kutztown.edu</label>
     </div>
    </div>

   <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> ISBN-10: </label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">".$book['ISBN10']."</label>
     </div>
    </div>

   <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
      <label id=\"bgrfnt\"> ISBN-13: </label>
     </div>
     <div class=\"col-sm-3 text-left\">
      <label id=\"bgrfnt\">".$book['ISBN13']."</label>
     </div>
    </div>

   <div class=\"row align-items-right\">
     <div class=\"col-sm-3\">
     </div>
     <div class=\"col-sm-3 text-right\">
     </div>
     <div class=\"col-sm-3 text-left\">
      <input class=\"form-control btn\" type=\"submit\" value=\"Add to Wishlist\">      
     </div>
    </div>


<!-- 
//********************************************************
//THIS IS CODE I'M KEEPING IN CASE WE NEED TO REFERENCE IT
//********************************************************

  <div class=\"form-row align-items-center\">
    <div class=\"col\">
      <label id=\"bgrfnt\"> Price: $  $price </label>
      <br>
      <label id=\"bgrfnt\"> Course Num:  ".$book['class']." </label>
    </div>
  </div>


  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
      <label id=\"bgrfnt\">Course Number: </label>
    </div>
    <div class=\"col\">
      <label id=\"bgrfnt\">".$book['class']."</label>
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
      <label id=\"bgrfnt\">Condition: </label>
    </div>
    <div class=\"col\">
      <label id=\"bgrfnt\">".$book['condition']."</label>
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
      <label id=\"bgrfnt\">Professor: </label>
    </div>
    <div class=\"col\">
      <label id=\"bgrfnt\">".$book['prof']."</label>
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
      <label id=\"bgrfnt\">Seller: </label>
    </div>
    <div class=\"col\">
      <label id=\"bgrfnt\">".$user['FirstName']." ".$user['LastName']." | ".$user['Email']."@live.kutztown.edu</label>
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
      <label id=\"bgrfnt\">ISBN-10: </label>
    </div>
    <div class=\"col\">
      <label id=\"bgrfnt\">".$book['ISBN10']."</label>
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
      <label id=\"bgrfnt\">ISBN-13: </label>
    </div>
    <div class=\"col\">
      <label id=\"bgrfnt\">".$book['ISBN13']."</label>
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

  <div class=\"form-row align-items-center\">
    <div class=\"col text-right\">
    </div>
    <div class=\"col\">
      <input class=\"form-control btn\" type=\"submit\" value=\"Add to Wishlist\">
    </div>
    <div class=\"col text-left\">
    </div>
  </div>

-->

</div>
</div>
</div>

  </form>


    </body>
    </html>

";
?>