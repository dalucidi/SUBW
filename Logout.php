<?php
 
session_start();
session_unset();
session_destroy();//destroyes the log in session info

header("Location: index.php");//links back to the index page
exit();//forces page back to index



?>
