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
				<div class="free-gifts__select" click-outside="isRow[<?php the_ID(); ?>]=false">
					<span class="free-gifts__value" ng-click="isRow[<?php the_ID(); ?>]=true;"><span ng-bind-html="(giftRow[<?php the_ID(); ?>]) ? giftRow[<?php the_ID(); ?>] : '<?php _e('Seleziona', 'iro'); ?>'"></span><i class="icon-arrow-down"></i></span>
					<ul class="free-gifts__options" ng-class="{'free-gifts__options--visible':isRow[<?php the_ID(); ?>]}">
						<?php for($i=0; $i<=$free_gift_max; $i++) : 
						?>
						<li class="free-gifts__option" ng-click="ngCart.freeGift({id: <?php the_ID(); ?>, qty: <?php echo $i; ?>}); giftRow[<?php the_ID(); ?>] = '<?php echo ($i == 0) ? __('Seleziona', 'iro') : $i; ?>'; isRow[<?php the_ID(); ?>]=false">
							<?php echo $i; ?>
						</li>
						<?php endfor; ?>
					</ul>
				</div>
			</div>
		</div>
<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
	<span class="free-gifts__button free-gifts__button--reset" ng-click="ngCart.resetGift();giftRow=[]"><?php _e('Azzera omaggi', 'iro'); ?><i class="icon-close"></i></span>
</div>