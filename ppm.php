<?php
/**
 * Plugin Name: Please Plug Me
 * Version:     0.0.1
 * Plugin URI:  http://osw3.net/wordpress/plugins/please-plug-me/
 * Description: A WordPress plugin boilerplate.
 * Author:      OSW3
 * Author URI:  http://osw3.net/
 * Text Domain: wordpress-ppm
 * Domain Path: /Plugin/Languages/
 * License:     MIT
 */










// DON'T EDIT THIS CODE ////////////////////////////////////////////////////////
// DON'T EDIT THIS CODE ////////////////////////////////////////////////////////

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

$ppm_root_file 	 = __FILE__;
$ppm_root_dir 	 = __DIR__;
$ppm_folder_name = basename(dirname($ppm_root_file));

if (!defined('ABSPATH')){
	die("The constant \"ABSPATH\" is undefined for the plugin \"$ppm_folder_name\".");
}

$ppm_required = [
	ABSPATH.'wp-admin/includes/plugin.php',
	$ppm_root_dir.'/Framework/bootstrap.php'
];

foreach ($ppm_required as $file) 
{
	file_exists($file) ? include_once $file : die("<strong>Plugin Error</strong>: A required file ($file) is not found for the plugin \"$ppm_folder_name\".");
}

// -- 

use \Framework\Components\Form\Response;
use \Framework\Components\Notices;
use \Framework\Kernel\Session;

class PPM 
{
	private $namespace;
	private $posttype;
	private $responses;

	public function __construct(string $namespace, string $posttype)
	{
		$this->namespace = $namespace; 
		$this->posttype = $posttype;
	}

	public function responses( array $posts, bool $asObject = false )
	{
		$response = new Response( $posts );
		$responses = $response->responses();
		$this->responses = $responses;

		return $asObject ? $responses : $responses->sanitizedResponses( $responses->getMetaTypes() );
	}

	public function validate( array $posts = [] )
	{
		$responses = (!empty($posts))
			? $this->responses( $posts )
			: $this->responses;

		return $responses->validate();
	}

	public function clearSession(bool $forced = false)
	{
		if ($forced)
		{
			$this->clear_session();
		}

        add_action('wp_footer', [$this, 'clear_session']);
	}
	public function clear_session()
	{
		$session = new Session($this->namespace);
		$session->clear($this->posttype);
	
		$notices = new Notices($this->namespace);
		$notices->clear();
	}
}