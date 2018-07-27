<?php 
/**
 * PPM_Filter_Exemple
 * 
 * @declaration (/Plugin/Config/config.php)
 * 
 * $config = [
 *      "filters" => [
 *          "PPM_Filter_Exemple" => "wp"
 *      ]
 * ];
 * 
 * 
 * print_r( apply_filters( 'PPM_Filter_Exemple', 'TEST me' ));
 */

/** 
 * Priority: 42
 * Params: name; test
 */

if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}

if (!function_exists('PPM_Filter_Exemple')) 
{
    function PPM_Filter_Exemple( $argument ) 
    {
        // var_dump( $argument );
        return $argument;
    }
}