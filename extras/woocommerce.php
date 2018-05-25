<?php
    require_once 'wp-seo-extra-setting.php';
    // Remove each style one by one
    add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
    function jk_dequeue_styles( $enqueue_styles ) {
        unset( $enqueue_styles['woocommerce-general'] );    // Remove the gloss
        unset( $enqueue_styles['woocommerce-layout'] );     // Remove the layout
        unset( $enqueue_styles['woocommerce-smallscreen'] );    // Remove the smallscreen optimisation
        return $enqueue_styles;
    }


    remove_action('wp_enqueue_scripts', array('WC_Frontend_Scripts', 'load_scripts'));

    // Or just remove them all in one line
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );
    /*
        @remove woocommerce_breadcrumb - 20
    */
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
    /*
        @remove woocommerce_output_product_data_tabs - 10
        @remove woocommerce_upsell_display - 15
        @remove woocommerce_output_related_products - 20
     */


    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    add_action('woocommerce_single_product_summary', 'woocommerce_show_product_images', 21);
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
    //remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    //add_action( 'woocommerce_before_add_to_cart_button', 'woocommerce_template_single_price', 10);
    add_filter('woocommerce_show_page_title', '__return_false');
    //remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
    add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_checkout_coupon_form', 10, 1 );


    //add_action( 'woocommerce_single_product_summary', array(WC_Structured_Data, 'generate_product_data'), 60 );

    add_filter('woocommerce_variable_price_html', 'custom_variation_price', 10, 2);

    function custom_variation_price( $price, $product ) {
        $defautls = iro_get_default_attributes($product);
        $variation_id = iro_find_matching_product_variation($product, $defautls);
        if($product->is_type('variable') && $variation_id != 0) {
            $selectedproduct = wc_get_product($variation_id);
            $price =  $selectedproduct->get_price();
        }
        $ex_price = explode('.', $price);
        $args = (count($ex_price)>1)?array():array('decimals'=>0);
        return wc_price($price, $args);
    }

    function get_variation_data_from_variation_id( $item_id ) {
        $_product = new WC_Product_Variation( $item_id );
        $variation_data = $_product->get_variation_attributes();
        //$variation_detail = woocommerce_get_formatted_variation( $variation_data, true );  // this will give all variation detail in one line
        $variation_detail = woocommerce_get_formatted_variation( $variation_data, false);  // this will give all variation detail one by one
        return $variation_detail; // $variation_detail will return string containing variation detail which can be used to print on website
        // return $variation_data; // $variation_data will return only the data which can be used to store variation data
    }

    add_filter('woocommerce_checkout_coupon_message', function($tag) {
        $tag = str_replace('href', 'ng-click="$event.preventDefault();isCoupon=!isCoupon" href', $tag);
        return $tag;
    }, 10, 1);

    function iro_wc_price($return, $price, $args) {
        //var_dump($args);
        $return = strip_tags($return, '<ins><del><small>');
        return $return;
    }
    add_filter('wc_price', 'iro_wc_price', 10, 3);
    /**
     * Find matching product variation
     *
     * @param WC_Product $product
     * @param array $attributes
     * @return int Matching variation ID or 0.
     */
    function iro_find_matching_product_variation( $product, $attributes ) {

        foreach( $attributes as $key => $value ) {
            if( strpos( $key, 'attribute_' ) === 0 ) {
                continue;
            }

            unset( $attributes[ $key ] );
            $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
        }

        if( class_exists('WC_Data_Store') ) {

            $data_store = WC_Data_Store::load( 'product' );
            return $data_store->find_matching_product_variation( $product, $attributes );

        } else {

            return $product->get_matching_variation( $attributes );

        }

    }
    /**
     * Get variation default attributes
     *
     * @param WC_Product $product
     * @return array
     */
    function iro_get_default_attributes( $product ) {

        if( method_exists( $product, 'get_default_attributes' ) ) {

            return $product->get_default_attributes();

        } else {

            return $product->get_variation_default_attributes();

        }

    }

    // TEMPLATE ADD TO CART
    function ng_add_to_cart() { ?>
        <script type="text/ng-template" id="addtocart.html">
            <?php wc_get_template('single-product/add-to-cart/ng-add-to-cart.php'); ?>
        </script>
    <?php }
    add_action('woocommerce_single_product_summary', 'ng_add_to_cart', 29);
    // MOVE SHIPPINGS
    function move_shippings() {
        wc_get_template_part('checkout/shipping', 'form' );
    }
    add_action( 'woocommerce_checkout_after_customer_details', function() { ?>
        <input type="hidden" ng-model="checkoutFields.calc_shipping" ng-init="checkoutFields.calc_shipping = 1" />
        <input type="hidden" ng-model="checkoutFields.shipping_calc_security" ng-init="checkoutFields.shipping_calc_security='<?php echo wp_create_nonce('cart-calculate-shipping'); ?>'" />
    <?php }, 9 );
    add_action('woocommerce_checkout_after_customer_details', 'move_shippings', 10);
    add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 11);
    function shipping_title() {
        return __( 'Modalità di spedizione', 'iro' );
    }
    add_filter( 'woocommerce_shipping_package_name', 'shipping_title', 10 );

    // DROPDOWN VARIATIONS
    $filters = array(array('filter_woocommerce_dropdown_variation_attribute_options_args', 1), array('woocommerce_dropdown_variation_attribute_options_html', 2), array('woocommerce_account_menu_items', 1), array('woocommerce_account_menu_item_classes', 2), array('woocommerce_account_orders_columns', 1), array('woocommerce_my_account_my_address_formatted_address', 3), array('woocommerce_formatted_address_replacements', 2), array('woocommerce_localisation_address_formats', 1), array('woocommerce_order_formatted_billing_address', 2), array('woocommerce_ajax_variation_threshold', 1), array('woocommerce_structured_data_product', 2), array('woocommerce_available_variation', 2));
    function my_filter_woocommerce_dropdown_variation_attribute_options_args($args) {
        $args['is_radio'] = false;
        return $args;
    }

    function my_woocommerce_available_variation($obj, $product) {
        $obj['title'] = $product->get_title();
        return $obj;
    }

    function my_woocommerce_ajax_variation_threshold($n) {
        return 70;
    }

    function my_woocommerce_dropdown_variation_attribute_options_html($html, $args) {
        $tpl = ($args['is_radio']) ? 'radio' : 'select';
        ob_start();
        include(locate_template( 'templates/woocommerce/variation-'.$tpl.'.php', false, true ));
        $html = ob_get_clean();
        return $html;
    }
    function my_woocommerce_order_formatted_billing_address($fields, $order) {
        $fields['vat'] = $order->billing_vat;
        return $fields;
    }

    function my_woocommerce_my_account_my_address_formatted_address( $fields, $customer_id, $type ) {
        if ( $type == 'billing' ) {
            $fields['vat'] = get_user_meta( $customer_id, 'billing_vat', true );
        }
        return $fields;
    }

    // Creating merger VAT variables for printing formatting
    function my_woocommerce_formatted_address_replacements( $address, $args ) {
        $address['{vat}'] = '';
        $address['{vat_upper}']= '';
        if ( ! empty( $args['vat'] ) ) {
            $address['{vat}'] = $args['vat'];
            $address['{vat_upper}'] = strtoupper($args['vat']);
        }
        return $address;
    }


    function my_woocommerce_localisation_address_formats( $formats ) {
        $formats['IT'] = "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}\n{vat_upper}";
        return $formats;
    }

    foreach($filters as $filter) {
        add_filter($filter[0], 'my_'.$filter[0], 10, $filter[1]);
    }

    function my_woocommerce_account_menu_item_classes($classes, $endpoint) {
        $classes = array(
            'account__item',
            'account__item--grow-top',
            'account__item--' . $endpoint,
        );
        return $classes;
    }

    function my_woocommerce_account_menu_items($items){
    
        $items['orders'] = __( 'I miei ordini', 'iro' );
        $items['edit-account'] = __( 'Dati personali', 'iro' );
        // array(
        //     'dashboard'       => __( 'Dashboard', 'woocommerce' ),
        //     'orders'          => __( 'Orders', 'woocommerce' ),
        //     'downloads'       => __( 'Downloads', 'woocommerce' ),
        //     'edit-address'    => __( 'Addresses', 'woocommerce' ),
        //     'payment-methods' => __( 'Payment methods', 'woocommerce' ),
        //     'edit-account'    => __( 'Account details', 'woocommerce' ),
        //     'customer-logout' => __( 'Logout', 'woocommerce' ),
        // );
        return $items;
    }

    function my_woocommerce_account_orders_columns($columns) {
        $columns['order-actions'] = '';
        //$columns['order-tracking'] = '';
        return $columns;
    }

    function my_woocommerce_structured_data_product($markup, $product) {
        $markup['sku'] = $product->get_sku() ? $product->get_sku() : $product->get_id();
        $markup['gtin'] = $product->get_sku() ? $product->get_sku() : $product->get_id();
        $prodotto_associato = wp_get_post_terms($product->get_id(), 'prodotto_associato');
        $ratings = get_terms(array('taxonomy'=>'rating'));
        if($prodotto_associato) {
            $total_args = array(
                array(
                    'taxonomy' => 'prodotto_associato',
                    'field' => 'term_id',
                    'terms' => array($prodotto_associato[0]->term_id)
                )
            );        
            $main_total = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $total_args)));  
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
                        'terms' => ($prodotto_associato[0]->term_id)
                    )
                );
                $totals[get_field('rating', 'rating_'.$rate->term_id)] = count(get_posts(array('post_type' => 'recensioni', 'posts_per_page' => -1, 'tax_query' => $tx)));
            }
            $average = 0;
            foreach ($totals as $key => $value) {
                $average += (intval($key) * $value);
            }
            $average = $average / $main_total;
            $markup['aggregateRating'] = array(
                '@type'       => 'AggregateRating',
                'ratingValue' => round($average, 1),
                'reviewCount' => $main_total,
            );
        }
        return $markup;
    }

    // FORMS

    function checkout_forms_args($args, $key, $value) {
        if(is_admin()) {
            return $args;
        }
        if($key == 'marketing_input') {
            return;
        }
        $base_field = (is_checkout()) ? 'checkout' : 'account';
        //$ng_model_key = (is_checkout()) ? str_replace(array('billing_', 'shipping_'), array('', 's_'), $key) : $key;
        $ng_model_key = $key;
        $form_type = preg_match('/(shipping)/', $key) ? 'shipping' : 'billing';
        $name_base = (is_checkout()) ? $base_field : $base_field . ucfirst($form_type);
        $customer_base = (is_checkout()) ? 'customer.' : 'customer.' . $form_type . '.';
        $args['custom_attributes']['ng-model'] = $base_field. 'Fields.'.$customer_base.$ng_model_key;
        $val = ($value) ? $value : $args['default'];
        $args['custom_attributes']['ng-init'] = $base_field. 'Fields.'.$customer_base.$ng_model_key.'=\''.$val.'\'';
        
        $args['custom_attributes']['ng-attr-placeholder'] = '{{('.$name_base. '.'.$key.'.$error.required && '.$name_base. '.'.$key.'.$touched) ? \''.__('Campo obbligatorio', 'iro').'\' : \''.$args['placeholder'].'\'}}';
        
        foreach ($args as $arg => $value) {
            if($arg == 'type'){
                add_filter('woocommerce_form_field_'.$value, 'iro_form_field', 10, 4 );
                break;
            }
        }
        return $args;
    }

    add_filter( 'woocommerce_form_field_args', 'checkout_forms_args', 10, 3 );

    add_action( 'woocommerce_after_edit_account_form', function() {
        wc_get_template_part('myaccount/my-address');
    }, 10, 1 );

    function iro_form_field($field, $key, $args, $value) {
        if(is_admin()) {
            return $field;
        }
        
        if($key == 'marketing_input') {
            return;
        }
        $kind = explode('_', $key)[0];
        $type = str_replace('wooccm', '', $args['type']);
        $field = strip_tags($field, '<input><select><option><label>');
        $field = str_replace('*', '', $field);
        $modifier = ($type == 'country' || $type == 'state') ? 'select' : $type;
        $base_class = (is_checkout()) ? 'checkout' : 'account';
        $field = '<div class="'.$base_class.'-'.$kind.'-fields__cell '.$base_class.'-'.$kind.'-fields__cell--s6 '.$base_class.'-'.$kind.'-fields__cell--'.str_replace('wooccm', '', $args['type']).'">'.$field.'</div>';
        if($type!='textarea' && $type!='state' && $type!='country') {
            $field = str_replace('input-text', ''.$base_class.'__input', $field);   
        } elseif($type=='textarea') {
            $field = str_replace('input-text', ''.$base_class.'__textarea', $field);
        }
        $required = '';
        if($args['required']) {
            if(preg_match('/(billing)/', $key)) {
                $required = ' required';
            } else {
                $required = ' ng-required="checkShippingAddress"';
            }
        }
        if($args['required']) {
            $field = str_replace('</label>', ' <span>*</span></label>', $field);
        }
                
        //$field = str_replace('placeholder', $placeholder.'placeholder', $field);
        $field = str_replace('<input', '<input'.$required, $field);

        if($type == 'country') {
            $options = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();
            if(count($options) > 1){
                $prepend = '<div class="'.$base_class.'__select" ng-click="isCountrySelected=!isCountrySelected"><span class="'.$base_class.'__value" ng-bind-html="(checkoutFields.'.$key.'_value) ? checkoutFields.'.$key.'_value : \''.$args['label'].'\'"></span><span class="'.$base_class.'__icons"><i class="icon-arrow-down"></i></span>';
                $field = str_replace('</label>', '</label>'.$prepend, $field);
                $li = '';
                $count_opts = 0;
                $initialSlide = $count_opts;
                foreach ( $options as $skey => $svalue ) {
                    $value_selected = ($skey == $value) ? ' ng-init="checkoutFields.'.$key.'_value=\''.addslashes($svalue).'\'"' : '';
                    if($skey == $value) {
                        $initialSlide = $count_opts;
                    } else {
                        $count_opts++;
                    }
                    $li .= '<div class="'.$base_class.'__option swiper-slide" ng-class="{\''.$base_class.'__option--selected\':'.$args['custom_attributes']['ng-model'].'==\'' . esc_attr( $skey ) . '\'}" ng-click="'.$args['custom_attributes']['ng-model'].'=\'' . esc_attr( $skey ) . '\';checkoutFields.'.$key.'_value=\''.addslashes($svalue).'\'"'.$value_selected.'>' . $svalue . '</div>';
                }
                $ul = '<div class="'.$base_class.'__options swiper-container" ng-class="{\''.$base_class.'__options--visible\':isCountrySelected}" scroller="'.$key.'" options="{initialSlide : '.$initialSlide.', slideToClickedSlide: true,\'freeMode\':true, \'direction\':\'vertical\',\'mousewheel\':true,\'slidesPerView\':\'auto\', \'scrollbar\':{\'el\':\'.swiper-scrollbar\', \'draggable\':true}}"><div class="swiper-wrapper">';
                $ul .= $li;
                $ul .= '</div><span class="swiper-scrollbar"></span></div></div>';
                $field = str_replace('</div>',$ul.'</div>', $field);
            } else {
                $field = str_replace('</label>', '</label><p class="checkout__mandatory">', $field);
                $field = str_replace('<input', '</p><input', $field);
                $field = str_replace('input-text', ''.$base_class.'__input', $field);
            }
        }
        if($type == 'state') {
            /* Get Country */
            $country_key = 'billing_state' === $key ? 'billing_country' : 'shipping_country';
            $current_cc  = WC()->checkout->get_value( $country_key );
            $states      = WC()->countries->get_states( $current_cc );
            $custom_attributes = array();
            $args['custom_attributes'] = array_filter( (array) $args['custom_attributes'] );
            if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
                foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
                }
            }
            $count_states = 0;
            $stateInitialSlde = $count_states;
                
            if(is_array( $states ) && !empty( $states )) {
                foreach ( $states as $ckey => $cvalue ) {
                    if($ckey == $value) {
                        $default_value = ' ng-init="checkoutFields.'.$key.'_value=\''.$cvalue.'\'"';
                        $stateInitialSlde = $count_states;
                        break;
                    }
                    $count_states++;
                }
            } else {
                $default_value = '';
            }
            $model = str_replace('state', 'country', $args['custom_attributes']['ng-model']);
            $field = '<div class="'.$base_class.'-'.$kind.'-fields__cell '.$base_class.'-'.$kind.'-fields__cell--s6 '.$base_class.'-'.$kind.'-fields__cell--'.str_replace('wooccm', '', $args['type']).'"'.$default_value.'><label for="'.$key.'">'.$args['label'].'</label>';
            $prepend = '<div ng-if="getCountries('.$model.')" class="'.$base_class.'__select" ng-click="isStateSelected=!isStateSelected" click-outside="isStateSelected=false"><span class="'.$base_class.'__value" ng-bind-html="(checkoutFields.'.$key.'_value) ? checkoutFields.'.$key.'_value : \''.$args['label'].'\'"></span><span class="'.$base_class.'__icons"><i class="icon-arrow-down"></i></span>';
            $field .= $prepend;
            $field .= '<select name="'.$key.'"'. implode(' ', $custom_attributes) .' ng-options="key as val for (key, val) in getCountries('.$model.')"'.$required.'></select>';
            $ul = '<div class="'.$base_class.'__options" scroller="'.$key.'" ng-class="{\''.$base_class.'__options--visible\':isStateSelected}" options="{initialSlide : '.$stateInitialSlde.', slideToClickedSlide: true, \'freeMode\':true, \'direction\':\'vertical\',\'mousewheel\':true,\'slidesPerView\':\'auto\', \'scrollbar\':{\'el\':\'.swiper-scrollbar\', \'draggable\':true}}"><div class="swiper-wrapper"><div class="'.$base_class.'__option swiper-slide" ng-class="{\''.$base_class.'__option--selected\':'.$args['custom_attributes']['ng-model'].'==key}" ng-click="'.$args['custom_attributes']['ng-model'].'=key;checkoutFields.'.$key.'_value=val" ng-repeat="(key, val) in getCountries('.str_replace('state', 'country', $args['custom_attributes']['ng-model']).')" ng-bind-html="val" on-finish-render="update_scroller"></div>';
            
            $ul .= '</div><span class="swiper-scrollbar"></span></div></div>';
            $ul .= '<input type="text" id="'.$key.'" class="'.$base_class.'__input" name="'.$key.'"'. implode(' ', $custom_attributes).'" ng-if="!getCountries('.$model.')"'.$required.'/>';
            $field .= $ul.'</div>';
        }
        return $field;
    }

    add_filter('woocommerce_cart_shipping_method_full_label', function($label, $method){
        $label = str_replace(': ' .wc_price($method->cost), '', $label);
        $append = '<br><span class="shipping__sublabel">'.__('Corriere espresso', 'iro').'</span>';
        return '<span>'.$label . $append.'</span>';
    }, 10, 2);


    function my_weso_add_extra_shipping_options( $method, $index ) {

    if ( 'no' === get_option( 'enable_woocommerce_extra_shipping_options', 'yes' ) ) {
        return;
    }

    $extra_shipping_options = weso_get_shipping_option_posts();

    $packages = WC()->shipping->get_packages();
    foreach ( $packages as $package_index => $package ) :

        // Ensure its only displaying for the current shipping package
        if ( $index !== $package_index ) {
            continue;
        }

        $chosen_method = isset( WC()->session->chosen_shipping_methods[ $package_index ] ) ? WC()->session->chosen_shipping_methods[ $package_index ] : '';
        foreach ( $extra_shipping_options as $shipping_option_post ) :
            $condition_groups = get_post_meta( $shipping_option_post->ID, '_shipping_option_conditions', true );
            $match = wpc_match_conditions( $condition_groups, array( 'context' => 'weso', 'package' => $package, 'package_index' => $package_index ) );
        endforeach;
        // Only set the options for the chosen shipping rate
        if($match):
        ?><div class='shipping__extra shipping__extra--grow slide-toggle' ng-class="{'slide-toggle--visible' : checkoutFields.shipping_method[<?php echo $package_index; ?>] == '<?php echo $method->id; ?>'}"><?php
            foreach ( $extra_shipping_options as $shipping_option_post ) :

                $condition_groups = get_post_meta( $shipping_option_post->ID, '_shipping_option_conditions', true );
                if ( wpc_match_conditions( $condition_groups, array( 'context' => 'weso', 'package' => $package, 'package_index' => $package_index ) ) ) :
                    //ob_start();
                    $post_id = $shipping_option_post->ID;
                    if ( empty( $post_id ) || 'shipping_option' != get_post_type( $post_id ) ) :
                        return;
                    endif;

                    foreach ( weso_get_shipping_options( $post_id ) as $key => $shipping_option ) :

                        if ( $option = new WESO_Shipping_Option( $post_id, $shipping_option['option_id'], $package_index ) ) :
                            $inputClass = new WESO_Iro_Checkbox_Field($option, $option->args );
                            $inputClass->output( $package_index );
                        else :
                            do_action( 'woocommerce_extra_shipping_options_output_' . $shipping_option['option_type'], $post_id, $package_index );
                        endif;

                    endforeach;
                endif;
            endforeach;
        ?></div><?php endif;

    endforeach;

}
remove_action( 'woocommerce_after_shipping_rate', 'weso_add_extra_shipping_options', 10 );
add_action( 'woocommerce_after_shipping_rate', 'my_weso_add_extra_shipping_options', 10, 2 );


