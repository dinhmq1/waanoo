// facebook SDK from facebook.com:

// main thing to establish cnx
	window.fbAsyncInit = function() {
          FB.init({
            appId      : '203207116448613',
            status     : true, 
            cookie     : true,
            xfbml      : true,
            oauth      : true,
          });
        };
        (function(d){
           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           d.getElementsByTagName('head')[0].appendChild(js);
         }(document));

// need to check if user is already signed in
	
function displayUser(user) {
       var userName = document.getElementById('userName');
       var greetingText = document.createTextNode('Greetings, '
         + user.name + '.');
   userName.appendChild(greetingText);
     }
