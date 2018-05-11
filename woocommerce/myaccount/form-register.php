
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
acf_set_language_to_default();
	$privacy = get_field('privacy_policy', 'options');
	acf_unset_language_to_default();

?>

<?php wc_print_notices(); ?>

<?php if(!is_user_logged_in()) : do_action( 'woocommerce_before_customer_login_form' ); ?>
<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : if(!is_user_logged_in()) : ?>

<div class="popup" id="register" login-form>
		
		<div class="swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto', mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} }">
		<div class="swiper-wrapper">
		
		<form class="popup__form swiper-slide" method="post" name="registerForm" novalidate ng-submit="sign('register')">
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
					<input type="text" ng-model="user.register_security" placeholder="<?php _e('Nome utente (campo obbligatorio)', 'iro'); ?>" required class="popup__input popup__input--white" name="username" id="reg_username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
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
			<p class="popup__row">
				<input type="checkbox" class="popup__checkbox" ng-model="user.privacy_input" id="register_privacy_input" value="true" required><label for="register_privacy_input"><span><?php _e('Acconsento all\'utilizzo dei dati inseriti secondo le finalità indicate dalla', 'iro'); ?> <a href="<?php echo $privacy; ?>" target="_blank">privacy policy</a></span></label>
			<input type="checkbox" class="popup__checkbox" ng-model="user.marketing_input" id="register_marketing_input" required value="true"><label for="register_marketing_input"><span><?php _e("Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl", 'iro'); ?></span></label>
		</p>
			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="popup__row">
				<input type="hidden" name="register_security" ng-model="user.register_security" ng-init="user.register_security='<?php echo wp_create_nonce('iro-register'); ?>'" />
				<?php //wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button class="popup__button" type="submit" ng-disabled="registerForm.$invalid"  ng-class="{'popup__button--loading':isLogging}" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php _e( 'Register', 'woocommerce' ); ?></button>
			</p>


		<div class="popup__footer">
			<span ng-click="close()" class="popup__button popup__button--dark"><?php _e('Continua come ospite', 'iro'); ?></span>
		</div>
			<?php do_action( 'woocommerce_register_form_end' ); ?>
	</form>
	<div class="popup__error" ng-if="error" ng-bind-html="errorMessage"></div>
	<?php 
		echo '</div><div class="swiper-scrollbar"></div></div>';
	 ?>
</div>
<?php endif; endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); endif; ?>