// PAYMENT ICONS
function iro_gateway_icon($icon, $id) {
    if($id == 'paypal') : 
        $icon = print_svg( get_stylesheet_directory_uri() . '/assets/images/paypal.svg' ); 
    else:
        $icon = '<i class="icon-'.$id.'"></i>';
    endif; 
    return $icon;
}
add_filter('woocommerce_gateway_icon', 'iro_gateway_icon', 10, 2);

// ADD BUILDER

function add_builder_to_product() {
    wc_get_template_part('single-product/plus');
    get_template_part( 'builder/init' );
}
add_action('woocommerce_after_single_product', 'add_builder_to_product', 30);

// ACCOUNT

require_once 'woocommerce-api.php';

function prova_xml($xml, $rec) {
    $str = '';
    foreach ($rec as $field => $value) {
        $str .= $field .'_' .$value;
        add_filter('woe_xml_child_labels_'.$field, function($child_labels) {
            $child_labels = strtolower($child_labels);
            return $child_labels . 'cazzo';
        });
    }
    return $xml;
}
add_filter('woe_xml_output_filter', 'prova_xml', 10, 2);


function has_bought_items($prod_arr) {
    $bought = false;
    // Get all customer orders
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => 'shop_order', // WC orders post type
        'post_status' => 'wc-completed' // Only orders with status "completed"
    ) );
    foreach ( $customer_orders as $customer_order ) {
        // Updated compatibility with WooCommerce 3+
        $order = wc_get_order( $customer_order );
        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
        
        // Iterating through each current customer products bought in the order
        foreach ($order->get_items() as $item) {
            // WC 3+ compatibility
            if ( version_compare( WC_VERSION, '3.0', '<' ) ) 
                $product_id = $item['product_id'];
            else
                $product_id = $item->get_product_id();

            // Your condition related to your 2 specific products Ids
            if ( in_array( $product_id, $prod_arr ) ) 
                $bought = true;
        }
    }
    // return "true" if one the specifics products have been bought before by customer
    return $bought;
}

