<?php acf_set_language_to_default();
	$main_product = get_field('main_product', 'options');
	acf_unset_language_to_default(); ?>
<div class="section section--grid">
	<?php $paper_review = get_sub_field('review'); if($paper_review): ?>
	<div class="section__cell  section__cell--s6">
		<?php include(locate_template( 'builder/commons/review.php', false, true )); ?>
	</div>
	<?php endif;
	$args = array('post_type' => 'recensioni','posts_per_page' => get_sub_field('posts_per_page') ? get_sub_field('posts_per_page') : 3);
	$reviews_ids = get_sub_field('reviews');
	$ratings_ids = get_sub_field('ratings');
	if($reviews_ids || $ratings_ids) {
		$args['tax_query']['relation'] = 'AND';
	}
	if($reviews_ids) {
		array_push($args['tax_query'], array(
			'taxonomy' => 'prodotto_associato',
			'field' => 'term_id',
			'terms' => $reviews_ids
		));
		$total_args = array(
			array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'term_id',
				'terms' => array($reviews_ids)
			)
		);
	}
	if($ratings_ids) {
		array_push($args['tax_query'], array(
			'taxonomy' => 'prodotto_associato',
			'field' => 'term_id',
			'terms' => $ratings_ids
		));
	}
	if(is_singular('product') && !$reviews_ids) {
		$term = wp_get_post_terms($post->ID, 'prodotto_associato');
		if($term) {
			if($ratings_ids){
				array_push($args['tax_query'], array(
					'taxonomy' => 'prodotto_associato',
					'field' => 'term_id',
					'terms' => array($term[0]->term_id)
				));
			} else {
				$args['tax_query'] = array(
					'taxonomy' => 'prodotto_associato',
					'field' => 'term_id',
					'terms' => array($term[0]->term_id)
				);
			}
		}
		$total_args = array(
			array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'term_id',
				'terms' => array($term[0]->term_id)
			)
		);
	}

	if(!is_singular('product') && !$reviews_ids) {
		if($main_product){
			$args['tax_query'] = array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'term_id',
				'terms' => array($main_product)
			);
		}
		$total_args = array(
			array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'term_id',
				'terms' => array($main_product)
			)
		);
	}
		
	
	$main_total = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $total_args)));
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
				'terms' => (is_singular('product') && !$reviews_ids) ? array($term[0]->term_id) : array($reviews_ids)
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
	$sref = (is_singular('product') && !$reviews_ids) ? 'app.page({slug : \''.basename(get_post_type_archive_link('recensioni')).'\', productId : '.$term[0]->term_id.'})' : 'app.page({slug : \''.basename(get_post_type_archive_link('recensioni')).'\'})';

$reviews = new WP_Query($args);
if($reviews->have_posts()) : 
?>
	<div class="section__cell section__cell--reviews<?php echo ($paper_review) ? ' section__cell--shrink-left-only' : ''; ?> section__cell--s<?php echo ($paper_review) ? 6 : 12; ?>">
		<div class="section__averages">
			<span class="section__average"><strong><?php echo $average?></strong> / 5</span>
			<span class="section__stars">
				<?php 
					for($i= 1; $i<= round($average, 0, PHP_ROUND_HALF_UP); $i++ ) {
						if ($average - $i > 0 && $average - $i < 1) {
							$is_half = true;
						} else {
							$is_half = false;
						}
						$starClass = ($is_half) ? 'section__star section__star--active-half' : 'section__star section__star--active';
						$stars = (!$is_half) ? '<i class="icon-stella"></i>' : '<span class="section__starhalf"><i class="icon-stella"></i></span><i class="icon-stella"></i>';
						echo '<span class="'.$starClass.'">'.$stars.'</span>';
					}
					$resto = round((5 - $average), 0, PHP_ROUND_HALF_UP);
					for($c = 0; $c<$resto; $c++) {
						echo '<span class="section__star"><i class="icon-stella"></i></span>';
					}
				?>
			</span>
			<span class="section__total"><?php echo $main_total; ?> <?php _e('Recensioni', 'iro'); ?></span>
			<a href="section__button section__button--slim" href="<?php echo get_post_type_archive_link('recensioni'); ?>" ui-sref="<?php echo $sref; ?>"><?php _e('Leggi tutte', 'iro'); ?></a>
		</div>
		<?php while($reviews->have_posts()) : $reviews->the_post(); ?>
			<?php
			$rating = wp_get_post_terms(get_the_ID(), 'rating');
			$user = $post->post_author;
			$name = get_field('review_name', 'user_'.$user);
			?>
			<div class="review review--grow-md review--grid">
				<div class="review__content review__content--cell-s8">
				<h2 class="review__title">
					<?php echo $name; ?>
				</h2>
				<div class="review__content review__content--grow">
					<?php the_excerpt(); ?>
				</div>
				</div>
				<?php if($rating) : ?>
				<div class="review__rating review__rating--grow review__rating--cell-s4">
					<?php 
						$rating = $rating->term_id;
						$rating = intval(get_field('rating', 'rating_'.$rating));
					 ?>
					<span class="review__value"><?php echo $rating; ?></span>
					<?php for($i = 0; $i < $rating; $i++) {
						echo '<i class="icon-stella"></i>';
					} ?>
				</div>
				<?php endif; ?>
			</div>
		<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
	</div>
	<?php endif; ?>
</div>