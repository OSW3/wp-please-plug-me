<?php

namespace Framework\Components\Form\Form;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Kernel\Config;
use \Framework\Kernel\Session;

if (!class_exists('Framework\Components\Form\Form\Form'))
{
    abstract class Form
    {
        const ATTR_TYPES = ['Accept','Choices','Cols','Disabled','Expanded',
            'Helper','Max','MaxLength','Min','Multiple','Name',
            'Placeholder','Readonly','Required','Rows','Step','Type','Value','Label',
            'Width','Id','Class','Schema','Loop'];

        /**
         * Field Accept
         */
        private $accept;

        /**
         * Field Algo
         */
        private $algo;

        /**
         * Field Attrs
         */
        private $attrs;
        
        /**
         * 
         */ 
        private $choices;
        
        /**
         * 
         */ 
        private $class;
        
        /**
         * 
         */ 
        private $cols;
        
        /**
         * Field config
         */
        private $config;

        /**
         * 
         */ 
        private $disabled;
        
        /**
         * 
         */ 
        private $expanded;
        
        /**
         * 
         */ 
        private $helper;
        
        /**
         * 
         */ 
        private $id;
        
        /**
         * 
         */ 
        private $label;
        
        /**
         * Number of loop on collection init
         */ 
        private $loop;
        
        /**
         * 
         */ 
        private $max;
        
        /**
         * 
         */ 
        private $maxLength;
        
        /**
         * 
         */ 
        private $min;
        
        /**
         * 
         */ 
        private $multiple;
        
        /**
         * 
         */ 
        private $name;
        
        /**
         * 
         */ 
        private $placeholder;
        
        /**
         * 
         */ 
        private $readonly;
        
        /**
         * 
         */ 
        private $required;
        
        /**
         * 
         */ 
        private $rows;
        
        /**
         * 
         */ 
        private $rules;
        
        /**
         * 
         */ 
        private $selected;
        
        /**
         * Schema for collection Type
         */ 
        private $schema;
        
        /**
         * 
         */ 
        private $step;

        /**
         * 
         */
        private $template;

        /**
         * 
         */
        private $template_type;
        
        /**
         * 
         */ 
        private $type;
        
        /**
         * 
         */ 
        private $value;
        
        /**
         * 
         */ 
        private $width;

        /**
         * Constructor
         * 
         * @param array $config
         * @param string $template, the type of field template ('metabox' | 'collection')
         */
        public function __construct(array $config, string $template_type = null)
        {
            $this->template_type = $template_type;

            // define Field Type
            $this->setConfig($config);

            // Call setter methods
            foreach (self::ATTR_TYPES as $type) 
            {
                $method = 'set'.$type;
                $this->$method();
            }

            // Build the field
            $this->builder();
        }

        /**
         * Default Field Builder
         */
        public function builder() {}

        /**
         * Render
         * 
         * Rendering the field
         */
        public function render()
        {

            // print_r( $this->getType() );

            // Init the Output
            $output = '';

            switch ($this->template_type) 
            {
                case 'collection':
                case 'metabox':

                    if ('collection' == $this->getType())
                    {
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
                    }
                    else
                    {
                        $output.= '<tr>';
                        $output.= '<th scope="row">';
                        $output.= $this->tagLabel();
                        $output.= '</th>';
                        $output.= '<td>';
                        $output.= $this->tagTemplate();
                        $output.= $this->tagHelper();
                        $output.= '</td>';
                        $output.= '</tr>';
                    }
                    break;

                // case 'collection':
                //     // $output.= '<div>';
                //     // $output.= $this->tagLabel();
                //     // $output.= $this->tagTemplate();
                //     // $output.= $this->tagHelper();
                //     // $output.= '</div>';

                //     $output.= '<tr>';
                //     $output.= '<th scope="row">';
                //     $output.= $this->tagLabel();
                //     $output.= '</th>';
                //     $output.= '<td>';
                //     $output.= $this->tagTemplate();
                //     $output.= $this->tagHelper();
                //     $output.= '</td>';
                //     $output.= '</tr>';
                //     break;
                
                default:
                    $output.= $this->tagTemplate();
                    break;
            }

            return $output;
        }


        /**
         * ----------------------------------------
         * Tag templates
         * ----------------------------------------
         */

        /**
         * Label
         */
        private function tagLabel()
        {
            $tag = '<label$1>$2$3</label>';

            if (!empty($this->getId())) { 
                $tag = preg_replace("/\\$1/", ' for="'.$this->getId().'"', $tag);
            }

            if (!empty($this->getLabel())) {
                $tag = preg_replace("/\\$2/", $this->getLabel(), $tag);
            }

            if ($this->getRequired()) { 
                $tag = preg_replace("/\\$3/", ' <span>*</span>', $tag);
            }

            $tag = preg_replace("/(:?\\$1|\\$2|\\$3)/", null, $tag);

            return $tag;
        }

        /**
         * File
         */
        private function tagFile()
        {
            $tag = "<table>";
            $tag.=  "<tr>";
            $tag.=      "<td>";
            // $tag.=          '<img src="'..'Framework/Assets/images/default.svg'.'">';
            $tag.=      "</td>";
            $tag.=      "<td>";
            $tag.=          $this->tagInput();
            $tag.=      "</td>";
            $tag.=  "</tr>";
            $tag.= "</table>";

            return $tag;
        }

        /**
         * Collection
         */
        private function tagCollection()
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

                $attr_name = $this->getConfig('post_type').'['.implode("][", explode("-", $schema['key'])).'][{{number}}]';
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

        /**
         * Helper
         */
        private function tagHelper()
        {
            $tag = null;

            if (!empty($this->getHelper()))
            {
                $helper = $this->getHelper();

                if (!is_array($helper)) {
                    $helper = [$helper];
                }

                foreach ($helper as $item) 
                {
                    if ('notice' == $item[0]) {
                        $tag.= '<p class="description ppm-description has-error">' . $item[1] . '</p>';
                    } else {
                        $tag.= '<p class="description ppm-description">' . $item[1] . '</p>';
                    }
                }
            }

            return $tag;
        }
        /**
         * <option>
         */
        private function tagOption()
        {
            return '<option{{attributes}}>'.$this->getLabel().'</option>';
        }
        private function tagOptionChoice( $field )
        {
            $tag = '<div class="choices-option"><label>$1 $2</label></div>';

            if ('checkbox' == $field->getType())
            {
                $field->setName( $field->getName().'['.$field->getValue().']' );
            }

            $tag = preg_replace("/\\$1/", $field->render(), $tag);
            $tag = preg_replace("/\\$2/", $field->getLabel(), $tag);

            return $tag;
        }
        /**
         * Choices Container for not expanded Choices
         * <select>
         */
        private function tagChoicesSelect()
        {
            return '<select{{attributes}}>'.$this->tagChoicesOption().'</select>';
        }
        /**
         * Choices Container for expanded Choices
         * <input type="checkbox">
         * <input type="radio">
         */
        private function tagChoicesInput()
        {
            return '<div class="choices-expanded '.$this->getClass().'">'.$this->tagChoicesOption().'</div>';
        }
        /**
         * Choice item
         */
        private function tagChoicesOption()
        {
            $tag = '';

            foreach ($this->getChoices() as $value => $label) 
            {
                // Tag options
                $options = array_merge($this->config,[
                    "label"     => $label,
                    "value"     => $value,
                    // "selected"  => $this->selected === $value,
                    "choices"   => []
                ]);

                // Tag object (checkbox)
                if ($this->getExpanded() && $this->getMultiple()) {
                    $tag.= $this->tagOptionChoice(new \Framework\Components\Form\Fields\Checkbox($options));
                }

                // Tag object (radio)
                elseif ($this->getExpanded() && !$this->getMultiple()) {
                    $tag.= $this->tagOptionChoice(new \Framework\Components\Form\Fields\Radio($options));
                }

                // Tag object (select)
                else {
                    $field = new \Framework\Components\Form\Fields\Option($options);
                    $tag.= $field->render();
                }
            }

            return $tag;
        }
        /**
         * <input/>
         */
        private function tagInput()
        {
            return '<input{{attributes}} />';
        }
        /**
         * <textarea>
         */
        private function tagTextarea()
        {
            return '<textarea{{attributes}}>'.$this->getValue().'</textarea>';
        }
        /**
         * <output>
         */
        private function tagOutput()
        {
            $for = $this->getValue();

            return '<output name="'.$this->getName().'" for="'.$for.'"></output>';
        }
        /**
         * wp_editor
         */
        private function tagWysiwyg()
        {
            $settings = array(
                'media_buttons' => false,
                // 'quicktags' => array( 
                //     'buttons' => 'strong,em,del,ul,ol,li,close' 
                // ),
                'editor_class' => $this->getClass(),
                'textarea_name' => $this->getId(),
                'textarea_rows' => 8
            );

            ob_start();
            wp_editor( $this->getValue(), $this->getId(), $settings );
            return ob_get_clean();
        }

        private function tagAttributes()
        {
            $attr = '';
            $attr.= $this->getAttrType();
            $attr.= $this->getAttrId();
            $attr.= $this->getAttrName();
            $attr.= $this->getAttrClass();
            $attr.= $this->getAttrPlaceholder();
            $attr.= $this->getAttrRequired();
            $attr.= $this->getAttrReadonly();
            $attr.= $this->getAttrDisabled();
            $attr.= $this->getAttrMaxLength();
            $attr.= $this->getAttrMultiple();
            $attr.= $this->getAttrMin();
            $attr.= $this->getAttrMax();
            $attr.= $this->getAttrStep();
            $attr.= $this->getAttrCols();
            $attr.= $this->getAttrRows();
            $attr.= $this->getAttrWidth();
            $attr.= $this->getAttrValue();
            $attr.= $this->getAttrAccept();

            return $attr;
        }

        /**
         * 
         */
        private function tagTemplate()
        {
            switch ($this->getType())
            {
                case 'collection':
                    $template = $this->tagCollection();
                    break;
                
                case 'select':
                case 'choices_select':
                    $template = $this->tagChoicesSelect();
                    break;

                case 'choices_checkbox':
                case 'choices_radio':
                    $template = $this->tagChoicesInput();
                    break;
                
                case 'option':
                    $template = $this->tagOption();
                    break;
                
                case 'output':
                    $template = $this->tagOutput();
                    break;
                
                case 'textarea':
                    $template = $this->tagTextarea();
                    break;
                
                case 'wysiwyg':
                    $template = $this->tagWysiwyg();
                    break;
                
                case 'file':
                    if ($this->getConfig('preview')) 
                    {
                        $template = $this->tagFile();
                        break;
                    }

                default:
                    $template = $this->tagInput();
            }

            return preg_replace("/{{attributes}}/", $this->tagAttributes(), $template);
        }


        /**
         * ----------------------------------------
         * Retrieve Attribute and config options from config.php
         * ----------------------------------------
         */

        /**
         * Config from config.php
         */
        private function setConfig(array $config)
        {
            $this->config = $config;

            $this->setAttrs();
            $this->setRules();

            return $this;
        }
        protected function getConfig(string $key = '')
        {
            if (isset( $this->config[$key] )) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Attributes from this->getConfig()
         */
        private function setAttrs()
        {
            // Default Attrs
            $this->attrs = array();

            $config = $this->config;

            if (isset($config['attr']) && is_array($config['attr']))
            {
                $this->attrs = $config['attr'];
            }

            return $this;
        }
        private function getAttr(string $key = '')
        {
            if (isset( $this->attrs[$key] )) 
            {
                return $this->attrs[$key];
            }

            return null;
        }

        /**
         * Rules from this->getConfig()
         */
        private function setRules()
        {
            // Default Attrs
            $this->rules = array();

            $config = $this->config;

            if (isset($config['rules']) && is_array($config['rules']))
            {
                $this->rules = $config['rules'];
            }

            return $this;
        }
        private function getRule(string $key = '')
        {
            if (isset( $this->rules[$key] )) 
            {
                return $this->rules[$key];
            }

            return null;
        }


        /**
         * ----------------------------------------
         * Options and Attribute Getters / Setters
         * ----------------------------------------
         */

        /**
         * Accept
         */
        protected function setAccept()
        {
            // Default class
            $this->accept = null;
            
            // Retrive Class parameters
            $accept = $this->getRule('allowed_types');

            if (is_array($accept))
            {
                $this->accept = implode(",", $accept);
            }
            else
            {
                $this->accept = $accept;
            }

            return $this;
        }
        protected function getAccept()
        {
            return $this->accept;
        }
        protected function getAttrAccept()
        {
            if ('file' == $this->getType() && null != $this->getAccept())
            {
                return ' accept="'.$this->getAccept().'"';
            }

            return null;
        }

        /**
         * Class
         */
        protected function setClass()
        {
            // Default class
            $this->class = 'ppm-control';
            
            if (
                is_admin() && 
                ($this->template_type == 'metabox' || $this->template_type == 'collection')&&
                !in_array($this->getType(), ['color'])
            ){
                $this->class.= ' regular-text';
            }

            // Retrieve value from session (after submission)
            $session = new Session($this->getConfig('namespace'));
            foreach ($session->errors($this->getConfig('post_type')) as $error) 
            {
                if ($error['key'] == $this->getConfig('key')) 
                {
                    $this->class.= ' has-error';
                }
            }

            // Retrive Class parameters
            $class = $this->getAttr('class');

            if (is_string($class))
            {
                $this->class.= ' '.$class;
            }

            return $this;
        }
        protected function getClass()
        {
            return $this->class;
        }
        protected function getAttrClass()
        {
            if (null != $this->getClass() && !in_array($this->getType(), ['option']))
            {
                return ' class="'.$this->getClass().'"';
            }

            return null;
        }

        /**
         * Choices
         */
        protected function setChoices(array $choices=[])
        {
            // Default choices
            $this->choices = $choices;

            if (!in_array($this->getType(), ['option']))
            {
                if ($this->getConfig('choices'))
                {
                    $this->choices = $this->getConfig('choices');
                }
            }

            return $this;
        }
        protected function getChoices()
        {
            return $this->choices;
        }

        /**
         * Cols
         */
        protected function setCols()
        {
            // Default cols
            $this->cols = null;

            // Retrive cols parameters
            $cols = $this->getAttr('cols');

            if (is_int($cols))
            {
                $this->cols = $cols;
            }

            return $this;
        }
        protected function getCols()
        {
            return $this->cols;
        }
        protected function getAttrCols()
        {
            return $this->getCols() ? ' cols="'.$this->getCols().'"' : null;
        }

        /**
         * Disabled
         */
        protected function setDisabled()
        {
            // Default readonly
            $this->disabled = false;

            // Retrive Disabled parameters
            $disabled = $this->getAttr('disabled');

            if (is_bool($disabled))
            {
                $this->disabled = $disabled;
            }

            return $this;
        }
        protected function getDisabled()
        {
            return $this->disabled;
        }
        protected function getAttrDisabled()
        {
            return $this->getDisabled() ? ' disabled="disabled"' : null;
        }

        /**
         * Expanded
         */
        protected function setExpanded()
        {
            // Default expanded
            $this->expanded = false;

            // Retrive Readonly parameters
            $expanded = $this->getConfig('expanded');

            if (is_bool($expanded))
            {
                $this->expanded = $expanded;
            }

            return $this;
        }
        protected function getExpanded()
        {
            return $this->expanded;
        }

        /**
         * Set Helper
         */
        protected function setHelper()
        {
            // Default helper
            $this->helper = [];

            // Retrieve value from session (after submission)
            $session = new Session($this->getConfig('namespace'));
            foreach ($session->errors($this->getConfig('post_type')) as $error) 
            {
                if ($error['key'] == $this->getConfig('key')) 
                {
                    array_push($this->helper, ["notice", $error['message']]);
                }
            }

            if ($this->getConfig('helper'))
            {
                array_push($this->helper, ["normal", $this->getConfig('helper')]);
            }

            return $this;
        }
        protected function getHelper()
        {
            return $this->helper;
        }

        /**
         * ID
         */
        protected function setId()
        {
            $id = $this->getAttr('id');

            if (null != $id) 
            {
                $this->id = $id;
            }
            else
            {
                $this->id = $this->getConfig('key');
            }


            if ('collection' == $this->template_type)
            {
                $this->id.= '-{{number}}';
            }


            if ('wysiwyg' == $this->getType())
            {
                $this->id = preg_replace("/\\[|\\]/", "____", $this->getName());
            }

            return $this;
        }
        protected function getId()
        {
            return $this->id;
        }
        protected function getAttrId()
        {
            if (!in_array($this->getType(), ['option']) && $this->getId())
            {
                return ' id="'. $this->getId() .'"';
            }

            return null;
        }

        /**
         * Label
         */
        protected function setLabel()
        {
            // Default label
            $this->label = '';

            if ($this->getConfig('label'))
            {
                $this->label = $this->getConfig('label');
            }

            // if ($this->getType() == 'collection')
            // {
            //     $this->label = "Item {{number}}";

            //     if (is_string($this->getRule('label')))
            //     {
            //         $this->label = $this->getRule('label');
            //     }
            // }

            return $this;
        }
        protected function getLabel()
        {
            return $this->label;
        }

        /**
         * Loop
         */
        protected function setLoop()
        {
            // default loop value
            $this->loop = 1;

            // $loop = $this->getConfig('loop');
            $loop = $this->getRule('init');

            if (is_int($loop) && $loop >= 0 ) 
            {
                $this->loop = $loop;
            }

            return $this;
        }
        protected function getLoop()
        {
            return $this->loop;
        }

        /**
         * Max 
         */
        protected function setMax()
        {
            // Default max
            $this->max = null;

            // Retrive Max parameters
            $max = $this->getAttr('max');

            if (is_int($max))
            {
                $this->max = $max;
            }

            return $this;
        }
        protected function getMax()
        {
            return $this->max;
        }
        protected function getAttrMax()
        {
            return $this->getMax() ? ' max="'.$this->getMax().'"' : null;
        }

        /**
         * Max Length
         */
        protected function setMaxLength()
        {
            // Default maxLength
            $this->maxLength = null;

            // Retrive Max parameters
            $maxLength = $this->getAttr('maxlength');

            if (is_int($maxLength))
            {
                $this->maxLength = $maxLength;
            }

            return $this;
        }
        protected function getMaxLength()
        {
            return $this->maxLength;
        }
        protected function getAttrMaxLength()
        {
            return $this->getMaxLength() ? ' maxlength="'.$this->getMaxLength().'"' : null;
        }

        /**
         * Set Attribute Min
         */
        protected function setMin()
        {
            // Default min
            $this->min = null;

            // Retrive Min parameters
            $min = $this->getAttr('min');

            if (is_int($min))
            {
                $this->min = $min;
            }

            return $this;
        }
        protected function getMin()
        {
            return $this->min;
        }
        protected function getAttrMin()
        {
            return $this->getMin() ? ' min="'.$this->getMin().'"' : null;
        }

        /**
         * Multiple
         */
        protected function setMultiple()
        {
            // Default multiple
            $this->multiple = false;

            // Retrive Readonly parameters
            $multiple = $this->getAttr('multiple');

            if (is_bool($multiple))
            {
                $this->multiple = $multiple;
            }

            return $this;
        }
        protected function getMultiple()
        {
            return $this->multiple;
        }
        protected function getAttrMultiple()
        {
            if (in_array($this->getType(), ['file','select']) && $this->getMultiple()) 
            {
                return ' multiple="multiple"';
            }

            return null;
        }

        /**
         * Name
         */
        protected function setName(string $name = '')
        {
            if (empty($name)) 
            {
                $name = $this->getConfig('post_type');
                $name.= '['.$this->getConfig('key').']';
            }

            $this->name = $name;
            
            return $this;
        }
        protected function getName()
        {
            return $this->name;
        }
        protected function getAttrName()
        {
            if (!in_array($this->getType(), ['option'])) 
            {
                return ' name="'.$this->getName().'"';
            }

            return null;
        }

        /**
         * Placeholder
         */
        protected function setPlaceholder()
        {
            // Default placeholder
            $this->placeholder = null;

            // Retrive placeholder parameters
            $placeholder = $this->getAttr('placeholder');

            if (is_string($placeholder))
            {
                $this->placeholder = $placeholder;
            }

            return $this;
        }
        protected function getPlaceholder()
        {
            return $this->placeholder;
        }
        protected function getAttrPlaceholder()
        {
            if (null != $this->getPlaceholder())
            {
                return ' placeholder="'.$this->getPlaceholder().'"';
            }

            return null;
        }

        /**
         * Readonly
         */
        protected function setReadonly()
        {
            // Default readonly
            $this->readonly = false;

            // Retrive Readonly parameters
            $readonly = $this->getAttr('readonly');

            if (is_bool($readonly))
            {
                $this->readonly = $readonly;
            }

            return $this;
        }
        protected function getReadonly()
        {
            return $this->readonly;
        }
        protected function getAttrReadonly()
        {
            return $this->getReadonly() ? ' readonly="readonly"' : null;
        }

        /**
         * Required
         */
        protected function setRequired()
        {
            // Default required
            $this->required = false;

            // Retrive Required parameters
            $required = $this->getAttr('required');

            if (is_bool($required))
            {
                $this->required = $required;
            }

            return $this;
        }
        protected function getRequired()
        {
            return $this->required;
        }
        protected function getAttrRequired()
        {
            return $this->getRequired() ? ' required="required"' : null;
        }

        /**
         * Rows
         */
        protected function setRows()
        {
            // Default Rows
            $this->rows = null;

            // Retrive rows parameters
            $rows = $this->getAttr('rows');

            if (is_int($rows))
            {
                $this->rows = $rows;
            }

            return $this;
        }
        protected function getRows()
        {
            return $this->rows;
        }
        protected function getAttrRows()
        {
            return $this->getRows() ? ' rows="'.$this->getRows().'"' : null;
        }

        /**
         * Schema
         * 
         * Define schema for Collection Type
         */
        protected function setSchema()
        {
            // Default Schema
            $this->schema = [];

            // Retrive Schema parameters
            $schema = $this->getConfig('schema');

            if (is_string($schema) || is_array($schema))
            {
                if (is_string($schema))
                {
                    $schema = [$schema];
                }

                $this->schema = $schema;
            }

            return $this;
        }
        protected function getSchema()
        {
            return $this->schema;
        }

        /**
         * Step
         */
        protected function setStep()
        {
            // Default Step
            $this->step = null;

            // Retrive Step parameters
            $step = $this->getAttr('step');

            if (is_int($step) || is_float($step))
            {
                $this->step = $step;
            }

            return $this;
        }
        protected function getStep()
        {
            return $this->step;
        }
        protected function getAttrStep()
        {
            return $this->getStep() ? ' step="'.$this->getStep().'"' : null;
        }

        /**
         * Type
         */
        protected function setType($type = null)
        {
            if (null === $type)
            {
                $called_class = get_called_class();
                $called_class = str_replace("\\", "/", $called_class);
                $called_class = basename($called_class);
                $called_class = strtolower($called_class);

                $type = $called_class;
            }

            if ($type == 'choices')
            {
                if ($this->getExpanded() && $this->getMultiple()) {
                    $type = "choices_checkbox";
                }
                elseif ($this->getExpanded() && !$this->getMultiple()) {
                    $type = "choices_radio";
                }
                else {
                    $type = "choices_select";
                }
            }

            $this->type = $type;

            return $this;
        }
        protected function getType()
        {
            return $this->type;
        }
        protected function getAttrType()
        {
            if (!in_array($this->getType(), ['option','select','textarea']))
            {            
                return ' type="'. $this->getType() .'"';
            }
        }

        /**
         * Value
         */
        protected function setValue()
        {
            // Default Value
            $this->value = null;

            $hide_pwd_value = false;
            
            // Retrieve value from session (after submission)
            $session = new Session($this->getConfig('namespace'));
            foreach ($session->responses($this->getConfig('post_type')) as $key => $value) 
            {
                if ($key == $this->getConfig('key')) 
                {
                    $this->value = $value;
                }
            }


            // Retrieve an existant value
            if (null == $this->value && !empty(get_post()))
            {
                $hide_pwd_value = true;
                $this->value = get_post_meta(
                    get_post()->ID, 
                    $this->getConfig('key'), 
                    true
                );
            }
            

            // Set default value
            if (null == $this->value) 
            {
                // Retrive Default parameters
                if ($this->getConfig('default'))
                {
                    $this->value = $this->getConfig('default');
                }
    
                // Override default value
                if ($this->getConfig('value'))
                {
                    $this->value = $this->getConfig('value');
                }
            }

            switch ($this->getType()) 
            {
                case 'date':
                    if ('today' == $this->value) {
                        $this->value = date('Y-m-d');
                    }
                    break;

                case 'time':
                    if ('now' == $this->value) {
                        $this->value = date('H:i');
                    }
                    break;

                case 'password':
                    if ($hide_pwd_value)
                    {
                        $this->value = '';
                    }
                    break;
            }
            return $this;
        }
        protected function getValue()
        {
            return $this->value;
        }
        protected function getAttrValue()
        {
            if ($this->getValue() && !in_array($this->getType(), ['select']))
            {
                if ('checkbox' == $this->getType() && 'on' === strtolower($this->getValue()))
                {
                    return ' checked="checked"';
                } 
                elseif ('option' == $this->getType() && $this->getValue() == $this->getConfig('default'))
                {
                    return ' selected="selected"';
                } 
                elseif ('collection' == $this->getType())
                {
                    // TODO: Value of collection 
                    // return ' selected="selected"';
                } 
                else 
                {
                    return ' value="'.$this->getValue().'"';
                }
            }

            return null;
        }

        /**
         * Width
         */
        protected function setWidth()
        {
            // Default Width
            $this->width = null;

            // Retrive rows parameters
            $width = $this->getAttr('width');

            if (is_int($width))
            {
                $this->width = $width;
            }

            return $this;
        }
        protected function getWidth()
        {
            return $this->width;
        }
        protected function getAttrWidth()
        {
            return $this->getWidth() ? ' width="'.$this->getWidth().'"' : null;
        }
    }
}
