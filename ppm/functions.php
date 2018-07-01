<?php

/**
 * Get a plugin options
 * @param string $plugin The name of the plugin
 * @param string $option The name of the option you need to retrieve
 */
if (!function_exists('PPM_GetOption')) 
{
    function PPM_GetOption(string $plugin, string $option)
    {
        $options = get_option($plugin);

        return isset($options[$option])
            ? $options[$option] 
            : null;
    }
}