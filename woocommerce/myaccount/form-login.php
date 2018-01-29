<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php if(!is_user_logged_in()) :
do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>


<?php endif; ?>
<div class="popup" ng-if="!isUserLoggedIn" login-form id="login">
	<form class="popup__form" name="loginForm" method="post" novalidate ng-submit="sign('login')">
		<header class="popup__header">
			<div class="popup__row popup__row--close">
				<div class="popup__image"></div>
				<i class="icon-chiudi" ng-click="close()"></i>
			</div>
			<div class="popup__row popup__row--grid">
				<h2 class="popup__title popup__title--small"><?php _e( 'Login', 'woocommerce' ); ?></h2>
				<a href="#register" class="popup__switch"><?php _e('Nuovo cliente? Registrati', 'iro'); ?></a>	
			</div>
		</header>
		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<p class="popup__row">
			<input type="text" ng-model="user.username" placeholder="<?php _e('Utente o email (campo obbligatorio)', 'iro'); ?>" class="popup__input popup__input--white" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
		</p>
		<p class="popup__row">
			<input type="password" ng-model="user.password" placeholder="<?php _e('Password (campo obbligatorio)', 'iro'); ?>" class="popup__input popup__input--white" type="password" name="password" id="password" />
		</p>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<p class="popup__row">
			<?php //wp_nonce_field( 'iro-login', 'security' ); ?>
			<input type="hidden" name="security" ng-model="user.security" ng-init="user.security='<?php echo wp_create_nonce('iro-login'); ?>'" />
			<button class="popup__button" ng-class="{'popup__button--loading':isLogging}" type="submit" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php _e( 'Login', 'woocommerce' ); ?></button>
		</p>
		<p class="popup__row popup__row--aligncenter">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
		</p>
		<div class="popup__footer">
			<a href="<?php echo home_url('/'); ?>" ui-sref="app.root({lang : '<?= ICL_LANGUAGE_CODE; ?>'})" class="popup__button popup__button--light"><?php _e('Continua come ospite', 'iro'); ?></a>
		</div>
		<?php do_action( 'woocommerce_login_form_end' ); ?>
	</form>
	<div class="popup__error" ng-if="error" ng-bind-html="errorMessage"></div>
</div>
<?php do_action( 'woocommerce_after_customer_login_form' ); endif; ?>
