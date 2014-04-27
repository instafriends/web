<h1><?php echo $bodyTitle; ?></h1>
<h2><?php echo $pageInfo; ?></h2>
<ul>
	<?php foreach( $users as $user ) { ?>
		<li id="<?php echo $user[ 'id' ]; ?>">
			<div id="relation<?php echo $user[ 'id' ]; ?>" style="float:right">
				<?php echo View::make( 'mobile.buttonAction' )->with( 'actionTitle', $actionTitle )->with( 'id', $user[ 'id' ] )->with( 'actionJS', $actionJS );?>
			</div>
			<div class="thumb left"><img src="<?php echo $user[ 'profile_picture' ];?>"/></div>
			<div class="info left">
				<div class="full_name"><?php echo $user[ 'full_name' ]?></div>
				<div class="username"><?php echo $user[ 'username' ]; ?></div>
			</div>
			<div class="clear"></div>
		</li>
	<?php } ?>
</ul>
<h2><?php echo $pageInfo; ?></h2>
<div id="pagination">
	<?php echo $pagination->previous() . ' ' . $pagination->next(); ?>
</div>
<script type="text/javascript">var _urlBase = '<?php echo URL::to( '/mobile/' ); ?>';</script>