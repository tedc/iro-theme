
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

<?php if(!is_user_logged_in()) : do_action( 'woocommerce_before_customer_login_form' ); ?>
<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="popup" ng-if="!isUserLoggedIn" id="register" login-form>
		<?php if(!is_handheld()) { ?>
		<div class="swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto', mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} }">
		<div class="swiper-wrapper">
		<?php } ?>
	
		<form class="popup__form<?php echo (!is_handheld()) ? ' swiper-slide' : ''; ?>" method="post" name="registerForm" novalidate ng-submit="sign('register')">
			<header class="popup__header">
				<div class="popup__row popup__row--close">
					<div class="popup__image"></div>
					<div class="popup__close" ng-click="close()"><?php _e('Chiudi', 'iro'); ?><i class="icon-chiudi"></i></div>
				</div>
				<div class="popup__row popup__row--grid">
					<h2 class="popup__title popup__title--small"><?php _e( 'Register', 'woocommerce' ); ?></h2>
					<a href="#login" class="popup__switch"><?php _e('Già cliente? Accedi', 'iro'); ?></a>	
				</div>
			</header>
			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="popup__row">
					<input type="text" ng-model="user.username" placeholder="<?php _e('Nome utente (campo obbligatorio)', 'iro'); ?>" required class="popup__input popup__input--white" name="username" id="reg_username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
				</p>

			<?php endif; ?>

			<p class="popup__row">
				<input type="email" required ng-model="user.email" placeholder="<?php _e('Indirizzo email (campo obbligatorio)', 'iro'); ?>" class="popup__input popup__input--white" name="email" id="reg_email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="popup__row">
					<input type="password" ng-model="user.password" placeholder="<?php _e('Password (campo obbligatorio)', 'iro'); ?>" required class="popup__input popup__input--white" name="password" id="reg_password" ng-change="pwdMatch(user)" />
				</p>

				<p class="popup__row">
					<input type="password" ng-model="user.password_confirm" placeholder="<?php _e('Conferma password (campo obbligatorio)', 'iro'); ?>" required class="popup__input popup__input--white" name="password_confirm" id="password_confirm" ng-change="pwdMatch(user)" />
				</p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="popup__row">
				<input type="hidden" name="security" ng-model="user.security" ng-init="user.security='<?php echo wp_create_nonce('iro-register'); ?>'" />
				<?php //wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button class="popup__button" type="submit"  ng-class="{'popup__button--loading':isLogging}" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php _e( 'Register', 'woocommerce' ); ?></button>
			</p>


		<div class="popup__footer">
			<span ng-click="close()" class="popup__button popup__button--dark"><?php _e('Continua come ospite', 'iro'); ?></span>
		</div>
			<?php do_action( 'woocommerce_register_form_end' ); ?>
	</form>
	<div class="popup__error" ng-if="error" ng-bind-html="errorMessage"></div>
	<?php if(!is_handheld()) {
		echo '</div><div class="swiper-scrollbar"></div></div>';
	} ?>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); endif; ?>