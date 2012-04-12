<html>
	<head>
		
		<style>
		
		
		
		</style>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>
		$(document).ready(function() {
			$('#uploader').click(function() {
				
				var w = window.open("imgform.php", "Photo Uploader!", "width=500,height=300,left=200,top=100");
				
				var watchClose = setInterval(function() {
					try {
					    if (w.closed) {
					     clearTimeout(watchClose);
					     //Do something here...
		/*
			Steps:
				-have JS set a cookie if the image uploads OK/ or session variable???
				-IF OK:
				-get the link for the image and show a thumbnail here
				-then set the location of the image temporarily 
				-if the user posts the event, the PHP script, takes the location of the image
				copies it to a permenant location, resizes image: thumbnail, lrg
				then finally we store these links in the DB.
		
		*/
						console.log("img uploader window closed");
						$('#suc').append("window Closes <br>");
					    }
				    } catch (e) {}
				 }, 100);
				 
				});
		});
		</script>
	</head>
	<body>
	
	<p>
	upload a picture:
	</p>
	
	<p>
		<button id='uploader'>Do it!</button> (popup)
	</p>
	
	<p id="suc">
		
	</p>
	
	</body>
</html>
