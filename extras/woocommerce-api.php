<?php
	use WooCommerce_Extra_Shipping_Options\WESO_Field_Has_Choices;
	class Iro_WC_AJAX extends WC_AJAX {

	    /**
	     - Hook in ajax handlers.
	     */
	    public static function init() {
	        add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
	        add_action( 'template_redirect', array( __CLASS__, 'do_wc_ajax' ), 0 );
	        self::add_ajax_events();
	    }

	    /**
	     - Get WC Ajax Endpoint.
	     - @param  string $request Optional
	     - @return string
	     */
	    public static function get_endpoint( $request = '' ) {
	        return esc_url_raw( add_query_arg( 'wc-ajax', $request, remove_query_arg( array( 'remove_item', 'add-to-cart', 'added-to-cart' ) ) ) );
	    }

	    /**
	     - Set WC AJAX constant and headers.
	     */
	    public static function define_ajax() {
	        if ( ! empty( $_GET['wc-ajax'] ) ) {
	            if ( ! defined( 'DOING_AJAX' ) ) {
	                define( 'DOING_AJAX', true );
	            }
	            if ( ! defined( 'WC_DOING_AJAX' ) ) {
	                define( 'WC_DOING_AJAX', true );
	            }
	            // Turn off display_errors during AJAX events to prevent malformed JSON
	            if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
	                @ini_set( 'display_errors', 0 );
	            }
	            $GLOBALS['wpdb']->hide_errors();
	        }
	    }

	    /**
	     - Send headers for WC Ajax Requests
	     - @since 2.5.0
	     */
	    private static function wc_ajax_headers() {
	        send_origin_headers();
	        @header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
	        @header( 'X-Robots-Tag: noindex' );
	        send_nosniff_header();
	        nocache_headers();
	        status_header( 200 );
	    }

	    /**
	     - Check for WC Ajax request and fire action.
	     */
	    public static function do_wc_ajax() {
	        global $wp_query;
	        if ( ! empty( $_GET['wc-ajax'] ) ) {
	            $wp_query->set( 'wc-ajax', sanitize_text_field( $_GET['wc-ajax'] ) );
	        }
	        if ( $action = $wp_query->get( 'wc-ajax' ) ) {
	            self::wc_ajax_headers();
	            do_action( 'wc_ajax_' . sanitize_text_field( $action ) );
	            die();
	        }
	    }

	    /**
	     - Add custom ajax events here
	     */
	    public static function add_ajax_events() {
	        // woocommerce_EVENT => nopriv
	        $ajax_events = array(
	            'iro_variation_add_to_cart_item' => true,
	            'iro_simple_add_to_cart_item' => true,
	            'iro_update_shipping_method' => true,
	            'iro_apply_coupon' => true,
	            'iro_login' => true,
	            'iro_register' => true,
	            'iro_empty_cart' => true,
	            'iro_udapte_item_quantity' => true,
	            'iro_form' => true,
	            'iro_save_address' => true,
	            'iro_get_cart' => true,
	            'iro_remove_cart_item' => true
	        );
	        foreach ( $ajax_events as $ajax_event => $nopriv ) {
	            add_action( 'wp_ajax_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
	            if ( $nopriv ) {
	                add_action( 'wp_ajax_nopriv_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
	                // WC AJAX can be used for frontend ajax requests
	                add_action( 'wc_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
	            }
	        }
	    }

	    /**
	     - Get a refreshed cart fragment. 
	     - 
	     - Copied from WC_AJAX but changed how data is returned. 
	     - You can add fragments (DOM Objects loaded via AJAX) by adding them
	     - through the 'add_to_cart_fragments'. 
	     - It's better to do it this way so you don't have to create the DOM via
	     - javascript because WooCommerce have a general javascript code that will
	     - automatically change the DOM Object for all the fragments loaded
	     - through here. I will give more info about this later.
	     */
	    public static function iro_get_refreshed_fragments_raw($product_id) {
	        // Get mini cart
	        // Fragments and mini cart are returned
	        $remove_item_url = '';
	        $item_key = '';
	        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
	        	if($cart_item['product_id'] == $product_id) {
	        		$remove_item_url = WC()->cart->get_remove_url( $cart_item_key );
	        		$item_key = $cart_item_key;
	        		break;
	        	}
	        }
	        $data = array(
	            'remove_item_url' => $remove_item_url,
	            'item_key' => $item_key,
	            'cart_hash' => 
	            apply_filters( 
	                'woocommerce_add_to_cart_hash', 
	                WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', 
	                WC()->cart->get_cart_for_session() )
	             );
	        /**
	         - Used 'return' here instead of 'wp_send_json()';
	         */
	        wp_send_json( $data ); 
	    }
	    public static function iro_remove_cart_item() {
	    	wp_send_json( $_REQUEST['item_key'] );
	    	// if($_REQUEST['item_key']) {
	    	// 	die();
	    	// } 
	    	// $cart_item_key = $_REQUEST['item_key'];
	    	// WC()->cart->remove_cart_item($cart_item_key);
	    	die();
	    }
	    public static function iro_empty_cart() {
	    	WC()->cart->empty_cart();
	    	die();
	    }
	    public static function iro_simple_add_to_cart_item() {
	    	ob_start();
	    	$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			$data = array($product_id, $quantity, $passed_validation);
			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity  ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
		
				// Return fragments
				self::iro_get_refreshed_fragments_raw($product_id);
			} else {
				
				// If there was an error adding to the cart, redirect to the product page to show any errors
				$data = array(
					'error' => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
					);
				wp_send_json( $data );
			}
			die();
	    }
	    public static function iro_variation_add_to_cart_item() {
	    	ob_start();
	    	$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
			$variation_id = $_POST['variation_id'];
			$variation  = $_POST['variation'];
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation  ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
		
				// Return fragments
				self::iro_get_refreshed_fragments_raw($product_id);
			} else {
				
				// If there was an error adding to the cart, redirect to the product page to show any errors
				$data = array(
					'error' => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
					);
				wp_send_json( $data );
			}
			die();
	    }
	    public static function iro_udapte_item_quantity() {
	    	//var_dump($_POST);
	    	if(!isset($_POST['item_key']) && !isset($_POST['quantity'])) die();
	    	$quantity = WC()->cart->set_quantity($_POST['item_key'], $_POST['quantity']);
	    	return $quantity;
	    	die();
	    }
	    public static function iro_update_shipping_method() {
			// Update Shipping
			//var_dump($_POST['calc_shipping'] );			
	    	check_ajax_referer( 'update-shipping-method', 'security' );
	    	wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

	        if ( ! empty( $_POST['calc_shipping'] ) && wp_verify_nonce( $_POST['calc_security'], 'cart-calculate-shipping' )) {
				self::calculate_shipping();
				WC()->cart->calculate_totals();
			}

	        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

	        if ( isset( $_POST['shipping_method'] ) && is_array( $_POST['shipping_method'] ) ) {
	            foreach ( $_POST['shipping_method'] as $i => $value ) {
	                $chosen_shipping_methods[ $i ] = wc_clean( $value );
	            }
	        }

	        WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );
			$extra_shipping_options = array();
			// Bail if required value is not set.
			// if ( ! isset( $_POST['post_data'] ) ) :
			// 	return;
			// endif;
			$extra_options_json = array();
			if (  isset( $_POST['post_data'] ) ) :
				// In the carts its passed as array, in checkout its in the post_data as query arg
				if ( ! is_array( $_POST['post_data'] ) ) {
					$args = wp_parse_args( $_POST['post_data'] );
				} else {
					$args = $_POST['post_data'];
				}		
				// Bail if no extra shipping options are set.
				// if ( ! isset( $args['extra_shipping_option'] ) ) :
				// 	return;
				// endif;
				if(isset($args['extra_shipping_option'])) :
					$post_extra_shipping_options = $args['extra_shipping_option'];
					foreach ( $post_extra_shipping_options as $package_id => $shipping_options ) :
						foreach ( $shipping_options as $shipping_option_post_id => $extra_options ) :
							foreach ( $extra_options as $key => $value ) :
								$extra_shipping_options[ sanitize_key( $package_id ) ][ absint( $shipping_option_post_id ) ][ $key ] = $value;
								foreach($value as $k => $v) {
									foreach ( weso_get_shipping_options( $shipping_option_post_id ) as $opt) {
										if($opt['option_id'] == $k && $v == true) {
											$extra_options_json = array_push($extra_options_json, array('extra_price' => $opt['option_cost'], 'extra_title' => $opt['option_name'], 'choices' => $opt['extra_options']));
										}		
									}
								}
							endforeach;
						endforeach;
					endforeach;
					WC()->session->set( 'extra_shipping_options', $extra_shipping_options );
				endif;
			endif;
	        WC()->cart->calculate_totals();    
	        $packages = WC()->shipping->get_packages();
	        $shippings = array('total' => WC()->cart->shipping_total + weso_get_extra_shipping_option_cost(), 'extras' => array(), 'packages' => array());
	        // if(WC()->session->get('extra_shipping_options')) {
	        // 	var_dump(WC()->session->get('extra_shipping_options'));
	        // }							
	        //var_dump($cart);
	        foreach ( $packages as $i => $package ) {
		        $chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
		        $product_names = array();

		        if ( sizeof( $packages ) > 1 ) {
		            foreach ( $package['contents'] as $item_id => $values ) {
		                $product_names[ $item_id ] = $values['data']->get_name() . ' &times;' . $values['quantity'];
		            }
		            $product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package );
		        }
		        $method = $package['rates'][$chosen_method];
		        $price = ($method->cost == 0) ? __('Gratuita', 'iro') : wc_price( $method->cost );
        		$available_methods = array();
        		foreach ($package['rates'] as $m) {
        			$checked = ($m->id == $chosen_method) ? true : false;
        			ob_start();
        			do_action( 'woocommerce_after_shipping_rate', $m, $i );
        			$e = ob_get_clean();
        			$price = ($m->cost == 0) ? __('Gratuita', 'iro') : wc_price( $m->cost );
        			$array = array('id' => sanitize_title( $m->id ), 'label' => wc_cart_totals_shipping_method_label( $m ), 'value' => esc_attr( $m->id ), 'checked' => $checked, 'extras' => $e, 'price' => $price);
        			array_push($available_methods, $array);
        		}
		        array_push( $shippings['packages'], array(
		            'package'              => $package,
		            'available_methods'    => $package['rates'],
		            'methods' => $available_methods,
		            'show_package_details' => sizeof( $packages ) > 1,
		            'package_details'      => implode( ', ', $product_names ),
		            // @codingStandardsIgnoreStart
		            'package_name'         => apply_filters( 'woocommerce_shipping_package_name', sprintf( _nx( 'Shipping', 'Shipping %d', ( $i + 1 ), 'shipping packages', 'woocommerce' ), ( $i + 1 ) ), $i, $package ),
		            // @codingStandardsIgnoreEnd
		            'index'                => $i,
		            'chosen_method'        => $chosen_method,
		            'chosen_label' => wc_cart_totals_shipping_method_label( $method ),
		            'chosen_price' => strip_tags($price),
		            'extras' => $extra_options_json
		        ) );
		    }
	        wp_send_json( $shippings );
	        wp_die();
	    }
	    public static function iro_apply_coupon() {

	        check_ajax_referer( 'apply-coupon', 'security' );

	        if ( ! empty( $_POST['coupon_code'] ) ) {
	            WC()->cart->add_discount( sanitize_text_field( $_POST['coupon_code'] ) );
	        } else {
	            wc_add_notice( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
	        }
	        $coupons = array();
	        foreach(WC()->cart->get_applied_coupons() as $code ) {
	        	array_push($coupons, self::get_coupon($code));
	        }
	        wp_send_json( $coupons );
	        wp_die();
	    }
	    public static function iro_login() {
	    	//check_ajax_referer('iro-login', 'ea234fc388');
	    	if(is_user_logged_in()) {
	    		die();
	    	}
	    	check_ajax_referer('iro-login', 'security');
	    	$info = array();
    		$info['user_login'] = $_POST['username'];
    		$info['user_password'] = $_POST['password'];
    		$info['remember'] = true;
    		$user_signon = wp_signon( $info, false );
	    	if ( is_wp_error($user_signon) ){
		        echo json_encode(array('loggedin'=>false, 'message'=>__('Username o password sbagliati.', 'iro')));
		    } else {
		        echo json_encode(array('loggedin'=>true, 'message'=>__('Login avvenuto con successo', 'iro'), 'redirect' => basename(wc_get_page_permalink('myaccount'))));
		    }
	    	die();
	    }
	    public static function iro_register() {
	    	check_ajax_referer('iro-register', 'security');
	    	if(is_user_logged_in()) {
	    		wp_die();
	    	}
	    	$username = ($_POST['username']) ? $_POST['username'] : $_POST['email'];
	    	$password_confirmation = ($_POST['password'] === $_POST['password_confirm']);
	    	$email = $_POST['email'];
	    	$user_id = username_exists($username);
	    	if(!$user_id && email_exists($email) == false && $password_confirmation) {
	    		$user_id = wp_create_user(
	    			$username, $password_confirmation, $user_email
	    		);
	    		$info = array();
    			$info['user_login'] = $username;
    			$info['user_password'] = $password_confirmation;
    			$user_signon = wp_signon( $info, false );
	    		echo json_encode(array('loggedin'=>true, 'message'=>__('Registrazione avvenuta.', 'iro'), 'redirect' => basename(wc_get_page_permalink('myaccount'))));
	    	} else {
	    		if($user_id) {
	    			echo json_encode(array('loggedin'=>false, 'message'=>__('Nome utente in uso da un altro utente.', 'iro')));
	    		} elseif(email_exists($email) == true) {
	    			echo json_encode(array('loggedin'=>false, 'message'=>__('Ci risulta un utente già registrato con questo indirizzo e-mail.', 'iro')));
	    		} else {
					echo json_encode(array('loggedin'=>false, 'message'=>__('Qualcosa è andato storto, verifica che tutti i campi siano stati inseriti correttamente.', 'iro'), 'altro' => array(email_exists($email), $password_confirmation)));
	    		}
	    	}
	    	die();
	    }
	    private static function get_coupon($coupon) {
	    	if ( is_string( $coupon ) ) {
		        $coupon = new WC_Coupon( $coupon );
		    }

		    $discount = array();

		    if ( $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax ) ) {
		       $discount['label'] = wc_cart_totals_coupon_label( $coupon, false );
		       $discount['code'] =  $coupon->get_code();
		       $discount['price'] = $amount;
		       $discount['type'] = $coupon->get_discount_type();
		       $discount['amount'] = $coupon->get_amount();
		    } elseif ( $coupon->get_free_shipping() ) {
		    	$discount['label'] = __( 'Free shipping coupon', 'woocommerce' );
		       	$discount['code'] =  $coupon->get_code();
		       	$discount['price'] = $amount;
		    }
		    $discount['remove'] = esc_url( add_query_arg( 'remove_coupon', urlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) );

		    return $discount;
	    }

	    public static function calculate_shipping() {
			try {
				WC()->shipping->reset_shipping();

				$country  = wc_clean( wp_unslash( $_POST['calc_shipping_country'] ) );
				$state    = wc_clean( wp_unslash( isset( $_POST['calc_shipping_state'] ) ? $_POST['calc_shipping_state'] : '' ) );
				$postcode = apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ? wc_clean( wp_unslash( $_POST['calc_shipping_postcode'] ) ) : '';
				$city     = apply_filters( 'woocommerce_shipping_calculator_enable_city', false ) ? wc_clean( wp_unslash( $_POST['calc_shipping_city'] ) ) : '';

				if ( $postcode && ! WC_Validation::is_postcode( $postcode, $country ) ) {
					throw new Exception( __( 'Please enter a valid postcode / ZIP.', 'woocommerce' ) );
				} elseif ( $postcode ) {
					$postcode = wc_format_postcode( $postcode, $country );
				}

				if ( $country ) {
					WC()->customer->set_location( $country, $state, $postcode, $city );
					WC()->customer->set_shipping_location( $country, $state, $postcode, $city );
				} else {
					WC()->customer->set_billing_address_to_base();
					WC()->customer->set_shipping_address_to_base();
				}

				WC()->customer->set_calculated_shipping( true );
				WC()->customer->save();
				//var_dump($postcode);
				//wc_add_notice( __( 'Shipping costs updated.', 'woocommerce' ), 'notice' );

				do_action( 'woocommerce_calculated_shipping' );

			} catch ( Exception $e ) {
				if ( ! empty( $e ) ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}
			}
		}

		public static function update_order_review() {
	        check_ajax_referer( 'update-order-review', 'security' );

	        wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );

	        if ( WC()->cart->is_empty() ) {
	            self::update_order_review_expired();
	        }

	        do_action( 'woocommerce_checkout_update_order_review', $_POST['post_data'] );

	        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

	        if ( isset( $_POST['shipping_method'] ) && is_array( $_POST['shipping_method'] ) ) {
	            foreach ( $_POST['shipping_method'] as $i => $value ) {
	                $chosen_shipping_methods[ $i ] = wc_clean( $value );
	            }
	        }

	        WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );
	        WC()->session->set( 'chosen_payment_method', empty( $_POST['payment_method'] ) ? '' : $_POST['payment_method'] );
	        WC()->customer->set_props( array(
	            'billing_country'   => isset( $_POST['country'] ) ? $_POST['country']     : null,
	            'billing_state'     => isset( $_POST['state'] ) ? $_POST['state']         : null,
	            'billing_postcode'  => isset( $_POST['postcode'] ) ? $_POST['postcode']   : null,
	            'billing_city'      => isset( $_POST['city'] ) ? $_POST['city']           : null,
	            'billing_address_1' => isset( $_POST['address'] ) ? $_POST['address']     : null,
	            'billing_address_2' => isset( $_POST['address_2'] ) ? $_POST['address_2'] : null,
	            'billing_vat' => isset( $_POST['vat'] ) ? $_POST['vat'] : null,
	        ) );

	        if ( wc_ship_to_billing_address_only() ) {
	            WC()->customer->set_props( array(
	                'shipping_country'   => isset( $_POST['country'] ) ? $_POST['country']     : null,
	                'shipping_state'     => isset( $_POST['state'] ) ? $_POST['state']         : null,
	                'shipping_postcode'  => isset( $_POST['postcode'] ) ? $_POST['postcode']   : null,
	                'shipping_city'      => isset( $_POST['city'] ) ? $_POST['city']           : null,
	                'shipping_address_1' => isset( $_POST['address'] ) ? $_POST['address']     : null,
	                'shipping_address_2' => isset( $_POST['address_2'] ) ? $_POST['address_2'] : null,
	            ) );
	            if ( ! empty( $_POST['country'] ) ) {
	                WC()->customer->set_calculated_shipping( true );
	            }
	        } else {
	            WC()->customer->set_props( array(
	                'shipping_country'   => isset( $_POST['s_country'] ) ? $_POST['s_country']     : null,
	                'shipping_state'     => isset( $_POST['s_state'] ) ? $_POST['s_state']         : null,
	                'shipping_postcode'  => isset( $_POST['s_postcode'] ) ? $_POST['s_postcode']   : null,
	                'shipping_city'      => isset( $_POST['s_city'] ) ? $_POST['s_city']           : null,
	                'shipping_address_1' => isset( $_POST['s_address'] ) ? $_POST['s_address']     : null,
	                'shipping_address_2' => isset( $_POST['s_address_2'] ) ? $_POST['s_address_2'] : null,
	            ) );
	            if ( ! empty( $_POST['s_country'] ) ) {
	                WC()->customer->set_calculated_shipping( true );
	            }
	        }

	        WC()->customer->save();
	        WC()->cart->calculate_totals();

	        // Get order review fragment
	        // ob_start();
	        // woocommerce_order_review();
	        // $woocommerce_order_review = ob_get_clean();

	        // // Get checkout payment fragment
	        // ob_start();
	        // woocommerce_checkout_payment();
	        // $woocommerce_checkout_payment = ob_get_clean();

	        // Get messages if reload checkout is not true
	        $messages = '';
	        if ( ! isset( WC()->session->reload_checkout ) ) {
	            ob_start();
	            wc_print_notices();
	            $messages = ob_get_clean();
	        }

	        unset( WC()->session->refresh_totals, WC()->session->reload_checkout );

	        wp_send_json( array(
	            'result'    => empty( $messages ) ? 'success' : 'failure',
	            'messages'  => $messages,
	            'reload'    => isset( WC()->session->reload_checkout ) ? 'true' : 'false',
	            // 'fragments' => apply_filters( 'woocommerce_update_order_review_fragments', array(
	            //     '.woocommerce-checkout-review-order-table' => $woocommerce_order_review,
	            //     '.woocommerce-checkout-payment'            => $woocommerce_checkout_payment,
	            // ) ),
	        ) );
	    }
	    public static function iro_form(){
	        check_ajax_referer( 'iro-contact-form', '_iro_form_nonce' );
	        if(isset($_POST['email']) && isset($_POST['sender']) && isset($_POST['tel']) && isset($_POST['_send_to']) && isset($_POST['_bcc']) && isset($_POST['message']) ) :
	            $email = $_POST['email'];
	            $name = $_POST['sender'];
	            $tel = $_POST['tel'];
	            $send_to = $_POST['_send_to'];
	            $bcc = explode(',', $_POST['_bcc']);
	            $sender = $name;
	            $message = $_POST['message'];
	            //var_dump($_POST['security_check']);
	            //$pTo = array('form@bspkn.it');
	            //$pTo = array('e.grandinetti@bspkn.it');
	            $pTo = $send_to;
	            $pSubject = __('Richiesta di contatto da') . ' ' . $sender;
	            $rSubject = __('Risposta automatica da') . ' '. get_bloginfo('name');
	            $tnx = __('Grazie per averci contattato.<br/>Ti risponderemo prima possibile','catellani');
	            $errorMessage = __('Verifica di aver compilato bene i campi o scrivi a','catellani');
	            $sent = __('Messaggio inviato correttamente','catellani');
	            $error = __('Messaggio non inviato','catellani');
	            $name_row = (!empty($_POST['sender'])) ? '<tr style="border-bottom: 1px solid #f8f8f8;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#7f7f7f;font-style:italic">'.__('Da','catellani').'</em><br />'.$sender.'</td></tr>' : "";
	            $email_row = (!empty($_POST['email'])) ? '<tr style="border-bottom: 1px solid #f8f8f8;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#a7a9ac;font-style:italic">Email</em><br /><a href="mailto:'.$email.'" style="text-decoration:none;font-weight:bold;color:#123f6d">'.$email.'</a></td></tr>' : "";
	            $tel_row = (!empty($_POST['tel'])) ? '<tr style="border-bottom: 1px solid #f8f8f8;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#7f7f7f;font-style:italic">'.__('Telefono','catellani').'</em><br />'.$tel.'</td></tr>' : "";
	            $message_row = (!empty($_POST['message'])) ? '<tr style="border-bottom: 1px solid #f8f8f8;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#7f7f7f;font-style:italic">'.__('Messaggio','catellani').'</em><br />'.stripslashes($message).'</td></tr>' : "";
	            $body = $name_row.$email_row.$tel_row.$message_row;
	            $resp = '<tr style="border-bottom: 1px solid #f8f8f8;"><td style="text-align:center;padding:20px;"><p style="line-height:1.35">'.$tnx.'</p></td></tr>';
	            function template($body) {
	                $html = '<html><head><meta charset="utf-8" /></head><body style="background-color:#f8f8f8"><div style="background-color:#fff;font-family:\'Helvetica Neue\', Helvetica, Arial, san-serif;font-size:18px;color:#58595b;max-width:550px;margin:0 auto;"><table style="width:100%;border-collapse:collapse;"><thead><tr><td style="padding: 20px;text-align:center; background-color:#fff"><a href="'.get_bloginfo('url').'" style="text-decoration:none"><img src="'.get_stylesheet_directory_uri().'/assets/images/logo.gif" style="border:0;width:100%;max-width:100px;height:auto"/></a></td></tr></thead><tfoot><tr><td style="padding:20px; text-align:center;color:#7f7f7f;font-size:11px">'.get_field('info', 'options').'<br /><a href="'.get_bloginfo('url').'" style="text-decoration:none;font-weight:bold;color:#123f6d">'.str_replace('http://', '', get_bloginfo('url')).'</a></td></tr></tfoot><tbody>'.$body.'</tbody></table></div></body></html>';
	                return $html;
	            }
	            if(get_field('custom_smtp', 'option') && have_rows('smtp', 'option') ) {
	                while (have_rows('smtp', 'option')) : the_row();
	                    $transport = Swift_SmtpTransport::newInstance(get_sub_field('url_provider'), get_sub_field('porta'), get_sub_field('encrypt'))->setUsername(get_sub_field('user'))->setPassword(get_sub_field('password'));
	                endwhile;
	            } else {
	            	$transport = Swift_MailTransport::newInstance();
	            }
	            $mMailer = Swift_Mailer::newInstance($transport);
	            $rEmail = Swift_Message::newInstance();
	            $mEmail = Swift_Message::newInstance();
	            $mEmail->setSubject($pSubject);
	            $mEmail->setTo($pTo);
	            $mEmail->setBcc($bcc);
	            $mEmail->setFrom(array($email => $name));
	            $mEmail->setReplyTo(array($email));
	            $mEmail->setBody(template($body), 'text/html');
	            $rEmail->setSubject($rSubject);
	            $rEmail->setFrom(array($pTo => get_bloginfo('name')));
	            $rEmail->setTo(array($email));
	            $rEmail->setBody(template($resp), 'text/html');
	            if( $mMailer->send($mEmail) && $mMailer->send($rEmail)){
	                $data = array('formMsg' => "<h3 class='form__subtitle form__subtitle'>".__('Grazie per averci contattato', 'iro')."</h3><p>".__('Ti risponderemo nel più breve tempo possibile', 'iro')."</p><a ui-sref='app.root({lang : \"".ICL_LANGUAGE_CODE."\"})' class='form__button' href='".get_home_url()."'>".__('Torna allo shop', 'iro')."</a>", 'new_nonce' => wp_create_nonce('iro-contact-form'));
	            } else {
	                $data = array('formMsg' => "<h3 class='form__subtitle--error'>Spiacenti, qualcosa è andato storto</h3><p>".__('Si è verificato un problema con il messaggio inviato. Riprova inserendo tutti i dati correttamente oppure contattaci all\'indirizzo email', 'iro')." <a href=\'mailto:".$send_to."'>".$send_to."</a>.</p><a ui-sref='app.root({lang : \"".ICL_LANGUAGE_CODE."\"})' class='form__button' href='".get_home_url()."'>".__('Torna allo shop', 'iro')."</a>", 'new_nonce' => wp_create_nonce('iro-contact-form'));
	            }
	            wp_send_json($data);
	            wp_die();
	        else :
	            wp_die();
	        endif;
	    }
	    public static function iro_save_address() {
	        global $wp;

	        check_ajax_referer( 'iro-save-address'.$_POST['address_kind'], 'security' );
	        $data = array();
	        $user_id = $_POST['user_id'];

	        if ( $user_id <= 0 ) {
	            return;
	        }

	        $load_address = isset( $_POST['address_kind'] ) ? wc_edit_address_i18n( sanitize_title( $_POST['address_kind'] ), true ) : 'billing';

	        $address = WC()->countries->get_address_fields( esc_attr( $_POST[ $load_address . '_country' ] ), $load_address . '_' );

	        foreach ( $address as $key => $field ) {

	            if ( ! isset( $field['type'] ) ) {
	                $field['type'] = 'text';
	            }

	            // Get Value.
	            switch ( $field['type'] ) {
	                case 'checkbox' :
	                    $_POST[ $key ] = (int) isset( $_POST[ $key ] );
	                    break;
	                default :
	                    $_POST[ $key ] = isset( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : '';
	                    break;
	            }

	            // Hook to allow modification of value.
	            $_POST[ $key ] = apply_filters( 'woocommerce_process_myaccount_field_' . $key, $_POST[ $key ] );

	            // Validation: Required fields.
	            if ( ! empty( $field['required'] ) && empty( $_POST[ $key ] ) ) {
	                //wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), $field['label'] ), 'error' );
	            }

	            if ( ! empty( $_POST[ $key ] ) ) {

	                // Validation rules.
	                if ( ! empty( $field['validate'] ) && is_array( $field['validate'] ) ) {
	                    foreach ( $field['validate'] as $rule ) {
	                        switch ( $rule ) {
	                            case 'postcode' :
	                                $_POST[ $key ] = strtoupper( str_replace( ' ', '', $_POST[ $key ] ) );

	                                if ( ! WC_Validation::is_postcode( $_POST[ $key ], $_POST[ $load_address . '_country' ] ) ) {
	                                    $data['error'] = __( 'Please enter a valid postcode / ZIP.', 'woocommerce' );
	                                } else {
	                                    $_POST[ $key ] = wc_format_postcode( $_POST[ $key ], $_POST[ $load_address . '_country' ] );
	                                }
	                                break;
	                            case 'phone' :
	                                $_POST[ $key ] = wc_format_phone_number( $_POST[ $key ] );

	                                if ( ! WC_Validation::is_phone( $_POST[ $key ] ) ) {
	                                    $data['error'] = sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), '<strong>' . $field['label'] . '</strong>' );
	                                }
	                                break;
	                            case 'email' :
	                                $_POST[ $key ] = strtolower( $_POST[ $key ] );

	                                if ( ! is_email( $_POST[ $key ] ) ) {
	                                    $data['error'] =  sprintf( __( '%s is not a valid email address.', 'woocommerce' ), '<strong>' . $field['label'] . '</strong>' );
	                                }
	                                break;
	                        }
	                    }
	                }
	            }
	        }

	        do_action( 'woocommerce_after_save_address_validation', $user_id, $load_address, $address );

	        if ( !isset($data['error']) ) {

	            foreach ( $address as $key => $field ) {
	                update_user_meta( $user_id, $key, $_POST[ $key ] );
	            }

	            $data['success'] = __( 'Address changed successfully.', 'woocommerce' );

	            do_action( 'woocommerce_customer_save_address', $user_id, $load_address );

	            $data['redirect'] = basename( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) );
				
	        }
	        wp_send_json( $data );
	        wp_die();
	    }
	    public static function iro_get_cart() {
	    	$data = array();
	    	if(! WC()->cart->is_empty()) :
	    		$data['cart_empty'] = false;
	    		$data['products'] = array();
	    		foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item) {
	    			$_product = wc_get_product($cart_item['product_id']);
	    			$variations = $_product->get_available_variations();
	    			$variation_details = null;
					if($cart_item['variation_id'] > 0) {
						foreach($variations as $variation) {
							if($variation['variation_id'] == $cart_item['variation_id']) {
								$variation_details = $variation;
							}
						}
	    				$cart_item['variation_details'] = $variation_details;
	    			}
	    			array_push($data['products'], $cart_item);
	    		}
	    	else :
	    		$data['cart_empty'] = true;
	    	endif;
	    	wp_send_json( $data );
	    }
	}

	$iro_wc_ajax = new Iro_WC_AJAX();
	$iro_wc_ajax->init();