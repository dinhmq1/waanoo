<?php
session_start();
$logged = $_SESSION['signed_in'];
if($logged == true)
	echo 1;
else
	echo 0;
?>
