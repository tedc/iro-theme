<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
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

//wc_print_notices();
?>
<div class="account account--grow-lg-bottom account--grid account--shrink account--mw-large" wc-account>
<?php
/**
 * My Account navigation.
 * @since 2.6.0
 */
do_action( 'woocommerce_account_navigation' ); ?>
<?php if(is_account_page() && !is_wc_endpoint_url() && has_post_thumbnail()) : ?>
<figure class="account__cell account__cell--align-center account__cell--s6 account__cell--figure">
	<?php the_post_thumbnail('large');
		/**
		 * My Account content.
		 * @since 2.6.0
		 */
		
	?>
</figure>
<?php endif; do_action( 'woocommerce_account_content' ); ?>
</div>
