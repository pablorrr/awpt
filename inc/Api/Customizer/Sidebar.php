<?php


namespace Awpt\Api\Customizer;


use WP_Customize_Control;

class Sidebar
{

    public function register($wp_customize)
    {

        // Theme layout settings.
        $wp_customize->add_section('_sidebar', array(
            'title' => __('Layout Settings - Sidebar', 'understrap'),
            'capability' => 'edit_theme_options',
            'description' => __('Sidebar locations', 'understrap'),
            'priority' => 162,
        ));

        $wp_customize->add_setting('understrap_sidebar_position', array(
            'default' => 'right',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'capability' => 'edit_theme_options',
        ));

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'understrap_sidebar_position', array(
                    'label' => __('Sidebar Positioning', 'understrap'),
                    'description' => __("Set sidebar's default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.",
                        'understrap'),
                    'section' => '_sidebar',
                    'settings' => 'understrap_sidebar_position',
                    'type' => 'select',
                    'sanitize_callback' => array($this, '_sanitize_select'),
                    'choices' => array(
                        'right' => __('Right sidebar', 'understrap'),
                        'left' => __('Left sidebar', 'understrap'),
                        'both' => __('Left & Right sidebars', 'understrap'),
                        'none' => __('No sidebar', 'understrap'),
                    ),
                    'priority' => '20',
                )
            ));


    }

    //select sanitization function
    public function _sanitize_select($input, $setting)
    {

        //input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
        $input = sanitize_key($input);

        //get the list of possible select options
        $choices = $setting->manager->get_control($setting->id)->choices;

        //return input if valid or return default option
        return (array_key_exists($input, $choices) ? $input : $setting->default);

    }

}