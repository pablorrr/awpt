<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */




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
