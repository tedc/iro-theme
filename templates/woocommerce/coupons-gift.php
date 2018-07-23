<div class="free-gifts">
	<h4 class="free-gifts__title"><?php echo _e('Sconto agguintivo sul carrello', 'iro'); ?></h4>
<?php
	$args = array(
		'post_type' => 'product',
		'post__in' => $free_gift_products,
		'posts_per_page' => count($free_gift_products)
	);
	$gifts = new WP_Query($args);
	while ($gifts->have_posts()) : $gifts->the_post(); ?>
		<div class="free-gifts__row">
			<div class="free-gifts__content free-gifts__content--grid" for="free-gift_<?php the_ID(); ?>" ng-init="ngCart.freeGift({id: <?php the_ID(); ?>, qty: <?php echo $qty; ?>})">
				<?php the_post_thumbnail( 'post-thumbnail' ); ?>
				<a class="free-gifts__link" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?><br/><span>x <?php echo $qty; ?></span></a>
				
			</div>
		</div>
<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
</div>