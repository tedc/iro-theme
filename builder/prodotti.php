<?php
$is_archive = get_sub_field('is_archive');
if($is_archive && get_sub_field('product_category')) {
	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => get_sub_field('product_category')
			)
		),
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'suppress_filters' => 0
	);
} else {
	$args = array(
		'post_type' => 'product',
		'post__in' => get_sub_field('products'),
		'posts_per_page' => count(get_sub_field('products')),
		'orderby' => 'post__in',
		'suppress_filters' => 0
	);
}
$base_class = $is_archive ? 'products' : 'related';
$prodotti = new WP_Query($args);
if($prodotti->have_posts()) : ?>
<section class="<?php echo $base_class; ?>">
	<header class="<?php echo $base_class; ?>__header <?php echo $base_class; ?>__header--mw <?php echo $base_class; ?>__header--aligncenter <?php echo $base_class; ?>__header--grow-md">
		<h2 class="<?php echo $base_class; ?>__title <?php echo $base_class; ?>__title--big"><?php the_sub_field('titolo_'.$name); ?></h2>
		<?php the_sub_field('testo_'.$name); ?>
	</header>
	<?php if($is_archive) : ?>
	<div class="<?php echo $base_class; ?>__container <?php echo $base_class; ?>__container--grow-md-top <?php echo $base_class; ?>--grid">
		<?php $count = 0; while($prodotti->have_posts()) : $prodotti->the_post();
			?>
		<div class="<?php echo $base_class; ?>__cell <?php echo $base_class; ?>__cell--s6 <?php echo $base_class .'__cell--shrink-'; echo ($count%2==0) ? 'right': 'left'; ?>-only">
			<figure class="<?php echo $base_class; ?>__figure">
				<?php the_post_thumbnail('large', array('class'=> $base_class.'__image')); ?>
				<span class="<?php echo $base_class; ?>__button <?php echo $base_class; ?>__button--dark"><?php _e('Scopri', 'iro'); ?></span>
			</figure>
			<div class="<?php echo $base_class; ?>__content <?php echo $base_class; ?>__content--shrink <?php echo $base_class; ?>__content--grow-md">
				<h3 class="<?php echo $base_class; ?>__title <?php echo $base_class; ?>__title--medium"><?php the_title(); ?></h3>
				<?php the_excerpt(); ?>
			</div>
			<a href="<?php the_permalink(); ?>" ui-sref="app.page({slug:'<?php echo basename(get_permalink()); ?>'})" class="<?php echo $base_class; ?>__permalink"><?php the_title(); ?></a>
		</div>
		<?php $count++; endwhile; wp_reset_postdata(); wp_reset_query(); ?>
	</div>
	<?php else : ?>
	<div class="<?php echo $base_class; ?>__container <?php echo $base_class; ?>__container--grow-md-top <?php echo $base_class; ?>__container--shrink-fw swiper-container">
		<ul class="<?php echo $base_class; ?>__list swiper-wrapper" scroller options="{slidesPerView : 'auto', freeMode : true}">
			<?php while($prodotti->have_posts()) : $prodotti->the_post();
			?>
			<li class="<?php echo $base_class; ?>__cell <?php echo $base_class; ?>__cell--shrink <?php echo $base_class; ?>__cell--s4 swiper-slide <?php echo $base_class; ?>__cell--grow-md">
				<a class="<?php echo $base_class; ?>__link" relahref="<?php the_permalink(); ?>" ui-sref="app.page({slug:'<?php echo basename(get_permalink()); ?>'})">
					<?php the_post_thumbnail('large', array('class'=> 'related__image')); ?>
					<span class="<?php echo $base_class; ?>__button <?php echo $base_class; ?>__button--dark"><?php the_title(); ?></span>
				</a>
			</li>
			<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
		</ul>
	</div>
	<?php endif; ?>
</section>
<?php 
endif; ?>