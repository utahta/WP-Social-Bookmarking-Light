<?php
/*
Copyright 2010 utahta (email : labs.ninxit@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * html format
 *
 * @param string $services
 * @param string $link
 * @param string $title
 * @return string
 */
function wp_social_bookmarking_light_output($services, $link, $title)
{
    $wp = new WpSocialBookmarkingLight($link, $title, get_bloginfo('name'));
    $service_types = wp_social_bookmarking_light_service_types();
    $out = '';
    foreach (explode(",", $services) as $service) {
        $service = trim($service);
        if ($service === '') {
            continue;
        }

        if (in_array($service, $service_types)) {
            $out .= '<div class="wsbl_' . $service . '">'
                . call_user_func(array(
                    $wp,
                    $service
                )) . '</div>'; // WpSocialBookmarkingLight method
        } else {
            $out .= "<div>[`$service` not found]</div>";
        }
    }

    if ($out == '') {
        return $out;
    }
    return "<div class='wp_social_bookmarking_light'>{$out}</div><br class='wp_social_bookmarking_light_clear' />";
}

/**
 * echo html format
 *
 * @param string $services
 * @param string $link
 * @param string $title
 */
function wp_social_bookmarking_light_output_e($services = null, $link = null, $title = null)
{
    if ($services == null) {
        $options = wp_social_bookmarking_light_options();
        $services = $options['services'];
    }
    echo wp_social_bookmarking_light_output($services, $link, $title);
}

/**
 * add_action wp_head
 * @deprecated
 */
function wp_social_bookmarking_light_wp_head()
{
    $plugin = new \WpSocialBookmarkingLight\Plugin();
    $plugin->head();
}

/**
 * true if can be displayed, false if not
 *
 * @return bool
 */
function wp_social_bookmarking_light_is_enabled()
{
    if (is_feed() || is_404() || is_robots() || (function_exists('is_ktai') && is_ktai())) {
        return false;
    }

    $options = wp_social_bookmarking_light_options();
    if ($options['single_page'] && !is_singular()) {
        return false;
    }
    if (!$options['is_page'] && is_page()) {
        return false;
    }

    global $wp_current_filter;
    if (in_array('get_the_excerpt', (array)$wp_current_filter)) {
        return false;
    }

    if (get_query_var('amp', false) !== false) {
        return false;
    }

    return true;
}

/**
 * Add the Share Buttons to the content.
 * add_filter "the_content"
 *
 * @param string $content
 * @return string
 */
function wp_social_bookmarking_light_the_content($content)
{
    if (!wp_social_bookmarking_light_is_enabled()) {
        return $content;
    }

    $options = wp_social_bookmarking_light_options();
    $out = wp_social_bookmarking_light_output($options['services'], get_permalink(), get_the_title());
    if ($out == '') {
        return $content;
    }
    if ($options['position'] === 'top') {
        return "{$out}{$content}";
    } elseif ($options['position'] === 'bottom') {
        return "{$content}{$out}";
    } elseif ($options['position'] === 'both') {
        return "{$out}{$content}{$out}";
    }
    return $content;
}

/**
 * wp_footer function
 * @deprecated
 */
function wp_social_bookmarking_light_wp_footer()
{
    $plugin = new \WpSocialBookmarkingLight\Plugin();
    $plugin->footer();
}
