<?php if( $user ) { ?>
	<div class="modal-header">
	  <a href="#" class="close">&times;</a>
	  	<h3>
			<?php echo $user->data->username;?>
			<?php if( isset( $user->data->full_name ) && !empty($user->data->full_name) ) { ?>
			- <?php echo $user->data->full_name; ?>
			<?php } ?>		
		</h3>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="span3">
				<img src="<?php echo $user->data->profile_picture; ?>" />
			</div>
			<div class="span6">
				<dl>
					<?php if( isset( $user->data->bio ) && !empty($user->data->bio) ) { ?>
					<dt>Bio</dt>
					<dd><?php echo $user->data->bio; ?></dd>
					<?php } ?>

					<?php if( isset( $user->data->website ) && !empty($user->data->website) ) { ?>
					<dt>Website</dt>
					<dd><a href="<?php echo $user->data->website; ?>" target="_blank"><?php echo $user->data->website; ?></a></dd>
					<?php } ?>
				</dl>
				<div class="row">
					<div class="span2">
						<strong><?php echo $user->data->counts->media; ?></strong>
						<br/>
						<small>photos</small>
					</div>
					<div class="span2">
						<strong><?php echo $user->data->counts->follows; ?></strong>
						<br/>
						<small>following</small>
					</div>
					<div class="span2">
						<strong><?php echo $user->data->counts->followed_by; ?></strong>
						<br/>
						<small>followers</small>
					</div>
				</div>		
			</div>
		</div>
		<div id="userPhotos">
			<a href="javascript://" onclick="userPhotos(<?php echo $user->data->id;?>);" class="btn info">Show <?php echo $user->data->username;?>'s photos</a>
		</div>
	</div>
	<div class="modal-footer">
		<div class="row">
			<div class="span5">
				<?php if( isset( $user->relationship ) ) { ?>
					<?php if( $user->relationship['outgoing_status'] == 'follows'  ) { ?>
			  			<span class="label success">you follow <?php echo $user->data->username;?></span>
					<?php } else { ?>
						<span class="label warning">you do not follow <?php echo $user->data->username;?></span>
					<?php } ?>
				<br />
					<?php if( $user->relationship['incoming_status'] == 'followed_by'  ) { ?>
			  			<span class="label success"><?php echo $user->data->username;?> follows you</span>
					<?php } else { ?>
						<span class="label warning"><?php echo $user->data->username;?> does not follow you</span>
					<?php } ?>
				<?php } ?>
		</div>
		<div class="span4">
				<?php if( isset( $user->relationship ) ) { ?>
					<div id="relationshipButton">
						<?php if( $user->relationship['outgoing_status'] == 'follows'  ) { ?>
				  			<a href="javascript://" onclick="unfollowUser(<?php echo $user->data->id;?>);" class="btn primary">Unfollow <?php echo $user->data->username;?></a>
						<?php } else { ?>
							<a href="javascript://" onclick="followUser(<?php echo $user->data->id;?>);" class="btn primary">Follow <?php echo $user->data->username;?></a>
						<?php } ?>
					</div>
				<?php } ?>
		</div>
		</div>
	</div>
<?php } else { ?>
	<div class="modal-header">
	  <a href="#" class="close">&times;</a>
	  <h3>Ops,</h3>
	</div>
	<div class="modal-body">
	  <p>There are something wrong, please try again.</p>
	</div>
<?php } ?>