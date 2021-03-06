<?php


namespace Awpt\Api;


use Awpt\Api\Customizer\Layout;
use Awpt\Api\Customizer\Main;
use Awpt\Api\Customizer\Sidebar;

/**
 * Class WP_Customizer
 * @package Awpt\Api
 */
/*
 *
 *
 * warosci opcji ustawin na front end dla Layout (container) znjduja sie
 *  w zminnej cointiner wewszytskich plikacch szblonoiwych motywu lacznie z index
 *
 * warosci dla ustwien sidebar positionb znajduja sie na sidebar-left i sidebar-right
 */

class WP_Customizer
{

    public function register()
    {
        // add_action( 'wp_head', array($this , 'output') );

        // add_action( 'customize_register', array( $this, 'setup' ) );

    }

    /**
     * @return string[]
     */
    public function get_classes()
    {
        return [
            Main::class,
            Sidebar::class,
            Layout::class,

        ];
    }

    /**
     * @param $wp_customize
     */
    public function setup($wp_customize)
    {
        foreach ($this->get_classes() as $class) {
            $service = new $class;
            if (method_exists($class, 'register')) {
                $service->register($wp_customize);
            }
        }
    }

    /**
     * Generate inline CSS for customizer options
     */
    public function output()
    {
        echo '<!--Customizer CSS--> <style type="text/css">';
        echo self::css('#sidebar', 'background-color', 'awps_sidebar_background_color');
        echo self::css('.site-footer', 'background-color', 'awps_footer_background_color');
        echo self::css('.site-header', 'background-color', 'awps_header_background_color');
        echo self::css('.site-header', 'color', 'awps_header_text_color');
        echo self::css('.site-header a', 'color', 'awps_header_link_color');
        echo '</style><!--/Customizer CSS-->';
    }

    /**
     * @param $selector
     * @param $property
     * @param $theme_mod
     * @return string
     */
    public static function css($selector, $property, $theme_mod)
    {
        $theme_mod = get_theme_mod($theme_mod);

        if (!empty($theme_mod)) {
            return sprintf('%s { %s:%s; }', $selector, $property, $theme_mod);
        }
    }
}