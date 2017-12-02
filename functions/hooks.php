<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


/**
 * Action
 * --
 * An action exemple
 */
if (!function_exists('WP_PleasePlugMe_Hook_Exemple'))
{
    function WP_PleasePlugMe_Hook_Exemple()
    {
        return "Hello, i'm an action. You can do what you whant inside me.";
    }
} 