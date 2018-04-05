<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$pid = wp_get_post_terms( get_the_ID(), 'prodotto_associato' );
the_title( '<h1 class="product__title product__title--big">', '</h1>' );
if($pid) :
$args = array(
	array(
		'taxonomy' => 'prodotto_associato',
		'field' => 'term_id',
		'terms' => array($pid[0]->term_id)
	)
);
$main_total = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $args)));
$ratings = get_terms(array('taxonomy'=>'rating', 'hide_empty'=>0));
$totals = array();
foreach ($ratings as $rate) {
	$tx = array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'rating',
			'field' => 'term_id',
			'terms' => array($rate->term_id)
		),
		array(
			'taxonomy' => 'prodotto_associato',
			'field' => 'term_id',
			'terms' => array($pid[0]->term_id)
		)
	);
	$totals[get_field('rating', 'rating_'.$rate->term_id)] = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $tx)));
}
$average = 0;
foreach ($totals as $key => $value) {
	$average += (intval($key) * $value);
}
$average = $average / $main_total;
$average = round($average, 1);
if(is_numeric($average) && $average > 0) :
?>
<div class="product__stars"><?php stars($average, 'product'); ?> <span class="product__star-label"><?php echo $main_total; ?> <?php _e('recensioni', 'iro'); ?></span></div><?php endif; endif; ?>
