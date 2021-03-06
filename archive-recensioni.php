<?php
	acf_set_language_to_default();
	$main_product = get_field('main_product', 'options');
	$review_base = get_field('review_base', 'options');
	acf_unset_language_to_default();
	$var = get_query_var( 'review_product' ) ? get_query_var( 'review_product' ) : $main_product;
	$field = get_query_var( 'review_product' ) ? 'slug' : 'term_id';
	$paged =  (get_query_var('paged')) ? get_query_var('paged') : 1;
	$ratings = get_terms(array('taxonomy'=>'rating'));	
	$current = get_term_by( $field, $var, 'prodotto_associato' );
	$products = get_terms(array('taxonomy' => 'prodotto_associato'));
	$products_ids = array();
	$pr_ids = array();
	foreach ($products as $pr) {
		array_push($pr_ids, $pr->term_id);
	}		
	$args = array(
		'post_type' => 'recensioni',
		'paged' => $paged
	);

	$args['tax_query'] = array(
		array(
			'taxonomy' => 'prodotto_associato',
			'field' => $field,
			'terms' => array($var)
		)
	);
	$main_total = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $args['tax_query'])));
	$totals = array();
	foreach (get_posts(array('post_type'=>'product', 'posts_per_page' => -1, 'tax_query' => array('taxonomy' => 'prodotto_associato', 'field' => 'term_id', 'terms' => $pr_ids))) as $pro) {
		array_push($products_ids, $pro->ID);
	}
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
				'field' => $field,
				'terms' => array($var)
			)
		);
		$object = get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $tx));
		if($object) {
			$totals[get_field('rating', 'rating_'.$rate->term_id)] = count($object);
		}
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
	<div class="reviews">
		<header class="reviews__header reviews__header--shrink-fw reviews__header--grid reviews__header--cell-s12">
			<figure class="reviews__figure reviews__figure--cell-s6">
				<?php echo wp_get_attachment_image( get_field('review_product_image', 'prodotto_associato_'.$current->term_id), 'full', false, array('class' => 'reviews__image') ); ?>		
			</figure>
			
			<?php 
				$average = 0;
				foreach ($totals as $key => $value) {
					$average += (intval($key) * $value);
				}
				$average = $average / $main_total;
				$average = round($average, 1);
			?>
			<div class="reviews__averages reviews__averages--grow-md reviews__averages--shrink-left-only reviews__averages--cell-s6">
				<h1 class="reviews__title reviews__title--big"><?php _e('Recensioni degli utenti', 'iro'); ?></h1>
				<div class="reviews__values reviews__values--grow-md-top">
					<span class="reviews__average"><strong><?php echo $average?></strong> / 5</span>
					<span class="reviews__stars">
						<?php
							stars($average, 'reviews');
						?>
					</span>
					<span class="reviews__total">
						<?php 
						$txt = _n('%s Recensione', '%s Recensioni', $main_total, 'iro');
						echo sprintf($txt, $main_total); ?>
					</span>
					<ul class="reviews__chart">
						<?php if(array_key_exists("5", $totals) && $totals["5"] > 0) { ?>
						<li class="reviews__row">
							<span><?php _e('Voto 5', 'iro'); ?></span>
							<span class="reviews__bar">
								<span class="reviews__percentage" rating-percentage="100"></span>
							</span>
							<span class="reviews__subtotal"><?php echo $totals[5]; ?></span>
						</li>
						<?php } ?>
						<?php if(array_key_exists("4", $totals) && $totals["4"] > 0) { ?>
						<li class="reviews__row">
							<span><?php _e('Voto 4', 'iro'); ?></span>
							<span class="reviews__bar">
								<span class="reviews__percentage" rating-percentage="<?php get_percentage(4, 5); ?>"></span>
							</span>
							<span class="reviews__subtotal"><?php echo $totals[4]; ?></span>
						</li>
						<?php } ?>
						<?php if(array_key_exists("3", $totals) && $totals["3"] > 0) { ?>
						<li class="reviews__row">
							<span><?php _e('Voto 3', 'iro'); ?></span>
							<span class="reviews__bar">
								<span class="reviews__percentage" rating-percentage="<?php get_percentage(3, 5); ?>"></span>
							</span>
							<span class="reviews__subtotal"><?php echo $totals[3]; ?></span>
						</li>
						<?php } ?>
						<?php if(array_key_exists("2", $totals) && $totals["2"] > 0) { ?>
						<li class="reviews__row">
							<span><?php _e('Voto 2', 'iro'); ?></span>
							<span class="reviews__bar">
								<span class="reviews__percentage" rating-percentage="2"></span>
							</span>
							<span class="reviews__subtotal"><?php echo $totals[2]; ?></span>
						</li>
						<?php } ?>
						<?php if(array_key_exists("1", $totals) && $totals["1"] > 0) { ?>
						<li class="reviews__row">
							<span><?php _e('Voto 1', 'iro'); ?></span>
							<span class="reviews__bar">
								<span class="reviews__percentage" rating-percentage="<?php get_percentage(1, 5); ?>"></span>
							</span>
							<span class="reviews__subtotal"><?php echo $totals[1]; ?></span>
						</li>
						<?php } ?>
					</ul>
				</div>
				<?php if(is_user_logged_in() && has_bought_items($products_ids)) : ?>
				<!-- <a href="<?php echo get_permalink($review_base); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($review_base)); ?>', productId : <?php echo $current->term_id; ?>})" class="reviews__button reviews__button--dark"><?php _e('Scrivi una recensione', 'iro'); ?></a> -->
				<a href="<?php echo get_permalink($review_base); ?>" class="reviews__button reviews__button--dark"><?php _e('Scrivi una recensione', 'iro'); ?></a>
			<?php endif; ?>
			</div>
			
		</header>
	<div class="reviews__wrapper reviews__wrapper--grow-md reviews__wrapper--shrink-fw reviews__wrapper--grid">
		<aside class="reviews__aside reviews__aside--grow-md-top reviews__aside--shrink-right-only reviews__aside--cell-s3">
			<?php _e('Mostra voto', 'iro'); ?>
			<div class="reviews__select" ng-click="isRatingSelected=!isRatingSelected" click-outside="isRatingSelected=false">
				<span class="reviews__value"><?php echo get_query_var('rating') ? __('Voto', 'iro') .' '.get_field('rating', 'rating_'.get_term_by( 'slug', get_query_var( 'rating' ), 'rating' )->term_id) : __('Seleziona', 'iro'); ?></span>
				<span class="reviews__icons"><i class="icon-arrow-down"></i></span>
				<ul class="reviews__options" ng-class="{'reviews__options--visible':isRatingSelected}">
				<?php foreach ($ratings as $r) : ?>
				<li class="review__option">	
				
					<?php $data = array(
						'productId' => get_query_var( 'productId' ),
						'review_product' => get_query_var( 'review_product' ),
						'rating' => $r->slug
					); ?>
					<!-- <a ui-sref="app.reviews({'rating' : '<?php echo $r->slug; ?>'})"> -->
					<a href="<?php echo  get_post_type_archive_link('recensioni'). '?' . http_build_query($data); ?>">
						<?php _e('Voto', 'iro'); ?> <?php the_field('rating', 'rating_'.$r->term_id); ?>
					</a>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php 
			if(count($products) > 1) :
			_e('Recensioni per', 'iro'); ?>
			<div class="reviews__select" ng-click="isProductSelected=!isProductSelected" click-outside="isProductSelected=false">
				<?php if(get_query_var('rating')) : ?>
				<?php endif; ?>
				<span class="reviews__value">
					<?php echo $current->name; ?>
				</span>
				<span class="reviews__icons"><i class="icon-arrow-down"></i></span>
				<ul class="reviews__options" ng-class="{'reviews__options--visible':isProductSelected}">
				<?php foreach ($products as $p) : 
				?>
				<li class="review__option">	
					<?php $data = array(
						'productId' => get_query_var( 'productId' ),
						'review_product' => $p->slug,
						'rating' => get_query_var( 'rating' )
					); ?>
					<a href="<?php echo  get_post_type_archive_link('recensioni') . '?' . http_build_query($data); ?>">
						<?php echo $p->name; ?>
					</a>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif;  
			if(is_user_logged_in() && has_bought_items($products_ids)) : ?>
				<!-- <a href="<?php echo get_permalink($review_base); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($review_base)); ?>', productId : <?php echo $current->term_id; ?>})" class="reviews__button reviews__button--dark"><?php _e('Scrivi una recensione', 'iro'); ?></a> -->
				<a href="<?php echo get_permalink($review_base); ?>?productId=<?php echo $current->term_id; ?>" class="reviews__button reviews__button--dark"><?php _e('Scrivi una recensione', 'iro'); ?></a>
			<?php endif; ?>
		</aside>
		<div class="reviews__container reviews__container--shrink-left-only reviews__container--cell-s9">
		<?php
			while ($query->have_posts()) : $query->the_post(); 
				get_template_part( 'review/single' );
			endwhile;
			wp_reset_query();
			wp_reset_postdata();
		?>

		<nav class="reviews__pagination reviews__pagination--grow-md reviews__pagination--grid">
		<?php 
		$pagination = paginate_links(array('format'=>'page/%#%', 'type' => 'array', 'prev_text' => __('Precedenti', 'iro'), 'next_text' => __('Successivi', 'iro')));
		if($pagination) :
	foreach ($pagination as $page) {
		if(preg_match('/<a\s+(?:[^"\'>]+|"[^"]*"|\'[^\']*\')*href=("[^"]+"|\'[^\']+\'|[^<>\s]+)/', $page, $matches)) {
			//echo $page;
			$sref = str_replace(array('\'', '"'), '', $matches[1]);
			$base_link = get_post_type_archive_link('recensioni')  . '/';
			$sref = explode('?', str_replace($base_link, '', $sref));
			$sref_obj = '';
			if($sref[0] != '') {
				$sref_obj .= "path : '" .$sref[0] ."'";
			}
			if(get_query_var( 'review_product' )) {
				if($paged > 1) {
					$append = ',';
				}
				$sref_obj .= $append."review_product:'".get_query_var( 'review_product' )."'";
			}
			if(get_query_var( 'rating' )) {
				if($paged > 1 || get_query_var( 'review_product' )) {
					$append = ',';
				}
				$sref_obj .= $append."rating:'".get_query_var( 'rating' )."'";
			}
			$sref = ($sref[0] != '') ? ' ui-sref="app.reviews({'.$sref_obj.'})"' : ' ui-sref="app.page({slug : \''.basename($base_link).'\'})"';
			//$page = str_replace('<a', '<a'.$sref, $page);
		}
		echo $page;
	} endif; ?></nav>
		</div>
	</div>
</div>

<?php endif; ?>