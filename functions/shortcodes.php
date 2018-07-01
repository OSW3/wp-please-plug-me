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
 * We declare a function code : "PPM_Exemple_Shorcode_Function", and
 * we call this function by : "PleasePlugMe_ShorcodeExemple_Name"
 * 
 * Declare this shortcode in config.json at :
 *      "shortcodes": {
 *          "PleasePlugMe_ShorcodeExemple_Name": "PPM_Exemple_Shorcode_Function"
 *      }
 * 
 * Use this shortcode like : 
 *      do_shortcode('[PleasePlugMe_ShorcodeExemple_Name]');
 */
if (!function_exists('PPM_Exemple_Shorcode_Function'))
{
    function PPM_Exemple_Shorcode_Function( $attributes, $content, $tag )
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