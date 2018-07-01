<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


// ****************************** //
// PPM Plugin : Utils function
// ****************************** //

/**
 * Get all items
 * --
 * @todo 1 : change the name of the function "PPM_GetAllItems"
 * @todo 2 : change the value of the $post_type "wpppm_custom_post"
 * 
 * @param bool $pagination Default true, If false WP_Query return all results without limit
 */

if (!function_exists('PPM_GetAllItems'))                                        // @todo 1
{
    function PPM_GetAllItems(bool $pagination = true)                           // @todo 1
    {
        // Post type
        $post_type = 'wpppm_custom_post';                                       // @todo 2

        // Have pagination ?
        $pagination = isset($pagination) ? $pagination : true;

        // Posts per page
        $per_page = $pagination ? get_option('posts_per_page') : -1;

        // Current page
        $paged = get_query_var('paged');
        $paged = $paged ? $paged : 1;

        // Query
        $query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => $per_page,
            'paged' => $paged,
        ]);

        // Results
        $results = [];
        if (isset($query->posts) && !empty($query->posts)) 
        {
            foreach ($query->posts as $key => $post) 
            {
                setup_postdata($post); 

                $id      = $post->ID;
                $title   = $post->post_title;
                $excerpt = get_the_excerpt();
                $content = $post->post_content;
                $date    = $post->post_date;
                $link    = get_page_url_by_slug($post->post_name, $post_type);
                $guid    = $post->guid;
                $images  = get_media_embedded_in_content($content, 'image');

                foreach ($images as $key => $image) 
                {
                    // Image ID
                    preg_match("/wp-image-(\d*)/", $image, $match);
                    $image_id = isset($match[1]) ? $match[1] : null;

                    // Image Width
                    preg_match("/width=\"(\d*)\"/", $image, $match);
                    $image_width = isset($match[1]) ? $match[1] : null;

                    // Image Height
                    preg_match("/height=\"(\d*)\"/", $image, $match);
                    $image_height = isset($match[1]) ? $match[1] : null;

                    // Image Url
                    preg_match("/src=\"(.*\.(?:jpg|gif|png))\"/s", $image, $match);
                    $image_src = isset($match[1]) ? $match[1] : null;

                    $images[$key] = (object)[
                        "id"    => $image_id,
                        "width" => $image_width,
                        "height"=> $image_height,
                        "src"   => $image_src,
                    ];
                }

                $result = (object) [
                    'ID' => $id,
                    'ID'      => $id,
                    'title'   => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'date'    => $date,
                    'link'    => $link,
                    'guid'    => $guid,
                    'images'  => $images
                ];

                array_push($results, $result);
            }
            
            wp_reset_postdata();
        }

        return (object) $results;
    }
}

/**
 * Get last item
 * --
 * @todo 1 : change the name of the function "PPM_GetAllItems"
 * @todo 2 : change the value of the $post_type "wpppm_custom_post"
 */

if (!function_exists('PPM_GetLastItem'))                                        // @todo 1
{
    function PPM_GetLastItem()                                                  // @todo 1
    {
        // Post type
        $post_type = 'wpppm_custom_post';                                       // @todo 2

        // Query
        $query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => 1 
        ]);

        // Result
        $result = [];
        if (isset($query->posts) && !empty($query->posts)) 
        {
            foreach ($query->posts as $post) 
            {
                setup_postdata( $post );

                $id = $post->ID;
                $title = $post->post_title;
                $date = $post->post_date;
                $excerpt = get_the_excerpt();
                $content = $post->post_content;
                $link = get_page_url_by_slug($post->post_name, $post_type);
                $guid = $post->guid;
                $images = get_media_embedded_in_content($content, 'image');

                foreach ($images as $key => $image) 
                {
                    // Image ID
                    preg_match("/wp-image-(\d*)/", $image, $match);
                    $image_id = isset($match[1]) ? $match[1] : null;

                    // Image Width
                    preg_match("/width=\"(\d*)\"/", $image, $match);
                    $image_width = isset($match[1]) ? $match[1] : null;

                    // Image Height
                    preg_match("/height=\"(\d*)\"/", $image, $match);
                    $image_height = isset($match[1]) ? $match[1] : null;

                    // Image Url
                    preg_match("/src=\"(.*\.(?:jpg|gif|png))\"/s", $image, $match);
                    $image_src = isset($match[1]) ? $match[1] : null;

                    $images[$key] = (object)[
                        "id" => $image_id,
                        "width" => $image_width,
                        "height" => $image_height,
                        "src" => $image_src,
                    ];
                }

                $result['ID'] = $id;
                $result['date'] = $date;
                $result['title'] = $title;
                $result['content'] = $content;
                $result['excerpt'] = $excerpt;
                $result['link'] = $link;
                $result['guid'] = $guid;
                $result['images'] = $images;

            }

            wp_reset_postdata();
        }

        return (object) $result;
    }
}


/**
 * Get a plugin options
 * --
 * @todo 1 : change the name of the function "PPM_Plugin_getOption"
 * @todo 2 : change the value of the variable $plugin "ppm_plugin_name"
 * 
 * @param string $option The name of the option you need to retrieve
 */
if (!function_exists('PPM_Plugin_getOption'))                                   // @todo 1
{
    function PPM_Plugin_getOption( string $option )                             // @todo 1
    {
        $plugin = "ppm_plugin_name";                                            // @todo 2
        return PPM_GetOption($plugin, $option);
    }
}


// ****************************** //
// PPM Plugin : Do function
// ****************************** //


if (!function_exists('my_custom_post'))
{
    function my_custom_post()
    {
        return "my-custom-post-slug";
    }
}


// ****************************** //
// PPM Plugin : Register MetaBox Callback Exemple
// ****************************** //

/**
 * Register MetaBox Callback Exemple
 * --
 * This is an exemple of the Metabox Callback function.
 * https://developer.wordpress.org/reference/functions/register_post_type/
 * http://wp-learner.com/wotdpress-development/adding-metaboxes-to-post-types/
 */
if (!function_exists('WP_PleasePlugMe_registerMetaBoxCallback'))
{
    function WP_PleasePlugMe_registerMetaBoxCallback( $post )
    {
        // var_dump( $post );
        // exit;
    }
} 