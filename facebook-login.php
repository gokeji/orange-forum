<?php


?>
	<div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      // init the FB JS SDK
      FB.init({
        appId      : '276849219022102',
        channelUrl : '//orange-forum.kaijiangao.com/channel.html',
        status     : true, 
        cookie     : true,
        xfbml      : true,
        oauth      : true
      });
      
       FB.Event.subscribe('auth.login', function(response) {
				  window.location.reload();
		   });
		   FB.Event.subscribe('auth.logout', function(response) {
				  window.location.reload();
		   });
    };
    // Load the SDK asynchronously
    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "http://connect.facebook.net/en_US/all.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));
    </script>
		<div class="content">
			<?php if(!$user): ?>			
			<div class="bottom"><div class="fb-login-button">Login with Facebook</div></div>
			<?php endif; ?>	
			
			<?php if ($user): ?>
				<img class="prof_pic" src="https://graph.facebook.com/<?php echo $user; ?>/picture">
				<div><?php echo $user_profile['name']; ?></div>
				<div class="bottom"><div class="logout"><a href="<?php echo $logoutUrl; ?>">Logout</a></div></div>
			<?php endif ?>
		</div>
<?php ?>