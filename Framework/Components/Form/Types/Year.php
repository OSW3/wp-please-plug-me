<?php

namespace Framework\Components\Form\Types;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Types\Text;

if (!class_exists('Framework\Components\Form\Types\Year'))
{
    class Year extends Choices 
    {
        /**
         * Field Builder
         */
        public function builder()
        {
            // Define choices
            $choices = [];

            // Default range
            $_range = [date('Y'), date('Y')-100];
            $range = [];

            // Retrieve Range parameter
            if ($this->getConfig('range'))
            {
                $range = $this->getConfig('range');
            }

            // Check range
            if (isset($range[0]) && isset($range[1]))
            {
                $_range = [
                    intval($range[0]),
                    intval($range[1])
                ];
            }

            // Check direction
            if ($_range[0] > $_range[1])
            {
                for ($i = $_range[0]; $i >= $_range[1]; $i--) 
                {
                    $choices[$i] = $i;
                }
            }
            else {
                for ($i = $_range[0]; $i <= $_range[1]; $i++) 
                {
                    $choices[$i] = $i;
                }
            }
            

            $this->setType('select');
            $this->setChoices($choices);
        }
    }
}
