<?php 
	$categories = wp_get_post_categories(get_the_ID());
	$count_cat = 0;
	foreach ($categories as $c) {
		$cat = get_category($c);
		echo (($count_cat >0) ? ', ': '') . '<a class="post__cat" href="'.get_term_link($c).'" ui-sref="app.category({name : \''.$cat->slug.'\'})">'.$cat->name.'</a>';
		$count_cat++;
	}
?>
<time class="post__updated" datetime="<?= get_post_time('c', true); ?>"><?= get_the_time(); ?></time>