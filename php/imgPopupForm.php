<html>
	<head>
		<style>
			html, body {
				margin: 0;
			    padding: 0;					
				font-family: helvetica, verdana, arial, sans-serif;
				text-align: center;
				background-color: white;
				color: black;
				font-size: 20px;
				position: relative;
			    height: 100%;
			    width: 100%;
			    background: #EEEDEA url(../images/buttons/pattern_test.png) repeat;
				}
			
			.bigSubmit {
				-moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
				-webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
				box-shadow:inset 0px 1px 0px 0px #bbdaf7;
				background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #79bbff), color-stop(1, #378de5) );
				background:-moz-linear-gradient( center top, #79bbff 5%, #378de5 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#79bbff', endColorstr='#378de5');
				background-color:#79bbff;
				-moz-border-radius:2px;
				-webkit-border-radius:2px;
				border-radius:2px;
				display:inline-block;
				color:#ffffff;
				font-family:arial;
				font-size:16px;
				font-weight:bold;
				padding:28px 39px;
				text-decoration:none;
				text-shadow:1px 1px 0px #528ecc;
			}.bigSubmit:hover {
				background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #378de5), color-stop(1, #79bbff) );
				background:-moz-linear-gradient( center top, #378de5 5%, #79bbff 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#378de5', endColorstr='#79bbff');
				background-color:#378de5;
			}.bigSubmit:active {
				position:relative;
				top:1px;
			}
			
			
			.littleFile {
				-moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
				-webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
				box-shadow:inset 0px 1px 0px 0px #bbdaf7;
				background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #79bbff), color-stop(1, #378de5) );
				background:-moz-linear-gradient( center top, #79bbff 5%, #378de5 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#79bbff', endColorstr='#378de5');
				background-color:#79bbff;
				-moz-border-radius:2px;
				-webkit-border-radius:2px;
				border-radius:2px;
				display:inline-block;
				color:#ffffff;
				font-family:arial;
				font-size:14px;
				font-weight:bold;
				padding:2px 15px;
				text-decoration:none;
				text-shadow:1px 1px 0px #528ecc;
			}.littleFile:hover {
				background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #378de5), color-stop(1, #79bbff) );
				background:-moz-linear-gradient( center top, #378de5 5%, #79bbff 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#378de5', endColorstr='#79bbff');
				background-color:#378de5;
			}.littleFile:active {
				position:relative;
				top:1px;
			}
			
		</style>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>
		$(document).ready(function() {
			// http://www.codestore.net/store.nsf/unid/DOMM-4U4K9B
			// use window.opener.$().val();
			$('#close').hide();
			
			var lol = $('#postedOK').val();
			console.log("postedOK: " + lol);
			if(lol == 1) {
				var fn = $("#filename").val();
				window.opener.jQuery("#imgFileLocation").val(fn);
				window.opener.jQuery("#isThereImage").val("1");
				console.log("filename: " + fn);
				$('#close').show();
				}
			
			$('#close').click( function() {
				self.close();
				});
		});
		</script>
	</head>
	<body>
	
	<p> &nbsp;
	</p>
	
	<p><h2>Upload a photo for your event:</h2>
		<form method='POST' enctype="multipart/form-data"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>

			<tr>
				<td>
					<input type="radio" checked="checked" name="imgType" value="computer" />
				</td>
				<td>
					From your computer: 
				</td>
				<td>
					<input type="file" name="image" size="30">
				</td>
			</tr>
				<td>
					<input type="radio" name="imgType" value="web" />
				</td>
				<td>
					From the web (paste image URL): 
				</td>
				<td>
					<input type="text" name="imageURL" size="40">
				</td>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
				<input class='bigSubmit' type="submit" value="Submit!" name="Submit" />
				</td>
			</tr>
		</table>
		
		</form>
	</p>
	
	<span id="close" >
		<a href="#" class="littleFile">
			Close
		</a>
	</span>
	
	
	<p id="posted">
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
	
	
	function post_image($file, $from_web) {
		//get the original name of the file from the clients machine
		if($from_web) {
			$filename = $file;
		} else {
 			$filename = stripslashes($_FILES['image']['name']);
		}
		
		//get the extension of the file in a lower case format
  		$extension = getExtension($filename);
 		$extension = strtolower($extension);
		//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
		//otherwise we will do more tests

		//echo "filename:".$filename."<br />";
		echo "extension:".$extension."<br />";
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
 			if($from_web) {
 				$size = filesize($file);
 			} else {
 				$size=filesize($_FILES['image']['tmp_name']);
			}

			//compare the size with the maxim size we defined and print error if bigger
			if ($size > MAX_SIZE*1024) {
				echo '<h1>File over size limit (1.5 mb).</h1>';
				$errors=1;
				return false;
				}


			$image_name = time().make_random().'.'.$extension;

			//echo "image name var ".$image_name;
			//the new name will be containing the full path where will be stored (images folder)

			$newname = "../images/img_temp/".$image_name;
			
			$GLOBALS['img_name_only'] = $image_name;

			//we verify if the image has been uploaded, and print error instead
			if($from_web) {
				$copied = copy($file, $newname);
			} else {
				$copied = copy($_FILES['image']['tmp_name'], $newname);
			}
			
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
		}// end post image
	
	
	function process_image_link($file, $link) {
		//get extension:
		$linkSplit = explode(".",$link);
		$extension = $linkSplit[count($linkSplit) - 1];
		//print_r($linkSplit);
		//echo "File extension:".$extension;
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
			//print error message
 			echo '<h1>Unknown extension!</h1>';
 			//$errors=1;
 			return false;
 			}
		// naming
		$image_name = time().make_random().'.'.$extension;
		$newname = "../images/img_temp/".$image_name;
		$GLOBALS['newname'] = $newname;
		$GLOBALS['img_name_only'] = $image_name;
		
		// write file/. used GD Library
		// http://stackoverflow.com/questions/3693058/php-save-image-file#comment12414710_3693058
		// http://www.php.net/manual/en/function.imagecopy.php
		$image = imagecreatefromjpeg($link);
		//imagecopy($image, $image, 0, 140, 0, 0, imagesx($image), imagesy($image));
		imagejpeg($image, $newname);
		
		return true;
	}
	
	function process_image_submit() {
		if(isset($_POST['Submit'])) {
		 	//reads the name of the file the user submitted for uploading
		 	$image=$_FILES['image']['name'];
		 	//echo $image;
		 	//if it is not empty
		 	if($image) {
		 		if(post_image(NULL, false)) {
		 			return true;
		 		}
				return false;
			} else {
				echo "<h1>Photo not sent</h1>";
			}	return false;
		}
	}
	
	

	// main logic:
	
	if(isset($_POST['Submit'])) {
		
		// if web, download image and then resize and whatnot.
		if($_POST['imgType'] == "web") {
			// regex to test if we have a valid ULR:
			$url = $_POST['imageURL'];
			if(filter_var($url, FILTER_VALIDATE_URL)) {
				//echo "image url:".$url;
				$file = fopen($url, 'r')
					or die("<h1>Could not read file from URL.</h1>");
				
				if(process_image_link($file, $url) == true) {
					// Do same stuff as below:
					$imgnamepath = $GLOBALS['newname'];
					$imgnameonly = $GLOBALS['img_name_only'];
					echo "Posted Image successfully! <br />";
					
					echo "<img src='$imgnamepath' width='300'/>";
					
					echo "<input type='hidden' id='postedOK' value='1' />";
					echo "<input type='hidden' id='filename' value='$imgnameonly' />";
					//echo "<div id='posted'></div>";
				}else {
					// All of the error messages should be issued from the function
					echo "<input type='hidden' id='postedOK' value='0' />";
				}
			} else {
				echo "<h1>Not a valid URL";
				echo "<input type='hidden' id='postedOK' value='0' />";
			}
		// if not take upload and deal with it.
		} else {
			if(process_image_submit() == true) {
				$imgnamepath = $GLOBALS['newname'];
				$imgnameonly = $GLOBALS['img_name_only'];
				echo "Posted Image successfully! <br />";
				
				echo "<img src='$imgnamepath' width='300'/>";
				
				echo "<input type='hidden' id='postedOK' value='1' />";
				echo "<input type='hidden' id='filename' value='$imgnameonly' />";
				//echo "<div id='posted'></div>";
			}
			else 
			echo "<input type='hidden' id='postedOK' value='0' />";
		}
	}
	else 
		echo "<input type='hidden' id='postedOK' value='0' />";
	?>
	</p>
	</body>
</html>