function display_price_in_variation_option_name( $term, $product ) {
    global $wpdb;
    $id = $product->get_id();
    $result = $wpdb->get_col( "SELECT slug FROM {$wpdb->prefix}terms WHERE name = '$term'" );

    $term_slug = ( !empty( $result ) ) ? $result[0] : $term;

    $query = "SELECT postmeta.post_id AS product_id
    FROM {$wpdb->prefix}postmeta AS postmeta
    LEFT JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
    WHERE postmeta.meta_key LIKE 'attribute_%'
    AND postmeta.meta_value = '$term_slug'
    AND products.post_parent = $id";

    $variation_ids = $wpdb->get_col( $query );
    $array = array();
    foreach ($variation_ids as $variation_id) {
        $_product = new WC_Product_Variation($variation_id);

        $_color = get_term_by( 'name', $_product->get_attribute('color'), 'pa_color');
        if($_color) {
           $array[$_color->slug] = wc_price( $_product->get_price());
        } else {
            $array[] = wc_price( $_product->get_price());
        }
    }
    // $parent = wp_get_post_parent_id( $variation_id[0] );
    // $string = '';
    // if ( $parent > 0 ) {
    //     $_product = new WC_Product_Variation( $variation_id[0] );

    //     //this is where you can actually customize how the price is displayed
    //     $string = woocommerce_price( $_product->get_price() );
    // }
    return $array;
} 


