<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no" />
		<title><?php echo $pageTitle; ?></title>
		<?php echo HTML::style('css/mobile.css'); ?>
		<?php echo Asset::scripts(); ?>
		<?php if( $_SERVER['LARAVEL_ENV'] == 'production' ) { ?>
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
		<?php } ?>
	</head>
	<body>
		<div id="header">
			<?php echo $header; ?>
		</div>
		<div id="content">
			<?php echo $content; ?>
		</div>
		<div id="footer">
			<?php echo $footer; ?>
		</div>
	</body>
</html>