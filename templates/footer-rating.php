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
?>
<div class="footer__cell footer__cell--grow footer__cell--last">
	<h4 class="footer__subtitle"><?php _e('Voto degli utenti', 'iro'); ?></h4>
	<span class="footer__average"><strong><?php echo $average?></strong> / 5</span>
	<span class="footer__stars">
		<?php 
			for($i= 1; $i<= round($average, 0, PHP_ROUND_HALF_UP); $i++ ) {
				if (($average + 1) - $i > 0 && ($average + 1) - $i < 1) {
					$is_half = true;
				} else {
					$is_half = false;
				}
				$starClass = ($is_half) ? 'footer__star footer__star--active-half' : 'footer__star footer__star--active';
				$stars = (!$is_half) ? '<i class="icon-stella"></i>' : '<span class="footer__starhalf"><i class="icon-stella"></i></span><i class="icon-stella"></i>';
				echo '<span class="'.$starClass.'">'.$stars.'</span>';
			}
			$resto = round((5 - $average), 0, PHP_ROUND_HALF_UP);
			for($c = 0; $c<$resto; $c++) {
				echo '<span class="footer__star"><i class="icon-stella"></i></span>';
			}
		?>
	</span>
</div>