function my_woocommerce_cancelled_order($id) {
    $url = is_user_logged_in() ? wc_get_page_permalink('myaccount') : home_url();
    wc_add_notice( sprintf( __( 'Il tuo ordine numero %s è stato cancellato con successo.', 'iro' ), $id  ), 'error' );
    wp_redirect( $url );
    exit;
}
add_action('woocommerce_cancelled_order', 'my_woocommerce_cancelled_order', 10, 1);


// INVOICE

add_action( 'wpo_wcpdf_after_item_meta', 'wpo_wcpdf_product_custom_field', 10, 3 );
function wpo_wcpdf_product_custom_field ( $template_type, $item, $order ) {
    // check if product exists first
    if (empty($item['product'])) return;
    $invoice = wcpdf_get_invoice( $order, true ); // true makes sets 'init' which makes sure an invoice number is created;
    $invoice_number = $invoice->get_number();
    // replace 'Location' with your custom field name!
    $lotto = get_field('lotto_dispositivo_medico', 'options');
    if (!empty($lotto)) {
        foreach ($lotto as $l) {
            if($l['prodotto'] ==  $item['product']->id || $l['prodotto_su_misura'] == $item['product']->id) :
                if($invoice_number >= $l['numero_fattura_minima'] && $invoice_number <= $l['numero_fattura_massima']) {
                    echo '<div class="product-medical">Lotto dispositivo medico: '.$l['numero_lotto'].'</div>';
                }
            endif;
        }
    }
}
/*
 * Validate when adding to cart
 * @param bool $passed
 * @param int $product_id
 * @param int $quantity
 * @return bool
 */
