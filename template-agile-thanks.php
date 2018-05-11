<?php
/**
 * Template Name: Agile Thanks
 */
acf_set_language_to_default();
	$materasso = get_field('materasso', 'options');
	acf_unset_language_to_default();
?>
<?php while (have_posts()) : the_post(); ?>
	<div class="header header--page header--shrink-fw">
		<div class="header__container header__container--grow-md">
			<?php get_template_part( 'templates/page', 'title'); ?>
		</div>
	</div>
	<div class="alert alert--grow-lg-bottom alert--shrink alert--mw-large alert--aligncenter">
	<?php the_post_thumbnail('full', array('class' => 'alert__image')); ?>
	<?php the_content(); ?>
  	<a href="<?php echo get_permalink($materasso); ?>" class="alert__button"><?php _e('Vai al Materasso IRO', 'iro'); ?></a>
  	<ng-agile-thanks></ng-agile-thanks>
  </div>
<?php endwhile; ?>