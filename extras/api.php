<?php
    add_action( 'rest_api_init', 'api_init' );
    
    function api_init() {
        register_rest_route('api/v1', '/instagram', array(
            "methods" => 'GET',
            "callback" => 'instagram_posts'
        ));
        function instagram_posts() {
            $client_id = get_field('instagram_client_id', 'options');
            $client_secret = get_field('instagram_client_secret', 'options');
            $access_token = get_field('instagram_access_token', 'options');
            $user_id = get_field('instagram_user_id', 'options');
            $count = get_field('instagram_count', 'options');
            $url = "https://api.instagram.com/v1/users/".$user_id."/media/recent";
            $access_token_parameters = array(
                'client_id'                =>     $client_id,
                'client_secret'            =>     $client_secret,
                'access_token'             =>     $access_token, 
                'redirect_uri'             =>     get_bloginfo('url'),
                'http_timeout'             => 600,
                'http_connect_timeout' => 2000,
                'count' => $count
            );
            $curl = curl_init($url . http_build_query($access_token_parameters));    // we init curl by passing the url
            //curl_setopt($curl,CURLOPT_POST,true);   // to send a POST request
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   // to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   // to stop cURL from verifying the peer's certificate.
            $items = curl_exec($curl);   // to perform the curl session
            curl_close($curl);   // to close the curl session
            // $api = new Instaphp\Instaphp([
            //         'client_id' => $client_id,
            //         'client_secret' => $client_secret,
            //         'redirect_uri' => get_bloginfo('url'),
            //         'http_timeout' => 6000,
            //         'http_connect_timeout' => 2000
            //     ]);
            // if(!$api) {
            //     return;
            // }
            // $api->setAccessToken($access_token);
            // $items = $api->Users->Recent($user_id, array('count'=>$count));
            //$cached = get_transient($user_id);
            return $items;
            // if($cached !== false) {
            //     return $cached;
            // } else {
            //     $expiration_time = 60*60*2;
            //     set_transient($user_id, $items, $expiration_time);
            //     return $items;
            // }
        }
        register_rest_route('api/v1', '/facebook', array(
            'methods' => 'GET',
            'callback' => 'faebook_posts'
        ));
        function faebook_posts() {
            $page_id = get_field('facebook_api', 'options')['facebook_page_id'];
            $cached = get_transient($page_id);
            $data = facebook_feed();
            return $data;
            if($cached !== false) {
                return $cached;
            } else {
                $expiration_time = 60*60*2;
                set_transient($page_id, $data, $expiration_time);
                return $data;
            }
        }
    }

    // Enable the option show in rest
    add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

    // Enable the option edit in rest
    add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );

    add_filter( 'acf/rest_api/item_permissions/update', function( $permission, $request, $type ) {
        if ( 'user' == $type && method_exists( $request, 'get_param' ) && get_current_user_id() == $request->get_param( 'id' ) ) {
            return true;
        }
        return $permission;
    }, 10, 3 );