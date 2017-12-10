<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


/**
 * Hook Exemple
 * --
 * This is an exemple of a hook function.
 * We declare a function code : "PleasePlugMe_HookExemple_Function"
 * 
 * Declare this hook in config.json at :
 *      "hooks": {
 *          "PleasePlugMe_HookExemple_Function": "wp"
 *      }
 * 
 * This hook is call at each event "wp"
 */
if (!function_exists('PleasePlugMe_HookExemple_Function'))
{
    function PleasePlugMe_HookExemple_Function()
    {
        if (!is_admin())
        {
            echo "Hello, i'm a hook. You can do what you whant inside me.";
        }
    }
} 