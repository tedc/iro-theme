<?php
	$args = array(
		'post_type' => 'promo',
		'posts_per_page' => 1,
	);
	$promo = new WP_Query($args);
	if($promo->have_posts()) : ?>
<div class="promo" ng-promo>
	<div class="promo__wrapper promo__wrapper--grid-nowrap">
	<?php while($promo->have_posts()) : $promo->the_post(); ?>
	<div class="promo__pre promo__pre--shrink-fw-left"><span><?php the_title(); ?></span></div>
	<div class="promo__content promo__content--shrink-fw-right">
		<?php 
			$content = get_the_content();
			$content = apply_filters('the_content', $content);
			$content = strip_tags($content, '<span>');
			echo '<span>'.$content.'</span>';
		 ?>
	</div>
<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
</div>
</div>
<?php endif; ?>