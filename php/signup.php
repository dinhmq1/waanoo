<?php
session_start();
require('cxn.php');
/* FROM FRONT:
 * 
	email_new: email, 
	password1: pass1,
	password2: pass2,
	first_name: fname,
	last_name: lname,
	user_sex: sex
*/

$email = $_REQUEST['email_new'];
$password1 = $_REQUEST['password1'];
$password2 = $_REQUEST['password2'];
$fname = $_REQUEST['first_name'];
$lname = $_REQUEST['last_name'];
$sex = $_REQUEST['user_sex'];

// DONT NEED ERROR MESSAGES. DOING FRONT END VERIFICATION

function verify_password($password1, $password2, $username){
	if (!empty($password1) or !empty($password2)){
		if ($password1 == $password2){
			 if (strlen($password1) >= 8){
				if ( !($username == $password1)) { //username cannot be psswd
					return true;
					}
				else
					return false;
				}
			else
				return false;
			} 
		else
			return false;
		}
	else
		return false;
	} //end verify psswd



function verify_state($state){
	if(!empty($state)){
		$match = 0;
		$all_states = array("AK","AL","AR","AS","AZ","CA","CO","CT","DC","DE","FL","GA","GU","HI","IA","ID","IL","IN","KS","KY","LA","MA","MD","ME","MH","MI","MN","MO","MS","MT","NC","ND","NE","NH","NJ","NM","NV","NY","OH","OK","OR","PA","PR","PW","RI","SC","SD","TN","TX","UT","VA","VI","VT","WA","WI","WV","WY");
		foreach($all_states as $test){
			if($test == $state){
					$match++;
				}
			}
		if($match == 1){
				return true;
			}
		else{ 
			return false;
			}
		}
	else{
		return false;
		}
	}


function verify_city($city){
	if(!empty($city) and (strlen($city) <= 25)){
		return true;
		}
	else{
		return false;
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


function find_username($username){
	$cxn = $GLOBALS['cxn'];

	$query_usern = "SELECT username FROM user_list WHERE username=?";
	$stm = $cxn->prepare($query_usern);
	$stm->bind_param("s", $username);
	$stm->execute();
	$stm->bind_result($db_username);
	$stm->fetch();
	$stm->close();

	if ($db_username == $username){
		return True;
		}
	else{
		return False;
		}
	}//end find_email()



function verify_email($email){
	if (!empty($email)){	
		if (preg_match("/^\w[[:alnum:]\.-_]+@[[:alnum:]\.-_]+\.[[:alnum:]]{2,4}$/i", $email) and filter_var($email, FILTER_VALIDATE_EMAIL)){
			if (!find_email($email)){
				return true;
				}
			else
				return false;
			}//end if RE validates
		else
			return false;	
		}//end if not empty clause
	else
		return false;
	} // end verify email. 



function validate_sex($sex){
	if($sex == "M" or $sex == "F")
		return true;
	else
		return false;
	}

function validate_names($name){
	if(preg_match("#[a-zA-Z0-9 -_]#i", $name))
		return true;
	else 
		return false;
	}
	
function get_user_id($email){
	$cxn = $GLOBALS['cxn'];
	$qry = "SELECT user_id FROM user_list WHERE email = ?";
	$stm = $cxn->prepare($qry);
	$stm->bind_param("s", $email);
	$stm->execute();
	$stm->bind_result($uid);
	$stm->fetch();
	$stm->close();
	
	return $uid;
	}


	


/********** The main function **********************************************************/

function main_validation($email, $password1, $password2, $fname, $lname, $sex){
	if(verify_email($email) == true and verify_password($password1, $password2, $lname) == true and validate_sex($sex) == true){
	//$username = validate_username($username);
		$password = sha1($password1);
		$cxn = $GLOBALS['cxn'];
		$last_ip = $_SERVER['REMOTE_ADDR'];
		$priv = "user";
		
		$query = "INSERT INTO user_list (email, password, first_name, last_name, date_added, last_login, last_ip, privlege_level, sex) 
				VALUES(?, ?, ?, ?, NOW(), NOW(), ?, ?, ?)";
		$stm2 = $cxn->prepare($query);
		//echo $email."...".$password."...".$fname."...".$lname."...".$last_ip."...".$priv."...".$sex;
		$stm2->bind_param("sssssss", $email, $password, $fname, $lname, $last_ip, $priv, $sex);
		$stm2->execute();
		$stm2->close();
		
		// pull user ID for session data
		$uid = get_user_id($email);
	
		//// set session infos
		$_SESSION['signed_in'] = true;
		$_SESSION['fname'] = $fname;
		$_SESSION['email'] = $email;
		$_SESSION['user_id'] = $uid;
		$_SESSION['privleges'] = "user";
		//$_SESSION['city'] = $city;
		//$_SESSION['state'] = $state;
		
		return true;
		}
	else{
		$_SESSION['signed_in'] = false;
		return false;
		}
	}//end main function!


$ret = main_validation($email, $password1, $password2, $fname, $lname, $sex);

// if we get a true, then everything went ok
if($ret == true)
	$status = 1;
else
	$status = 0;
	
$arr = array("status" => $status, "name" => $fname);

$json = json_encode($arr);
echo $json;
?>
