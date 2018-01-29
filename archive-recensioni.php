<?php
var_dump(get_query_var('rating'));
	acf_set_language_to_default();
	$main_product = get_field('main_product', 'options');
	acf_unset_language_to_default();
	$var = get_query_var( 'review_product' );
	$paged =  (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' => 'recensioni',
		'paged' => $paged
	);
	if($var) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'name',
				'terms' => array($var)
			)
		);
	} else {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'prodotto_associato',
				'field' => 'term_id',
				'terms' => array($main_product)
			)
		);
	}
	if(get_query_var( 'rating' )) {
		$args['tax_query']['relation'] = 'AND';
		array_push($args['tax_query'], array(
			'taxonomy'=>'rating',
			'field' => 'name',
			'terms' => array(get_query_var( 'rating' ))
		)
		);
	}
	$query = new WP_Query($args);
	if($query->have_posts()) : ?>
	<div class="reviews reviews--shrink-fw reviews--grid">
		<aside class="reviews__aside reviews__aside--shrink-right-only reviews__aside--cell-s3">
			<?php _e('Mostra voto', 'iro'); ?>
			<div class="reviews__select">
				<?php if(get_query_var('rating')) : ?>
				<?php endif; ?>
				<span class="reviews__value"></span>
				<span class="reviews__icons"><i class="icon-arrow-down"></i></span>
				<ul class="reviews__options">
				<?php $ratings = get_terms(array('taxonomy'=>'rating', 'hide_empty'=>0));
					foreach ($ratings as $r) : ?>
				<li class="review__option">	
					<a ui-sref="app.reviews({'rating' : '<?php echo $r->slug; ?>'})">
						<?php _e('Voto', 'iro'); ?> <?php the_field('rating', 'rating_'.$r->term_id); ?>
					</a>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>
		</aside>
	<div class="reviews__container reviews__container--cell-s9">
	<?php
		while ($query->have_posts()) : $query->the_post(); 
			get_template_part( 'review/single' );
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
?>
	</div>
	</div>

<?php endif; ?>