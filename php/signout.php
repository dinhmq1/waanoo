<?php
session_start();
$_SESSION['signed_in'] = false;
unset($_SESSION['user_id']);
unset($_SESSION['privledges']);
unset($_SESSION['email']);
unset($_SESSION['fname']);

echo 1;
?>
