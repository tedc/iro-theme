<?php
	$current = $post->ID;
	$current_categories = wp_get_post_categories($current);
	$args = array(
		'posts_per_page' => 2,
		'post__not_in' => array($current),
		'category__in' => $current_categories
	);
	$loop = new WP_Query($args);
	if($loop->have_posts()) : ?>
<footer class="posts posts--grow-lg-bottom posts--shrink-fw posts--grid">
	<header class="posts__header posts__header--grow-lg-top posts__header--cell-s12 posts__header--grow--lg">	
		<h3 class="posts__title posts_title--big-aligncenter"><?php _e('Altro dal blog', 'iro'); ?></h3>
	</header>
	<?php 
	$count_news = 1;
	$paged = 0;
	while($loop->have_posts()) : $loop->the_post();
	include(locate_template('templates/content.php', false, false));
	endwhile; wp_reset_query(); wp_reset_postdata(); ?>
</footer>

<?php endif; ?>