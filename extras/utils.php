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