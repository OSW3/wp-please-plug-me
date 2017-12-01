<?php
// NEED TO BE CHANGED : WP_PluginBoilerplate

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) exit;


/**
 * Action
 * --
 * An action exemple
 */
if (!function_exists('WP_PleasePlugMe_Action_Exemple'))
{
    function WP_PleasePlugMe_Action_Exemple()
    {
        return "Hello, i'm an action. You can do what you whant inside me.";
    }
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


/**
 * Util
 * --
 * Session Start
 */
if (!function_exists('OSW3_SessionStart'))
{
    function OSW3_SessionStart()
    {
        if (empty(session_id())) session_start();
    }
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
 * Register MetaBox Callback
 * --
 * https://developer.wordpress.org/reference/functions/register_post_type/
 * http://wp-learner.com/wotdpress-development/adding-metaboxes-to-post-types/
 */
if (!function_exists('WP_PleasePlugMe_registerMetaBoxCallback'))
{
    function WP_PleasePlugMe_registerMetaBoxCallback( $post )
    {
        // var_dump( $post );
        // exit;
        // add_meta_box('property-contact', 'Contact', 'searchin_property_contact_meta', 'property', 'side', 'default');

    }
} 
