<?php 
/**
 * PPM_Exemple_FormSubmit
 * 
 * @trigger: "template_redirect"
 * 
 * @declaration (/Plugin/Config/config.php)
 * 
 * $config = [
 *      "hooks" => [
 *          "PPM_Exemple_FormSubmit" => "template_redirect"
 *      ]
 * ];
 */

if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}

if (!function_exists('PPM_Exemple_FormSubmit')) 
{
    function PPM_Exemple_FormSubmit() 
    {
        // Identify the custom PostType
        $_NAMESPACE = "please_plug_me";
        $_POSTTYPE = "ppm_custom_post";

        $ppm = new PPM($_NAMESPACE, $_POSTTYPE);

        // Make sure we execute this hook on Non Admin side && the request 
        // method is Post
        if (!is_admin() && 'POST' === $_SERVER['REQUEST_METHOD'])
        {
            // Retrieve the Custom Post configuration by a ShortCode
            // $post = do_shortcode('[please_plug_me:ppm_custom_post:_config]');
            $posts = shortcode($_NAMESPACE.':_posts');
            $posts = json_decode($posts, true);
            
            // Retrieve Responses
            $responses = $ppm->responses( $posts );

            // Response validation
            $isValid = $ppm->validate();

            // Save data
            if ($isValid)
            {
                // Post Title
                $post_title = $responses['demo_text'];

                // Post content
                $post_content = '';

                // Post Type
                $post_type = $_POSTTYPE;

                // Post ID
                $post_id = wp_insert_post([
                    'post_title'    => $post_title,
                    'post_content'  => $post_content,
                    'post_type'     => $post_type,
                    'post_status'   => 'private',
                    'comment_status'=> 'closed',
                    'ping_status'   => 'closed', 
                ]); 

                // Save Post Meta
                foreach ($responses as $key => $response) 
                {
                    update_post_meta($post_id, $key, $response);
                }

                $ppm->clearSession(true);
            }

            // User Redirection
            $redirect = isset($_POST['_wp_http_referer']) ? $_POST['_wp_http_referer'] : "/";

            if (wp_redirect($redirect)) 
            {
                exit;
            }
        }

        $ppm->clearSession();
    }
}