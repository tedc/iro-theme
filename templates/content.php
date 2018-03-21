<?php
	$is_first = ($count_news == 0 && $paged == 1);
	$post_class = 'post post--grow-lg-top ';
	$post_class .= ($is_first) ? 'post--cell-s12 post--grid' : 'post--cell-s6';
	if(!$is_first) {
		$post_class .= ($count_news%2==0) ? ' post--shrink-left-half' : ' post--shrink-right-half';
	}
?>
<article class="<?php echo $post_class; ?>">
	<?php if(!$is_first) : ?>
		<div class="post__meta post__meta--grid">
			<?php include(locate_template('templates/entry-meta.php', false, false)); ?>
		</div>
	<?php endif; ?>
	<figure class="post__figure<?php echo ($is_first) ? ' post__figure--cell-s6' : ''; ?>">
		<?php the_post_thumbnail('full'); ?>
	</figure>
	<div class="post__content <?php echo ($is_first) ? 'post__content--grow-md post__content--cell-s6' : 'post__content--grow-top'; ?>">
		<?php if($is_first) : ?>
			<?php get_template_part('templates/entry', 'meta'); ?>
			<!-- <h2 class="post__title post__title--medium"><a href="<?php the_permalink(); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink()); ?>'})"><?php the_title(); ?></a></h2> -->
			<h2 class="post__title post__title--medium"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			<!-- <a href="<?php the_permalink(); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink()); ?>'})" class="post__readmore"><?php _e('Leggi tutto', 'iro'); ?><i class="icon-arrow-right"></i></a> -->
			<a href="<?php the_permalink(); ?>" class="post__readmore"><?php _e('Leggi tutto', 'iro'); ?><i class="icon-arrow-right"></i></a>
		<?php else : ?>
			<!-- <h2 class="post__title post__title--medium"><a href="<?php the_permalink(); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink()); ?>'})"><?php the_title(); ?></a></h2> -->
			<h2 class="post__title post__title--medium"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php endif; ?>
	</div>
	<?php if(!$is_first) {?>
	<!-- <a class="post__permalink" href="<?php the_permalink(); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink()); ?>'})"><?php _e('Leggi tutto', 'iro'); ?></a> -->
<a class="post__permalink" href="<?php the_permalink(); ?>"><?php _e('Leggi tutto', 'iro'); ?></a>
		<?php } ?>
</article>