<?php
/**
 * Template Name: Faq Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part( 'builder/init' ); ?>
<?php endwhile; ?>