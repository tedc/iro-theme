<?php
    $options = $args['options']; 
    $product = $args['product'];
    $attribute = $args['attribute']; 
    $name = isset($args['name']) ? $args['name'] : 'attribute_' . sanitize_title( $attribute ); 
    $id = isset($args['id']) ? $args['id'] : sanitize_title( $attribute ); 
    $class = $args['class']; 
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options. 
 
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) { 
        $attributes = $product->get_variation_attributes(); 
        $options = $attributes[ $attribute ]; 
    } 
    $html = '<div class="variation__select" ng-click="isSelected=!isSelected" click-outside="isSelected=false"><span class="variation__value" ng-bind-html="variationValue(sizeSelected.name, variationPriceIndex)"></span><span class="variation__icons"><i class="icon-arrow-down"></i></span>';
    $html .= '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" ng-model="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">'; 
   
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
            $count_opt = 0;
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) {
                    $term_name = '<strong>'.preg_replace('/([0-9]{1,3}([x][0-9]{1,3})([x][0-9]{1,3}[,][0-9]{1,2})?)/', '<span>$1</span>', $term->name) . '</strong>';
                    
                     $ngselected = $args['selected'] == $term->slug ? ' ng-init=";variationPriceIndex='.$count_opt.';singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '=\''.$args['selected'].'\'; sizeSelected={name :\''.$term_name.'\', sizes :\''.$term->description.'\'}"' : '';
                    
                    $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . $ngselected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>'; 
                    $count_opt++;
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
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
            $c = 0;
            $initialSlide = $c;
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) {
                    if($term->slug != $args['selected'] ) {
                        $initialSlide = $c;
                    } else {
                        $c++;
                    }
                }
            }
        }
    }
    $html .= '<div class="variation__options swiper-container" scroller="product_selector" options="{initialSlide: '.$initialSlide.', slideToClickedSlide: true, \'freeMode\':true, \'direction\':\'vertical\',\'mousewheel\':true,\'slidesPerView\':\'auto\', \'scrollbar\':{\'el\':\'.swiper-scrollbar\', \'draggable\':true}}" ng-class="{\'variation__options--visible\':isSelected}"><div class="swiper-wrapper">';
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
            $count_select = 0;
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) {
                    $_prices = htmlspecialchars( wp_json_encode( display_price_in_variation_option_name($term->name, $product) ) );
                    $_is_custom = (get_field('is_custom_size', $term)) ? 'true' : 'false';
                    //$_prices = str_replace(array('[', ']'), '', $_prices);
                    $term_name = '<strong>'.preg_replace('/([0-9]{1,3}([x][0-9]{1,3})([x][0-9]{1,3})?)/', '<span>$1</span>', $term->name) . '</strong>';
                    $html .= '<div class="variation__option swiper-slide" ng-click="$event.stopPropagation();singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '=\''.esc_attr( $term->slug ) .'\'; sizeSelected={name :\''.$term_name.'\', sizes :\''.$term->description.'\'};getVariation();isSelected=false;variationPriceIndex='.$count_select.';isCustomSize='.$_is_custom.';" ng-class="{\'variation__option--selected\':singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '==\''.esc_attr( $term->slug ) .'\'}" ng-init="variationPrices['.$count_select.']='.$_prices.'">' . $term_name . '<em ng-bind-html="variationPrice('.$count_select.')"></em></div>'; 
                    $count_select++;
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
 
    $html .= '</div><span class="swiper-scrollbar"></span></div>';
    $html .= '</div>';
    echo $html;