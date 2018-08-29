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
		private $local_path;
		private $remote_path;

		/**
		 * 
		 */
		private $local_map;
		private $remote_map;

		/**
		 * 
		 */
		public function __construct($bs)
		{
            // Retrieve the bootstrap class instance
			$this->bs = $bs;
			
			// Setters for local and remote Path
			$this->setLocalPath();
			$this->setRemotePath();
			
			// Setters for local and remote Map
			$this->setLocalMap();
			$this->setRemoteMap();

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

			// echo "<pre>";
			// print_r( $this->getRemoteMap() );
			// echo "</pre>";

			// echo "<pre>";
			// print_r( $this->getLocalMap() );
			// echo "</pre>";

			echo "<pre>";
			print_r( $this->diffMap() );
			echo "</pre>";


			foreach ($this->diffMap() as $md5 => $file) 
			{
				if ($file != 'Kernel/Updater.php')
				{
					$source = $this->getRemotePath().$file;
					$dest = $this->getLocalPath().$file;
					copy($source, $dest);

					echo "<pre>";
					print_r([
						$source, 
						$dest
					]);
					echo "</pre>";
				}
			}
			// $this->getRemoteMap();
			// $this->makeMap();
			exit;

			if ($this->check())
			{
				// TODO: Show update notice
			}
		}

		/**
		 * Path
		 */
		private function setRemotePath()
		{
			$this->remote_path = "https://raw.githubusercontent.com/OSW3/wp-please-plug-me/develop/Framework/";

			return $this;
		}
		private function getRemotePath()
		{
			return $this->remote_path;
		}
		private function setLocalPath()
		{
			$this->local_path= $this->bs->getRoot().'Framework/';

			return $this;
		}
		private function getLocalPath()
		{
			return $this->local_path;
		}

		/**
		 * Map
		 */
		private function setRemoteMap()
		{
			$this->remote_map = [];

			// Define remote Map file url
			$url = $this->getRemotePath().self::FILE_MAP;

			// Get map content
			if ($map = @file_get_contents($url))
			{
				$this->remote_map = json_decode($map, true);
			}

			return $this;
		}
		private function getRemoteMap()
		{
			return $this->remote_map;
		}
		private function setLocalMap()
		{
			$this->local_map = json_decode($this->makeMap(), true);

			return $this;
		}
		private function getLocalMap()
		{
			return $this->local_map;
		}

		// Generate Map of local 
		private function makeMap()
		{
			$map = [];

			$scan = $this->scandir( $this->getLocalPath() );

			foreach ($scan as $path) 
			{
				$file = str_replace($this->getLocalPath(), '', $path);
				$md5 = md5_file($path);

				if ('map' != $file)
				{
					$map[$md5] = $file;
				}
			}

			return json_encode($map);
		}
		private function diffMap()
		{
			return array_diff_assoc($this->getRemoteMap(), $this->getLocalMap());
			// return array_diff_assoc($this->getLocalMap(), $this->getRemoteMap());
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
			$file = $this->getLocalPath().self::FILE_VERSION;

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
			$file = $this->getRemotePath().self::FILE_VERSION;

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
		 * Compare the version number
		 */
		private function checkVersion()
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
