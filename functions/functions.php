<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


/**
 * Util
 * --
 * Options Getter
 */
if (!function_exists('WP_PleasePlugMe_getOption'))
{
    function WP_PleasePlugMe_getOption( $param )
    {
        $options = get_option( "osw3_plugin_boilerplate" ); // <-- Don't forget to change the option name
        return $options[ $param ];
    }
} 



/**
 * Do:
 */
if (!function_exists('my_custom_post'))
{
    function my_custom_post()
    {
        return "my-custom-post-slug";
    }
} 

/**
 * Register MetaBox Callback Exemple
 * --
 * This is an exemple of the Metabox Callback function.
 * https://developer.wordpress.org/reference/functions/register_post_type/
 * http://wp-learner.com/wotdpress-development/adding-metaboxes-to-post-types/
 */
if (!function_exists('WP_PleasePlugMe_registerMetaBoxCallback'))
{
    function WP_PleasePlugMe_registerMetaBoxCallback( $post )
    {
        // var_dump( $post );
        // exit;
    }
} 