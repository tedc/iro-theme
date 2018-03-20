<?php
/**
 * Template Name: Custom Template
 */
?>

<?php while (have_posts()) : the_post(); 
	$body_html = get_post_meta($id, 'post_content_html', true);
if(!empty($body_html)) {
	echo $body_html;
} else {
?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part( 'builder/init' ); ?>
<?php } endwhile; ?>
