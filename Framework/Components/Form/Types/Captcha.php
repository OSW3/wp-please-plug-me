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

if (!class_exists('Framework\Components\Form\Types\Captcha'))
{
    class Captcha extends Types 
    {
        private $captchaType;
        private $captchaKey;
        private $captchaSecret;

        /**
         * Tag Attributes
         */
        public function attributes()
        {
            return [];
        }

        /**
         * Tag Template
         */
        public function tag()
        {
            switch ($this->getCaptchaType())
            {
                case 'recaptcha':
                    return $this->tag_reCaptcha();
            }
        }

        /**
         * tag for Google reCaptcha
         */
        public function tag_reCaptcha()
        {
            // Google Script Injection
            if (is_admin())
            {
                add_action('admin_head', function(){ echo "<script src='https://www.google.com/recaptcha/api.js'></script>"; });
            }
            else
            {
                wp_enqueue_script('g-recaptcha','https://www.google.com/recaptcha/api.js');
            }
            
            // Set tag
            return '<div class="g-recaptcha" data-sitekey="'.$this->getCaptchaKey().'"></div>';
        }

        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setCaptchaType();
            $this->setCaptchaKey();
            $this->setCaptchaSecret();
        }


        /**
         * ----------------------------------------
         * Options and Attribute Getters / Setters
         * ----------------------------------------
         */

        /**
         * ReCaptche Type
         */
        private function setCaptchaType()
        {
            $this->captchaType = $this->getRule('type');

            return $this;
        }
        private function getCaptchaType()
        {
            return $this->captchaType;
        }

        /**
         * ReCaptche Key
         */
        private function setCaptchaKey()
        {
            $this->captchaKey = $this->getRule('key');

            return $this;
        }
        private function getCaptchaKey()
        {
            return $this->captchaKey;
        }

        /**
         * ReCaptche Secret
         */
        private function setCaptchaSecret()
        {
            $this->captchaSecret = $this->getRule('secret');

            return $this;
        }
        private function getCaptchaSecret()
        {
            return $this->captchaSecret;
        }
    }
}