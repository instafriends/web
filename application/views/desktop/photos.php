<h4>Last medias</h4>
<div style="overflow:auto;height:100px;">
<?php if( isset( $photos ) ){
	foreach( $photos as $photo ){ ?>
		<a <?php if( $photo['caption'] != '' ) { ?> title="<?php echo $photo['caption'];?>" <?php } ?> href="<?php echo $photo['link'];?>" target="_blank"><img width="50" height="50" src="<?php echo $photo['thumbnail'];?>"/></a>
	<?php }
} else {?>
	<p>There are something wrong, please try again.</p>
<?php } ?>
</div>