<h1><?php echo $bodyTitle; ?></h1>
<ul id="dashboard">
	<li>
		<span class="number"><?php echo $numberOfFriends; ?></span>
		<a href="<?php echo URL::to('mobile/list/friends');?>"><?php echo $friendsTitle; ?></a>
	<li>
		<span class="number"><?php echo $numberOfFans; ?></span>
		<a href="<?php echo URL::to('mobile/list/fans');?>"><?php echo $fansTitle; ?></a>
	</li>
	<li>
		<span class="number"><?php echo $numberOfFollowing; ?></span>
		<a href="<?php echo URL::to('mobile/list/following');?>"><?php echo $followingTitle; ?></a>
	</li>
</ul>
<ul id="dashboard">
	<li>
		<a href="<?php echo URL::to('mobile/followme');?>"><?php echo $followmeTitle; ?></a>
	</li>
	<li>
		<a href="<?php echo URL::to('mobile/about');?>"><?php echo $aboutTitle; ?></a>
	</li>
</ul>