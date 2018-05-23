<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-account__edit.php.
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

do_action( 'woocommerce_before_edit_account_form' ); ?>
<div class="account__cell account__cell--grow-md" ng-init="isAccount = []">
	<header class="account__header account__header--grid">
		<h3 class="account__subtitle"><?php _e('Dati dell\'account', 'iro'); ?></h3>
		<span class="account__button account__button--slim-dark" ng-click="isAccount['details']=!isAccount['details']; reload()"><?php _e('Modifica', 'iro'); ?></span>
	</header><!-- /header -->
	<div class="account__desc account__desc--grow-md-top slide-toggle slide-toggle--visible" ng-class="{'slide-toggle--visible' : !isAccount['details']}">
		<?php echo esc_attr( $user->first_name ); ?> <?php echo esc_attr( $user->last_name ); ?>
		<p><?php echo esc_attr( $user->user_email ); ?></p>
	</div>
	<form class="account__edit account__edit--grid slide-toggle" name="saveAccount" ng-class="{'slide-toggle--visible':isAccount['details']}" method="post" ng-submit="updateAccount(saveAccount.$valid)">
		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
		<div class="account__cell account__cell--shrink-right-half account__cell--s6">
			<p class="account__row account__row--grow-top">
				<label class="account__label" for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input class="account__input" type="text" ng-model="account.account_first_name" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" ng-init="account.account_first_name='<?php echo esc_attr( $user->first_name ); ?>'"/>
			</p>
		</div>
		<div class="account__cell account__cell--shrink-left-half account__cell--s6">
			<p class="account__row account__row--grow-top">
				<label class="account__label" for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input class="account__input" ng-model="account.account_last_name" type="text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" ng-init="account.account_last_name='<?php echo esc_attr( $user->last_name ); ?>'" />
			</p>
		</div>
		<div class="account__cell account__cell--shrink-right-half account__cell--s6">
			<p class="account__row account__row--grow-top">
				<label class="account__label" for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input class="account__input" ng-model="account.account_email" type="email" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" ng-init="account.account_email='<?php echo esc_attr( $user->user_email ); ?>'" />
			</p>
		</div>
		<div class="account__cell account__cell--shrink-left-half account__cell--s6">
			<p class="account__row account__row--grow-top">
				<label class="account__label" for="password_current"><?php esc_html_e( 'Password corrente', 'iro' ); ?></label>
				<input class="account__input" ng-model="account.password_current" type="password" name="password_current" id="password_current" />
			</p>
		</div>
		<div class="account__cell account__cell--shrink-right-half account__cell--s6">
			<p class="account__row account__row--grow-top">
				<label class="account__label" for="password_1"><?php esc_html_e( 'Nuova password', 'iro' ); ?></label>
				<input class="account__input" ng-model="account.password_1" type="password" name="password_1" id="password_1" />
			</p>
		</div>
		<div class="account__cell account__cell--shrink-left-half account__cell--s6">
			<p class="account__row account__row--grow-top">
				<label class="account__label" for="password_2"><?php esc_html_e( 'Conferma nuova password', 'iro' ); ?></label>
				<input class="account__input" ng-model="account.password_2" type="password" name="password_2" id="password_2" />
			</p>
		</div>


		
		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<footer class="account__footer account__footer--s12 account__footer--grow-top">
			<?php wp_nonce_field( 'save_account_details' ); ?>
			<input type="hidden" ng-model="account._wpnonce" ng-init="account._wpnonce='<?php echo wp_create_nonce('save_account_details'); ?>'">
			<input type="hidden" ng-model="account._wp_http_referer" ng-init="account._wp_http_referer='<?php echo esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ); ?>'" />
			<?php var_dump($user->marketing_input); ?>
			<input type="checkbox" class="account__checkbox"<?php if($user->marketing_input && !empty($user->marketing_input)) { ?> ng-init="account.marketing_input=<?php _e( $user->marketing_input ); ?>"<?php } ?> ng-model="account.marketing_input" id="account_marketing_input" value="true"><label for="account_marketing_input"><span><?php _e("Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl", 'iro'); ?></span></label>
			<button type="submit" class="account__button account__button--dark" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
			<input class="account__input" type="hidden" name="action" value="save_account_details" ng-init="account.action='save_account_details'" />
		</footer>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</div>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
