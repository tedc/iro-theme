<?php
	$args = array(
		'post_type' => 'promo',
		'posts_per_page' => 1,
	);
	$promo = new WP_Query($args);
	if($promo->have_posts()) : ?>
<div class="promo promo--grid" ng-promo>
	<?php while($promo->have_posts()) : $promo->the_post(); ?>
	<div class="promo__pre promo__pre--shrink-fw-left"><span><?php the_title(); ?></span></div>
	<div class="promo__content promo__content--shrink-fw-right">
		<?php the_content(); ?>
	</div>
<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
</div>
<?php endif; ?>