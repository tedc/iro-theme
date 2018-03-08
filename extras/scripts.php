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
		$blog_base = get_option('page_for_posts');
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
			'cart' => WC_AJAX::get_endpoint('iro_get_cart'),
			'remove' => WC_AJAX::get_endpoint('iro_remove_cart_item'),
			'address' => WC_AJAX::get_endpoint('iro_save_address'),
			'checkout' => WC_AJAX::get_endpoint('checkout'),
			'accountBase' => basename(wc_get_page_permalink('myaccount')),
			'checkoutPage' => basename(wc_get_page_permalink('checkout')),
			'orderBase' => basename(wc_get_page_permalink('checkout')) . '/'. basename(wc_get_endpoint_url('order-received')),
			'logged' => (bool)is_user_logged_in(),
			'country_select_params' => apply_filters( 'wc_country_select_params', array(
			'countries'              => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) )) ),
			'review_base' => basename(get_permalink($review_base)),
			'reviews' => basename(get_post_type_archive_link('recensioni'))
		);
		$vars = array(
			"main" => array(
				'mobile' => (bool)is_handheld(),
				'assets' => get_stylesheet_directory_uri() . '/assets/',
				'base' => get_home_url(),
				'home' => get_post(get_option('page_on_front'))->post_name,
				'error' => $error,
				'errorTitle' => __('Pagina non trovata', 'catellani'),
				'blog' => basename(get_permalink($blog_base)),
				'category' => get_option( 'category_base' )
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
		wp_deregister_script( 'wc_additional_variation_images_script' );

		remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
		// Unless we're in the store, remove all the cruft!
	}

	add_action( 'wp_enqueue_scripts', 'iro_scripts', 1001, 1 );

	function templates() {
		get_template_part('templates/templates');
	}

	add_action( 'wp_footer', 'templates');

function header_scripts()
	{ ?>
		<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '192305908199271');
  fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5TPC55T');</script>
<!-- End Google Tag Manager -->
<?php 
ob_start();
get_template_part( 'extras/critical', null );
$critical = ob_get_clean();
$critical = str_replace('../fonts/', get_stylesheet_directory_uri() . '/assets/fonts/', $critical);
echo $critical;
?>
<script>
      /*!
      Modified for brevity from https://github.com/filamentgroup/loadCSS
      loadCSS: load a CSS file asynchronously.
      [c]2014 @scottjehl, Filament Group, Inc.
      Licensed MIT
      */
      function loadCSS(href){
        var ss = window.document.createElement('link'),
            ref = window.document.getElementsByTagName('head')[0];

        ss.rel = 'stylesheet';
        ss.href = href;

        // temporarily, set media to something non-matching to ensure it'll
        // fetch without blocking render
        ss.media = 'only x';

        ref.appendChild(ss);

        setTimeout( function(){
          // set media back to `all` so that the stylesheet applies once it loads
          ss.media = 'all';
        },0);
      }
      loadCSS('<?php echo Assets\asset_path('styles/main.css'); ?>');
    </script>
    <noscript>
      <!-- Let's not assume anything -->
      <link rel="stylesheet" href="<?php echo Assets\asset_path('styles/main.css'); ?>">
    </noscript>
	<?php }
	add_action( 'wp_head','header_scripts');

	function body_scripts()
	{ ?>
		
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=192305908199271&ev=PageView&noscript=1"
/></noscript>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TPC55T"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php }

	add_action('iro_header', 'body_scripts');