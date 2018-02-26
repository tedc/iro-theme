<?php
/**
 * Template Name: Review Form Page
 */
?>
<?php if(is_user_logged_in()) : get_template_part( 'review/form' ); else : ?>
	<div class="review review--grow-lg review--shrink review--aligncenter review--mw-large" review>
		<hr class="divider divider--md"/>
		<h2 class="review__title review__title--big"><?php _e('Utente non registrato', 'iro'); ?></h2>
		<div class="review__row review__row--grow"><p><?php _e('Per recensire i nostri prodotti, devi prima accedere al sito', 'iro'); ?></p></div>
		<a href="#login" class="review__button"><?php _e('Entra', 'iro'); ?></a>
		<hr class="divider divider--md"/>
	</div>

<?php endif; ?>