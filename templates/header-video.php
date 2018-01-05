<?php 
	$src = get_field('header_video', false, false);
	$video_file = get_field('video_file');
	$video = 'header';
?>
<?php include(locate_template( 'builder/commons/video.php', false, true )); ?>