<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
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
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices(); ?>

<form class="resetpassword resetpassword--grow-md-bottom resetpassword--shrink resetpassword--mw-large" name="formPassword"  ng-submit="lostPassword(formPassword)" novalidate>

	<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

	<p class="resetpassword__row">
		<label for="user_login" class="resetpassword__label"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?></label>
		<input class="resetpassword__input" required type="text" name="user_login" ng-model="passwordFields.user_login" id="user_login" />
	</p>

	
	<?php do_action( 'woocommerce_lostpassword_form' ); ?>

	<p class="resetpassword__row resetpassword__row--aligncenter">
		<input type="hidden" name="wc_reset_password" ng-init="passwordFields.wc_reset_password=true" value="true" />
		<button type="submit" ng-class="{'resetpassword__button--loading' : passwordRecovering}" class="resetpassword__button" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
	</p>

	<input type="hidden" ng-init="passwordFields._wpnonce='<?php echo wp_create_nonce( 'lost_password' ); ?>'" />

</form>
