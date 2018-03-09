<?php
    $options = $args['options']; 
    $product = $args['product']; 
    $attribute = $args['attribute']; 
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute ); 
    $id = $args['id'] ? $args['id'] : sanitize_title( $attribute ); 
    $class = $args['class']; 
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options. 
 
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) { 
        $attributes = $product->get_variation_attributes(); 
        $options = $attributes[ $attribute ]; 
    } 
    $html = '<div class="variation__select"><span class="search__value"><strong>{{sizeSelected.name}}</strong>{{sizeSelected.sizes}}</span><span class="search__icons"><i class="icon-arrow-down"></i></span>';
    $html .= '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" ng-model="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">'; 
   
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
 
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) {
                     $ngselected = $args['selected'] == $term->slug ? ' ng-init="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '=\''.$args['selected'].'\'; sizeSelected={name :\''.$term->name.'\', sizes :\''.$term->description.'\'}"' : '';
                    
                    $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . $ngselected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>'; 
                } 
            } 
        } else { 
            foreach ( $options as $option ) { 
                // This handles < 2.4.0 bw compatibility where text attributes were not sanitized. 
                $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false ); 
                $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>'; 
            } 
        } 
    } 
 
    $html .= '</select>';
    $html .= '<div class="variation__options swiper-container" scroller="product_selector" options="{\'freeMode\':true, \'direction\':\'vertical\',\'mousewheel\':true,\'slidesPerView\':\'auto\', \'scrollbar\':{\'el\':\'.swiper-scrollbar\', \'draggable\':true}}"><div class="swiper-wrapper">';
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
 
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) {
                    $html .= '<li  class="variation__option" ng-click="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '=\''.esc_attr( $term->slug ) .'\'; sizeSelected={name :\''.$term->name.'\', sizes :\''.$term->description.'\'};getVariation()" ng-class="{\'variation__option--selected\':singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '==\''.esc_attr( $term->slug ) .'\'}"><strong>' . esc_html( $term->name  ) . '</strong>'.esc_html($term->description).'</li>'; 
                } 
            } 
        } else { 
            foreach ( $options as $option ) { 
                // This handles < 2.4.0 bw compatibility where text attributes were not sanitized. 
                $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false ); 
                $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>'; 
            } 
        } 
    } 
 
    $html .= '</div></div>';
    $html .= '</div>';
    echo $html;