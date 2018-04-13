<?php
	$args = array(
		'post_type' => 'promo',
		'posts_per_page' => 1,
	);
	$promo = new WP_Query($args);
	if($promo->have_posts()) : ?>
<div class="promo promo--grid" ng-promo>
	<div class="promo__pre promo__pre--shrink-fw-left"><?php the_title(); ?></div>
	<div class="promo__content promo__content--shrink-fw-right">
		<?php the_content(); ?>
	</div>
</div>
<?php endif; ?>