<div class="free-gifts" ng-class="{'free-gifts--disabled':ngCart.isGiftDisabled(<?php echo $free_gift_max; ?>)}">
	<h4 class="free-gifts__title"><?php _e('Sconto aggiuntivo sul carrello', 'iro'); ?></h4>
	<div class="free-gifts__row">
			<div class="free-gifts__content free-gifts__content--grid" for="free-gift_<?php the_ID(); ?>">
				<span>-<?php echo $sconto; ?>%</span>
			</div>
	</div>
</div>