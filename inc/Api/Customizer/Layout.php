<?php


namespace Awpt\Api\Customizer;


use WP_Customize_Control;

class Layout
{
    public function register($wp_customize)
    {
        // Theme layout settings.
        $wp_customize->add_section('_container', array(
            'title' => __('Layout Settings - Container', 'understrap'),
            'capability' => 'edit_theme_options',
            'description' => __('Container width', 'understrap'),
            'priority' => 161,
        ));

        $wp_customize->add_setting('understrap_container_type', array(
            'default' => 'container',
            'type' => 'theme_mod',
            'sanitize_callback' => array($this, '_sanitize_select'),
            'capability' => 'edit_theme_options',
        ));

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'understrap_container_type', array(
                    'label' => __('Container Width', 'understrap'),
                    'description' => __("Choose between Bootstrap's container and container-fluid", 'understrap'),
                    'section' => '_container',
                    'settings' => 'understrap_container_type',
                    'type' => 'select',
                    'choices' => array(
                        'container' => __('Fixed width container', 'understrap'),
                        'container-fluid' => __('Full width container', 'understrap'),
                    ),
                    'priority' => '10',
                )
            ));

    }


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