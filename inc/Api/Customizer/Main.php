<?php


namespace Awpt\Api\Customizer;


class Main
{
    public function register($wp_customize)
    {

        $wp_customize->get_setting('blogname')->transport = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
        $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    }

}

