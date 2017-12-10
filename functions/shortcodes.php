<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


/**
 * Shortcode Exemple
 * --
 * This is an exemple of a shortcode function.
 * We declare a function code : "PleasePlugMe_ShorcodeExemple_Function", and
 * we call this function by : "PleasePlugMe_ShorcodeExemple_Name"
 * 
 * Declare this shortcode in config.json at :
 *      "shortcodes": {
 *          "PleasePlugMe_ShorcodeExemple_Name": "PleasePlugMe_ShorcodeExemple_Function"
 *      }
 * 
 * Use this shortcode like : 
 *      do_shortcode('[PleasePlugMe_ShorcodeExemple_Name]');
 */
if (!function_exists('PleasePlugMe_ShorcodeExemple_Function'))
{
    function PleasePlugMe_ShorcodeExemple_Function( $attributes, $content, $tag )
    {
        echo "<h3>Shortcode \$attributes</h3>";
        var_dump($attributes);

        echo "<h3>Shortcode \$content</h3>";
        print_r($content);

        echo "<h3>Shortcode \$tag</h3>";
        print_r($tag);
        return "Hello, i'm a shortcode. You can do what you whant inside me.";
    }
} 