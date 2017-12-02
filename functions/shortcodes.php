<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


/**
 * Shortcode
 * --
 * A shortcode exemple
 * usage: do_shortcode('[WP_PleasePlugMe_Shorcode_Exemple]');
 */
if (!function_exists('WP_PleasePlugMe_Shorcode_Exemple'))
{
    function WP_PleasePlugMe_Shorcode_Exemple()
    {
        return "Hello, i'm a shortcode. You can do what you whant inside me.";
    }
} 