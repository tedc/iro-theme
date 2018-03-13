<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$plus = wp_get_post_terms($product->get_id(), 'product_plus');
if($plus) :
?>
<div class="plus plus--grow-md-bottom plus--shrink-fw" scroller options="{freeMode : true, slidesPerView: 'auto', initialSlide : <?php echo count($plus) > 2 ? 1 : 0; ?>, centeredSlides : true, breakpoints : {640 : {initialSlide : <?php echo count($plus) > 2 ? 1 : 0; ?>}}}">
	<ul class="plus__wrapper swiper-wrapper">
		<?php 
			foreach ($plus as $p) :
			$icon_kind = get_field('icon_kind', 'product_plus_'.$p->term_id);
			$icon = get_field('icon_'.$icon_kind, 'product_plus_'.$p->term_id);
		 ?>
		<li class="plus__item plus__item--shrink swiper-slide">
			<?php if($icon_kind == 'svg') : ?>
			<?php echo print_svg($icon); ?>
			<?php else : ?>
			<i class="icon-<?php echo $icon; ?>"></i>
			<?php endif; ?>
			<span class="plus__name"><?php echo $p->name; ?></span>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
<hr id="product-trigger" class="trigger" />