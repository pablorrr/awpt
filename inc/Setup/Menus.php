<?php

namespace Awpt\Setup;
/**
 * Menus
 */
class Menus
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action('after_setup_theme', array($this, 'menus'));
    }

    public function menus()
    {

        register_nav_menus(array(
            'primary' => __('Primary Menu', 'understrap'),
            'shop-menu' => __('Woocomm', 'understrap'),
        ));

    }
}