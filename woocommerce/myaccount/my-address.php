<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	), $customer_id );
} else {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
	), $customer_id );
}

?>




<?php foreach ( $get_addresses as $name => $title ) : ?>
	<div class="account__cell account__cell--address account__cell--grow-md">
		<header class="account__header account__header--grid">
			<h3 class="account__subtitle"><?php echo $title; ?></h3>
			<span class="account__button accont__button--slim-light" ng-click="isAccount['<?php echo $name; ?>']=!isAccount['<?php echo $name; ?>']"><?php _e( 'Edit', 'woocommerce' ); ?></span>
		</header>
		<address class="account__desc account__desc--grow-md-top slide-toggle slide-toggle--visible" ng-class="{'slide-toggle--visible' : !isAccount['<?php echo $name; ?>']}"><?php
			$address = wc_get_account_formatted_address( $name );
			echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
		?></address>
		<?php 
			WC_Shortcode_My_Account::edit_address($name);
		?>
	</div>
<?php endforeach; ?>

