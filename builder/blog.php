<div class="blog blog--grid blog--row blog--shrink-fw">	
	<div class="flowers flowers--top flowers--flipped"></div>
	<header class="blog__header blog__header--aligncenter">
		<h2 class="blog__title blog__title--big"><?php _e('Iro Blog', 'iro'); ?></h2>
	</header>
	<?php 
		$blog = new WP_Query(array('posts_per_page' => 1));
		$paged = 1;
		$count_news = 0;
		while($blog->have_posts()) : $blog->the_post();
			include(locate_template('templates/content.php', false, false));
		endwhile; wp_reset_postdata(); wp_reset_query();
	?>
	<div class="flowers flowers--bottom flowers--flipped-x"></div>
	<nav class="blog__nav blog__nav--grow-md-top blog__nav--aligncenter">
	<a href="<?php get_permalink(get_option('page_for_posts')); ?>" ui-sref="app.page({slug : '<?php echo basename( get_permalink(get_option('page_for_posts'))); ?>'})" class="blog__button blog__button--light"><?php _e('Iro Blog', 'iro'); ?></a>
</nav>
</div>