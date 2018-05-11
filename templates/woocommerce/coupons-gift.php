<div class="free-gifts">
	<h4 class="free-gifts__title"><?php echo ($free_gift_title) ? $free_gift_title : __('Seleziona uno dei prodotti in omaggio con il tuo coupon'); ?></h4>
<?php
	$args = array(
		'post_type' => 'product',
		'post__in' => $free_gift_products,
		'posts_per_page' => count($free_gift_products)
	);
	$gifts = new WP_Query($args);
	while ($gifts->have_posts()) : $gifts->the_post(); ?>
		<div class="free-gifts__row" ng-class="{'free-gifts__row--disabled':ngCart.isGiftDisabled(<?php the_ID(); ?>, <?php echo $free_gift_max; ?>)}">
			<div class="free-gifts__content free-gifts__content--grid" for="free-gift_<?php the_ID(); ?>">
				<?php the_post_thumbnail( 'post-thumbnail' ); ?>
				<a class="free-gifts__link" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>
				<div class="free-gift__select" ng-click="isRow[<?php the_ID(); ?>]=!isRow[<?php the_ID(); ?>]" click-outside="isRow[<?php the_ID(); ?>]=false">
					<span class="free-figts__value"><span ng-bind-html="(giftRow[<?php the_ID(); ?>]) ? giftRow[<?php the_ID(); ?>] : '<?php _e('Seleziona', 'iro'); ?>'"></span><i class="icon-arrow-down"></i></span>
					<ul class="free-gifts__options" ng-class="{'free-gifts__options--visible':isRow[<?php the_ID(); ?>]}">
						<?php for($i=0; $i<=$free_gift_max; $i++) : 
						?>
						<li class="free-gifts__option" ng-click="ngCart.freeGift({id: <?php the_ID(); ?>, qty: <?php echo $i; ?>}); giftRow[<?php the_ID(); ?>] = '<?php echo ($i == 0) ? __('Seleziona', 'iro') : $i; ?>'; isRow[<?php the_ID(); ?>]=false">
							<?php echo ($i==0) ? __('Seleziona', 'iro') : $i; ?>
						</li>
						<?php endfor; ?>
				</div>

			</div>
		</div>
<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
</div>