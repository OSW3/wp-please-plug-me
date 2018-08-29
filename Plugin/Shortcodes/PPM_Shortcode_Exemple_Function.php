<?php 
/**
 * PPM_Shortcode_Exemple_Function
 * 
 * This is an exemple of a shortcode function.
 * We declare a function code : "PPM_Shortcode_Exemple_Code", and
 * we call this function by : "PPM_Shortcode_Exemple_Function"
 * 
 * 
 * @declaration (/Plugin/Config/config.php)
 * 
 * $config = [
 *      "shortcodes" => [
 *          "PPM_Shortcode_Exemple_Function" => "PPM_Shortcode_Exemple_Code"
 *      ]
 * ];
 * 
 * 
 * @usage
 * 
 * With callback
 * <?= do_shortcode('[PPM_Shortcode_Exemple_Code]'); ?>
 * 
 * Without callback
 * <?php do_shortcode('[PPM_Shortcode_Exemple_Code]'); ?>
 * 
 * With attribute
 * <?php do_shortcode('[PPM_Shortcode_Exemple_Code key="value"]'); ?>
 */

if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}

if (!function_exists('PPM_Shortcode_Exemple_Function')) 
{
    function PPM_Shortcode_Exemple_Function( $attributes, $content, $tag ) 
    {
        if (!is_admin())
        {
            echo "<h3>Shortcode \$attributes</h3>";
            if (is_array($attributes)) 
            {
                foreach ($attributes as $key => $value) {
                    echo $key ." : ". $value ."<br>";
                }
            }
            else 
            {
                echo "No attribute";
            }
    
            echo "<h3>Shortcode \$content</h3>";
            print_r($content);
    
            echo "<h3>Shortcode \$tag</h3>";
            echo $tag ."<br>";

            return "Hello, i'm a shortcode. You can do what you whant inside me.";
        }
    }
}