<?php

namespace Framework\Components;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

if (!class_exists('Framework\Components\FileSystem'))
{
    class FileSystem
    {
        /**
         * Extensions
         */
        const EXTENSION_CSS = ".css";
        const EXTENSION_JS  = ".js";
        const EXTENSION_PHP = ".php";

        /**
         * Directories
         */
        const DIRECTORY_CONFIG      = "Plugin/Config/";
        const DIRECTORY_HOOKS       = "Plugin/Hooks/";
        const DIRECTORY_FILTERS     = "Plugin/Filters/";
        const DIRECTORY_SHORTCODES  = "Plugin/Shortcodes/";
        const DIRECTORY_STYLES      = "Plugin/Public/assets/styles/";
        const DIRECTORY_SCRIPTS     = "Plugin/Public/assets/scripts/";
        const DIRECTORY_IMAGES      = "Plugin/Public/assets/images/";

        /**
         * Files
         */
        const FILE_CONFIG = self::DIRECTORY_CONFIG.'config'.self::EXTENSION_PHP;
    }
}