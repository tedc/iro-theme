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

<?php 
	$pagination = paginate_links(array('format'=>'page/%#%', 'type' => 'array', 'prev_text' => __('Precedenti', 'iro'), 'next_text' => __('Successivi', 'iro')));
	$count = 0;
	if($pagination) : ?>
	<nav class="blog__pagination blog__pagination--grow-lg blog__pagination--grid blog__pagination--cell-s12">
<?php 
	foreach ($pagination as $page) {
		if(preg_match('/<a\s+(?:[^"\'>]+|"[^"]*"|\'[^\']*\')*href=("[^"]+"|\'[^\']+\'|[^<>\s]+)/', $page, $matches)) {
			//echo $page;
			$sref = str_replace(array('\'', '"'), '', $matches[1]);
			if(is_category()) {
				$base_link = get_term_link($term->term_id) . '/';
			} else {
				$base_link = get_permalink(get_option('page_for_posts'))  . '/';
			}
			$sref = str_replace($base_link, '', $sref);
			if($sref!=''){
				$sref = (is_category()) ? ' ui-sref="app.category({name : \''.$term->slug.'\', path : \''.$sref.'\'})"' : ' ui-sref="app.blog({path : \''.$sref.'\'})"';
			} else {
				$sref = (is_category()) ? ' ui-sref="app.category({name : \''.$term->slug.'\'})"' : ' ui-sref="app.page({slug : \'blog\'})"';
			}
			$page = str_replace('<a', '<a'.$sref, $page);
		}
		echo $page;
		$count++;
	} ?>
<?php
	endif;
 ?>
</div>
