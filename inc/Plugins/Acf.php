<?php
/**
 * ACF PRO
 *
 * @link https://github.com/elliotcondon/acf
 *
 * @package awps
 */

namespace Awpt\Plugins;

class Acf
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        if (!class_exists('ACF')) {
            return;
        }
        add_filter('acf/fields/google_map/api', array(&$this, '_acf_google_map_api'));

        add_action('acf/input/admin_enqueue_scripts', array(&$this, '_acf_admin_enqueue_scripts'));
    }


//source:https://www.advancedcustomfields.com/resources/acf-input-admin_enqueue_scripts/
    public function _acf_admin_enqueue_scripts()
    {

        $file_css = get_template_directory() . '/css/my-acf-input.css';
        $file_js = get_template_directory() . '/js/my-acf-input.js';

        if (file_exists($file_css)) {
            $file_css = get_stylesheet_directory_uri() . '/css/my-acf-input.css';
            wp_enqueue_style('my-acf-input-css', $file_css, false, '1.0.0');
        }

        if (file_exists($file_js)) {
            $file_js = get_stylesheet_directory_uri() . '/js/my-acf-input.js';
            wp_enqueue_script('my-acf-input-js', $file_js, false, '1.0.0');
        }

    }

//source: https://www.advancedcustomfields.com/resources/acf-fields-google_map-api/
    public function _acf_google_map_api($args)
    {
        $args['key'] = 'xxx';
        return $args;
    }


}
