<?php
	//define('WP_MAX_MEMORY_LIMIT', 512);
	function ng_app($html) {
		$html =  $html . ' class="no-js" ng-app="sprfc"';
		return $html;
	}

	//add_filter( 'language_attributes', 'ng_app', 100 );
	function theme_setup() {
		add_theme_support( 'custom-logo' );
		add_theme_support('woocommerce');
		register_nav_menus([
		    'footer_pages_menu' => __('Iro', 'iro'),
		    'footer_products_menu' => __('Prodotti', 'iro'),
		  ]);
	}
	
	add_action('after_setup_theme', 'theme_setup');
	add_filter('show_admin_bar', '__return_false');
	// ADD BUILDER TO CONTENT
	// function builder_shortcode($attr) {
	// 	ob_start();
	// 	get_template_part('builder/init');
	// 	return ob_get_clean();
	// }
	// add_shortcode( 'builder', 'builder_shortcode' );

	// function add_builder_shortcode($post_id) {
	// 	$field = get_field_object('layout');
	// 	if( empty($_POST['acf'][$field['key']]) ) {
	// 		return;
	// 	}
	// 	$args = array(
	// 		'ID' => $post_id,
	// 		'post_content' => '[builder]'
	// 	);
	// 	wp_update_post($args);
	// }
	// add_action( 'acf/save_post', 'add_builder_shortcode' );

	// CHANGE WRAPPER

	add_filter('sage/wrap_base', 'my_sage_wrap');
	
	function my_sage_wrap($templates) {
		array_unshift($templates, 'extras/base.php');
		return $templates;
	}

	function na_remove_slug( $post_link, $post, $leavename ) {
		global $wp_query;
		if ( ('product' != $post->post_type && 'prodotto' != $post->post_type ) || 'publish' != $post->post_status ) {
	        return $post_link;
	    }
	    // if($)
	    $uri = '';
	    $append = '';
	    foreach ( $post->ancestors as $parent ) {
	        $uri = get_post( $parent )->post_name . "/" . $uri;
	    }

	    $post_link = str_replace( $uri, '', $post_link );
	    
	    //$new_link = str_replace( array('/' . $post->post_type . '/', '/prodotto/'), '/', $post_link );
	    $post_link = str_replace( array('/' . $post->post_type . '/', '/prodotto/'), '/', $post_link . $append);
	    return $post_link;
	}
	add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );


	function gp_parse_request_trick( $query ) {
	 
	    // Only noop the main query
	    if ( ! $query->is_main_query() )
	        return;
	    //var_dump(empty( $query->query['tab'] ), count( $query->query ));
	 	//Only noop our very specific rewrite rule match
	 	if ( 2 != count( $query->query ) || ! isset( $query->query['page'] )  ) {
	        return;
	    }

	 
	    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
	    if ( ! empty( $query->query['name'] ) ) {
	        $query->set( 'post_type', array( 'post', 'page', 'product', 'prodotto' ) );
	    }
	}
	add_action( 'pre_get_posts', 'gp_parse_request_trick', 10 );

	// Allow SVG
	add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

		global $wp_version;
		if ( $wp_version !== '4.7.1' ) {
		return $data;
		}

		$filetype = wp_check_filetype( $filename, $mimes );

		return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
		];

	}, 10, 4 );

	function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		$mimes['ogg'] = 'video/ogg';
		$mimes['ogv'] = 'video/ogv';
		
		return $mimes;
	}
	add_filter( 'upload_mimes', 'cc_mime_types' );

	function fix_svg() {
		echo '<style type="text/css">.attachment-266x266, .thumbnail img { width: 100% !important; height: auto !important; }</style>';
	}
	add_action( 'admin_head', 'fix_svg' );

	if( function_exists('acf_add_options_page') ) {	
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Footer',
			'menu_title'	=> 'Footer Settings',
			'parent_slug' => 'themes.php'
		));
		acf_add_options_page(array(
			'page_title' 	=> 'Instagram Settings',
			'menu_title'	=> 'Instagram',
			'menu_slug' => 'instagram',
			'icon_url' => get_stylesheet_directory_uri() . '/assets/images/instagram.png'
		));
		acf_add_options_page(array(
			'page_title' 	=> 'Facebook Settings',
			'menu_title'	=> 'Facebook',
			'menu_slug' => 'facebook',
			'icon_url' => 'dashicons-facebook'
		));
		acf_add_options_page(array(
			'page_title' 	=> 'Mailchimp Settings',
			'menu_title'	=> 'Mailchimp',
			'menu_slug' => 'mailchimp',
			'icon_url' => 'dashicons-email'
		));
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Campi comuni',
			'menu_title'	=> 'Campi comuni',
			'parent_slug' => 'themes.php'
		));
	}

	add_action('admin_head', 'my_custom_fonts');

	function my_custom_fonts() {
	  echo '<style>
	    .toplevel_page_instagram img {
	    	height: 20px;
	    }
	  </style>';
	}

	add_filter('deprecated_constructor_trigger_error', '__return_false');
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	add_filter('excerpt_more','__return_false');

	function my_acf_init() {
	
		acf_update_setting('google_api_key', 'AIzaSyDPjHDg5-EGUjQ_zDjltstiGMJ-aVkHuU0');
	}

	add_action('acf/init', 'my_acf_init');

	add_action( 'init', 'my_add_excerpts_to_pages' );
	function my_add_excerpts_to_pages() {
		add_post_type_support( 'page', 'excerpt' );
	}

	add_filter( 'query_vars', 'add_query_vars');
    function add_query_vars($vars){
        $vars[] = __('tab', 'iro');
        $vars[] = 'eo_count';
        $vars[] = 'productId';
        $vars[] = 'review_product';
        $vars[] = 'rating';
        $vars[] = 'step';
        //$vars[] = 'dichiarazione_di_conformita';
        //$vars[] = 'carta_di_identita';
       	$vars[] = 'files';
       	$vars[] = 'delete-account';
       	$vars[] = 'promo_id';
        return $vars;
    }
    function rewrite_popup_url() {
        $name = 'step';
        add_rewrite_endpoint($name, EP_PERMALINK );
        add_rewrite_endpoint('files', EP_ALL );
        add_rewrite_endpoint( 'delete-account', EP_ROOT | EP_PAGES );
    }
    add_action('init', 'rewrite_popup_url' );

    function my_custom_flush_rewrite_rules() {
	    flush_rewrite_rules();
	}

	add_action( 'wp_loaded', 'my_custom_flush_rewrite_rules' );

    function setup_seo_endpoint() {
    // Ensures the $query_vars['item'] is available
	    add_rewrite_tag( '%files%', '([^&]+)' );

	    // Requires flushing endpoints whenever the 
	    // front page is switched to a different page
	    $page_on_front = get_option( 'page_on_front' );

	    // Match the front page and pass item value as a query var.
	    add_rewrite_rule( '^files/([^/]*)/?', 'index.php?page_id='.$page_on_front.'&files=$matches[1]', 'top' );
	    // Match non-front page pages.
	    add_rewrite_rule( '^(.*)/files/([^/]*)/?', 'index.php?pagename=$matches[1]&static=true&files=$matches[2]', 'top' );
	}
	add_action( 'init', 'setup_seo_endpoint', 1);

	// http://wordpress.stackexchange.com/a/220484/52463
	// In order to keep WordPress from forcing a redirect to the canonical
	// home page, the redirect needs to be disabled.
	function disable_canonical_redirect_for_front_page( $redirect ) {
	    if ( is_page() && $front_page = get_option( 'page_on_front' ) ) {
	        if ( is_page( $front_page ) ) {
	            $redirect = false;
	        }
	    }

	    return $redirect;
	}
	add_filter( 'redirect_canonical', 'disable_canonical_redirect_for_front_page' );


    function extend_facebook_at($value, $post_id, $field) {
    	$group = get_field('facebook_api', 'options');
    	$new_value = extend_access_token($group);
    	$value = $new_value['facebook_extended_at'];
    	return $value;
    }
    //add_filter('acf/update_value/name=facebook_extended_at', 'extend_facebook_at', 10, 3);
    //add_action( 'acf/save_post', 'extend_facebook_at', 100 );


    function iro_larger_image($atts) {
        $src = $atts['src'];
        ob_start(); 
        include(locate_template( 'builder/commons/large-image.php', false, true ));
        return ob_get_clean();
    }

    add_shortcode( 'larger_image', 'iro_larger_image' );

    function my_gallery_shortcode( $output = '', $attrs ) {
		global $post;
		$row = str_replace(',', '', $attrs['ids']);
		$ids = explode(',', $attrs['ids']);
		$images = array();
		$full = true;
		foreach ($ids as $id) {
			array_push($images, array('ID' => $id, 'url' => wp_get_attachment_image_src( $id, 'full')[0], 'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true)));
		}
		ob_start();
		$prepend = ($post->post_type == 'post') ? '</div>' : '';
		$append = ($post->post_type == 'post') ? '<div class="post__content post__content--grow-lg post__content--mw-large">' : '';
		include(locate_template('builder/commons/gallery.php', false, false));
		$output = $prepend.'<div class="post__gallery" id="slider_'.$row.'">'.ob_get_clean().'</div>'.$append;
		return $output;
	}

	add_filter( 'post_gallery', 'my_gallery_shortcode', 10, 2 );
	function custom_paginate_links($link) {
		$term = get_queried_object();
		if(is_category()) {
			$base_link = get_term_link($term->term_id) . '/';
		} else {
			$base_link = get_permalink(get_option('page_for_posts'))  . '/';
		}
		$sref = str_replace($base_link, '', $link);
		$sref = (is_category()) ? '" ui-sref="app.category({name : \''.$term->slug.'\', path : \''.$sref.'\'})' : '" ui-sref="app.blog({path : \''.$sref.'\'})';
		//$link = $link . $sref;
		return $link;
	}
	//add_filter( 'paginate_links', 'custom_paginate_links', 10, 1 );

	function promo_value_fn( $atts, $content ){
		return '</span><span class="promo__discount">'.$content.'</span><span>';
	}
	add_shortcode( 'promo_value', 'promo_value_fn' );

	function promo__expire_fn( $atts, $content ){
		return '</span><span class="promo__sep">/</span><span class="promo__expire">'.$content.'</span><span>';
	}
	add_shortcode( 'promo_expire', 'promo__expire_fn' );
	function promo__name_fn( $atts, $content ){
		return '</span><span class="promo__name">'.$content.'</span><span>';
	}
	add_shortcode( 'promo_name', 'promo__name_fn' );


	function footer_scripts() { ?>
	<script type="text/javascript">
_linkedin_data_partner_id = "322636";
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid=322636&fmt=gif" />
</noscript>
<?php } add_action( 'wp_footer', 'footer_scripts', 10, 1 );


//unset all those WPSEO shizzle  columns
function rkv_remove_columns( $columns ) {
	unset( $columns['wpseo-score-readability'] );
	unset( $columns['wpseo-score'] );
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );
	unset( $columns['wpseo-links'] );
	unset( $columns['wpseo-linked'] );
	return $columns;
}
add_filter ( 'manage_edit-product_columns', 'rkv_remove_columns' );