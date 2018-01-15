<?php
$args = array(
	'post_type' => 'recensioni',
	'post__in' => array($review_id)
);
$review = new WP_Query($args);
while($review->have_posts()) : $review->the_post();
	$contentClass = 'section__text  section__text--grow-md-top';
	$press = wp_get_post_terms( get_the_ID(), 'press' )[0];
	$press_logo = wp_get_attachment_image_src( get_field('logo_testata', 'press_'.$press->term_id)['ID'], 'full', false );
?>
<div class="<?php echo $section__content; ?>">
	<h2 class="section__title section__title--medium">"<?php the_title(); ?>"</h2>
	<div class="<?php echo $contentClass; ?>">
		<?php the_content(); ?>
	</div>
	<nav class="section__link section__link--grow-md-top">
		<a href="<?php the_field('review_'.get_field('review_kind')); ?>" target="_blank" class="section__article">
			<img src="<?php echo $press_logo[0]; ?>" style="max-width: <?php echo (($press_logo[1] / 2)/16); ?>em">
			<span class="icon-arrow-link"></span>
		</a>
	</nav>
</div>
<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
