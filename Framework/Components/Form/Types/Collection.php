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
         * List of collection items (on load)
         */
        private $items = [];

        /**
         * Tag Attributes
         */
        public function attributes()
        {
            // return ['id', 'name'];
            return ['id', 'name', 'class'];
        }

        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setType('collection');
            $this->setSchema();
            $this->setLoop();
            $this->setItems();
        }

        private function setItems()
        {
            // Retrieve items on responses of session
            $session = $this->session->responses($this->getConfig('post_type'));
            if (isset($session[$this->getConfig('key')]))
            {
                $types = $session[$this->getConfig('key')];

                foreach ($types as $type => $values) 
                {
                    foreach ($values as $key => $value) 
                    {
                        if (!isset($this->items[$key]))
                        {
                            $this->items[$key] = array();
                        }
                        
                        $this->items[$key] = array_merge($this->items[$key], [$type => $value]);
                    }
                }
            }

            if (!empty($this->items))
            {
                $this->setLoop(0);
            }
            
            return $this;
        }
        private function getItems()
        {
            return $this->items;
        }

        /**
         * Tag Template
         */
        public function tag()
        {
            $tag = '';
            $tag.= $this->the_container();
            $tag.= $this->the_add_button();
            $tag.= '<script type="text/html" data-collection="'.$this->getId().'" data-type="prototype">';
            // $tag.= $this->the_item();
            $tag.= '<div id="ppm-collection-item-{{number}}" class="ppm-collection-item">';
            $tag.= $this->the_item_header();
            $tag.= '<table class="form-table ppm-collection">';
            $tag.= '<tbody>';
            $tag.= $this->the_item_body();
            $tag.= '</tbody>';
            $tag.= '</table>';
            $tag.= '</div>';
            $tag.= '</script>';

            return $tag;
        }

        /**
         * Template of Collection container
         */
        public function the_container()
        {
            // Have items on load (after response or retireve post by Id)
            // $items = [];

            $tag = '<div id="'.$this->getId().'" class="ppm-collection-container" data-collection="'.$this->getId().'" data-type="container" data-loop="'.$this->getLoop().'">';


            // echo "<pre>";
            // print_r($this->getItems());
            // echo "</pre>";

            foreach ($this->getItems() as $key => $item) 
            {
                $item_template = $this->the_item( $item );
                $tag .= preg_replace("/{{number}}/", $key, $item_template);
            }


            // $tag .= $this->getConfig('key');



            $tag.= '</div>';
            return $tag;
        }

        /**
         * Template of Add Button
         */
        public function the_add_button()
        {
            $tag = '<div class="ppm-collection-control">';
            $tag.= '<button type="button" class="button button-secondary button-large" data-collection="'.$this->getId().'" data-type="control" data-action="add">Add</button>';
            $tag.= '</div>';

            return $tag;
        }

        /**
         * Template of Item 
         */
        public function the_item($item)
        {
            $tag= '<div id="ppm-collection-item-{{number}}" class="ppm-collection-item">';
            $tag.= $this->the_item_header();
            $tag.= '<table class="form-table ppm-collection">';
            $tag.= '<tbody>';
            $tag.= $this->the_item_body($item);
            $tag.= '</tbody>';
            $tag.= '</table>';
            $tag.= '</div>';
            
            return $tag;
        }

        /**
         * Template of Item Header
         */
        public function the_item_header()
        {
            $tag = '<div class="ppm-collection-item-header">';
            $tag.= '<div class="ppm-collection-item-actions hidden">';
            $tag.= '<button type="button" class="button button-link button-small dashicons-before dashicons-dismiss" data-collection="'.$this->getId().'" data-type="control" data-action="delete"></button>';
            $tag.= '</div>';
            $tag.= '<h4>'.$this->getLabel().'</h4>';
            $tag.= '</div>';
            
            return $tag;
        }

        /**
         * Template of Item Body
         */
        public function the_item_body(array $item = [])
        {
            $tag = '';

            foreach ($this->getSchema() as $schema) 
            {
                $schema['post_type'] = $this->getConfig('post_type');
                $schema['namespace'] = $this->getConfig('namespace');
                $schema['collection'] = $this->getId();

                $type_name = $this->getName() .'['.$schema['key'].'][{{number}}]';

                $type_class = ucfirst(strtolower($schema['type']));
                $type_class = "\\Framework\\Components\\Form\\Types\\".$type_class;
                $type = new $type_class($schema, 'collection');
                $type->setName($type_name);
                
                if (!empty($item) && isset( $item[$schema['key']] ))
                {
                    $type->setValue($item[$schema['key']]);
                }

                $tag.= $type->render();
            }
            
            return $tag;
        }

        /**
         * Temple of the collection container
         */
        public function render()
        {
            $output = '';
            
            if (null != $this->tagHelper())
            {
                $output.= '<tr>';
                $output.= '<td class="ppm-collection-row ppm-collection-row-header">';
                // $output.= $this->tagLabel();
                $output.= $this->tagHelper();
                $output.= '</td>';
                $output.= '</tr>';
            }
            
            $output.= '<tr>';
            $output.= '<td class="ppm-collection-row">';
            $output.= $this->tagTemplate();
            $output.= '</td>';
            $output.= '</tr>';

            return $output;
        }
    }
}