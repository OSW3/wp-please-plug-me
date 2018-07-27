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

if (!class_exists('Framework\Kernel\Update'))
{
	class Update extends Kernel
	{
		/**
		 * Current version
		 */
		private $current_version;

		/**
		 * Last version
		 */
		private $last_version;



		/**
		 * Set the current version number
		 * 
		 * @return object $this
		 */
		private function setCurrentVersion()
		{
			$this->current_version = null;

			return $this;
		}

		/**
		 * Get the current version number
		 * 
		 * @return string The current version number
		 */
		private function getCurrentVersion()
		{
			return $this->current_version;
		}

		/**
		 * Set the last version number
		 * 
		 * @return object $this
		 */
		private function setLastVersion()
		{
			$this->last_version = null;

			return $this;
		}

		/**
		 * Get the last version number
		 * 
		 * @return string The last version number
		 */
		private function getLastVersion()
		{
			return $this->last_version;
		}

		/**
		 * Compare the version number
		 */
		private function compare($a, $b)
		{
			# code...
		}
	}
}
