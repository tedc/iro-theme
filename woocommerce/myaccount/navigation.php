<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
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
	exit;
}

if(is_account_page() && !is_wc_endpoint_url()) :
do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="account__cell account__cell--align-center account__cell--shrink-right-only account__cell--s6">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
			if($endpoint != 'dashboard' && $endpoint != 'edit-address') {
			$btn_class = ($endpoint == 'customer-logout') ? 'account__button--dark' : 'account__button--dark';
		?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<?php if($endpoint=='customer-logout') : ?>
				<div class="account__bubble" ng-class="{'account__bubble--visible':isLogout}">
					<i class="icon-chiudi" ng-click="isLogout=false"></i>
					<p><?php _e('Sei sicuro di voler uscire?', 'iro'); ?> <span class="account__confirm" ng-click="logout('<?php echo wc_logout_url(); ?>')"><?php _e('Sì', 'iro'); ?></span></p>
				</div>
				<?php endif; ?>
				<!-- <a<?php if($endpoint!='customer-logout'){?> ui-sref="app.account({path : '<?php echo basename(wc_get_account_endpoint_url( $endpoint )); ?>'})"<?php } else { ?> ng-class="{'account__button--loading' : isLoggingOut}" ng-click="isLogout=!isLogout"<?php } ?> class="account__button <?php echo $btn_class; ?>"><?php echo esc_html( $label ); ?></a> -->
				<a<?php if($endpoint!='customer-logout'){?> href="<?php echo wc_get_account_endpoint_url( $endpoint ); ?>"<?php } else { ?> ng-class="{'account__button--loading' : isLoggingOut}" ng-click="isLogout=!isLogout"<?php } ?> class="account__button <?php echo $btn_class; ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php } endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); endif;

?>
