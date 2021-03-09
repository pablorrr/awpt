<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Initialize theme default settings
 */
require get_template_directory() . '/inc/theme-settings.php';


/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/inc/bootstrap-wp-navwalker.php';




/**
 * Load Vendor Composer Directory -autoload
 */
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) :
    require_once dirname(__FILE__) . '/vendor/autoload.php';
endif;

/**
 * Init Theme with register services
 */
if (class_exists('Awpt\\Init')) :
    Awpt\Init::register_services();
endif;
