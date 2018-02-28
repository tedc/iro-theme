<?php 
	$is_cols = get_sub_field('certification_cols');
	$title = get_sub_field('certification_title');
	$text = get_sub_field('certification_text');
	$cert_class = 'section';
	$cert_class = ($is_cols ) ? 'section--grid' : ''; 
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
	<div class="section__cell section__cell--grow-md<?php echo ($is_cols) ? ' section__cell--s6 section__cell--shrink-right-only' : '' ?>" ?>
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
	<?php endif; ?>
	<div class="section__cell <?php echo ($is_cols) ? ' section__cell--s6' : '' ?><?php echo ($title || $text) ? ' section__cell--shrink-left-only' : ''; ?>">
		<?php while($cert->have_posts()) : $cert->the_post(); ?>
		<div class="section__certification">
			<figure class="section__figure"><?php the_post_thumbnail('large'); ?></figure>
			<div class="section__content">
				<h3 class="section__title section__title--small"><?php the_title(); ?></h3>
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
</div>
<?php endif; ?>