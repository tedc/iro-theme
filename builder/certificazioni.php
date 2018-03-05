<?php 
	$is_cols = get_sub_field('certification_cols');
	$title = get_sub_field('certification_title');
	$text = get_sub_field('certification_text');
	$cert_class = 'section section--certification';
	$cert_class .= ($is_cols ) ? ' section--grid' : ''; 
	$args = array(
		'post_type' => 'certificazioni',
		'posts_per_page' => -1
	);
	if(is_singular('product')) {
		$productID = wp_get_post_terms($post->ID, 'prodotto_associato');
		if($productID) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'prodotto_associato',
					'field' => 'term_id',
					'terms' => array($productID)
				)
			);
		}
	}
	$cert = new WP_Query($args);
	if($cert->have_posts()) :
?>
<div class="<?php echo $cert_class; ?>">
	<?php if($title || $text) : ?>
	<div class="section__cell section__cell--grow-md<?php echo ($is_cols) ? ' section__cell--s6 section__cell--shrink-right-only' : ' section__cell--mw-large section__cell--aligncenter'; ?>">
		<?php 
		$contentClass = 'section__text';	
		if($title) : ?>
		<h2 class="section__title section__title--medium"><?php echo $title; ?></h2>
		<?php 
		$contentClass .= ' section__text--grow-top';
		endif; 
		if($text) : 
		echo '<div class="'.$contentClass.'">'.$text.'</div>'; 
		endif; 
		?>
	</div>
	<?php endif; 
		var_dump($cert);
	?>
	<div class="section__cell <?php echo ($is_cols) ? ' section__cell--s6' : ' section__cell--mw-large' ?><?php echo (($title || $text) && $is_cols) ? ' section__cell--shrink-left-only' : ''; ?>">
		<?php while($cert->have_posts()) : $cert->the_post(); ?>
		<div class="section__certification section__certification--grow">
			<figure class="section__figure"><?php the_post_thumbnail('large'); ?></figure>
			<div class="section__content">
				<h3 class="section__title"><?php the_title(); ?></h3>
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
	</div>
</div>
<?php endif; ?>