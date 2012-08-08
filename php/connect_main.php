<?php

$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "Waanoo";
$cxn = mysqli_connect($host,$user,$password,$dbname)
                        or die ("lol fail");

$GLOBALS['cxn'] = $cxn;
?>