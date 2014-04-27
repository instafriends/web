<section id="<?php echo $section; ?>">
	<div class="page-header">
		<h1><?php echo ucfirst($section); ?>
			<small><?php printf($title, count($friends)); ?></small>
		</h1>
	</div>
	<div>
		<ul  class="media-grid" id="users-<?php echo $section; ?>">
		</ul>	
	</div>
	<script>
	var <?php echo $section?> = new Array();
	var <?php echo $section?>Showing = 0;
	<?php foreach ($friends as $data) { ?>
		<?php echo $section?>.push({'id' : '<?php echo $data['id']; ?>','username' : '<?php echo $data['username']; ?>','profile_picture' : '<?php echo $data['profile_picture']; ?>'});	
	<?php } ?>
	</script>
	<button class="btn info" onclick="show<?php echo $section?>( stepsToShow );" id="btn-<?php echo $section?>">Show more <?php echo $section;?></button>
</section>