function startUpload(){
    document.getElementById('f1_upload_process').style.visibility = 'visible';
    return true;
	}

function stopUpload(success){
      var result = '';
      if (success == 1){
         document.getElementById('result').innerHTML =
           '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         document.getElementById('result').innerHTML = 
           '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      return true;   
	}