function iro_custom_size_add_to_cart_validation($passed, $product_id, $qty){

    if( isset( $_POST['_custom_size'] ) && sanitize_text_field( $_POST['_custom_size'] ) == '' ){
        $product = wc_get_product( $product_id );
        wc_add_notice( sprintf( __( '%s cannot be added to the cart until you enter some custom text.', 'kia-plugin-textdomain' ), $product->get_title() ), 'error' );
        return false;
    }

    return $passed;

}
add_filter( 'woocommerce_add_to_cart_validation', 'iro_custom_size_add_to_cart_validation', 10, 3 );

 /*
 * Add custom data to the cart item
 * @param array $cart_item
 * @param int $product_id
 * @return array
 */
function iro_custom_size_add_cart_item_data( $cart_item, $product_id ){

    if( isset( $_POST['_custom_size'] ) ) {
        $cart_item['custom_size'] = sanitize_text_field( $_POST['_custom_size'] );
    }

    return $cart_item;

}
add_filter( 'woocommerce_add_cart_item_data', 'iro_custom_size_add_cart_item_data', 10, 2 );


/*
 * Add meta to order item
 * @param int $item_id
 * @param array $values
 * @return void
 */
function iro_custom_size_add_order_item_meta( $item_id, $values ) {

    if ( ! empty( $values['custom_size'] ) ) {
        wc_add_order_item_meta( $item_id, 'custom_size', $values['custom_size'] );           
    }
}
add_action( 'woocommerce_add_order_item_meta', 'iro_custom_size_add_order_item_meta', 10, 2 );

add_action( 'woocommerce_checkout_create_order_line_item', 'custom_checkout_create_order_line_item', 20, 4 );
// function custom_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
    
//     // Get cart item custom data and update order item meta
//     if( isset( $values['custom_size'] ) ) {
//         $item->update_meta_data( 'custom_size', $values['custom_size'] );
//     }
// }


/*
 * Get item data to display in cart
 * @param array $other_data
 * @param array $cart_item
 * @return array
 */
function iro_custom_size_get_item_data( $other_data, $cart_item ) {

    if ( isset( $cart_item['custom_size'] ) ){

        $other_data[] = array(
            'name' => __( 'Misure', 'iro' ),
            'value' => sanitize_text_field( $cart_item['custom_size'] )
        );

    }

    return $other_data;

}
add_filter( 'woocommerce_get_item_data', 'iro_custom_size_get_item_data', 10, 2 );

/*
 * Show custom field in order overview
 * @param array $cart_item
 * @param array $order_item
 * @return array
 */
