<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Saira:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
</head>
<body>  
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

<?php
  include_once 'dbconn.php';//Include useful DB functions
  db_connect();//Connect to DB
  if(isset($_SESSION['userID']) && $_SESSION['userID'] != FALSE)// If user is logged in
  {
    $user = getUserById($_SESSION['userID']) ;//Get user information from DB
    echo"
    <!DOCTYPE html>
    <html>
    <head>
    	<link rel='stylesheet' type='text/css' href='HomeStyle.css'>
      <link rel='icon' type='image/png' sizes='16x16' href='images/favicon2.png'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
    	
    </head>
    <!-- <body> -->
    	<a class='header' href='index.php'><img class='Leanord' src='images/worm.png'></img></a>
    	<ul>
    		<li><a href='index.php'>Home</a></li>
    		<li><a href='aboutUs.php'>About Us</a></li>
    		<li><a href='aboutSUBW.php'>About SUBW</a></li>

    		<form method='get' action='Query.php'><li style='border-right: none;'><input type='text' id='searchBar' name='SearchBar' class='searchBar' placeholder='Book/ISBN/Professor/KU Username' onkeydown='searchDB()'></li></form>

                <li style='float: right;'><a href='newBookListing.php'>List Book</a></li>
    		<li style='float: right;'><a href='Logout.php'>Logout</a></li>
    		<li style='float: right; border-right: none;'><a href='userSettings.php'>Hello, ".$user['FirstName']."</a></li>
    		<div id='liveSearch'></div>
    	</ul>
    </html>

    <script type='text/javascript'>
    	function SearchDB(str)
    	{
    		var input ;
    		if(str.length == 0)
    		{
    			document.getElementById('liveSearch').innerHTML='' ;
    			document.getElementById('livesearch').style.border='0px';
        		return;
    		}

    		if (window.XMLHttpRequest) 
    		{
        		// code for IE7+, Firefox, Chrome, Opera, Safari
        		xmlhttp=new XMLHttpRequest();
      		} 
      		else 
      		{  // code for IE6, IE5
        		xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
      		}
      		xmlhttp.onreadystatechange=function() 
      		{
        		if (this.readyState==4 && this.status==200) 
        		{
          			document.getElementById('livesearch').innerHTML=this.responseText;
          			document.getElementById('livesearch').style.border='1px solid #A5ACB2';
        		}
      		}
      xmlhttp.open('GET','livesearch.php?q='+str,true);
      xmlhttp.send();
    	}
    </script> ";
  }
  else //If user is not logged in
  {
    echo"
    <!DOCTYPE html>
    <html>
    <head>
      <link rel='stylesheet' type='text/css' href='HomeStyle.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
      
    </head>
    <!-- <body> -->
      <a class='header' href='index.php'><img class='Leanord' src='images/worm.png'></img></a>
      <ul>
        <li><a href='index.php'>Home</a></li>
        <li><a href='aboutUs.php'>About Us</a></li>
        <li><a href='aboutSUBW.php'>About SUBW</a></li>

        <form method='get' action='Query.php'><li style='border-right: none;'><input type='text' id='searchBar' name='SearchBar' class='searchBar' placeholder='Book/ISBN/Professor/KU Username' onkeydown='searchDB()'></li></form>

        <li style='float: right;'><a href='sign_in.php'>Login</a></li>
        <li style='float: right; border-right: none;'><a href='create_account.php'>Sign Up</a></li>
        <div id='liveSearch'></div>
      </ul>
    <!-- </body> -->
    </html>

    <script type='text/javascript'>
      function SearchDB(str)
      {
        var input ;
        if(str.length == 0)
        {
          document.getElementById('liveSearch').innerHTML='' ;
          document.getElementById('livesearch').style.border='0px';
            return;
        }

        if (window.XMLHttpRequest) 
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
          } 
          else 
          {  // code for IE6, IE5
            xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
          }
          xmlhttp.onreadystatechange=function() 
          {
            if (this.readyState==4 && this.status==200) 
            {
                document.getElementById('livesearch').innerHTML=this.responseText;
                document.getElementById('livesearch').style.border='1px solid #A5ACB2';
            }
          }
      xmlhttp.open('GET','livesearch.php?q='+str,true);
      xmlhttp.send();
      }
    </script> ";
  }
?>
