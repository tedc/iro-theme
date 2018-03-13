<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="swiper-container" scroller options="{slidesPerView: 'auto', direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true}}">
	<div class="swiper-wrapper">
		<div class="swiper-slide">
		<script type="text/ng-template" id="order-review.html">
			<?php wc_get_template_part('checkout/order', 'review'); ?>
		</script>
		<ngcart-cart template-url="order-review.html"></ngcart-cart>
		</div>
	</div>
	<div class="swiper-scrollbar"></div>
</div>