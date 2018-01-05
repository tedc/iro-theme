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