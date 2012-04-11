<html>
	<head>
		
		<style>
		
		
		
		</style>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>
		
		
		
		</script>
	</head>
	<body>
	
	<p>
	upload a picture:
	</p>
	
	<p>
		<form method='POST' enctype="multipart/form-data"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
		Upload a photo for your event:<input type="file" name="image"><br>
		<input type="submit" value="Submit!" name="Submit" />
		</form>
	</p>
	
	
	<p>
	<?php
		//define a maxim size for the uploaded images in Kb
	define ("MAX_SIZE","1500"); 
	
	
	function make_random($lenth = 30) {
		    // makes a random alpha numeric string of a given lenth
	    $aZ09 = array_merge(range('A', 'Z'), range('a', 'z'),range(0, 9));
	    $out ='';
	    for($c=0;$c < $lenth;$c++) {
	       $out .= $aZ09[mt_rand(0,count($aZ09)-1)];
			}
	    return $out;
		}
	
	//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
	function getExtension($str) {
		 $i = strrpos($str,".");
		 if (!$i) { 
			return ""; 
			}
		 $l = strlen($str) - $i;
		 $ext = substr($str,$i+1,$l);
		 return $ext;
		}
	
	//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
	//and it will be changed to 1 if an errro occures.  
	//If the error occures the file will not be uploaded.
	
	 
	function post_image() {
		//$errors=0;
		//checks if the form has been submitted
		if(isset($_POST['Submit'])) {
		 	//reads the name of the file the user submitted for uploading
		 	$image=$_FILES['image']['name'];
		 	//echo $image;
		 	//if it is not empty
		 	if($image) {
				//get the original name of the file from the clients machine
		 		$filename = stripslashes($_FILES['image']['name']);
				//get the extension of the file in a lower case format
		  		$extension = getExtension($filename);
		 		$extension = strtolower($extension);
				//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
				//otherwise we will do more tests
	
				//echo $filename;
				//echo $extension;
		 		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					//print error message
		 			echo '<h1>Unknown extension!</h1>';
		 			//$errors=1;
		 			return false;
		 			}
		 		else {
					//get the size of the image in bytes
		 			//$_FILES['image']['tmp_name'] is the temporary filename of the file
		 			//in which the uploaded file was stored on the server
		 			$size=filesize($_FILES['image']['tmp_name']);
	
					//compare the size with the maxim size we defined and print error if bigger
					if ($size > MAX_SIZE*1024) {
						echo '<h1>File over size limit (1.5 mb).</h1>';
						$errors=1;
						return false;
						}
	
	
					$image_name = time().make_random().'.'.$extension;
	
					//echo "image name var ".$image_name;
					//the new name will be containing the full path where will be stored (images folder)
	
					$newname = "img_db/".$image_name;
	
					//we verify if the image has been uploaded, and print error instead
					$copied = copy($_FILES['image']['tmp_name'], $newname);
					if (!$copied) {
						echo '<h1>Copy unsuccessfull!</h1>';
						$errors=1;
						return false;
	
						}
					else {
						//echo "accepted";
						$GLOBALS['newname'] = $newname;
						return true;
						}
				}//else for extension known.
			}//make sure image is not empty
		}//recheck is POST isset
	}//end function POSTIMAGE


	// main logic:
	
	if(isset($_POST['Submit'])) {
		if(post_image() == True) {
			$imgname = $GLOBALS['newname'];
			echo "Posted Image successfully! <br />";
			echo "<img src='$imgname' width='300'/>";
	
		}
	}
	?>
	</p>
	</body>
</html>