function iro_custom_size_order_item_product( $cart_item, $order_item ){

    if( isset( $order_item['custom_size'] ) ){
        $cart_item_meta['custom_size'] = $order_item['custom_size'];
    }

    return $cart_item;

}
add_filter( 'woocommerce_order_item_product', 'iro_custom_size_order_item_product', 10, 2 );


/* 
 * Add the field to order emails 
 * @param array $keys 
 * @return array 
 */ 
function iro_custom_size_email_order_meta_fields( $fields ) { 
    $fields['custom_size'] = __( 'Misure', 'iro' ); 
    return $fields; 
} 
add_filter('woocommerce_email_order_meta_fields', 'iro_custom_size_email_order_meta_fields');

function iro_custom_size_order_item_display_meta_key($display_key, $meta) {
    if($meta->key == 'custom_size') {
        $display_key = __('Misure', 'iro');
    }
    return $display_key;
}

add_filter('woocommerce_order_item_display_meta_key', 'iro_custom_size_order_item_display_meta_key', 10, 2);

add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['free_gift']) {
        $qty = count($_POST['free_gift']);
        update_post_meta( $order_id, '_free_gift_total', $qty);
        $i = 0;
        foreach ($_POST['free_gift'] as $f) {
            update_post_meta( $order_id, '_free_gift_product_id_'.$i, $f['id']);
            update_post_meta( $order_id, '_free_gift_product_qty_'.$i, $f['qty']);
            $i++;
        }
    }
}

add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 11, 3 );

function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
    if(get_post_meta( $order->id, '_free_gift_total')) {
        $html.= '<div style="margin-bottom:40px"><table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; border: 1px solid #e5e5e5;" border="1"><thead><tr><th class="td" scope="col" style="text-align: left; color: #797a7c; border: 1px solid #e5e5e5; padding: 12px;">Prodotto</th><th class="td" scope="col" style="text-align: left; color: #797a7c; border: 1px solid #e5e5e5; padding: 12px;">Quantità</th></tr></thead><tbody>';

        for($i = 0; $i < get_post_meta($order->id, '_free_gift_total', true); $i++) {
            $html .= '<tr class="order_item"><td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; padding: 12px;">'.get_the_title(get_post_meta($order->id, '_free_gift_product_id_'.$i, true)).'</td>
            <td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; padding: 12px;">'.get_post_meta($order->id, '_free_gift_product_qty_'.$i, true).'</td></tr>';
        }

        $html .= '</tbody></table></div>';

        $fields['free_gift'] = array(
            'label' => __( 'Prodotti omaggio' ),
            'value' => $html,
        );
    }
    return $fields;
}

add_filter('woocommerce_email_order_meta_fields','tracking_woocommerce_email_order_meta_fields', 10, 3 ); 
function tracking_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) { 
    $html = '';
    if(get_field('corriere', $order->get_id()) ) : 
        $html.= '<div style="margin-bottom:40px"><table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; border: 1px solid #e5e5e5;" border="1"><thead><tr><th class="td" scope="col" style="text-align: left; color: #797a7c; border: 1px solid #e5e5e5; padding: 12px;">Prodotto</th><th class="td" scope="col" style="text-align: left; color: #797a7c; border: 1px solid #e5e5e5; padding: 12px;">Codice di tracciamento</th></tr></thead><tbody><tr class="order_item"><td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; padding: 12px;">'.get_field('corriere', $order->get_id()).'</td>';
        if(get_field('tracking_code', $order->get_id())) : 
            $html .=  '<td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; padding: 12px;">'.get_field('tracking_code', $order->get_id()).'</td>';
        endif; 
    $html .= '</tbody></table><table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; border: 1px solid #e5e5e5;border-top:0;" border="1"><thead><th class="td" scope="col" style="text-align: left; color: #797a7c; border: 1px solid #e5e5e5; padding: 12px;border-top:0;">Link per il tracciamento</th><th class="td" scope="col" style="text-align: left; color: #797a7c; border: 1px solid #e5e5e5;border-top:0;padding: 12px;">Data di spedizione</th></thead><tbody><tr class="order_item">';

        if(get_field('tracking_url', $order->get_id())) :
            $html .=  '<td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; padding: 12px;"><a href="'.get_field('tracking_url', $order->get_id()).'">Url</a></td>';
        endif; 
        if(get_field('tracking_date', $order->get_id())) :
            $html .='<td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; color: #797a7c; padding: 12px;">'.get_field('tracking_date', $order->get_id()). '</td>';
         endif; 
    $html .= '</tbody></table></div>';
    endif;
    $fields['tracking_code'] = array(
        'label' => __( 'Segui la spedizione' ),
        'value' => $html,
    );
    return $fields;
}

function your_custom_field_function_name($order_id){
    $html = '';
    if(get_post_meta( $order_id, '_free_gift_total')) {
        $html .= '<tr><td class="label">'.__( 'Prodotti omaggio con il coupon:', 'iro' ).'</td><td width="1%"></td><td>';
        for($i = 0; $i < get_post_meta( $order_id, '_free_gift_total', true); $i++) {
            $html .= '<strong>'.get_the_title(get_post_meta( $order_id, '_free_gift_product_id_'.$i, true)).'</strong> x'.get_post_meta( $order_id, '_free_gift_product_qty_'.$i, true).'<br/>';
        }
        $html .= '</td></tr>';
    }
    echo $html;
}

add_action( 'woocommerce_admin_order_totals_after_discount', 'your_custom_field_function_name', 10, 1 );

add_action( 'wpo_wcpdf_after_order_data', 'wpo_wcpdf_delivery_date', 10, 2 );
function wpo_wcpdf_delivery_date ($template_type, $order) {
    $order_id = $order->get_id();
    $html = '';
    if(get_post_meta( $order_id, '_free_gift_total')) {
        $html .= '<tr><td class="label">'.__( 'Prodotti omaggio con il coupon:', 'iro' ).'</td><td>';
        for($i = 0; $i < get_post_meta( $order_id, '_free_gift_total', true); $i++) {
            $html .= '<strong>'.get_the_title(get_post_meta( $order_id, '_free_gift_product_id_'.$i, true)).'</strong> x'.get_post_meta( $order_id, '_free_gift_product_qty_'.$i, true).'<br/>';
        }
        $html .= '</td></tr>';
    }
    echo $html;
}

