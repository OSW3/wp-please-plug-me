<?php

namespace Framework\Components\Form\Types;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Types;

if (!class_exists('Framework\Components\Form\Types\Datetime'))
{
    class Datetime extends Types 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            // TODO: List
            // TODO: Step
            return ['type', 'id', 'name', 'class', 'value', 'list', 'disabled', 'max', 'min', 'readonly', 'required', 'step'];
        }
        
        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setType('datetime-local');
        }



// <input type="datetime-local" name="thedate" min="2018-08-10T03:23" list="dates">

// <datalist id="dates">
//     <option value="1985-09-10T22:30">
//     <option value="1982-03-15T23:10">
// </datalist>
    }
}
