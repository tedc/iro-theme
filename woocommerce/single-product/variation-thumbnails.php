<?php
	global $product;
	$post_id = $product->get_id();
	if($product->is_type('variable')) :

	$product_obj = new WC_Product_Variable( $post_id );
	$variations = $product_obj->get_available_variations();
	$variation_array = array();

	foreach ($variations as $variation) :

	$variation_id = $variation['variation_id'];
	$image_ids = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
	
	$image_ids = array_filter( explode( ',', $image_ids ) );

	$the_product = wc_get_product( $variation_id );

	$variation_main_image = get_post_meta( $variation_id, '_thumbnail_id', true );
	if ( ! empty( $variation_main_image ) ) {
			array_unshift( $image_ids, $variation_main_image );
	}

	// If there are still no image IDs set, fallback to original main image
	if ( $the_product && empty( $image_ids ) ) {
			$main_image_id = $the_product->get_image_id();

			if ( ! empty( $main_image_id ) ) {
				array_unshift( $image_ids, $main_image_id );
			}
	}
	$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
	$placeholder       = 'with-images';
	$swiper_container = 'swiper-container';
	$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
		'product__gallery',
		'product__gallery--' . $placeholder,
		'product__gallery--columns-' . absint( $columns ),
		'product__gallery--align-start',
		'product__gallery--shrink-right-only',
		//'product__gallery--grow-md',
		$swiper_container
	) );
	$swiper_wrapper = ' swiper-wrapper';
	$swiper_slide = ' swiper-slide';
	$options = ' scroller="product" options="{\'effect\':\'fade\',\'fadeEffect\':{\'crossFade\':true}}"';


	$main_images = '<div class="'. esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ).'"'. $options.'><div class="product__gallery-wrapper'. $swiper_wrapper.'">';

	$loop = 0;

	if ( 0 < count( $image_ids ) ) {
		/*
		 * When there is no support for gallery zoom, we need to add
		 * an image link so that the lightbox can be triggered.
		 */
		$add_image_link = current_theme_supports( 'wc-product-gallery-zoom' ) ? false : true;

		// We need to also check if theme supports lightbox.
		if ( ! current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
			$add_image_link = false;
		}

		// Build html.
		foreach ( $image_ids as $id ) {
			$image_title     = esc_attr( get_the_title( $id ) );
			$full_size_image = wp_get_attachment_image_src( $id, 'full' );
			$thumbnail       = wp_get_attachment_image_src( $id, 'shop_thumbnail' );

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $id ),
				'data-caption'            => get_post_field( 'post_excerpt', $id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			// See if we need to get the first image of the variation
			// only run one time.
			// if ( ( apply_filters( 'wc_additional_variation_images_get_first_image', false ) || $this->cloud_zoom_exists() ) && 0 === $loop ) {

			// 	$html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image flex-active-slide">';

			// 	if ( $add_image_link ) {
			// 		$html .= '<a href="' . wp_get_attachment_url( $id ) . '">';
			// 	}

			// 	$html .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );

			// 	if ( $add_image_link ) {
			// 		$html .= '</a>';
			// 	}

			// 	$html .= '</div>';

			// 	$main_images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $id );

			// 	$loop++;
			// 	continue;
			// }

			$attach_image = wp_get_attachment_image( $id, 'shop_single', false, $attributes );
			
			// Build the list of variations as main images in case a custom
			// theme has flexslider type lightbox.

			if($the_product->get_gallery_image_ids()) {
				ob_start();
				do_action( 'woocommerce_product_thumbnails' );
				$html .= ob_get_clean();
			}
			$main_images .= apply_filters( 'woocommerce_single_product_image_html', sprintf( '<figure data-thumb="%s" class="product__gallery-image'.$swiper_slide.'">%s</figure>', esc_url( $thumbnail[0] ), $attach_image ), $post_id );

			$loop++;
		}
		$main_images .= '<div class="product__gallery-pagination swiper-pagination swiper-pagination-bullets">';
		$pages_html  = '<span class="product__gallery-page swiper-pagination-bullet" ng-click="productSlideTo(0)" ng-class="{\'swiper-pagination-bullet-active\' : currentProductSlide == 0}">';
		$pages_html .= get_the_post_thumbnail( $post_id, 'shop_thumbnail', $attributes );
		$pages_html .= '</span>';
		$main_images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $pages_html, get_post_thumbnail_id( $post_id ) );
		$attachment_ids = $the_product->get_gallery_image_ids();
		if ( $attachment_ids && has_post_thumbnail() ) {
			$thumb = 1;
			foreach ( $attachment_ids as $attachment_id ) {
				$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
				$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
				$attributes      = array(
					'title'                   => get_post_field( 'post_title', $attachment_id ),
				);
				$pages_html  = '<span class="product__gallery-page swiper-pagination-bullet" ng-click="productSlideTo('.$thumb.')" ng-class="{\'swiper-pagination-bullet-active\' : currentProductSlide == '.$thumb.'}">';
				$pages_html .= wp_get_attachment_image( $attachment_id, 'shop_thumbnail', false, $attributes );
		 		$pages_html .= '</span>';

				$main_images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $pages_html, $attachment_id );
				$thumb++;
			}
		}
		$main_images .= '</div>';
	} else {
		$main_images .= '<div class="woocommerce-product-gallery__image--placeholder">';
		$main_images .= wc_placeholder_img();
		$main_images .= '</div>';
	}

	$main_images .= '</div></div>';
	if(!in_array($variation['attributes']['attribute_pa_color'], $variation_array)){
		echo $main_images;
	}
	array_push($variation_array, $variation['attributes']['attribute_pa_color']);
	endforeach;
	endif;