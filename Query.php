<?php

//
// Filename:	Query.php
// Purpose:	Handles searching based on various criteria including isbn, name, professor, seller
//

session_start();//Create/use session variable
require_once("header.php"); //Brings header onto page
require_once("dbconn.php"); //Brings in database functions
include_once 'functions.php';
db_connect(); // Connect to DB

echo "<html><head><link rel='stylesheet' type='text/css' href='HomeStyle.css'></head>"; //Connects stylesheet to page

echo "<body class=\"bg-base\">";

//Grab the search from GET array
$search = $_GET['SearchBar'] ;

//Int variable that says whether results have been found for query or not
$res = 0 ;
//An array to keep track of books already being displayed so we don't display the same thing twice
$tracker = array();
//Array to keep track of users already displayed 
$users = array() ;

$cquery = "SELECT * FROM Book WHERE ISBN_10 LIKE '%$search%' " ; //Look for books with ISBN10
$res += queryBooks($cquery, $tracker) ;

$cquery = "SELECT * FROM Book WHERE ISBN_13 LIKE '%$search%' " ;//Look for books with ISBN13
$res += queryBooks($cquery, $tracker) ;

$cquery = "SELECT * FROM Book WHERE Name LIKE '%$search%' " ;//Look for book by name
$res += queryBooks($cquery, $tracker) ;

$cquery = "SELECT * FROM Book WHERE Professor LIKE '%$search%' " ;//Look for book by professor name
$res += queryBooks($cquery, $tracker) ;

$cquery = "SELECT * FROM Book WHERE Class LIKE '%$search%' " ;//Look for book by class prefix/number
$res += queryBooks($cquery, $tracker) ;

$cquery = "SELECT * FROM Book WHERE UserID LIKE '%$search%' " ;//Look for books being sold by specific user
$res += queryBooks($cquery, $tracker) ;

$cquery = "SELECT * FROM User WHERE Email LIKE '%$search%' " ;//Look for user by email
$res += queryUsers($cquery, $users) ;

$cquery = "SELECT * FROM User WHERE FirstName LIKE '%$search%' " ;//Look for user by FirstName
$res += queryUsers($cquery, $users) ;

$cquery = "SELECT * FROM User WHERE LastName LIKE '%$search%' " ;//Look for user by LastName
$res += queryUsers($cquery, $users) ;

if(strpos($search, ' '))//If there is a space in the search, assume it could be a first and last name
{
    $search_ar = explode(' ', $search) ; //Make string into an array
    $cquery = "SELECT * FROM User WHERE LastName LIKE '%$search_ar[1]%' AND FirstName LIKE '%$search_ar[0]%' " ;//Look for user by Full Name
    $res += queryUsers($cquery, $users) ;
}

//If no results found, inform user
if(!$res)
{
    echo "<img class= 'Leanord' src='images/Sweat.png' ><h1 style='text-align: center ; font-size: 80px;'>This is kind of embarrassing but we couldn't find what you were looking for :(</h1><h2 style='text-align: center ; font-size: 65px ;'> Try double checking the spelling on your search</h2>" ;
}
echo "</body>";
echo "</html>" ;
?>
