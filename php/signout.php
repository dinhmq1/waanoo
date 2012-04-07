<?php
session_start();
$_SESSION['signed_in'] = false;
$_SESSION['user_id'] = 0;

unset($_SESSION['privledges']);
unset($_SESSION['email']);
unset($_SESSION['fname']);

echo 1;
?>
