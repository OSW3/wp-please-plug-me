<?php
/**
 * Plugin Name: Please Plug Me
 * Plugin URI: http://osw3.net/wordpress/plugins/please-plug-me/
 * Description: A WordPress plugin boilerplate.
 * Version: 0.1
 * Author: OSW3
 * Author URI: http://osw3.net/
 * License: OSW3
 * Text Domain: wpppm
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}

require_once(__DIR__.'/ppm/index.php');