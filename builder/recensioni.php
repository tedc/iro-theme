<?php 
	acf_set_language_to_default();
	$main_product = get_field('main_product', 'options');
	acf_unset_language_to_default();
	$paper_review = get_sub_field('review');
?>
<div class="section section--<?php echo ($paper_review) ? 'shrink-fw  section--grid' : 'mw-large section--shrink'; ?>">
	<?php  if($paper_review): ?>
	<div class="section__cell  section__cell--s6">
		<?php include(locate_template( 'builder/commons/review.php', false, true )); ?>
	</div>
	<?php endif;
	$args = array('post_type' => 'recensioni','posts_per_page' => get_sub_field('posts_per_page') ? get_sub_field('posts_per_page') : 2);
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
	$ratings = get_terms(array('taxonomy'=>'rating'));
	$totals = array();
	foreach ($ratings as $rate) {
		if(is_singular('product') && !$reviews_ids) {
			$pId = $term[0]->term_id;
		}
		if(!is_singular('product') && !$reviews_ids) {
			$pId = $main_product;
		}
		if($reviews_ids) {
			$pId = $reviews_ids;
		}
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
				'terms' => ($pId)
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
		<h3 class="section__title section__title--medium-aligncenter section__title--review"><?php _e('Recensioni', 'iro'); ?></h3>
		<div class="section__top section__top--grid">
			<div class="section__averages">
				<span class="section__average"><strong><?php echo $average?></strong> / 5</span>
				<span class="section__stars">
					<?php 
						stars($average, 'section');
					?>
				</span>
				<span class="section__total"><?php echo $main_total; ?> <?php _e('Recensioni', 'iro'); ?></span>
				
			</div>
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
					<?php echo $name; ?> <time pubdate="<?php the_time('Y/m/d'); ?>" class="review__time"><?php the_time(); ?></time>
				</h2>
				<div class="review__content review__content--grow">
					<?php the_excerpt(); ?>
				</div>
				</div>
				<?php if($rating) : ?>
				<div class="review__rating review__rating--grow review__rating--cell-s4">
					<?php 
						$rating = $rating[0]->term_id;
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
		<footer class="section__link section__link--aligncenter section__link--grow-md-top">
				<!-- <a class="section__button section__button--dark" href="<?php echo get_post_type_archive_link('recensioni'); ?>" ui-sref="<?php echo $sref; ?>"><?php _e('Leggi tutte', 'iro'); ?></a> -->
				<a class="section__button section__button--dark" href="<?php echo get_post_type_archive_link('recensioni'); ?>"><?php _e('Leggi tutte', 'iro'); ?></a>
		
		</footer>
	</div>
	<?php endif; ?>
</div>