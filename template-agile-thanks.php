<?php
/**
 * Template Name: Agile Thanks
 */
?>
<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<div class="alert alert--grow-lg-bottom alert--shrink alert--mw-large alert--aligncenter">
	<?php the_content(); ?>
  	<a href="<?php echo get_permalink($materasso); ?>" class="page__button"><?php _e('Continua lo shopping'); ?></a>
  	<ng-agile-thanks></ng-agile-thanks>
  </div>
<?php endwhile; ?>