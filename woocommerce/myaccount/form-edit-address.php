<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
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
global $wp;

$page_title = ( 'billing' === $load_address ) ? __( 'Billing address', 'woocommerce' ) : __( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

	<form method="post" name="account<?php echo ucfirst($load_address); ?>" class="account__edit slide-toggle" ng-class="{'slide-toggle--visible':isAccount['<?php echo $load_address; ?>']}" ng-submit="save(account<?php echo ucfirst($load_address); ?>.$valid, 'address', '<?php echo $load_address; ?>')" novalidate>

		<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<div class="account-<?php echo $load_address; ?>-fields__wrapper account-<?php echo $load_address; ?>-fields__wrapper--grow-md-top account-<?php echo $load_address; ?>-fields__wrapper--grid">
				<?php
				foreach ( $address as $key => $field ) {
					if ( isset( $field['country_field'], $address[ $field['country_field'] ] ) ) {
						$field['country'] = wc_get_post_data_by_key( $field['country_field'], $address[ $field['country_field'] ]['value'] );
					}
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				?>
			</div>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<div class="account__footer account__footer--grow-top">
				<button type="submit" class="account__button account__button--dark" ng-class="{'account__button--loading':accountFields.isSaving['<?php echo $load_address; ?>']}" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>" ng-disabled="account<?php echo ucfirst($load_address); ?>.$invalid"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
				<?php //wp_nonce_field( 'woocommerce-edit_address' ); ?>
				<input type="hidden" name="user_id" ng-init="accountFields.customer.<?php echo $load_address; ?>.security='<?php echo wp_create_nonce('iro-save-address'.$load_address); ?>'" ng-model="accountFields.customer.<?php echo $load_address; ?>.security" value="?php echo wp_create_nonce('iro-save-address'.$load_address); ?>" />
				<input type="hidden" name="user_id" ng-init="accountFields.customer.<?php echo $load_address; ?>.user_id=<?php echo get_current_user_id(); ?>" ng-model="accountFields.customer.<?php echo $load_address; ?>.user_id" value="<?php echo get_current_user_id(); ?>" />
			</div>
	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
