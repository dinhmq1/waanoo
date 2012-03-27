<?php
require("cxn.php");
// backend of the AJAX signin.js login script.

/*
 * user_email: email,
 * user_password: password
 */
$errors = "";
$GLOBALS['errors'] = $errors;

function verify_password($password, $email){
	$errors = $GLOBALS['errors'];

	if(strlen($password) >= 8){
		$cxn = $GLOBALS['cxn'];

		$query_psswd = "SELECT password FROM user_list WHERE email=? ";
		$stm = $cxn->prepare($query_psswd);
		$stm->bind_param("s", $email);
		$stm->execute();
		$stm->bind_result($hashed);
		$stm->fetch();

		$hashed_new = sha1($password);

		if ($hashed_new == $hashed){
			$stm->close();
			return True;
			}
		else{
			$stm->close();	
			return False;
			}
		}
	else{
		$errors .= "password field empty";
		$GLOBALS['errors'] = $errors;
		return False;
		}

	} //end verify psswd


function find_email($email){
	$cxn = $GLOBALS['cxn'];
	
	//this needs to be prepared!
	$query_email = "SELECT email FROM user_list WHERE email=?";

	$stm = $cxn->prepare($query_email);
	$stm->bind_param("s", $email);
	$stm->execute();
	$stm->bind_result($db_email);
	$stm->fetch();

	if ($db_email == $email){
		$stm->close();
		return True;
		}
	else{
		$stm->close();
		return False;
		}
	}//end find_email()




function verify_email($email){
	if(!empty($email)){
	
		$errors = $GLOBALS['errors'];
		//other p.m 
		// preg_match("#[^0-9 a-z\.-_]+@[0-9 a-z\.-_]+$#i", $email)
		if (preg_match("/^\w[[:alnum:]\.-_]+@[[:alnum:]\.-_]+\.[[:alnum:]]{2,4}$/i", $email) and filter_var($email, FILTER_VALIDATE_EMAIL))
			{
			$cxn = $GLOBALS['cxn'];
			//$email = mysql_real_escape_string($cxn, $email);	
			if(find_email($email)) { //checks to see if email is in system
				return $email;
				}
			else{
				$errors .= "Oh No couldn't find $email in our system!<br>";
				$GLOBALS['errors'] = $errors;
				return false;
				}
			}//end if RE validates
		else{
			$errors .= "Oh No, that email was an incorrect format, try again <br>";
			$GLOBALS['errors'] = $errors;
			return false;
			}	
		}//end if not empty clause
	else{
		$errors .= "Oh No, the email was empty, try again<br>";
		$GLOBALS['errors'] = $errors;
		return false;
		}

	} // end verify email. 


function main_validation($email, $password){
	$errors = $GLOBALS['errors'];
	$email2 = verify_email($email);
	if($email2 != false){
		if(verify_password($password, $email2)){
	
			$cxn = $GLOBALS['cxn'];
			$query_email = "SELECT user_id, first_name FROM user_list WHERE email=?";
	
			$stm2 = $cxn->prepare($query_email);
			$stm2->bind_param("s", $email2);
			$stm2->execute();
			$stm2->bind_result($user_id, $first_name);
			$stm2->fetch();
			$stm2->close();
			
			
			$last_ip = $_SERVER['REMOTE_ADDR'];
			//pulled out the one in the table, so we don't need to use prepareds again.
			$query_login_time = "UPDATE user_list SET last_login=NOW(), last_ip='$last_ip' WHERE user_id='$user_id' ";
			$res = mysqli_query($cxn,$query_login_time)
				or die ("error: ".mysqli_error($cxn));
			
			/// set session infos
			$_SESSION['signed_in'] = true;
			$_SESSION['email'] = $email2;
			$_SESSION['fname'] = $first_name;
			$_SESSION['user_id'] = $user_id;
			//$_SESSION['city'] = $city;
			//$_SESSION['state'] = $state;
			
			
			$arr = array("user_id" => $user_id, "name" => $first_name);
			return $arr;
			}
		else{
			$errors .= "password did not match our records";
			$GLOBALS['errors'] = $errors;
			$_SESSION['signed_in'] = false;
			return array("user_id" => 0, "name" => "failure");
			}
		}
	else{
		$errors .= "email was not found";
		$GLOBALS['errors'] = $errors;
		$_SESSION['signed_in'] = false;
		return array("user_id" => 0, "name" => "failure");
		}
	}//end main function!


$email = $_REQUEST['user_email'];
$pass = $_REQUEST['user_password'];

$arr = main_validation($email, $pass);
extract($arr);

$errors = $GLOBALS['errors'];
$status = 1;
if(strlen($errors) > 1) {
	$status = 0;
	}

$arr_to_encode = array("status" => $status, "message" => $errors, "name" => $name, "id" => $user_id);
echo json_encode($arr_to_encode);

// IF GOOD, START SESSION

?>
