<?php 
/**
 * PPM_Hook_Exemple
 * 
 * @declaration (/Plugin/Config/config.php)
 * 
 * $config = [
 *      "hooks" => [
 *          "PPM_Hook_Exemple" => "wp"
 *      ]
 * ];
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

if (!function_exists('PPM_Hook_Exemple')) 
{
    function PPM_Hook_Exemple() 
    {
        if (!is_admin())
        {
            echo "Hello, i'm a hook. You can do what you whant inside me.";
        }
    }
}