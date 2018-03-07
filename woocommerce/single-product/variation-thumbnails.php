<?php
	global $product;
	if($product->is_type('variable')) :

	$product_obj = new WC_Product_Variable( $product->get_id() );
	$variations = $product_obj->get_available_variations();

	foreach ($variations as $variation) :
		$variation_id = $variation['variation_id'];
	$image_ids = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
	
	$image_ids = array_filter( explode( ',', $image_ids ) );

	$product = wc_get_product( $variation_id );

	$variation_main_image = get_post_meta( $variation_id, '_thumbnail_id', true );
	if ( ! empty( $variation_main_image ) ) {
			array_unshift( $image_ids, $variation_main_image );
	}

	// If there are still no image IDs set, fallback to original main image
	if ( $product && empty( $image_ids ) ) {
			$main_image_id = $product->get_image_id();

			if ( ! empty( $main_image_id ) ) {
				array_unshift( $image_ids, $main_image_id );
			}
	}


	$main_images = '<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-' . apply_filters( 'woocommerce_product_thumbnails_columns', 4 ) . ' images" data-columns="' . apply_filters( 'woocommerce_product_thumbnails_columns', 4 ) . '"><figure class="woocommerce-product-gallery__wrapper">';

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
				'title'                   => $image_title,
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			// See if we need to get the first image of the variation
			// only run one time.
			if ( ( apply_filters( 'wc_additional_variation_images_get_first_image', false ) || $this->cloud_zoom_exists() ) && 0 === $loop ) {

				$html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image flex-active-slide">';

				if ( $add_image_link ) {
					$html .= '<a href="' . wp_get_attachment_url( $id ) . '">';
				}

				$html .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );

				if ( $add_image_link ) {
					$html .= '</a>';
				}

				$html .= '</div>';

				$main_images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $id );

				$loop++;
				continue;
			}

			if ( $add_image_link ) {
				$attach_image = '<a href="' . wp_get_attachment_url( $id ) . '">' . wp_get_attachment_image( $id, 'shop_single', false, $attributes ) . '</a>';
			} else {
				$attach_image = wp_get_attachment_image( $id, 'shop_single', false, $attributes );
			}

			// Build the list of variations as main images in case a custom
			// theme has flexslider type lightbox.
			$main_images .= apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div data-thumb="%s" class="woocommerce-product-gallery__image flex-active-slide">%s</div>', esc_url( $thumbnail[0] ), $attach_image ), $post_id );

			$loop++;
		}
	} else {
		$main_images .= '<div class="woocommerce-product-gallery__image--placeholder">';
		$main_images .= wc_placeholder_img();
		$main_images .= '</div>';
	}

	$main_images .= '</figure></div>';
	echo $main_images;
	endforeach;
	endif;