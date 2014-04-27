<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>InstaFriends</title>
    <meta name="description" content="The easiest way to manage your friends at Instagram.">
    <meta name="author" content="Daniel Camargo @pererinha">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le styles -->
	<?php echo HTML::style('css/bootstrap.min.css');?>
	<?php echo HTML::style('css/docs.css');?>
	<?php echo HTML::style('css/ui.totop.css');?>
    <style type="text/css">
      body {
        padding-top: 60px;
		background:#F5F5F5;
      }
		a.user{
			width:80px;
			height:80px;
			text-decoration:none;
		}
		a.user div{
			display:none;
		}
		a.user:hover div.options{
			display:block;
			background:#000;
			width:80px;
			height:80px;
			opacity:0.7;
			filter:alpha(opacity=70); /* For IE8 and earlier */
		}
		a.user:hover img{
			margin-top:-80px;
		}
		a.user:hover .options.img{
			margin-top:0;
		}
		a.user span.info, a.user span.unfollow, a.user span.follow{
			display:block;
			color:#FFF;
			font-size:11px;
			cursor:pointer;
			padding:2px 5px;
			height:20px;
		}
			 a.user span.unfollow{
				color:red;
			}
			a.user span.follow{
				color:green;
			}
			a.user span.wait{
				display:block;
				background:url(<?php echo URL::to_asset('img/desktop/loading-data.gif');?>);
				height:16px;
				width:16px;
			}
    </style>
 	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<?php echo HTML::script('js/desktop/app.js');?>
	<?php echo HTML::script('js/desktop/bootstrap-modal.js');?>
	<?php echo HTML::script('js/desktop/bootstrap-scrollspy.js');?>
	<?php echo HTML::script('js/desktop/bootstrap-twipsy.js');?>
	<?php echo HTML::script('js/desktop/jquery.ui.totop.js');?>
	<?php echo HTML::script('js/desktop/easing.js');?>
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
	    <div class="topbar">
	      <div class="fill">
	        <div class="container">
	          <a class="brand" href="#">InstaFriends</a>
	          <ul class="nav">
	            <li><a href="#friends">Friends</a></li>
	            <li><a href="#fans">Fans</a></li>
				<li><a href="#following">Following</a></li>
				<li><a href="<?php echo URL::to('desktop/logout');?>">Logout</a></li>
	          </ul>
	        </div>
	      </div>
	    </div>
	<div class="inner">
        <div class="container">
          <h1><em><?php echo $user['info']->data->username;?></em></h1>
			<div class="row">
			<div class="span3">
				<img src="<?php echo $user['info']->data->profile_picture; ?>" />
			</div>
			<div class="span12">
				<dl>
					<?php if( isset( $user['info']->data->full_name ) && !empty($user['info']->data->full_name) ) { ?>
					<dt>Name</dt>
					<dd><?php echo $user['info']->data->full_name; ?></dd>
					<?php } ?>

					<?php if( isset( $user['info']->data->bio ) && !empty($user['info']->data->bio) ) { ?>
					<dt>Bio</dt>
					<dd><?php echo $user['info']->data->bio; ?></dd>
					<?php } ?>

					<?php if( isset( $user['info']->data->website ) && !empty($user['info']->data->website) ) { ?>
					<dt>Website</dt>
					<dd><a href="<?php echo $user['info']->data->website; ?>" target="_blank"><?php echo $user['info']->data->website; ?></a></dd>
					<?php } ?>
				</dl>
				<div class="row">
					<div class="span2">
						<strong><?php echo $user['info']->data->counts->media; ?></strong>
						<br/>
						<small>photos</small>
					</div>
					<div class="span2">
						<strong><?php echo $user['info']->data->counts->follows; ?></strong>
						<br/>
						<small>follows</small>
					</div>
					<div class="span2">
						<strong><?php echo $user['info']->data->counts->followed_by; ?></strong>
						<br/>
						<small>followers</small>
					</div>
					<div class="span2">
						<strong id="data-fans"><img src="<?php echo URL::to_asset('img/desktop/loading-data.gif');?>"></strong>
						<br/>
						<small>fans</small>
					</div>
					<div class="span2">
						<strong id="data-friends"><img src="<?php echo URL::to_asset('img/desktop/loading-data.gif');?>"></strong>
						<br/>
						<small>friends</small>
					</div>
					<div class="span2">
						<strong id="data-following"><img src="<?php echo URL::to_asset('img/desktop/loading-data.gif');?>"></strong>
						<br/>
						<small>following</small>
					</div>
				</div>
				<br />		
			</div>
		</div>
		</div>
        </div><!-- /container -->
    </header>
    <div class="container">
		<div id="content">
			<center style="padding:50px 0;"><img src="<?php echo URL::to_asset('img/desktop/loading.gif');?>"><br/><br/> Loading data</center>
		</div>
		<div id="modal-from-dom" class="modal hide fade">
			<div class="modal-body">
				<center><img src="<?php echo URL::to_asset('img/desktop/loading.gif');?>"></center>
			</div>
		</div>
      <footer>
		<div class="row">
			<div class="span12">
        		Any ideas? Contact me <a href="http://twitter.com/pererinha" target="_blank">@pererinha</a>
			</div>
			<div class="span4" id="followMeButton">
				<button class="btn danger" onclick="followMe(3314628)">Follow me at instagram ;)</button>
			</div>
		</div>
		<br />
      </footer>
    </div> <!-- /container -->
	<script>
	var loading = '<img src="<?php echo URL::to_asset('img/desktop/loading.gif');?>">';
	var loadingSmall = '<img src="<?php echo URL::to_asset('img/desktoploading-data.gif');?>">';
	var userInfoUrl = '<?php echo URL::to('desktop/userinfo'); ?>/';
	var userPhotosUrl = '<?php echo URL::to('desktop/photos'); ?>/';
	var userFollowUrl = '<?php echo URL::to('desktop/follow'); ?>/';
	var userUnfollowUrl = '<?php echo URL::to('desktop/unfollow'); ?>/';
	$(document).ready(function() {
		$().UItoTop({ easingType: 'easeOutQuart' });
		$.ajax({
			url: "<?php echo URL::to('desktop/data'); ?>/",
			success: function( content ){
				$('#content').html(content);
				showfriends(10);
				showfans(10);
				showfollowing(10);
		  }
		});
	});
	</script>
<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/xaJoxYVgKzJRYBXtNNyYKA.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>
  </body>
</html>
