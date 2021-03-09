<?php

namespace Awpt\Setup;

/**
 * Enqueue.
 */
class Enqueue
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }


    public function enqueue_scripts()
    {
        // Get the theme data.
        $the_theme = wp_get_theme();

        wp_enqueue_style('understrap-styles', get_stylesheet_directory_uri() . '/css/theme.min.css', array(), $the_theme->get('Version'), false);
        wp_enqueue_script('jquery');
        wp_enqueue_script('popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), true);
        wp_enqueue_script('understrap-scripts', get_template_directory_uri() . '/js/theme.min.js', array(), $the_theme->get('Version'), true);
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
