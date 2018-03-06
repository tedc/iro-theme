<?php $faq = get_posts(array(
	'post_type' => 'faq',
	'post__in' => get_sub_field('domande'),
	'posts_per_page' => count(get_sub_field('domande')),
	'orderby' => 'post__in',
	'suppress_filters' => 0
));
if($faq) : ?>
<section class="faq faq--shrink faq--mw-large" ng-init="isFaq=[]">
	<header class="faq__header faq__header--grow-md">
		<?php if(is_page_template('template-faq.php')) : ?>
		<h1 class="faq__title faq__title--aligncenter faq__title--big"><?php the_title(); ?></h1>
		<?php else : ?>
		<h2 class="faq__title faq__title--aligncenter faq__title--big"><?php _e('Domande frequenti', 'iro'); ?></h2>
		<?php endif; ?>
	</header>
	<ul class="faq__list">
	<?php foreach($faq as $f) : 
		$faq_title = get_the_title($f->ID);
	?>
	<li class="faq__item faq__item--grow-md" ng-class="{'faq__item--active':isFaq['<?php echo sanitize_title($faq_title); ?>']}">
		<header class="faq__header faq__header--grid-nowrap" ng-click="isFaq['<?php echo sanitize_title($faq_title); ?>']=!isFaq['<?php echo sanitize_title($faq_title); ?>']">
			<h3 class="faq__subtitle"><?php echo $faq_title; ?></h3>
			<span class="faq__close">+</span>
		</header>
		<div class="faq__content faq__content--grow-top slide-toggle" ng-class="{'slide-toggle--visible':isFaq['<?php echo sanitize_title($faq_title); ?>']}">
			<div class="faq__text faq__text--grow-top">
				<?php echo apply_filters('the_content', $f->post_content); ?>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
	</ul>
</section>
<?php endif; ?>