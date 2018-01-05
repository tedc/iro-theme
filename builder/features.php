<?php $args = array(
	'post_type' => $name,
	'post__in' => get_sub_field($name),
	'posts_per_page' => count(get_sub_field($name)),
	'orderby' => 'post__in',
	'suppress_filters' => 0
);
$features = new WP_Query($args);
if($features->have_posts()) : ?>
<section class="features features--shrink-fw features--grid">
	<header class="features__header features__header--s4 features__header--grow-md">
		<h2 class="features__title features__title--big"><?php the_sub_field('titolo_'.$name); ?></h2>
		<?php the_sub_field('testo_'.$name); ?>
	</header>
	<div class="features__container features__container--grid swiper-container" scroller options="{'slidesPerView' : 'auto', 'freeMode' : true, 'simulateTouch':true,'grabCursor':true}">
		<ul class="features__cell features__cell--s8 swiper-wrapper">
			<?php while($features->have_posts()) : $features->the_post();
			?>
			<li class="features__cell features__cell features__cell--aligncenter features__cell--s8 swiper-slide features__cell--grow-md" id="feature_<?php echo the_ID(); ?>" title="<?php _e('Trascina e scopri', 'iro'); ?>">
				<?php the_post_thumbnail('large'); ?>
				<h3 class="features__title"><?php the_title(); ?></h3>
				<?php the_content(); ?>
			</li>
			<?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
		</ul>
	</div>
</section>
<?php endif; ?>