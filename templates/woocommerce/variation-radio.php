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
 
    $html = ''; 
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
 
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) { 
                    $ngselected = $args['selected'] == $term->slug ? ' ng-init="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '=\''.$args['selected'].'\'"' : '';
                    $html .= '<input type="radio" id="attr_'.$term->term_id.'" ng-value="\'' . esc_attr( $term->slug ) . '\'" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . $ngselected . ' name="' . esc_attr( $name ) . '" ng-model="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" ng-change="getVariation()"/><label ng-style="{backgroundColor : \''.get_field('colore', $attribute.'_'.$term->term_id).'\'}" for="attr_'.$term->term_id.'">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</label>'; 
                } 
            } 
        } else {
            $count = 0;
            foreach ( $options as $option ) { 
                // This handles < 2.4.0 bw compatibility where text attributes were not sanitized. 
                $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false ); 
                $html .= '<input value="' . esc_attr( $option ) . '" ' . $selected . ' name="' . esc_attr( $name ) . '" ng-model="singleProductVariation.attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" ng-change="getVariation()"/><label for="">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</label>'; 
                $count++;
            } 
        } 
    } 
 
    $html .= '</select>';
    echo $html;