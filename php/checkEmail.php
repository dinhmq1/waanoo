<?php
require('cxn.php');

// tests email uniqueness through ajax
function find_email($email)
	{
	$cxn = $GLOBALS['cxn'];

	$query_email = "SELECT email FROM user_list WHERE email=?";

	$stm = $cxn->prepare($query_email);
	$stm->bind_param("s", $email);
	$stm->execute();
	$stm->bind_result($db_email);
	$stm->fetch();
	$stm->close();

	if ($db_email == $email)
		{
		return true;
		}
	else
		return false;
	}//end find_email()

$email = $_REQUEST['test_mail'];

// 1 is true, matched in DB. 0 is false, not found in DB
if(find_email($email)){
	echo 1;
	}
else
	echo 0;
?>