// Delete Account Functionality 

add_action( 'template_redirect', 'custom_vc_endpoint', 10, 5 );
function custom_vc_endpoint(){
    global $wp_query;
    if(isset($wp_query->query['delete-account'])){
        if(!current_user_can( 'manage_options' )){
                // Get the current user
            $user_id = get_current_user_id();

            // Get user meta
            $meta = get_user_meta( $user_id );
            if($meta){
                foreach ( $meta as $key => $val ) {
                    delete_user_meta( $user_id, $key );
                }
            }

            // Destroy user's session
            wp_logout();

            // Delete the user's account
            require_once(ABSPATH.'wp-admin/includes/user.php' );
            $deleted = wp_delete_user( $user_id );

            wp_redirect( home_url('/') );
            die();
        } else {
            wp_redirect( wc_get_page_permalink('myaccount') );
            die();
        }
    }
}

// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

/**
 * Create new fields for variations
 *
*/
function variation_settings_fields( $loop, $variation_data, $variation ) {

    // Text Field
    woocommerce_wp_text_input( 
        array( 
            'id'          => '_aviability_text[' . $variation->ID . ']', 
            'label'       => __( 'Disponibilità in', 'iro' ), 
            'placeholder' => '',
            'desc_tip'    => 'true',
            'description' => __( 'Inserisci il testo su eventuale disponibilità (es: \'Disponibile in 15 giorni.\'.', 'woocommerce' ),
            'value'       => get_post_meta( $variation->ID, '_aviability_text', true )
        )
    );
}

/**
 * Save new fields for variations
 *
*/
function save_variation_settings_fields( $post_id ) {
    // Text Field
    $text_field = $_POST['_aviability_text'][ $post_id ];
    if( ! empty( $text_field ) ) {
        update_post_meta( $post_id, '_aviability_text', esc_attr( $text_field ) );
    }
}


// TERMINI PER MARKETING

function iro_get_account_fields() {
    return apply_filters( 'iro_account_fields', array(
        'marketing_input' => array(
            'type'        => 'checkbox',
            'label'       => __("Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl", 'iro'),
            'placeholder' => __( "Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl", 'iro')
        ),
    ) );
}


function iro_is_field_visible( $field_args ) {
    $visible = true;
    $action = filter_input( INPUT_POST, 'action' );
 
    if ( is_admin() && ! empty( $field_args['hide_in_admin'] ) ) {
        $visible = false;
    } elseif ( ( is_account_page() || $action === 'save_account_details' ) && is_user_logged_in() && ! empty( $field_args['hide_in_account'] ) ) {
        $visible = false;
    } elseif ( ( is_account_page() || $action === 'save_account_details' ) && ! is_user_logged_in() && ! empty( $field_args['hide_in_registration'] ) ) {
        $visible = false;
    } elseif ( is_checkout() && ! empty( $field_args['hide_in_checkout'] ) ) {
        $visible = false;
    }
 
    return $visible;
}
function iro_is_userdata( $key ) {
    $userdata = array(
        'user_pass',
        'user_login',
        'user_nicename',
        'user_url',
        'user_email',
        'display_name',
        'nickname',
        'first_name',
        'last_name',
        'description',
        'rich_editing',
        'user_registered',
        'role',
        'jabber',
        'aim',
        'yim',
        'show_admin_bar_front',
    );
 
    return in_array( $key, $userdata );
}
function iro_save_account_fields( $customer_id ) {
    $fields = iro_get_account_fields();
    $sanitized_data = array();
 
    foreach ( $fields as $key => $field_args ) {
        if ( ! iro_is_field_visible( $field_args ) ) {
            continue;
        }
 
        $sanitize = isset( $field_args['sanitize'] ) ? $field_args['sanitize'] : 'wc_clean';
        $value    = isset( $_POST[ $key ] ) ? call_user_func( $sanitize, $_POST[ $key ] ) : '';
 
        if ( iro_is_userdata( $key ) ) {
            $sanitized_data[ $key ] = $value;
            continue;
        }        
 
        update_user_meta( $customer_id, $key, $value );
    }
 
    if ( ! empty( $sanitized_data ) ) {
        $sanitized_data['ID'] = $customer_id;
        wp_update_user( $sanitized_data );
    }
}
 
add_action( 'woocommerce_created_customer', 'iro_save_account_fields' ); // register/checkout
add_action( 'personal_options_update', 'iro_save_account_fields' ); // edit own account admin
add_action( 'edit_user_profile_update', 'iro_save_account_fields' ); // edit other account admin
add_action( 'woocommerce_save_account_details', 'iro_save_account_fields' ); // edit WC account


function iro_print_user_frontend_fields() {
    $fields            = iro_get_account_fields();
    $is_user_logged_in = is_user_logged_in();
 
    foreach ( $fields as $key => $field_args ) {
        $value = null;
 
        if ( $is_user_logged_in && ! empty( $field_args['hide_in_account'] ) ) {
            continue;
        }
 
        if ( ! $is_user_logged_in && ! empty( $field_args['hide_in_registration'] ) ) {
            continue;
        }
 
        if ( $is_user_logged_in ) {
            $user_id = iro_get_edit_user_id();
            $value   = iro_get_userdata( $user_id, $key );
        }
 
        $value = isset( $field_args['value'] ) ? $field_args['value'] : $value;
 
        woocommerce_form_field( $key, $field_args, $value );
    }
}
 
/**
 * Add fields to admin area.
 *
 * @see https://irowp.com/blog/the-ultimate-guide-to-adding-custom-woocommerce-user-account-fields/
 */
