<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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
wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
    acf_set_language_to_default();
    $phone = get_field('phone', 'options');
    $phone_unformatted = preg_replace('/[^0-9,.]/','',str_replace('+', '00', $phone)); 
    acf_unset_language_to_default();
?>
<form name="checkout" method="post" class="checkout checkout--shrink-fw" ng-submit="sendCheckout(checkout)" novalidate>
	<nav class="checkout__banner checkout__banner--grid checkout__banner--shrink-fw">
		<a href="<?php echo home_url('/'); ?>" ui-sref="app.root({lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})" class="icon-logo"></a>
		<div class="checkout__banner-nav">
			<span ng-click="slideTo(checkout.$valid, 2, 0)" ng-class="{current : checkoutObj.current == 0 && !isConfirm}"><?php _e('I tuoi dati', 'iro'); ?></span>
			<span ng-click="slideTo(checkout.$valid, 2, 1)" ng-class="{current : checkoutObj.current == 1 && !isConfirm}"><?php _e('Spedizione', 'iro'); ?></span>
			<span ng-click="slideTo(checkout.$valid, 2, 2)" ng-class="{current : checkoutObj.current == 2 && !isConfirm}"><?php _e('Pagamento', 'iro'); ?></span>
			<span ng-click="slideTo(checkout.$valid, 2)" ng-class="{current : isConfirm}"><?php _e('Conferma', 'iro'); ?></span>
		</div>
		<a href="tel:<?php echo $phone_unformatted; ?>" class="checkout__banner-btn checkout__banner-btn--phone"><i class="icon-phone"></i><span><?php _e('Assistenza', 'iro'); ?><br/><?php echo $phone; ?></span></a>
	</nav>
	<div class="checkout__main checkout__main--grid slide-toggle slide-toggle--visible" ng-class="{'slide-toggle--visible' : !isConfirm}">
	<?php if ( $checkout->get_checkout_fields() ) : ?>
	<div class="checkout__cell checkout__cell--slider checkout__cell--shrink-right-only checkout__cell--s8">
		<div class="checkout__slider" scroller="checkout" options='{"autoHeight":true, "allowTouchMove":false, "speed" : 1000}'>
			<div class="checkout__slider-wrapper swiper-wrapper">
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
			<div class="checkout__slide checkout__slide--grow-md swiper-slide">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
			
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			</div>
			<nav class="checkout__nav checkout__nav--grow-md checkout__nav--grid">
				<a class="checkout__button checkout__button--dark" href="<?php echo home_url('/'); ?>" ui-sref="app.root({lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})"><?php _e('Continua lo shopping', 'iro'); ?></a>
				<span class="checkout__button" ng-class="{'checkout__button--loading':isCheckoutUpdating}" ng-click="next(true, 2)"><?php _e('Continua', 'iro'); ?></span>
			</nav>
		</div>
	</div>
	<?php endif; ?>
	<aside class="checkout__cell checkout__cell--order-review checkout__cell--shrink-left-only checkout__cell--s4" ng-class="{'slide-toggle--visible' : isConfirm}">
		<div class="order-review">
			<header class="order-review__header order-review__header--mobile">
				<h4 class="order-review__subtitle"><?php _e('Riepilogo ordine'); ?></h4>
				<span class="order-review__header-toggle" ng-click="isOrderToggle=!isOrderToggle"><strong>{{ngCart.totalCost() | currency:'€'}}</strong><i class="icon-arrow-down"></i></span>
			</header>
		<div class="order-review__wrapper slide-toggle" ng-class="{'slide-toggle--visible':isOrderToggle}">
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</div>
		</div>
	</aside>
	</div>
	<?php wc_get_template_part('checkout/form', 'confirm'); ?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
