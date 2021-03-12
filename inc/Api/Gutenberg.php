<?php
/**
 * Build Gutenberg Blocks
 *
 * @package awps
 */

namespace Awpt\Api;

use Awpt\Api\Callbacks\GutenbergCallback;

/**
 * Customizer class
 */
class Gutenberg
{
    /**
     * Register default hooks and actions for WordPress
     *
     * @return WordPress add_action()
     */

    private  $gutenbergCallback;

    public function __construct()
    {
        $this->gutenbergCallback = new GutenbergCallback();
    }

    public function register()
    {
        if (!function_exists('register_block_type')) {
            return;
        }

        add_action('init', array($this, 'gutenberg_enqueue'));

    }


    /**
     * Enqueue scripts and styles of your Gutenberg blocks
     * @return
     */


    public function gutenberg_enqueue()
    {

        $file_js = get_template_directory() . '/inc/api/public/js/block.js';

        if (file_exists($file_js)) {
            $file_js = get_stylesheet_directory_uri() . '/inc/api/public/js/block.js';
            wp_register_script(
                'gutenberg-examples-dynamic', $file_js,
                ['wp-blocks', 'wp-element', 'wp-editor', 'wp-data']

            );
        }

        register_block_type('gutenberg-examples/example-dynamic', array(
            'editor_script' => 'gutenberg-examples-dynamic',
            'render_callback' => array( $this->gutenbergCallback, 'gutenberg_examples_dynamic_render_callback')
        ));


    }


}