function iro_print_user_admin_fields() {
    $fields = iro_get_account_fields();
    ?>
    <h2><?php _e( 'Additional Information', 'iro' ); ?></h2>
    <table class="form-table" id="iro-additional-information">
        <tbody>
        <?php foreach ( $fields as $key => $field_args ) { ?>
            <?php
            if ( ! empty( $field_args['hide_in_admin'] ) ) {
                continue;
            }
 
            $user_id = iro_get_edit_user_id();
            $value   = iro_get_userdata( $user_id, $key );
            $value = is_array($value) ? $value[0] : $value;
            //var_dump(checked( $value, 1, false ), $value);
            ?>
            <tr>
                <th>
                    <label for="<?php echo $key; ?>"><?php echo $field_args['label']; ?></label>
                </th>
                <td>
                    <?php $field_args['label'] = false; 
                    ?>
                    <?php woocommerce_form_field( $key, $field_args, $value ); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
}
 


add_action( 'woocommerce_register_form', 'iro_print_user_frontend_fields', 10 ); 
add_action( 'woocommerce_edit_account_form', 'iro_print_user_frontend_fields', 10 );

add_action( 'show_user_profile', 'iro_print_user_admin_fields', 30 ); // admin: edit profile
add_action( 'edit_user_profile', 'iro_print_user_admin_fields', 30 ); // admin: edit other users
/**
 * Get currently editing user ID (frontend account/edit profile/edit other user).
 *
 * @see https://irowp.com/blog/the-ultimate-guide-to-adding-custom-woocommerce-user-account-fields/
 *
 * @return int
 */
function iro_get_edit_user_id() {
    return isset( $_GET['user_id'] ) ? (int) $_GET['user_id'] : get_current_user_id();
}

function iro_get_userdata( $user_id, $key ) {
    if ( ! iro_is_userdata( $key ) ) {
        return get_user_meta( $user_id, $key );
    }
 
    $userdata = get_userdata( $user_id );
 
    if ( ! $userdata || ! isset( $userdata->{$key} ) ) {
        return '';
    }
 
    return $userdata->{$key};
}

/**
 * Modify checkboxes/radio fields.
 *
 * @param string $field
 * @param string $key
 * @param array  $args
 * @param string $value
 *
 * @see https://irowp.com/blog/the-ultimate-guide-to-adding-custom-woocommerce-user-account-fields/
 *
 * @return string
 */
function iro_form_field_modify( $field, $key, $args, $value ) {
    if(!is_admin()) {
        return $field;
    }
    ob_start();
    iro_print_list_field( $key, $args, $value );
    $field = ob_get_clean();

    if ( $args['return'] ) {
        return $field;
    } else {
        echo $field;
    }
}

add_filter( 'woocommerce_form_field_checkboxes', 'iro_form_field_modify', 10, 4 );
add_filter( 'woocommerce_form_field_radio', 'iro_form_field_modify', 10, 4 );

/**
 * Print a list field (checkboxes|radio).
 *
 * @param string $key
 * @param array  $field_args
 * @param mixed  $value
 *
 * @see https://irowp.com/blog/the-ultimate-guide-to-adding-custom-woocommerce-user-account-fields/
 */
function iro_print_list_field( $key, $field_args, $value = null ) {
    if(!is_admin()) {
        return;
    }
    $value = empty( $value ) && $field_args['type'] === 'checkboxes' ? array() : $value;
    ?>
    <div class="form-row">
        <?php if ( ! empty( $field_args['label'] ) ) { ?>
            <label>
                <?php echo $field_args['label']; ?>
                <?php if ( ! empty( $field_args['required'] ) ) { ?>
                    <abbr class="required" title="<?php echo esc_attr__( 'required', 'woocommerce' ); ?>">*</abbr>
                <?php } ?>
            </label>
        <?php } ?>
        <ul>
            <?php foreach ( $field_args['options'] as $option_value => $option_label ) {
                $id         = sprintf( '%s_%s', $key, sanitize_title_with_dashes( $option_label ) );
                $option_key = $field_args['type'] === 'checkboxes' ? sprintf( '%s[%s]', $key, $option_value ) : $key;
                $type       = $field_args['type'] === 'checkboxes' ? 'checkbox' : $field_args['type'];
                $checked    = $field_args['type'] === 'checkboxes' ? in_array( $option_value, $value ) : $option_value == $value;
                ?>
                <li>
                    <label for="<?php echo esc_attr( $id ); ?>">
                        <input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $option_key ); ?>" value="<?php echo esc_attr( $option_value ); ?>" <?php checked( $checked ); ?>>
                        <?php echo $option_label; ?>
                    </label>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

function iro_add_post_data_to_account_fields( $fields ) {
    if ( empty( $_POST ) ) {
        return $fields;
    }

    foreach ( $fields as $key => $field_args ) {
        if ( empty( $_POST[ $key ] ) ) {
            $fields[ $key ]['value'] = '';
            continue;
        }

        $fields[ $key ]['value'] = $_POST[ $key ];
    }

    return $fields;
}

add_filter( 'iro_account_fields', 'iro_add_post_data_to_account_fields', 10, 1 );


add_action( 'woocommerce_cart_calculate_fees', 'discount_based_on_total', 25, 1 );
function discount_based_on_total( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    if($cart->has_discount('sognairo') && $cart->has_discount('promoiro')) return;
    $total = $cart->cart_contents_total;

    // Percentage discount (10%)
    $discount = $total * 0.25;

    // Add the Scon
    $cart->add_fee( __('Sconto in aggiunta sul carrello', 'iro'), -$discount );
}

// function iro_checkout_fields( $checkout_fields ) {
//     $fields = iro_get_account_fields();
 
//     foreach ( $fields as $key => $field_args ) {
//         $checkout_fields['account'][ $key ] = $field_args;
//     }
 
//     return $checkout_fields;
// }
 
// add_filter( 'woocommerce_checkout_fields', 'iro_checkout_fields', 10, 1 );