<?php  

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$fp = fopen('events.txt', 'a+'); 
fwrite($fp,"\n". $_GET["date"] .''. $_GET["time"] .''. $_GET["title"] .''. $_GET["description"]);  
fclose($fp);
?>  