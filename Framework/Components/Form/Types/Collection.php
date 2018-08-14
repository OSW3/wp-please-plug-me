<?php

namespace Framework\Components\Form\Types;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Form\Form;

if (!class_exists('Framework\Components\Form\Types\Collection'))
{
    class Collection extends Form 
    {
        /**
         * Tag Template
         */
        protected function tag()
        {
            
            $tag = "\n\n\n\n\n\n\n\n\n\n\n\n";

            // echo "<pre>";
            // print_r($this->config);
            // echo "</pre>";
            
            $tag.= '<div id="'.$this->getId().'" class="ppm-collection-container" data-collection="'.$this->getId().'" data-type="container" data-loop="'.$this->getLoop().'"></div>';
            
            $tag.= '<div class="ppm-collection-control">';
            $tag.= '<button type="button" class="button button-secondary button-large" data-collection="'.$this->getId().'" data-type="control" data-action="add">Add</button>';
            $tag.= '</div>';
            
            $tag.= '<script type="text/html" data-collection="'.$this->getId().'" data-type="prototype">';
            $tag.= '<div id="ppm-collection-item-{{number}}" class="ppm-collection-item">';

            $tag.= '<div class="ppm-collection-item-header">';
            $tag.= '<div class="ppm-collection-item-actions hidden">';
            $tag.= '<button type="button" class="button button-link button-small dashicons-before dashicons-dismiss" data-collection="'.$this->getId().'" data-type="control" data-action="delete"></button>';
            $tag.= '</div>';
            $tag.= '<h4>'.$this->getLabel().'</h4>';
            $tag.= '</div>';

            $tag.= '<table class="form-table ppm-collection">';
            $tag.= '<tbody>';
            foreach ($this->getSchema() as $schema) 
            {
                $schema['post_type'] = $this->getConfig('post_type');
                $schema['namespace'] = $this->getConfig('namespace');
                $schema['collection'] = $this->getId();

                $fieldClass = ucfirst(strtolower($schema['type']));
                $fieldClass = "\\Framework\\Components\\Form\\Fields\\".$fieldClass;
                $field = new $fieldClass($schema, 'collection');
                
                
                
                // $attr_name = $this->getConfig('post_type').'['.$this->getId().']['.$schema['key'].'][{{number}}]';

                // $attr_name = $this->getConfig('post_type').'['.implode("][", explode("-", $schema['key'])).'][{{number}}]';
                $attr_name = $this->getConfig('post_type').'[{{number}}]['.implode("][", explode("-", $schema['key'])).']';
                $field->setName($attr_name);




                // $tag.= $field->getAttrId();
                // $tag.= '<br>';

                // $tag.= $field->getAttrName();
                // $tag.= '<br>';

                // $tag.= $field->getAttrClass();
                // $tag.= '<br>';
                
                $tag.= $field->render();
                // $tag.= '<br>';
            }
            $tag.= '</tbody>';
            $tag.= '</table>';
            $tag.= '</div>';
            $tag.= '</script>';

            $tag.= "\n\n\n\n\n\n\n\n\n\n\n\n";
            
            return $tag;
        }
    }
}
