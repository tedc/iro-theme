<?php $args = array(
	'post_type' => $name,
	'post__in' => get_sub_field($name),
	'posts_per_page' => count(get_sub_field($name)),
	'orderby' => 'post__in',
	'suppress_filters' => 0
);
$datasheet = new WP_Query($args);
if($datasheet->have_posts()) : ?>
<section class="<?php echo $name; ?> <?php echo $name; ?>--grid">
	<header class="<?php echo $name; ?>__header <?php echo $name; ?>__header--grow">
		<h2 class="<?php echo $name; ?>__title <?php echo $name; ?>__title--big"><?php _e('Scheda tecnica', 'iro'); ?></h2>
	</header>
	<?php $c = 0; while($datasheet->have_posts()) : $datasheet->the_post(); ?>
	<div class="<?php echo $name; ?>__row <?php echo $name; ?>__row--grow-md <?php echo $name; ?>__row--grid<?php echo ($c == ($datasheet->found_posts - 1)) ? ' '.$name.'__row--last' : ''; ?>">
		<div class="<?php echo $name; ?>__cell <?php echo $name; ?>__cell--grow-bottom <?php echo $name; ?>__cell--s4 <?php echo $name; ?>__cell--shrink-right-only">
			<h3 class="<?php echo $name; ?>__subtitle"><?php the_title(); ?></h3>
		</div>
		<div class="<?php echo $name; ?>__cell <?php echo $name; ?>__cell--s8">
			<?php the_content(); ?>
		</div>
	</div>
	<?php $c++; endwhile; wp_reset_postdata(); wp_reset_query(); ?>	
	</div>
</section>
<?php endif; ?>