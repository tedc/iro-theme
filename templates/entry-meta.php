<?php if(!isset($is_first) && is_single()) : ?>
<div class="post__info">
<?php 
endif;
	$categories = wp_get_post_categories(get_the_ID());
	$count_cat = 0;
	foreach ($categories as $c) {
		$cat = get_category($c);
		echo (($count_cat >0) ? ', ': '') . '<a class="post__cat" href="'.get_term_link($c).'" ui-sref="app.category({name : \''.$cat->slug.'\'})">'.$cat->name.'</a>';
		$count_cat++;
	}
?>
<time class="post__updated" datetime="<?= get_post_time('c', true); ?>"><?= get_the_time(); ?></time>
<?php if(!isset($is_first) && is_single()) : ?>
</div>
<div class="post__social">
	<strong><?php _e('Condividi', 'iro'); ?></strong>
	<a href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank"><i class="icon-facebook"></i></a>
    <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank"><i class="icon-google-plus"></i></a>
    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="_blank"><i class="icon-twitter"></i></a>
</div>
<?php endif; ?>