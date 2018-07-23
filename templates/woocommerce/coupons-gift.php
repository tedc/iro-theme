<div class="free-gifts" ng-class="{'free-gifts--disabled':ngCart.isGiftDisabled(<?php echo $free_gift_max; ?>)}">
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
			<div class="free-gifts__content free-gifts__content--grid" for="free-gift_<?php the_ID(); ?>">
				<?php the_post_thumbnail( 'post-thumbnail' ); ?>
				<a class="free-gifts__link" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>
				<div class="free-gifts__select">
					<ul class="free-gifts__options" ng-class="{'free-gifts__options--visible':isRow[<?php the_ID(); ?>]}">
						<li class="free-gifts__option">
							<?php echo $i; ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
	<span class="free-gifts__button free-gifts__button--reset" ng-click="ngCart.resetGift();giftRow=[]"><?php _e('Azzera omaggi', 'iro'); ?><i class="icon-close"></i></span>
</div>