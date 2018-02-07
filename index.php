<div class="blog blog--grid blog--grow-lg blog--shrink-fw">
	<header class="blog__header">
		<h1 class="blog__title blog__title--big"><?php $page = get_option('page_for_posts'); echo get_the_title($page); ?></h1>
	</header><!-- /header -->
<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$count_news = 0;
	while (have_posts()) : the_post(); 
		include(locate_template('templates/content.php', false, false));
		if($paged == 1) {
			if($count_news == 3) :
				$sn = 'instagram';
			elseif($count_news == 5) :
				$sn = 'facebook';
			endif;
			get_template_part( 'builder/'.$sn );
		}
	$count_news++; 
	endwhile; ?>

<?php the_posts_navigation(); ?>
</div>
