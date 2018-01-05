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