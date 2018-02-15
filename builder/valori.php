<?php $args = array(
	'post_type' => $name,
	'post__in' => get_sub_field($name),
	'posts_per_page' => count(get_sub_field($name)),
	'orderby' => 'post__in',
	'suppress_filters' => 0
);
$image = get_sub_field($name.'_image')['ID'];
$values = new WP_Query($args);
if($values->have_posts()) : ?>
<section class="<?php echo $name; ?> <?php echo $name; ?>--grow-md">
	<span class="<?php echo $name; ?>__nav">
		<i class="icon-arrow-left"></i>
	</span>
	<div class="<?php echo $name; ?>__main">
		<div class="<?php echo $name; ?>__container swiper-container" scroller="values" options="{effect : 'magnify', 'grabCursor' : true, magnifyEffect: {slideResizerClass : '.<?php echo $name; ?>__icon'},  init: false, slideToClickedSlide : true, speed : 750}">
			<ul class="<?php echo $name; ?>__wrapper swiper-wrapper">
				<?php $slide = 0; while($values->have_posts()) : $values->the_post();
				?>
				<li class="<?php echo $name; ?>__item swiper-slide <?php echo $name; ?>__cell--grow-md<?php echo ($slide==0) ? ' swiper-slide-active':''; ?>" id="valore_<?php echo the_ID(); ?>" title="<?php _e('Trascina e scopri', 'iro'); ?>">
					<div class="<?php echo $name; ?>__circle">
						<?php the_post_thumbnail('large', array('class' => $name.'__icon')); ?>
					</div>
					<div class="<?php echo $name; ?>__content">
						<h3 class="<?php echo $name; ?>__title"><?php the_title(); ?></h3>
						<div class="<?php echo $name; ?>__text">
							<?php the_content(); ?>
						</div>
					</div>
				</li>
				<?php $slide++; endwhile; wp_reset_postdata(); wp_reset_query(); ?>
			</ul>
		</div>
	</div>
	<span class="<?php echo $name; ?>__nav">
		<i class="icon-arrow-right"></i>
	</span>
	<?php echo wp_get_attachment_image( $image, 'full', false, array('class' => $name.'__image' )); ?>
</section>
<?php endif; ?>