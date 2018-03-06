<?php
/**
 * Template Name: Contact
 */
?>
<?php while (have_posts()) : the_post(); ?>
<article <?php post_class('contact contact--grid'); ?>>
	<figure class="contact__cell contact__cell--figure contact__cell--s4">
		<?php the_post_thumbnail('full', array('class' => 'contact__image')); ?>
	</figure>
	<div class="contact__cell contact__cell--grow-md contact__cell--shrink-fw-right contact__cell--s8">
		<header  class="contact__header contact__header--shrink-left-only contact__header--grow-md-bottom">
			<h1 class="contact__title contact__title--medium"><?php the_title(); ?></h1>	
		</header>
		<div class="contact__content contact__content--shrink-left-only contact__content--grow-md-bottom">
			<?php the_content(); ?>
		</div>
	  	<?php get_template_part( 'templates/form'); ?>
 	</div>
</article>
<?php endwhile; ?>