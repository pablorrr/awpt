<?php
/**
 * Hooks
 */

namespace Awpt\Setup;


class Hooks
{
    public function register()
    {
        add_action('admin_init', array($this, 'understrap_wpdocs_theme_add_editor_styles'));
        add_action('_add_post_link_navigator', array($this, 'understrap_post_nav'));
        add_action('_custom_paginate', array($this, 'understrap_pagination'));
        add_action('edit_category', array($this, 'category_transient_flusher'));
        add_action('save_post', array($this, 'category_transient_flusher'));
    }


    /**
     * Registers an editor stylesheet for the theme.
     */
    public function understrap_wpdocs_theme_add_editor_styles()
    {
        add_editor_style('css/custom-editor-style.css');
    }

    /**
     * Display navigation to next/previous post when applicable.
     */
    public function understrap_post_nav()
    {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);

        if (!$next && !$previous) {
            return;
        }
        ?>
        <nav class="container navigation post-navigation">
            <h2 class="sr-only"><?php _e('Post navigation', 'understrap'); ?></h2>
            <div class="row nav-links justify-content-between">
                <?php

                if (get_previous_post_link()) {
                    previous_post_link('<span class="nav-previous">%link</span>', _x('<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'understrap'));
                }
                if (get_next_post_link()) {
                    next_post_link('<span class="nav-next">%link</span>', _x('%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'understrap'));
                }
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->

        <?php
    }

    /**
     * Custom Pagination
     */

    public function understrap_pagination()
    {
        if (is_singular()) {
            return;
        }

        global $wp_query;

        /** Stop execution if there's only 1 page */
        if ($wp_query->max_num_pages <= 1) {
            return;
        }

        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        $max = intval($wp_query->max_num_pages);

        /**    Add current page to the array */
        if ($paged >= 1) {
            $links[] = $paged;
        }

        /**    Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (($paged + 2) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<nav aria-label="Page navigation"><ul class="pagination ">' . "\n";

        /**    Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $class = 1 == $paged ? ' class="active page-item"' : ' class="page-item"';

            printf('<li %s><a class="page-link" href="%s"><i class="fa fa-step-backward" aria-hidden="true"></i></a></li>' . "\n",
                $class, esc_url(get_pagenum_link(1)), '1');

            /**    Previous Post Link */
            if (get_previous_posts_link()) {
                printf('<li class="page-item page-item-direction page-item-prev"><span class="page-link">%1$s</span></li> ' . "\n",
                    get_previous_posts_link('<span aria-hidden="true">&laquo;</span><span class="sr-only">Previous page</span>'));
            }

            if (!in_array(2, $links)) {
                echo '<li class="page-item"></li>';
            }
        }

        // Link to current page, plus 2 pages in either direction if necessary.
        sort($links);
        foreach ((array)$links as $link) {
            $class = $paged == $link ? ' class="active page-item"' : ' class="page-item"';
            printf('<li %s><a href="%s" class="page-link">%s</a></li>' . "\n", $class,
                esc_url(get_pagenum_link($link)), $link);
        }

        // Next Post Link.
        if (get_next_posts_link()) {
            printf('<li class="page-item page-item-direction page-item-next"><span class="page-link">%s</span></li>' . "\n",
                get_next_posts_link('<span aria-hidden="true">&raquo;</span><span class="sr-only">Next page</span>'));
        }

        // Link to last page, plus ellipses if necessary.
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links)) {
                echo '<li class="page-item"></li>' . "\n";
            }

            $class = $paged == $max ? ' class="active "' : ' class="page-item"';
            printf('<li %s><a class="page-link" href="%s" aria-label="Next"><span aria-hidden="true"><i class="fa fa-step-forward" aria-hidden="true"></i></span><span class="sr-only">%s</span></a></li>' . "\n",
                $class . '', esc_url(get_pagenum_link(esc_html($max))), esc_html($max));
        }

        echo '</ul></nav>' . "\n";
    }

    public function category_transient_flusher()
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        delete_transient('awps_categories');
    }


}