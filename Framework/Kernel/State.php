<?php

namespace Framework\Kernel;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Kernel\Kernel;
use \Framework\Register\Options;

if (!class_exists('Framework\Kernel\State'))
{
	class State extends Kernel
	{
		/**
		 * Plugin activation
		 */
		public function activate()
		{
			// Add plugin options 
			// TODO: (if not already added)
			Options::add([ $this->getNamespace() => $this->getOptions() ]);

			// Create Specific Database (if not already created)

			// Initialize Text domain


            // print_r("\n");
			// echo "activate"; exit;
		}

		/**
		 * Plugin deactivation
		 */
		public function deactivate()
		{
			// Delete plugin options (if not preserved)
			Options::delete( $this->getNamespace() );

			// Delete Specific Database (if not preserved)

			// Delete Text domain (if not preserved)



			// echo "Stop"; exit;
		}
	}
}
