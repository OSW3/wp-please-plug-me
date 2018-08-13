<?php

namespace Framework\Components\Form\Form;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

if (!class_exists('Framework\Components\Form\Form\Types'))
{
    class Types
    {
        /**
         * List of valid Types
         */
        const TYPES = ['checkbox','choices','collection','color','date',
            'datetime','email','file','hidden','month','number','option',
            'output','password','radio','range','search','tel','text','textarea',
            'time','url','week','wysiwyg','year'];

        // /**
        //  * The instance of the bootstrap class
        //  * 
        //  * @param object instance
        //  */
        // // protected $bs;

        // /**
        //  * List of
        //  */

        // /**
        //  * Types register
        //  */
        // private $types = array();

        /**
         * 
         */
        public function __construct($bs)
        {
            // Retrieve the bootstrap class instance
            $this->bs = $bs;
        }
    }
}