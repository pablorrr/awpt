<?php

namespace Awpt\Setup;

class Setup
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('after_setup_theme', array($this, 'content_width'), 0);
    }

    public function setup()
    {
        /*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on understrap, use a find and replace
		 * to change 'understrap' to the name of your theme in all the template files
		 */
        load_theme_textdomain('understrap', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Adding Thumbnail basic support
         */
        add_theme_support('post-thumbnails');

        /*
         * Adding support for Widget edit icons in customizer
         */
        add_theme_support('customize-selective-refresh-widgets');

        /*
         * Enable support for Post Formats.
         * See http://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('understrap_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
//TODO : CHECK CUSTOM LOGO FUNCTIONALITY IN CUSTOMIZER AREA
        // Set up the WordPress Theme logo feature.
        add_theme_support('custom-logo');

    }

    /*
        Define a max content width to allow WordPress to properly resize your images
    */
    public function content_width()
    {
        // Set the content width based on the theme's design and stylesheet.
        if (!isset($content_width)) {
            $GLOBALS['content_width'] = apply_filters('content_width', 1440);
        }
    }
}
