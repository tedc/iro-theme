<?php
$rating = wp_get_post_terms(get_the_ID(), 'rating')[0]->term_id;
$product = wp_get_post_terms(get_the_ID(), 'prodotto_associato')[0]; 
$user = $post->post_author;
$name = get_field('review_name', 'user_'.$user);
$city = get_field('review_city', 'user_'.$user);
$age = get_field('review_age', 'user_'.$user);
?>
<div class="review review--grow-md review--grid">
	<div class="review__content review__content--cell-s8">
	<span class="review__product"><?php echo $product->name; ?></span>
	<h2 class="review__title">
		<?php the_title(); ?>
	</h2>
	<div class="review__content review__content--grow">
		<?php the_content(); ?>
	</div>
	<div class="review__meta">
		<strong><?php echo $name; ?></strong>
		<span><?php echo $age .', '. $city; ?></span>
		<time pubdate="<?php the_time('Y/m/d'); ?>" class="review__time"> - <?php the_time(); ?></time>
	</div>
	</div>
	<div class="review__rating review__rating--grow review__rating--cell-s4">
		<?php 
			$rating = wp_get_post_terms(get_the_ID(), 'rating')[0]->term_id;
			$rating = intval(get_field('rating', 'rating_'.$rating));
		 ?>
		<span class="review__value"><?php echo $rating; ?></span>
		<?php for($i = 0; $i < $rating; $i++) {
			echo '<i class="icon-stella"></i>';
		} ?>
	</div>
</div>