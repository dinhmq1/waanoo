<?php

session_start();
require('cxn.php');
/* FROM FRONT:
 * 
 * 						email_new: email, 
						password1: pass1,
						password2: pass2,
						first_name: fname,
						last_name: lname,
						user_sex: sex
*/

$email = $_REQUEST['email'];
$password1 = $_REQUEST['pass1'];
$password2 = $_REQUEST['pass2'];
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$sex = $_REQUEST['sex'];

$error = "";
$num_errors = 0;

function verify_password_encode($password1, $password2, $username)
	{
	if (!empty($password1) or !empty($password2))
		{
		if ($password1 == $password2)
			{
			 if (strlen($password1) >= 8)
				{
				if ( !($username == $password1)) //username cannot be psswd
					{
						$cxn = $GLOBALS['cxn'];
					 //$password1 = mysql_real_escape_string($cxn, $password1);
					 $hashed = sha1($password1);
					 return $hashed;
					}
				else
					{
					$error .=  "Your password cannot be the same as your username!";
					$num_errors .= 1;
					}
				}
			else
				{
				$error .=  "password must be 8 letters, numbers, or special charachters";
				$num_errors .= 1;
				} //end length clause
			} 
		else
			{
			$error .=  "passwords do not match";
			$num_errors .= 1;
			} //end matching clause. 
		}
	else
		{
		$error .=  "password field empty";
		$num_errors .= 1;
		}

	} //end verify psswd

function verify_state($state)
	{
		if(!empty($state))
			{
			$match = 0;
			$all_states = array("AK","AL","AR","AS","AZ","CA","CO","CT","DC","DE","FL","GA","GU","HI","IA","ID","IL","IN","KS","KY","LA","MA","MD","ME","MH","MI","MN","MO","MS","MT","NC","ND","NE","NH","NJ","NM","NV","NY","OH","OK","OR","PA","PR","PW","RI","SC","SD","TN","TX","UT","VA","VI","VT","WA","WI","WV","WY");
			foreach($all_states as $test)
				{
					if($test == $state)
						{
							$match++;
						}
				}
			if($match == 1)
				{
					$error .=  "error with state";
				}
			else
				{
				$error .=  "error with state";
				
				}
			}
		else
			{
				$error .=  "error with state";
			}

	}

function verify_city($city)
	{
		if(!empty($city) and (strlen($city) <= 25))
			{
				$city = preg_replace("#[^a-z -]#", "", $city);
				return $city;
			}
		else
			{
			$error .=  "error with city";
			}

	}


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
		return True;
		}
	else
		{
		return False;
		}
	}//end find_email()

function find_username($username)
	{
	$cxn = $GLOBALS['cxn'];

	$query_usern = "SELECT username FROM user_list WHERE username=?";
	$stm = $cxn->prepare($query_usern);
	$stm->bind_param("s", $username);
	$stm->execute();
	$stm->bind_result($db_username);
	$stm->fetch();
	$stm->close();

	if ($db_username == $username)
		{
		return True;
		}
	else
		{
		return False;
		}
	}//end find_email()



function verify_email($email)
	{
	if (!empty($email))
		{	
		if (preg_match("/^\w[[:alnum:]\.-_]+@[[:alnum:]\.-_]+\.[[:alnum:]]{2,4}$/i", $email) and filter_var($email, FILTER_VALIDATE_EMAIL))
			{
			$cxn = $GLOBALS['cxn'];
			//$email = mysql_real_escape_string($cxn, $email);	
			if (!find_email($email))  //email should NOT be in system already for signup!
				{
				return $email;
				}
			else
				{
				$error .=  "Oh No, $email is taken already! <br>";
				$num_errors .= 1;
				}
			}//end if RE validates
		else
			{
			$error .=  "Oh No, that email was an incorrect format, try again <br>";
			$num_errors .= 1;
			}	

		}//end if not empty clause
	else
		{
		$error .=  "Oh No, the email was empty, try again<br>";
		$num_errors .= 1;
		}

	} // end verify email. 



function validate_sex($sex){
	if($sex == "M" or $sex == "F")
		return true;
	else
		return false;
	}

function validate_names($name){
	if(preg_match("#[a-zA-Z -]#i", $name)
		return true;
	else 
		return false;
	}


function main_validation($email, $password1, $password2, $fname, $lname, $sex, $num_errors);
	{

	$email = verify_email($email);
	//$username = validate_username($username);
	$password = verify_password_encode($password1, $password2, $email);
	$state = verify_state($state);
	$city = verify_city($city);

	
	if($num_errors <= 0){
	
		$cxn = $GLOBALS['cxn'];
	
		$query = "INSERT INTO user_list (email,username,password,city,state,date_added) 
				VALUES(?, ?, ?, ?, ?, NOW())";
		$stm2 = $cxn->prepare($query);
		$stm2->bind_param("sssss", $email, $username, $password, $city, $state);
		$stm2->execute();
		$stm2->close();
	
		//$_SESSION['city'] = $city;
		//$_SESSION['state'] = $state;
		$_SESSION['email'] = $email;
		$_SESSION['username'] = $username;
		}
	else{
		$error .= "User was not signed up";
		}
	}//end main function!

main_validation($email, $password1, $password2, $fname, $lname, $sex, $num_errors);

if($error == "")
	$status = 0;
else
	$status = 1;
	
$name = $fname." ".$lname;
$arr = array("status" => $status, "errors" => $error, "name" => $name);

$json = json_encode($arr);
echo $json;
?>
