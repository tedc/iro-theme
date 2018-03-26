<?php
	function print_svg($file) {
		$svg = file_get_contents($file); 
        $svg = preg_replace('/(<[^>]+) id=".*?"/', '$1', $svg);
        $svg = preg_replace('/(<[^>]+) data-name=".*?"/', '$1', $svg);
        $svg = preg_replace('/(w*)?<title>[^>]+<\/title>(w*)?/i', '', $svg);
        return $svg;
	}

	function my_excerpt($length, $excerpt = false) { 
		global $post;
		if ($excerpt) return $excerpt;
	    $text = strip_shortcodes( $post->post_content );
	    $text = apply_filters('the_content', $text);
	    $text = str_replace(']]>', ']]&gt;', $text);
	    $text = strip_tags($text);
	    $excerpt_length = apply_filters('excerpt_length', $length);
	    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
	    if ( count($words) > $excerpt_length ) {
	            array_pop($words);
	            $text = implode(' ', $words);
	            $text = $text;
	    } else {
	            $text = implode(' ', $words);
	    }

	    return apply_filters('wp_trim_excerpt', $text);
	}

	function scrollmagic($val) {
		if(!is_handheld()) {
			echo ' ng-sm=\'{'.$val.'}\'';
		}
	}

	// function odd_even($f) {
	// 	$field = get_field_object($f); 
	// 	unset($field['choices'][get_field($f)]); 
	// 	return key($field['choices']);
	// }

	function get_slider($field, $id, $row, $main = false) {
		global $sitepress;
		if($main) {
			if(get_field($field)) {
				$slider = get_field($field);
			} else {
				$slider = get_field($field, $id);
			}	
		} else {
			if(get_sub_field($field)) {
				$slider = get_sub_field($field);
			} else {		
				while(have_rows('layout', $id)) : the_row();
					if(get_row_layout() == 'full-slider') :
						$slider = get_sub_field($field);
						break;
					endif;
				endwhile;
			}
		}
		if(ICL_LANGUAGE_CODE != $sitepress->get_default_language()){
			$slider = array_reverse($slider);
		}
		return $slider;
	}

	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	include( locate_template( 'extras/vendor/autoload.php', false, false) );
    use Facebook\FacebookRequest;
    function fb_init($opts) {
        $fb = new Facebook\Facebook([
            'app_id' => $opts['facebook_app_id'],
            'app_secret' => $opts['facebook_app_secret']
        ]);
        return $fb;
    }
    // FB INIT
    function extend_access_token($opts) {
        if(!isset($opts['facebook_access_token']) || $opts['facebook_access_token'] == '') {
            return $opts;
        }
        $fb = fb_init($opts);
        $fbApp = fb_init($opts)->getApp();
        $fbClient = fb_init($opts)->getClient();
        $extend = new Facebook\Authentication\OAuth2Client(
            $fbApp,
            $fbClient
        );
        $extend_response = $extend->getLongLivedAccessToken($opts['facebook_access_token']);
        $extend_result = $extend_response->getValue();
        $request = new Facebook\FacebookRequest(
            $fbApp,
            $extend_result,
            'GET',
            '/'. $opts['facebook_page_id'] . '/?fields=access_token'
        );
        try {
            $response = $fb->getClient()->sendRequest($request);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            return false;
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
           return false;
            exit;
        }
        $response = $fb->getClient()->sendRequest($request);
        $result = $response->getGraphObject()->asArray();
        $pageToken = $result['access_token'];
        $opts['facebook_extended_at'] = $pageToken;
        return $opts;
    }

    function facebook_feed() {
    	acf_set_language_to_default();
        $options = get_field('facebook_api', 'options');
        acf_unset_language_to_default();
        $page_id = $options['facebook_page_id'];
        if(!isset($options) || !array_key_exists('facebook_extended_at', $options) || $options['facebook_extended_at'] == '') {
            return $options;
        }
        $fb = fb_init($options);
        $fbApp = $fb->getApp();
        $fbClient = $fb->getClient();

        // EXTENDING THE ACCESS TOKEN
        //            
       
        $token = $options['facebook_extended_at'];
        
        #return print_r($request);
        #return $extend_response->isLongLived();
        $pageRequest = new Facebook\FacebookRequest(
            $fbApp,
            $token,
            'GET',
            '/'. $page_id . '/posts?fields=id,attachments,from,message,message_tags,story,story_tags,link,source,name,caption,description,type,status_type,object_id,created_time&limit='.$options['facebook_limit']
        );
        $pageInfo = new Facebook\FacebookRequest(
            $fbApp,
            $token,
            'GET',
            '/'. $page_id . '?fields=link'
        );
        //$facebookSession = new FacebookSession($pageToken);
        try {
            $pageResponse = $fb->getClient()->sendRequest($pageRequest);
            $infoResponse = $fb->getClient()->sendRequest($pageInfo);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $pageResponse->getDecodedBody();
        $infoNode = $infoResponse->getDecodedBody();
        $data = array('info' => $infoNode, 'feed' => $graphNode['data']);
        return $data;
    }

    function get_percentage($v, $t) {
        echo (($v * 100) / $t);
    }

    function stars($average, $class_base) {
        $average = 3.5;
        for($i= 1; $i<= round($average + 1, 0, PHP_ROUND_HALF_UP) && $i<= 5; $i++ ) {
            if (($average + 1) - $i > 0 && ($average + 1) - $i < 1) {
                $is_half = (($average + 1) - $i > 0.8) ? false : true;
            } else {
                $is_half = false;
            }
            $starClass = ($is_half) ? $class_base .'__star '. $class_base .'__star--active-half' : $class_base .'__star '. $class_base .'__star--active';
            $stars = (!$is_half) ? '<i class="icon-stella"></i>' : '<span class="'. $class_base .'__starhalf"><i class="icon-stella"></i></span><i class="icon-stella"></i>';
            echo '<span class="'.$starClass.'">'.$stars.'</span>';
        }
        if(5 - $average >= 1) {
            $resto = round((5 - $average), 0, PHP_ROUND_HALF_UP);
            for($c = 0; $c<$resto; $c++) {
                echo '<span class="'. $class_base .'__star"><i class="icon-stella"></i></span>';
            }
        }
    }

    function instagram_object() {
            $client_id = get_field('instagram_client_id', 'options');
            $client_secret = get_field('instagram_client_secret', 'options');
            $access_token = get_field('instagram_access_token', 'options');
            $user_id = get_field('instagram_user_id', 'options');
            $count = get_field('instagram_count', 'options');
            $access_token_parameters = array(
                'client_id'                =>     $client_id,
                'client_secret'            =>     $client_secret,
                'access_token'             =>     $access_token, 
                'redirect_uri'             =>     get_bloginfo('url'),
                'http_timeout'             => 600,
                'http_connect_timeout' => 2000,
                'count' => $count
            );
            $url = "https://api.instagram.com/v1/users/".$user_id."/media/recent?".http_build_query($access_token_parameters);
            $curl = curl_init($url);    // we init curl by passing the url
            //curl_setopt($curl,CURLOPT_POST,true);   // to send a POST request   
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            $items = json_decode($items);
            return $items;
            $cached = get_transient($user_id);
            // return $items;
            if($cached !== false) {
                return $cached;
            } else {
                $expiration_time = 60*60*2;
                set_transient($user_id, $items, $expiration_time);
                return $items;
            }
    }