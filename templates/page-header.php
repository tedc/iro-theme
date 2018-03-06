<?php 
	use Roots\Sage\Titles;
	if(!is_checkout() && !is_account_page()) : 
	$the_id = get_the_ID();
	$thumb_id = get_post_thumbnail_id($the_id);
	$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : get_bloginfo('name') . ': '.Titles\title();
	$image_size = (is_mobile()) ? 'large' : 'full';
	$image_class = 'header__image';
	$image_class .= (get_field('background_position')) ? ' header__image'.get_field('background_position') : '';
	$image = wp_get_attachment_image( $thumb_id, $image_size, false, array('class' => 'header__image', 'alt' => $alt) );
	$header_class = 'header header--shrink-fw';
	$header_class .= (!is_page_template() || get_field('header_small')) ? ' header--page' : '';
	$header_class .= (get_field('white_text')) ? ' header--clear' : '';
?>
<div class="<?= $header_class; ?>">
	<?php 
	if(get_field('is_video')) :
		get_template_part( 'templates/header', 'video');
	else :
		echo $image;
	endif;
	?>
	<div class="header__container header__container--grow-<?php echo (!is_page_template() || get_field('header_small')) ? 'md' : 'lg'; ?> header__container--grow-lg">
		<?php get_template_part( 'templates/page', 'title'); ?>
		<?php if(get_field('header_iframe') && get_field('is_video')) : ?>
		<span class="header__play" ng-class="{'header__play--ready': playerReady['header_video_<?php echo get_the_ID(); ?>']}" ng-click="playIframe('header_video_<?php echo get_the_ID(); ?>')">
			<i class="icon-play"></i>
			<span><?php _e('Guarda il video', 'iro'); ?></span>
		</span>
		<?php endif; ?>
	</div>
	<span class="header__goto" go-to="#header-trigger"><?php _e('Scroll' ,'iro'); ?><i class="icon-arrow-down"></i></span>
</div>
<hr id="header-trigger" />
<?php 
else : 
	if(is_account_page()) : ?>
	<div class="header header--shrink header--mw-large">
		<div class="header__container<?php if(is_wc_endpoint_url()):?> header__container--grid<?php endif; ?> header__container--grow-md header__container--grow-lg">
			<?php get_template_part( 'templates/page', 'title'); ?>
			<?php if(is_wc_endpoint_url()) : ?>
				<a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="account__back" ui-sref="app.page({slug : '<?php echo basename(wc_get_page_permalink('myaccount')); ?>'})"><i class="icon-arrow-left"></i><?php _e('Indietro', 'iro'); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php 
	else:
?>
<div class="header header--page header--shrink-fw">
	<div class="header__container header__container--grow-md header__container--grow-lg">
		<?php get_template_part( 'templates/page', 'title'); ?>
	</div>
</div>
<?php endif; endif; ?>