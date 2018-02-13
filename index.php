<div class="blog blog--grid blog--grow-lg blog--shrink-fw">
	<header class="blog__header">
		<h1 class="blog__title blog__title--big"><?php if(is_category()) : single_cat_title(); else : $page = get_option('page_for_posts'); echo get_the_title($page); endif; ?></h1>
	</header><!-- /header -->
<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$count_news = 0;
	while (have_posts()) : the_post(); 
		include(locate_template('templates/content.php', false, false));
		$sn = '';
		if($paged == 1 && !is_category()) {
			if($count_news == 2) :
				$sn = 'facebook';
			elseif($count_news == 4) :
				$sn = 'instagram';
			endif;
			get_template_part( 'builder/'.$sn );
		}
	$count_news++; 
	endwhile; ?>

<?php the_posts_navigation(); ?>
</div>
