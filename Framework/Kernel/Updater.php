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
		const FILE_MAP = 'map';
		const FILE_VERSION = 'VERSION';

        /**
         * The instance of the bootstrap class
         * 
         * @param object instance
         */
		private $bs;

		/**
		 * define the mode of this updater
		 */
		private $mode;
		
		/**
		 * Current version
		 */
		private $c_version;

		/**
		 * Last version
		 */
		private $l_version;

		/**
		 * 
		 */
		private $local;

		/**
		 * 
		 */
		private $remote;

		/**
		 * 
		 */
		private $map;

		/**
		 * 
		 */
		public function __construct($bs)
		{
            // Retrieve the bootstrap class instance
			$this->bs = $bs;
			
			// Setters
			$this->setLocal();
			$this->setRemote();
			$this->setMode();
			$this->setCurrentVersion();
			$this->setLastVersion();

			// echo "<pre>";
			// print_r( $this->getRemote() );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( $this->getLocal() );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( $this->getMode() );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( $this->getCurrentVersion() );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( $this->getLastVersion() );
			// echo "</pre>";

			// echo "<pre>";
			// var_dump( $this->check() );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( md5_file($this->getRemote().self::FILE_VERSION) );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( md5_file($this->getLocal().self::FILE_VERSION) );
			// echo "</pre>";

			$this->getRemoteMap();
			// $this->makeMap();
			exit;

			if ($this->check())
			{
				// TODO: Show update notice
			}
		}

		/**
		 * Bases path / url
		 */
		private function setRemote()
		{
			$this->remote = "https://raw.githubusercontent.com/OSW3/wp-please-plug-me/develop/Framework/";

			return $this;
		}
		private function getRemote()
		{
			return $this->remote;
		}
		private function setLocal()
		{
			$this->local= $this->bs->getRoot().'Framework/';

			return $this;
		}
		private function getLocal()
		{
			return $this->local;
		}

		/**
		 * Updater Mode
		 */
		private function setMode()
		{
			// TODO: change to add this in config.php
			$this->mode = "auto";

			return $this;
		}
		private function getMode()
		{
			return $this->mode;
		}

		/**
		 * Current version
		 */
		private function setCurrentVersion()
		{
			// Default version
			$this->c_version = null;

			// Define file "version" path
			$file = $this->getLocal().self::FILE_VERSION;

			// Read file
			if (file_exists($file)) 
			{
				$this->c_version = trim(file_get_contents($file));
			}

			return $this;
		}
		private function getCurrentVersion()
		{
			return $this->c_version;
		}

		/**
		 * Last version
		 */
		private function setLastVersion()
		{
			// Default version
			$this->l_version = null;

			// Define file "version" URI
			$file = $this->getRemote().self::FILE_VERSION;

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $file);
			curl_setopt($curl, CURLOPT_COOKIESESSION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$version = curl_exec($curl);
			curl_close($curl);

			$this->l_version = trim($version);

			return $this;
		}
		private function getLastVersion()
		{
			return $this->l_version;
		}

		/**
		 * Map
		 */
		private function getRemoteMap()
		{
			$this->map = [];

			// Define remote Map file url
			$url = $this->getRemote().self::FILE_MAP;

			// Get map content
			$map = @file_get_contents($url);

			if (false === $map)
			{
				$map = null;
			}

			$map = explode("\n", $map);


			// try {
			// 	$file = file_get_contents($url);
			// }
			// catch (\Exception $e) {
			// 	// echo $e->getMessage();
			// 	$file = "Nooooo";
			// }

			echo "<pre>";
			var_dump( $map );
			echo "</pre>";

		}
		public function makeMap()
		{
			$map = [];

			$scan = $this->scandir( $this->getLocal() );

			foreach ($scan as $path) 
			{
				$file = str_replace($this->getLocal(), '', $path);
				$md5 = md5_file($path);

				if ('map' != $file)
				{
					$map[$md5] = $file;
				}
			}

			return json_encode($map);
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





		public function scandir(string $target)
		{
			$results = [];

			if (is_dir($target))
			{
				$files = glob( $target . '*', GLOB_MARK );

				foreach ($files as $file) 
				{
					if (is_dir($file))
					{
						$results = array_merge($results, $this->scandir( $file ));
					}
					else
					{
						array_push($results, $file);
					}
				}
			}

			return $results;
		}






	}
}
