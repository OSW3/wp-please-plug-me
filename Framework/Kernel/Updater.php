<?php

namespace Framework\Kernel;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

// use \Framework\Kernel\Kernel;

if (!class_exists('Framework\Kernel\Updater'))
{
	class Updater
	{
        /**
         * The instance of the bootstrap class
         * 
         * @param object instance
         */
		private $bs;
		
		/**
		 * Current version
		 */
		private $current_version;

		/**
		 * Last version
		 */
		private $last_version;

		/**
		 * 
		 */
		public function __construct($bs)
		{
            // Retrieve the bootstrap class instance
			$this->bs = $bs;
			
			// Setters
			$this->setCurrentVersion();
			$this->setLastVersion();

			if ($this->check())
			{
				// TODO: Show update notice
			}
		}

		/**
		 * Set the current version number
		 * 
		 * @return object $this
		 */
		private function setCurrentVersion()
		{
			// Default version
			$this->current_version = null;

			// Define file "version" path
			$file = $this->bs->getRoot().'Framework/VERSION';

			// Read file
			if (file_exists($file)) 
			{
				$this->current_version = trim(file_get_contents($file));
			}

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
			// Default version
			$this->last_version = null;

			// Define file "version" URI
			$file = 'https://raw.githubusercontent.com/OSW3/wp-please-plug-me/develop/Framework/VERSION';

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $file);
			curl_setopt($curl, CURLOPT_COOKIESESSION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$version = curl_exec($curl);
			curl_close($curl);

			$this->last_version = trim($version);

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
		private function check()
		{
			if (null != $this->getCurrentVersion() && null != $this->getLastVersion())
			{
				return version_compare( 
					$this->getCurrentVersion(), 
					$this->getLastVersion() , 
					'<'
				);
			}

			return false;
		}
	}
}
