<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
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

//wc_print_notices(); ?>

<form class="resetpassword resetpassword--grow-md-bottom resetpassword--shrink resetpassword--mw-large" name="formPassword" novalidate ng-submit="lostPassword(formPassword)">

	<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

	<p class="resetpassword__row">
		<label for="password_1" class="resetpassword__label"><?php esc_html_e( 'New password', 'woocommerce' ); ?> <span class="resetpassword__required">*</span></label>
		<input type="password" ng-model="passwordFields.password_1" class="resetpassword__input" required ng-attr-placeholder="{{(formPassword.password_1.$error.required && formPassword.passowrd_1.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '<?php esc_html_e( 'New password', 'woocommerce' ); ?>'}}" name="password_1" id="password_1" />
	</p>
	<p class="resetpassword__row">
		<label for="password_2" class="resetpassword__label"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?> <span class="resetpassword__required">*</span></label>
		<input type="password" ng-model="passwordFields.password_2" ng-attr-placeholder="{{(formPassword.password_2.$error.required && formPassword.passowrd_2.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '<?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>'}}" class="resetpassword__input" required name="password_2" id="password_2" />
	</p>

	<input type="hidden" ng-model="passwordFields.reset_key" ng-init="passwordFields.reset_key='<?php echo esc_attr( $args['key'] ); ?>'" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
	<input type="hidden" ng-model="passwordFields.reset_login" ng-init="passwordFields.reset_login='<?php echo esc_attr( $args['login'] ); ?>'" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

	<?php do_action( 'woocommerce_resetpassword_form' ); ?>

	<p class="resetpassword__row">
		<input type="hidden" name="wc_reset_password" ng-model="passwordFields.wc_reset_password" ng-init="passwordFields.wc_reset_password=true" value="true" />
		<button type="submit" class="resetpassword__button"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
	</p>
	<input type="hidden" ng-init="passwordFields._wpnonce='<?php echo wp_create_nonce( 'reset_password' ); ?>'" />
	<div class="resetpassword__message" ng-if="resetPasswordMessage" ng-bind-html="resetPasswordMessage" ng-class="{'resetpassword__message--error' :isResetPasswordError}"></div>
</form>
