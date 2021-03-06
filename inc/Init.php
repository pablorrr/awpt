<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Inspirid with https://github.com/Alecaddd/awps
 *
 * @package awps
 */

namespace Awpt;

final class Init
{
    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     * https://stackoverflow.com/questions/32448481/what-does-classnameclass-mean-in-php/32448573
     */
    public static function get_services()
    {
        return [
            Core\Tags::class,
            Core\Sidebar::class,
            Setup\Setup::class,
            Setup\Menus::class,
            Setup\Filters::class,
            Setup\Hooks::class,
            Setup\Enqueue::class,
            Custom\PostTypes::class,
            Custom\Comments::class,
            Plugins\WooCommerce::class,
            Custom\Admin::class,

            Api\WP_Customizer::class,
            Api\Gutenberg::class,//handle custom Gutenberg block

            Plugins\ThemeJetpack::class,
            Plugins\Acf::class,
            Plugins\Plugin_Activation::class
        ];
    }

    /**
     * Loop through the classes, initialize them, and call the register() method if it exists
     * @return
     * register service of theme
     */
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();

            }
        }
    }

    /**
     * Initialize the class
     * @param class $class class from the services array
     * @return class instance        new instance of the class
     * create instantiation of class
     */
    private static function instantiate($class)
    {
        return new $class();
    }

}
