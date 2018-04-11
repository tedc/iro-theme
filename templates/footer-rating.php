<?php
	acf_set_language_to_default();
	$main_product = get_field('main_product', 'options');
	acf_unset_language_to_default();
	$args = array(
		array(
			'taxonomy' => 'prodotto_associato',
			'field' => 'term_id',
			'terms' => array($main_product)
		)
	);
	$main_total = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $args)));
	$ratings = get_terms(array('taxonomy'=>'rating', 'hide_empty'=>0));
	$totals = array();
	foreach ($ratings as $rate) {
		$tx = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'rating',
				'field' => 'term_id',
				'terms' => array($rate->term_id)
			),
			array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'term_id',
				'terms' => array($main_product)
			)
		);
		$totals[get_field('rating', 'rating_'.$rate->term_id)] = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $tx)));
	}
	$average = 0;
	foreach ($totals as $key => $value) {
		$average += (intval($key) * $value);
	}
	$average = $average / $main_total;
	$average = round($average, 1);
	if(is_numeric($average) && $average > 0) :
?>
<div class="footer__cell footer__cell--grow footer__cell--last">
	<h4 class="footer__subtitle"><?php _e('Voto degli utenti', 'iro'); ?></h4>
	<span class="footer__average"><strong><?php echo $average?></strong> / 5</span>
	<span class="footer__stars">
		<?php 
			stars($average, 'footer');
		?>
	</span>
	<a class="footer__permalink" href="<?php echo get_post_type_archive_link('recensioni'); ?>"></a>
</div>
<?php endif; ?>