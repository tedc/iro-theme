<?php 
	use Roots\Sage\Titles;
	$src = get_field('header_iframe', false, false);
	$video_file = get_field('header_video_file');
	$video = 'header'; 
	if($src) {
		$src = explode('?v=', $src);
		$src = explode('&', $src[1]);
		$src = $src[0];
	}
	if($video_file) : 
	$file = preg_replace('/\\.[^.\\s]{3,4}$/', '', $video_file['url']);
	$video_id = $video.'_video_'. get_the_ID();
?>
<div class="<?php echo $video; ?>__video" ng-style="{backgroundImage : 'url(<?php echo $file; ?>.jpg)'}">
	<video<?php if(!is_handheld()): ?> loop<?php endif; ?> class="<?php echo $video; ?>__video-item" ng-video muted poster="<?php echo $file; ?>.jpg">
		<source src="<?php echo $file; ?>.mp4" type="video/mp4"/>
		<source src="<?php echo $file; ?>.webm" type="video/webm"/>
	</video>
</div>
<?php else :
	$image_size = (is_mobile()) ? 'large' : 'full';
	$thumb_id = get_post_thumbnail_id($post->ID);
	$image_class = 'header__image';
	$image_class .= (get_field('background_position')) ? ' header__image'.get_field('background_position') : '';
	$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : get_bloginfo('name') . ': '.Titles\title();
	the_post_thumbnail( $image_size, array('class' => $image_class, 'alt' => $alt, 'data-object-fit' => true) );
endif; if(get_field('header_iframe')) :?>
<div class="<?php echo $video; ?>__iframe" player-id="<?php echo $video_id; ?>" ng-player="<?php echo $src; ?>" ng-class="{'<?php echo $video; ?>__iframe--visible': isVideo['<?php echo $video_id; ?>']}">
	<?php include(locate_template( 'builder/commons/video.php', false, true )); ?>
</div>
<?php endif; ?>