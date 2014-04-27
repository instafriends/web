<h1><?php echo $bodyTitle; ?></h1>
<ul class="about">
	<li>
	<p><?php echo $description; ?></p>
	<a href="<?php echo URL::to('mobile/authorize');?>" class="buttonSignin">Sign in with Instagram</a>
	<br />
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://inst.me" data-text="InstaFriends - Who's not following you back on Instagram?" data-via="pererinha" data-size="large" data-hashtags="instafriends">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<br /><br />
		<a href="http://twitter.com/pererinha">
			Any ideas? Contact me @pererinha
		</a>
	</li>
</ul>
<ul id="dashboard">
	<li>
		<a href="<?php echo URL::to('mobile/about');?>"><?php echo $aboutTitle; ?></a>
	</li>
</ul>
<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/xaJoxYVgKzJRYBXtNNyYKA.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>