<div class="free-gifts">
	<h4 class="free-gifts__title"><?php _e('Seleziona uno dei prodotti in omaggio con il tuo coupon'); ?></h4>
<?php
	$args = array(
		'post_type' => 'product',
		'post__in' => $free_gift_products,
		'posts_per_page' => count($free_gift_products)
	);
	$gifts = new WP_Query($args);
	while ($gifts->have_posts()) : $gifts->the_post(); ?>
		<div class="free-gift__row free-gift__row--grid">
			<input type="radio" name="free_gift" ng-model="ngCart.free_gift" ng-change="ngCart.freeGift(ngCart.free_gift)" value="<?php the_ID(); ?>" id="free-gift_<?php the_ID(); ?>" />
			<label class="free-gift__content" for="free-gift_<?php the_ID(); ?>">
				<?php the_post_thumbnail_url( 'post-thumbnail' ); ?>
				<div class="free-gift__title"><?php the_title(); ?></div>
			</label>
		</div>
<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
</div>