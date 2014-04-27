<?php if( $isDashBoard ) { ?>
<a href="<?php echo URL::to('/mobile/logout/');?>" class="buttonBack">
	<?php echo $logoutTitle; ?>
</a>
<?php } else { ?>
<a href="<?php echo URL::to('/mobile/dashboard/');?>" class="buttonBack">
	« 
</a>
<?php } ?>
<div class="profile-image left">
	<img src="<?php echo $user[ 'info' ]->data->profile_picture; ?>"/>
</div>
<div class="profile-info left">
	<strong><?php echo $user[ 'info' ]->data->username; ?></strong><br />
	<?php echo $user[ 'info' ]->data->full_name; ?>
</div>
<div class="clear"></div>