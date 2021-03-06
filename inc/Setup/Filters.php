<?php


namespace Awpt\Setup;
/**
 * Filters
 */
class Filters
{
    /**
     * Register all filters in this theme
     */

    public function register()
    {
        add_filter('excerpt_more', array($this, '_custom_excerpt_more'));
        add_filter('wp_trim_excerpt', array($this, '_all_excerpts_get_more_link'));
        add_filter('tiny_mce_before_init', array($this, 'understrap_tiny_mce_before_init'));
        add_filter('mce_buttons_2', array($this, 'understrap_tiny_mce_style_formats'));
        add_filter( 'body_class', array($this,'understrap_body_classes') );
        add_filter( 'body_class',array($this,'understrap_adjust_body_class') );
        add_filter( 'get_custom_logo',array($this, 'understrap_change_logo_class') );
    }






    public function understrap_tiny_mce_style_formats($styles)
    {
        array_unshift($styles, 'styleselect');
        return $styles;
    }


    public function understrap_tiny_mce_before_init($settings)
    {

        $style_formats = array(
            array(
                'title' => 'Lead Paragraph',
                'selector' => 'p',
                'classes' => 'lead',
                'wrapper' => true
            ),
            array(
                'title' => 'Small',
                'inline' => 'small'
            ),
            array(
                'title' => 'Blockquote',
                'block' => 'blockquote',
                'classes' => 'blockquote',
                'wrapper' => true
            ),
            array(
                'title' => 'Blockquote Footer',
                'block' => 'footer',
                'classes' => 'blockquote-footer',
                'wrapper' => true
            ),
            array(
                'title' => 'Cite',
                'inline' => 'cite'
            )
        );

        if (isset($settings['style_formats'])) {
            $orig_style_formats = json_decode($settings['style_formats'], true);
            $style_formats = array_merge($orig_style_formats, $style_formats);
        }

        $settings['style_formats'] = json_encode($style_formats);
        return $settings;
    }


    /**
     * Removes the ... from the excerpt read more link
     *
     * @param string $more The excerpt.
     *
     * @return string
     */
    public function _custom_excerpt_more($more): string
    {
        return '';
    }


    /**
     * Adds a custom read more link to all excerpts, manually or automatically generated
     *
     * @param string $post_excerpt Posts's excerpt.
     *
     * @return string
     */
    public function _all_excerpts_get_more_link(string $post_excerpt): string
    {

        return $post_excerpt . ' [...]<p><a class="btn btn-secondary understrap-read-more-link" href="' . esc_url(get_permalink(get_the_ID())) . '">' . __('Read More...',
                'understrap') . '</a></p>';
    }
    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     *
     * @return array
     */
    public function understrap_body_classes( $classes ): array
    {
        // Adds a class of group-blog to blogs with more than 1 published author.
        if ( is_multi_author() ) {
            $classes[] = 'group-blog';
        }
        // Adds a class of hfeed to non-singular pages.
        if ( ! is_singular() ) {
            $classes[] = 'hfeed';
        }

        return $classes;
    }

    /**
     * Setup body classes.
     *
     * @param string $classes CSS classes.
     *
     * @return mixed
     */
    public function understrap_adjust_body_class( $classes )
    {

        foreach ( $classes as $key => $value ) {
            if ( 'tag' == $value ) {
                unset( $classes[ $key ] );
            }
        }

        return $classes;

    }
    /**
     * Replaces logo CSS class.
     *
     * @param string $html Markup.
     *
     * @return mixed
     */
    public function understrap_change_logo_class( $html ): mixed
    {

        $html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
        $html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
        $html = str_replace( 'alt=""', 'title="Home" alt="logo"' , $html );

        return $html;
    }

}