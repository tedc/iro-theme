<?php

    if ( !function_exists( 'acf_get_language_default' ) )
    {
        function acf_get_language_default()
        {
            return acf_get_setting( 'default_language' );
        }
    }

    if ( !function_exists( 'acf_set_language_to_default' ) )
    {
        function acf_set_language_to_default()
        {
            add_filter( 'acf/settings/current_language', 'acf_get_language_default', 100 );
        }
    }

    if ( !function_exists( 'acf_unset_language_to_default' ) )
    {
        function acf_unset_language_to_default()
        {
            remove_filter( 'acf/settings/current_language', 'acf_get_language_default', 100 );
        }
    }

    function lang_nav() {
        $languages = icl_get_languages('skip_missing=0');
        $langs = '';
       foreach($languages as $l){
            $language_link = $l['url'];
            $language_code = $l['language_code'];
            $langs .= ($language_code != ICL_LANGUAGE_CODE) ? '<a href="'.$language_link.'" class="banner__btn banner__btn--lang">'.$language_code.'</a>' : '';
        }
        echo $langs;
    }