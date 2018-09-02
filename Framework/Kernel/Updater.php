<?php

namespace Framework\Kernel;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Notices;
use \Framework\Kernel\Config;
use \Framework\Kernel\Request;

if (!class_exists('Framework\Kernel\Updater'))
{
	class Updater
	{
		const FILE_MAP = 'map.json';

		const FILE_VERSION = 'VERSION';

		/**
		 * List of file excluded from the update
		 */
		// const EXCLUDE = ['Kernel/Updater.php'];
		const EXCLUDE = [];

        /**
         * The instance of the bootstrap class
         * 
         * @param object instance
         */
		private $bs;

        /**
         * Bases path / URL
         */
		private $bases;

        /**
         * Map
         * 
         * @param array
         */
		private $maps;

        /**
         * Update modes
         * 
         * @param array
         */
		private $modes;

		/**
		 * Versions
		 */
		private $versions;

		/**
		 * Sections
		 * 
		 * Array of section want to update (plugin and/or framework)
		 */
		private $sections;

		/**
		 * 
		 */
		public function __construct($bs)
		{
            // Retrieve the bootstrap class instance
			$this->bs = $bs;

			// Instance of Request
			$request = new Request;

			// Instance of Notices
			$this->notices = new Notices($this->bs->getNamespace());

			// Define sectionswe want to update (plugin and/or framework)
			$this->setSections();

			if ($request->isActionUpdate())
			{
				$this->startUpdate($request->get("section"));

				header("location: ".$request->getReferer());
				exit;
			}

			// Define Bases of Plugins and Framework, local and Remote
			$this->setBases();

			// Define Versions of Plugins and Framework, local and Remote
			$this->setVersions();

			// define the alternative API for updating checking
			// $this->checkUpdate( new \StdClass() );
			add_filter('pre_set_site_transient_update_plugins', array(&$this, 'checkUpdate'));
		
			// Display Notices
			$this->notices->get();
		}
		public function __destruct()
		{
			// Clear Notices
            $this->notices->clear();
		}

		/**
		 * Base
		 */
		private function setBases()
		{
			// Default bases
			$bases = [];

			if (in_array($this->getMode('plugin'), ['auto', 'manual']))
			{
				// Default
				$bases['plugin'] = [
					'local' => $this->bs->getRoot().'Plugin/',
					'remote' => Config::SOURCES.'Plugin/'
				];
			}

			if (in_array($this->getMode('framework'), ['auto', 'manual', 'plugin']))
			{
				// Default
				$bases['framework'] = [
					'local' => $this->bs->getRoot().'Framework/',
					'remote' => Config::SOURCES.'Framework/'
				];
			}

			$this->bases = $bases;

			return $this;
		}

		/**
		 * Map
		 */
		private function setMaps()
		{
			$maps = [];

			// Local Map
			$maps['local'] = $this->generateMap();

			// Remote Map
			$maps['remote'] = [];
			$remote_url = $this->bases['framework']['remote'].self::FILE_MAP;
			if ($map = @file_get_contents($remote_url))
			{
				$maps['remote'] = json_decode($map, true);
			}

			$this->maps = $maps;

			return $this;
		}
		private function getLocalMap()
		{
			return $this->maps['local'];
		}
		private function getRemoteMap()
		{
			return $this->maps['remote'];
		}
		private function generateMap()
		{
			$map = [];
			$base = $this->bases['framework']['local'];

			$scan = $this->scandir($base);

			foreach ($scan as $path) 
			{
				$file = str_replace($base, '', $path);
				$exclude = array_merge(self::EXCLUDE, [self::FILE_MAP]);
				if (!in_array($file, $exclude))
				{
					$md5 = md5(md5_file($path).md5($path));
					$map[$md5] = $file;
				}
			}

			return $map;
		}
		private function makeMap()
		{
			// The file
			$file = $this->bases['framework']['local'].self::FILE_MAP;

			// The data
			$data = $this->generateMap();

			$fp = fopen($file, 'w');
			fwrite($fp, json_encode($data));
			fclose($fp);
		}

		/**
		 * Modes
		 */
		private function getMode(string $section = '')
		{
			if (isset($this->modes[$section]))
			{
				return $this->modes[$section];
			}

			return false;
		}

		/**
		 * Section
		 */
		private function setSections()
		{
			$this->sections = [];
			$this->modes = [];

			$sections = $this->bs->getUpdate();

			if (isset($sections['plugin']) && in_array($sections['plugin'], ['auto', 'manual']))
			{
				$this->modes['plugin'] = $sections['plugin'];
				array_push($this->sections, 'plugin');
			}

			if (isset($sections['framework']) && in_array($sections['framework'], ['auto', 'manual', 'plugin']))
			{
				$this->modes['framework'] = $sections['framework'];
				array_push($this->sections, 'framework');
			}

			return $this;
		}
		private function getSections()
		{
			return $this->sections;
		}

		/**
		 * Update
		 */
		public function checkUpdate($transient)
		{
			foreach ($this->getSections() as $section) 
			{
				$current_version = $this->versions[$section]['local'];
				$remote_version = $this->versions[$section]['remote'];

				$update = version_compare( $current_version, $remote_version, '<');

				if ('auto' == $this->getMode($section) && $update)
				{
					$this->startUpdate($section);
				}

				elseif ('manual' == $this->getMode($section) && $update)
				{
					$params = http_build_query([
						'ppm' => $this->bs->getNamespace(),
						'action' => 'update',
						'section' => $section
					]);

					$url = implode("?", [admin_url("plugins.php"), $params]);

					$message = __("The plugin <strong>".$this->bs->getName()."</strong> need an update. Please <a href=\"$url\">update now</a>.");
					$this->notices->warning('ppm', $message, true);
				}
			}

			return $transient;
		}
		private function startUpdate(string $section = '')
		{
			// Define Maps of Plugins and Framework, local and Remote
			$this->setMaps();
			
			if (in_array($section, $this->getSections()))
			{
				// Generate the remove list
				$rm = array_diff_assoc($this->getLocalMap(), $this->getRemoteMap());
				// Generate the Download list
				$dl = array_diff_assoc($this->getRemoteMap(), $this->getLocalMap());

				// Remove files ares not in remote repository
				foreach ($rm as $file) 
				{
					if (!in_array($file, self::EXCLUDE))
					{
						$source = $this->bases[$section]['local'].$file;
						if (file_exists($source))
						{
							unlink($source);
						}
					}
				}

				// Copy files are not already in local
				foreach ($dl as $file) 
				{
					if (!in_array($file, self::EXCLUDE))
					{
						$source = $this->bases[$section]['remote'].$file;
						$dest = $this->bases[$section]['local'].$file;
						
						copy($source, $dest);
					}
				}

				// Update the map
				$this->makeMap();
			}
			// exit;
		}

		/**
		 * Versions
		 */
		private function setVersions()
		{
			// Default versions
			$versions = [];

			if (in_array($this->getMode('plugin'), ['auto', 'manual']))
			{
				// Default
				$versions['plugin'] = [
					'local' => null,
					'remote' => null
				];

				// Local Version of Plugin
				$lvp = $this->bs->getVersion();
				if (is_string($lvp) && !empty($lvp))
				{
					$versions['plugin']['local'] = $lvp;
				}
	
				// Remote  Version of Plugin
				$rvp = null; // TODO: 
				if (is_string($rvp) && !empty($rvp))
				{
					$versions['plugin']['remote'] = $rvp;
				}
			}

			if (in_array($this->getMode('framework'), ['auto', 'manual', 'plugin']))
			{
				// Default
				$versions['framework'] = [
					'local' => null,
					'remote' => null
				];

				// Local Version of Framework
				$lvf = null;
				$file = $this->bases['framework']['local'].self::FILE_VERSION;
				if (file_exists($file)) 
				{
					$lvf = trim(file_get_contents($file));
				}
				if (is_string($lvf) && !empty($lvf))
				{
					$versions['framework']['local'] = $lvf;
				}
	
	
				// Remote  Version of Framework
				$rvf = null;
				$file = $this->bases['framework']['remote'].self::FILE_VERSION;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $file);
				curl_setopt($curl, CURLOPT_COOKIESESSION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$version = curl_exec($curl);
				curl_close($curl);
				$rvf = trim($version);
				if (is_string($rvf) && !empty($rvf))
				{
					$versions['framework']['remote'] = $rvf;
				}
			}

			$this->versions = $versions;

			return $this;
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