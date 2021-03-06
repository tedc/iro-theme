<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version 3.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$default = ($product->is_type('variable')) ? $product->get_variation_default_attribute( 'pa_color' ) : false;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$swiper_container = ($product->get_gallery_image_ids() && has_post_thumbnail()) ? 'swiper-container' : '';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'product__gallery',
	'product__gallery--' . $placeholder,
	'product__gallery--shrink-right-only',
	'product__gallery--visible',
	'product__gallery--columns-' . absint( $columns ),
	//'product__gallery--grow-md',
	$swiper_container
) );
$swiper_wrapper = ($product->get_gallery_image_ids() && has_post_thumbnail()) ? ' swiper-wrapper' : '';
$swiper_slide = ($product->get_gallery_image_ids() && has_post_thumbnail()) ? ' swiper-slide' : '';
$options = ($product->get_gallery_image_ids() && has_post_thumbnail()) ? ' scroller="product" options="{\'effect\':\'fade\',\'fadeEffect\':{\'crossFade\':true}}"' : '';
?>
<div class="product__images product__images--align-start">
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"<?php echo $options; if($product->is_type('variable') ) : ?> ng-class="{'product__gallery--visible' : singleProductVariation.attribute_pa_color == '<?php echo $default; ?>'}"<?php endif; ?>>
	<div class="product__gallery-wrapper<?php echo $swiper_wrapper; ?>">
		<?php
		$attributes = array(
			'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
			'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
			'data-src'                => $full_size_image[0],
			'data-large_image'        => $full_size_image[0],
			'data-large_image_width'  => $full_size_image[1],
			'data-large_image_height' => $full_size_image[2],
		);

		if ( has_post_thumbnail() ) {
			$html  = '<figure data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'full' ) . '" class="product__gallery-image'.$swiper_slide.'">';
			$html .= get_the_post_thumbnail( $post->ID, 'full', $attributes );
			$html .= '</figure>';
			if($product->get_gallery_image_ids()) {
				ob_start();
				do_action( 'woocommerce_product_thumbnails' );
				$html .= ob_get_clean();
			}
		} else {
			$html  = '<div class="product__gallery-placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</figure>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

		//do_action( 'woocommerce_product_thumbnails' );
		?>
	</div>
	<?php if($product->get_gallery_image_ids() && has_post_thumbnail()) : ?>
	<div class="product__gallery-pagination swiper-pagination swiper-pagination-bullets">
		<?php
		$pages_html  = '<span class="product__gallery-page swiper-pagination-bullet swiper-pagination-bullet-active" data-slide-to="0">';
		$pages_html .= get_the_post_thumbnail( $post->ID, 'shop_thumbnail', $attributes );
		$pages_html .= '</span>';
		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $pages_html, get_post_thumbnail_id( $post->ID ) );
		$attachment_ids = $product->get_gallery_image_ids();
		if ( $attachment_ids && has_post_thumbnail() ) {
			$thumb = 1;
			foreach ( $attachment_ids as $attachment_id ) {
				$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
				$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
				$attributes      = array(
					'title'                   => get_post_field( 'post_title', $attachment_id ),
				);
				$pages_html  = '<span class="product__gallery-page swiper-pagination-bullet" data-slide-to="'.$thumb.'">';
				$pages_html .= wp_get_attachment_image( $attachment_id, 'shop_thumbnail', false, $attributes );
		 		$pages_html .= '</span>';

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $pages_html, $attachment_id );
				$thumb++;
			}
		} ?>
	</div>
	<?php endif; ?>
</div>
<?php wc_get_template_part('single-product/variation-thumbnails'); ?>
</div>
<hr id="product-price-trigger" class="trigger" />