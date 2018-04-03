<div class="blog blog--grid blog--grow-lg blog--shrink-fw">	
	<header class="blog__header blog__header--aligncenter">
		<h2 class="blog__title blog__title--big"><?php _e('IRO Blog', 'iro'); ?></h2>
	</header>
	<?php 
		$blog = new WP_Query(array('posts_per_page' => 1));
		$paged = 1;
		$count_news = 0;
		while($blog->have_posts()) : $blog->the_post();
			include(locate_template('templates/content.php', false, false));
		endwhile; wp_reset_postdata(); wp_reset_query();
	?>
	<nav class="blog__nav blog__nav--cell-s12 blog__nav--grow-md-top blog__nav--aligncenter">
		<!-- <a href="<?php get_permalink(get_option('page_for_posts')); ?>" ui-sref="app.page({slug : '<?php echo basename( get_permalink(get_option('page_for_posts'))); ?>'})" class="blog__button blog__button--dark"><?php _e('Iro Blog', 'iro'); ?></a> -->
	<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="blog__button blog__button--dark"><?php _e('Iro Blog', 'iro'); ?></a>
</nav>
</div>