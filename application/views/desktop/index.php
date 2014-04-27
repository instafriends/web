<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>InstaFriends</title>
    <meta name="description" content="Who's not following you back on Instagram? The easiest way to manage your friends at Instagram.">
    <meta name="author" content="Daniel Camargo @pererinha">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le styles -->
	<?php echo HTML::style('css/bootstrap.min.css');?>
	<?php echo HTML::style('css/docs.css');?>
    <style type="text/css">
	  html, body{background:#F5F5F5;}
      body {
        padding-top: 60px;
      }
    </style>
 	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<?php echo HTML::script('js/desktop/app.js');?>
	<?php echo HTML::script('js/desktop/bootstrap-modal.js');?>
	<?php echo HTML::script('js/desktop/google-code-prettify/prettify.js');?>
	<script>$(function () { prettyPrint() })</script>
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-9647027-4']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
  </head>
  <body>
    <header class="jumbotron subhead" id="overview">
	    
    </header>
    <div class="container">
		<section id="">
			<div class="page-header">
				<h1 style="font-size:60px;margin:15px;">InstaFriends</h1>
			</div>
			<div>
				<h3>
					Who's not following you back on Instagram? The easiest way to manage your friends at Instagram.
				</h3>
				<div style="margin:25px 10px;">
					<a class="btn large success" href="<?php echo $authorizeUrl;?>">Sign in with Instagram</a>
				</div>
				<div>
					* If you want to log in with another Instagram account consider make logoff of Instragram by clicking here:
					<a href="https://instagr.am/accounts/logout/">https://instagr.am/accounts/logout/</a>
					<br/><br/>
				</div>
				<div style="width:130px;text-align:left">
					<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://inst.me" data-text="InstaFriends - Who's not following you back on Instagram?" data-via="pererinha" data-size="large" data-hashtags="instafriends">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					<!-- Place this render call where appropriate -->
					<script type="text/javascript">
					  (function() {
					    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					    po.src = 'https://apis.google.com/js/plusone.js';
					    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>
					<!-- Place this tag where you want the +1 button to render -->
					<g:plusone annotation="inline"></g:plusone>
				</div>
				<iframe src="http://www.facebook.com/plugins/like.php?href=http://danielcamargo.com/InstaFriends/"
				        scrolling="no" frameborder="0"
				        style="border:none; width:450px; height:80px"></iframe>
			</div>
		</section>
      <footer>
		<div class="row">
			<div class="span14">
        		Any ideas? Contact me <a href="http://twitter.com/pererinha" target="_blank">@pererinha</a>
			</div>
			<div class="span2">
				<a href="#" onclick="showLog();">Log</a> | 
				<a href="#" onclick="showAbout();">About</a> 
			</div>
		</div>
		<br />
      </footer>
		<div id="modal-from-dom" class="modal hide fade">
			<div class="modal-header">
				<a href="#" class="close">&times;</a>
				<h4>About</h4>
			</div>
			<div class="modal-body">
				<h5>I build it for two reasons:</h5>
				<ol>
					<li>First of all I wanted to know who was following me at Instagram and also who I was following but were not following me back.</li>
					<li>The second one, I was willing to code something and I also was interested in learn this php framework named Laravel.</li>
				</ol>
				<h5>Thanks to <small>The people/softwares who made it possible</small></h5>
				<ul>
					<li>My friend <a href="http://twitter.com/cleberwsantos" target="_blank">@cleberwsantos</a> who shows me the Laravel and Bootstrap :)</li>
					<li><a href="http://instagram.com" target="_blank">Instagram</a></li>
					<li><a href="https://github.com/macuenca/Instagram-PHP-API/" target="_blank">Instagram-PHP-API</a></li>
					<li><a href="http://twitter.github.com/bootstrap/" target="_blank">Bootstrap, from Twitter</a></li>
					<li><a href="http://laravel.com/" target="_blank">Laravel - A Clean & Classy PHP Framework</a></li>
				</ul>
				<p>Instafriends isn't an official Instagram service.</p>
			</div>
		</div>
		<div id="modal-from-dom-log" class="modal hide fade">
			<div class="modal-header">
				<a href="#" class="close">&times;</a>
				<h4>Log</h4>
			</div>
			<div class="modal-body">
				<h5>History:</h5>
				<ul>
					<li>Nov 28, 2011 - Mobile version.</li>
					<li>Nov 27, 2011 - Follow/Unfollow in just one click (thanks to @puppypalace user).</li>
					<li>Nov 2, 2011 - Release the first and beta version.</li>
				</ul>
			</div>
		</div>
    </div> <!-- /container -->
  </body>
<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/xaJoxYVgKzJRYBXtNNyYKA.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>
</html>
