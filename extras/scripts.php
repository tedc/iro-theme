<?php
	use Roots\Sage\Assets;
	function in_array_r($item , $array){
    	return preg_match('/"'.json_encode($item).'"/i' , json_encode($array));
	}
	function ikreativ_async_scripts($url)
	{
	    if ( strpos( $url, '#asyncload') === false )
	        return $url;
	    else if ( is_admin() )
	        return str_replace( '#asyncload', '', $url );
	    else
		return str_replace( '#asyncload', '', $url )."' async='async"; 
	    }
	add_filter( 'clean_url', 'ikreativ_async_scripts', 11, 1 );
	
	function iro_scripts() {
		global $sitepress;
		global $post;
		wp_deregister_script('irojs');
		//$log = (is_user_logged_in() && is_admin_bar_showing()) ? ' logged-in admin-bar' : ''; 
		//$body_classes = join( ' ', get_body_class( array($contactBar, $white ) ) );
		$languages = icl_get_languages('skip_missing=0&orderby=code');
		ob_start();
		include(locate_template( '404.php', false, true ));
		$error = ob_get_clean();
        $translations = [];
        foreach ($languages as $language) {
        	$translations[] = $language['language_code'];
        }
        $codes = join('|', $translations);
        $languages = apply_filters('wpml_active_languages', null);
	 //    $translations = [];
	 //    $type = (is_tax()) ? get_queried_object()->taxonomy : get_post_type();
	 //    $the_id = (is_tax()) ? get_queried_object()->term_id : $post->ID;
	 //    $front_page = []; 
	 //    foreach ($languages as $language) {
	 //    	$current_lang = $language['language_code'];
  //       	$post_id = apply_filters('wpml_object_id', $the_id, $type, false, $current_lang);
	 //        $href = (is_tax()) ? get_term_link($post_id, $type) : get_permalink($post_id);
	 //        $front_page[$current_lang] = $sitepress->convert_url(get_home_url(), $current_lang);
	 //        $href = (is_front_page()) ? $front_page[$current_lang] : $href;
	 //        $translations[$current_lang] = $href;
	 //    }
		// $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		// $lang = explode('-', $lang)[0];
		// if($lang != $sitepress->get_default_language()) {
		// 	$lang = in_array_r(array('language_code'=>$lang), $languages) ? $lang : 'en';
		// } else {
		// 	$lang = $lang;
		// }
		// $url = (isset($translations[$lang])) ? $translations[$lang] : $front_page[$lang];
		// $redirect = array('current' => ICL_LANGUAGE_CODE, 'url' => $url, 'lang' => $lang);
		// $ajax = array('url' => admin_url('admin-ajax.php'), 'action' => 'catellanipdf');
		
		acf_set_language_to_default();
		$review_base = get_field('review_base', 'options');
		$reviews = get_field('reviews', 'options');
		acf_unset_language_to_default();
		$wc = array(
			'form' => WC_AJAX::get_endpoint('iro_form'),
			'variation_add' => WC_AJAX::get_endpoint('iro_variation_add_to_cart_item'),
			'add' => WC_AJAX::get_endpoint('iro_simple_add_to_cart_item'),
			'qty' => WC_AJAX::get_endpoint('iro_udapte_item_quantity'),
			'shippings' => WC_AJAX::get_endpoint('iro_update_shipping_method'),
			'coupons' => WC_AJAX::get_endpoint('iro_apply_coupon'),
			'login' => WC_AJAX::get_endpoint('iro_login'),
			'register' => WC_AJAX::get_endpoint('iro_register'),
			'empty' => WC_AJAX::get_endpoint('iro_empty_cart'),
			'address' => WC_AJAX::get_endpoint('iro_save_address'),
			'checkout' => WC_AJAX::get_endpoint('checkout'),
			'accountBase' => basename(wc_get_page_permalink('myaccount')),
			'checkoutPage' => basename(wc_get_page_permalink('checkout')),
			'orderBase' => basename(wc_get_page_permalink('checkout')) . '/'. basename(wc_get_endpoint_url('order-received')),
			'logged' => (bool)is_user_logged_in(),
			'country_select_params' => apply_filters( 'wc_country_select_params', array(
			'countries'              => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) )) ),
			'review_base' => basename(get_permalink($review_base)),
			'reviews' => basename(get_permalink($reviews))
		);
		$vars = array(
			"main" => array(
				'mobile' => (bool)is_handheld(),
				'assets' => get_stylesheet_directory_uri() . '/assets/',
				'base' => get_home_url(),
				'home' => get_post(get_option('page_on_front'))->post_name,
				'error' => $error,
				'errorTitle' => __('Pagina non trovata', 'catellani')
			),
			'lang' => array(
				'default' => $sitepress->get_default_language(),
				'langs' => $codes,
				//'redirect' => $redirect
			),
			'wc' => $wc
			// "api" => array(	
			// 	'google_api_key' => acf_get_setting('google_api_key'),
			// ),
			// "strings" => array(
			// 	'btn_stores' => __('Cerca rivenditori', 'catellani'),
			// 	'select_any' => __('Qualsiasi', 'catellani'),
			// 	'empty_store' => __('Non risultano store presenti nella zona', 'catellani')
			// )
		);
		wp_enqueue_script('irojs', Assets\asset_path('scripts/main.js#asyncload'), null, null, true);
		wp_localize_script( 'irojs', 'vars', $vars );
		wp_deregister_script( 'cffscripts' );
		wp_deregister_script( 'sb_instagram_scripts' );
		wp_deregister_script( 'wp-embed' );
		wp_deregister_script( 'extra-shipping-options' );
		wp_deregister_script( 'jquery-ui-datepicker' );


		remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
		// Unless we're in the store, remove all the cruft!
	}

	add_action( 'wp_enqueue_scripts', 'iro_scripts', 1001, 1 );

	function templates() {
		get_template_part('templates/templates');
	}

	add_action( 'wp_footer', 'templates');